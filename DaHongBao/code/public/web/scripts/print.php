<?PHP
$t_array=explode(' ',microtime());
$P_S_T=$t_array[0]+$t_array[1];
session_start();
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');


$src = $_GET['url'];
//echo $src;



$tpl = new sTemplate();


$tpl->setTemplate("new/print.tpl");

if($_GET['id']==""){
	$tpl->assign("src", $src);
}else{
	$oCoupon = new Coupon($_GET['id']);
	$couponRow = $oCoupon->getCouponInfo();
	$tpl->assign("content", $couponRow['LongRestr']);
}

$tpl->displayTemplate();


?>