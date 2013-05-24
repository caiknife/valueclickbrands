<?php
/*
* package_name : file_name
* ------------------
* 统计商家每个category数量

*
* PHP versions 5
* 
* @Author   : thomas fu(tfu@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com <http://www.mezimedia.com/> )
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: syncMerCategoryCouponCnt.php,v 1.1 2013/05/03 09:25:52 thomas_fu Exp $
*/
// Setup autoloading
require dirname(__DIR__) . '/init.php';

use Custom\Util\Utilities;
use BackEnd\Model;
use Custom\Util\WordSplitter;

$startTime = microtime(true);
$startDate = Utilities::getDateTime('Y-m-d');

Utilities::println($startDate . ' stat Merchant Category Coupon Count');
$serviceManger = $application->getServiceManager();
$adapter = $serviceManger->get('Custom\Db\Adapter\DefaultAdapter');

$couponTable = new Model\Coupon\CouponTable($adapter);
$merchantTable = new Model\Merchant\MerchantTable($adapter);
$merchantCategoryTable = new Model\Merchant\MerchantCategoryTable($adapter);
$merList = $merchantTable->getListByActive();

foreach ($merList as $merInfo) {
    $merid = $merInfo['MerchantID'];
    $where['MerchantID'] = $merid;
    $merchantCategoryTable->update(array('r_OnlineCouponCount' => 0, 'r_OnlineDiscountCount' => 0), $where);
    $couponCate = $couponTable->getCouponCateByMerID($merid);
    Utilities::println('process merchant = ' . $merid);
    foreach ($couponCate as $catid => $couponCateInfo) {
        $merchantCategoryTable->replace(
            $merid,
            $catid,
            $couponCateInfo['CouponCnt'],
            $couponCateInfo['DiscountCnt']
        );
    }
}
Utilities::println('end' . Utilities::getDateTime('Y-m-d H:i:s'));
?>