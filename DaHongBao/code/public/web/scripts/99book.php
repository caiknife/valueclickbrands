<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");


$oCoupon = new Coupon($p);
if(isset($email)) {
	$sql = "insert ignore into 99mail(email, submit) values('".$email."',NOW())";
	DBQuery::instance()->executeUpdate($sql);
	echo("<" ."script language=javascript>");
	echo("self.close();");
	echo("<"."/script>");
} else {
	$tpl = new sTemplate();
	$tpl->setTemplate("99book.tpl");
	$tpl->assign("LINK_ROOT", __LINK_ROOT);
	$tpl->displayTemplate();
}

?>
