<?php
session_start();
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Customer.php");

require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');


$phpwind = new PHPWIND();

if(count($_SESSION['digestarray'])){

}else{
	$_SESSION['digestarray'] = array();
}

if($phpwind->digest($_GET['id'])){

	$digestarray = $_SESSION['digestarray'];
	array_push($digestarray,$_GET['id']);
	$digestarray = array_unique($digestarray);
	$_SESSION['digestarray'] = $digestarray;
	echo $_GET['id'];
}
?>
