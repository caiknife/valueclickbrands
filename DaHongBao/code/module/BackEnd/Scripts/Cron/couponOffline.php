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
* @Version  : CVS: $Id: couponOffline.php,v 1.1 2013/04/17 10:26:51 thomas_fu Exp $
*/
// Setup autoloading
require dirname(__DIR__) . '/init.php';

use Custom\Util\Utilities;
use BackEnd\Model;
use Custom\Util\WordSplitter;

$startTime = microtime(true);
$startDate = Utilities::getDateTime('Y-m-d');

Utilities::println($startDate . ' coupon Offline starting');
$serviceManger = $application->getServiceManager();
$adapter = $serviceManger->get('Custom\Db\Adapter\DefaultAdapter');

$couponTable = new Model\Coupon\CouponTable($adapter);
$merchantTable = new Model\Merchant\MerchantTable($adapter);

//下线国内优惠卷
$couponEndData = Utilities::getDateTime('Y-m-d H:i:s');
$offlineCouponCnt = $couponTable->setOfflineCoupon($couponEndData, 'CN');
Utilities::println('end, offline cn coupon count = ' . $offlineCouponCnt);

//下线国外优惠卷
$couponEndData = Utilities::getDateTime('Y-m-d H:i:s', time() - 3600 * 8);
$offlineCouponCnt = $couponTable->setOfflineCoupon($couponEndData, 'US');
Utilities::println('end, offline us coupon count = ' . $offlineCouponCnt);
?>