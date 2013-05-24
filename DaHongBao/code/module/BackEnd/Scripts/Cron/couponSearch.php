<?php
/*
* package_name : file_name
* ------------------
* typecomment

*
* PHP versions 5
* 
* @Author   : thomas fu(tfu@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com <http://www.mezimedia.com/> )
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: couponSearch.php,v 1.1 2013/04/17 10:26:51 thomas_fu Exp $
*/
// Setup autoloading
require dirname(__DIR__) . '/init.php';

use Custom\Util\Utilities;
use BackEnd\Model;
use Custom\Util\WordSplitter;

$startTime = microtime(true);
$startDate = Utilities::getDateTime('Y-m-d');

Utilities::println($startDate . ' starting');
$serviceManger = $application->getServiceManager();
$adapter = $serviceManger->get('Custom\Db\Adapter\DefaultAdapter');
$couponSearchTable = new Model\Coupon\CouponSearchTable($adapter);
$couponTable = new Model\Coupon\CouponTable($adapter);
$merchantTable = new Model\Merchant\MerchantTable($adapter);

//1. 删除所有search记录
$where = '1';
$couponSearchTable->delete($where);

foreach ($couponTable->getList() as $couponInfo) {
    //找出商家中英文名称
    $merchantInfo = $merchantTable->getInfoById($couponInfo['MerchantID']);
    $insertData['CouponID'] = $couponInfo['CouponID'];
    $insertData['MerchantID'] = $couponInfo['MerchantID'];
    $insertData['MerchantNameEN'] = $merchantInfo['MerchantNameEN'];
    $insertData['CouponRestriction'] = $couponInfo['CouponRestriction'];
    $insertData['CouponStartDate'] = $couponInfo['CouponStartDate'];
    $insertData['CouponEndDate'] = $couponInfo['CouponEndDate'];
    $insertData['CouponType'] = $couponInfo['CouponType'];
    $insertData['SiteID'] = $couponInfo['SiteID'];
    $insertData['InsertDateTime'] = Utilities::getDateTime('Y-m-d H:i:s');

    //只有中文需要拆词
    if ($couponInfo['SiteID'] == 1) {
        $insertData['CouponName'] = implode(' ', WordSplitter::instance()->execute($couponInfo['CouponName']));
        $insertData['MerchantName'] = implode(' ', WordSplitter::instance()->execute($merchantInfo['MerchantName']));
        $insertData['CouponDescription'] = implode(' ', WordSplitter::instance()->execute($couponInfo['CouponDescription']));
    } else {
        $insertData['CouponName'] = $couponInfo['CouponName'];
        $insertData['MerchantName'] = $merchantInfo['MerchantName'];
        $insertData['CouponDescription'] = $couponInfo['CouponDescription'];
    }
    $couponSearchTable->insert($insertData);
    
    Utilities::println('couponName = ' . $couponInfo['CouponName'] . ', change to ' . $insertData['CouponName']);
}

$useTime = microtime(true) - $startTime;
Utilities::println('end . use time = ' . $useTime);
?>