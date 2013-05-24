<?php
/*
 * package_name : autoImport.php
 * ------------------
 * feed导入流程
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: autoImport.php,v 1.1 2013/04/15 10:57:14 rock Exp $
 */

// Setup autoloading
require dirname(__DIR__) . '/init.php';

use BackEnd\Process\CouponProcess;
use Custom\Util\Utilities;
use BackEnd\Model;

$startTime = microtime(true);
$startDateTime = Utilities::getDateTime('Y-m-d H:i:s');
Utilities::println($startDateTime . ',  starting process ...');

// 初始化设置共同参数
$merid = '';
$affiliateID = '';
$fileName = '';
parse_str($argv[1]);

if (empty($merid) && empty($affiliateID)) {
    Utilities::println('import feed need merid or affiliateID.');
    exit;
}
if (empty($affiliateID)) {
    $affiliateID = 0;
}
//1 初始化Adapter配置 & FEED导入配置 & 设置FEED导入状态
$serviceManger = $application->getServiceManager();
$adapter = $serviceManger->get('Custom\Db\Adapter\DefaultAdapter');
$update['ImportFeedRunStatus'] = 'RUNNING';
if ($merid > 0) {
    $merchantFeedConfigTable = new Model\FeedConfig\MerchantFeedConfigTable($adapter);
    $merchantFeedConfigTable->replace($update, $merid, $affiliateID);
    $lockKey = 'Merchant_' . $merid;
} else {
    $feedConfigTable = new Model\FeedConfig\AffiliateFeedConfigTable($adapter);
    $merchantFeedConfigTable = new Model\FeedConfig\MerchantFeedConfigTable($adapter);
    $merchantAliasTable = new Model\Merchant\MerchantAliasTable($adapter);
    $lockKey = 'Affiliate_' . $affiliateID;
    //找出这个联盟所有的商家
    $merchantList = $merchantAliasTable->getMerListByAffID($affiliateID);
    foreach ($merchantList as $merchantInfo) {
        if ($merchantInfo['dataFeedFromAffiliate'] == 'YES') {
            $merchantFeedConfigTable->replace($update, $merchantInfo['MerchantID'], $affiliateID);
        }
    }
    $feedConfigTable->replace($update, $affiliateID);
}

//2 执行导入流程
try {
    $couponProcess = new CouponProcess($serviceManger);
    
    if ($merid > 0) {
        $couponProcess->merchantExecute($merid, $fileName);
    } else {
        $couponProcess->affiliateExecute($affiliateID, $fileName);
    }
    $update['ImportFeedRunStatus'] = 'SUCCESS';
    $statInfo = $couponProcess->getStatInfo();
    $update = array (
        'ImportFeedRunStatus'           => 'SUCCESS',
        'ImportFeedFileName'            => $statInfo['ImportFeed']['FileName'],
        'ImportFeedFileDateTime'        => $statInfo['ImportFeed']['FileNameDateTime'],
        'ImportFeedFileTotalCount'      => $statInfo['ImportFeed']['InitCnt'],
        'ImportFeedUserDataCount'       => $statInfo['ImportFeed']['SuccessCnt'],
        'ImportFeedNewCouponCount'      => $statInfo['ImportCoupon']['NewCouponCount'],
        'ImportFeedNewDiscountCount'    => $statInfo['ImportCoupon']['NewDiscountCount'],
        'ImportFeedExistCouponCount'      => $statInfo['ImportCoupon']['ExistCouponCount'],
        'ImportFeedExistDiscountCount'    => $statInfo['ImportCoupon']['ExistDiscountCount'],
        'ImportFeedUserDataRemainCount' => $statInfo['ImportCoupon']['UserDataRemainCount'],
        'ImportFeedStartTime'           => $startDateTime,
        'ImportFeedEndTime'             => Utilities::getDateTime(),
        'ImportFeedRunDetailInfo'       => var_export($statInfo, true),
        'CreateDateTime'                => Utilities::getDateTime()
    );
} catch (\Exception $e) {
    $statInfo = $couponProcess->getStatInfo();
    $update['ImportFeedRunStatus'] = 'FAIL';
    $update['ImportFeedFileName'] = $statInfo['ImportFeed']['FileName'];
    $update['ImportFeedFileDateTime'] = $statInfo['ImportFeed']['FileNameDateTime'];
    $update['ImportFeedStartTime'] = $startDateTime;
    $errMsg = $e->getMessage() . $e->getTraceAsString();
    $update['ImportFeedRunDetailInfo'] = $errMsg;
    print_r($errMsg);
}

//3 更新操作
if ($merid > 0) {
    $merchantFeedConfigTable->replace($update, $merid, $affiliateID);
} else {
    $feedConfigTable->replace($update, $affiliateID);
    //同步联盟的导入配置到商家配置，方便前台查看管理
    foreach ($merchantList as $merchantInfo) {
        $merUpdate['ImportFeedRunStatus'] = $update['ImportFeedRunStatus'];
        $merid = $merchantInfo['MerchantID'];
        if ($merUpdate['ImportFeedRunStatus'] == 'SUCCESS') {
            $merUpdate['ImportFeedRunStatus'] = 'SUCCESS';
            //feed import
            $merUpdate['ImportFeedFileName'] = $statInfo['ImportFeed']['FileName'];
            $merUpdate['ImportFeedFileDateTime'] = $statInfo['ImportFeed']['FileNameDateTime'];
            $merUpdate['ImportFeedStartTime'] = $startDateTime;
            
            $merStatInfo = $statInfo['ImportFeed']['Merchant'][$merid];
            
            $merUpdate['ImportFeedUserDataCount'] = $merStatInfo['SuccessCnt'] > 0 
                                                    ? $merStatInfo['SuccessCnt'] : 0;
            
            //coupon import
            $merUpdate['ImportFeedNewCouponCount'] = $merStatInfo['NewCouponCount'] > 0 
                                                     ? $merStatInfo['NewCouponCount'] : 0;
            
            $merUpdate['ImportFeedNewDiscountCount'] = $merStatInfo['NewDiscountCount'] > 0 
                                                       ? $merStatInfo['NewDiscountCount'] : 0;
            
            $merUpdate['ImportFeedUserDataRemainCount'] = $merStatInfo['UserDataRemainCount'] > 0 
                                                          ? $merStatInfo['UserDataRemainCount'] : 0;
            
            //time
            $merUpdate['ImportFeedEndTime'] = Utilities::getDateTime();
            $merUpdate['ImportFeedRunDetailInfo'] = var_export($statInfo, true);
            $merUpdate['CreateDateTime'] = Utilities::getDateTime();
        }
        $merchantFeedConfigTable->replace($merUpdate, $merchantInfo['MerchantID'], $affiliateID);
    }
}

print_r(var_export($statInfo, true));

//4 解锁
$processRuntimeTable = new Model\ProcessRuntimeTable($adapter);
$processRuntimeTable->unLock('ImportFeed', $lockKey);

Utilities::println('Done, Use time = ' . (round(microtime(true) - $startTime)));
?>