<?php
/**
 * seach.php
 *-------------------------
 *
 * seach
 *
 * PHP versions 4
 *
 * LICENSE: This source file is from CouponMountain.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 4.0
 * @version    CVS: $Id: merchant_go.php,v 1.1 2013/04/15 10:58:06 rock Exp $
 * @link       http://www.couponmountain.co.uk/
 * @deprecated File deprecated in Release 2.0.0
 */

session_start();
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
include_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Search.php");
include_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$text = $_GET['nameurl'];

$phpwind = new PHPWIND();

$a = $phpwind->getmerchant($text);


$go = "/Me-".$a[0][NameURL]."--Mi-".$a[0][Merchant_].".html";
   redirect301($go);
   exit;



?>