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
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

include_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.thumb.php");

require_once(__INCLUDE_ROOT."lib/util/class.FileDistribute.php");
require_once(__INCLUDE_ROOT."lib/classes/class.discount.php");

$oCity = new City($_COOKIE['cityid']);
$tpl = new sTemplate();
$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

if($_POST['step']==2){

	$arrValue['Descript'] = $_POST['Descript'];
	$arrValue['Detail'] = $_POST['Detail'];

	$tag = $_POST['tag'];
	$userid = $_POST['userid'];

	list($imageWidth, $imageHeight, $imageType, $imageAttr) = getimagesize($_FILES['addfile']['tmp_name']);
	if($_FILES['addfile']['tmp_name'] && (!$imageType || $imageType > 2)) {
		$tpl->assign('error', '1');
	}else{
		if($_FILES['addfile']['tmp_name']) {
			$arrValue['ImageDownload']=1;
		}else{
			$arrValue['ImageDownload']=0;
		}

		$odiscount = new Discount();
		$couponid = $odiscount->addDiscountByUser($arrValue,$tag,$userid);

		if($_FILES['addfile']['tmp_name']) {
			$t = $couponid%100;

			//$b = createdir(__IMAGE_ADD."images");
			$b = createdir(__IMAGE_ADD."add");
			$b = createdir(__IMAGE_ADD."add/".$t);

			if   (substr($_FILES['addfile']['type'],0,5)   ==   "image")   {
				$go = @copy($_FILES['addfile']['tmp_name'], __IMAGE_ADD."add/".$t."/".$couponid.".jpg");
			}


			$src = __IMAGE_ADD."add/".$t."/".$couponid.".jpg";
			$outsrc = __IMAGE_ADD."add/".$t."/".$couponid."_small.jpg";

			$th = new ThumbHandler();
			@$th->setSrcImg($src);
			@$th->setDstImg($outsrc);
			@$th->createImg(99,66);  //discount small pic

			$files1 = "add/".$t."/".$couponid.".jpg";
			$files2 = "add/".$t."/".$couponid."_small.jpg";
			FileDistribute :: syncImages($files1);
			FileDistribute :: syncImages($files2);

		}
		


		$tpl->assign('couponid',$couponid);

		$citylist = $oCity->getCityList();
		$tpl->assign('citylist',$citylist);
	}
	$tpl->setTemplate("new/discount_useradd1.htm");

}elseif($_POST['step']==3){
	$couponid = $_POST['couponid'];
	$startdate = $_POST['StartDate'];
	$enddate = $_POST['ExpireDate'];
	$city = $_POST['city'];

	$odiscount = new Discount();
	$couponid = $odiscount->UpdateDiscountByUser($couponid,$startdate,$enddate,$city);

	Header("location:/discount_hot.html");
	exit();

}else{

	$tpl->setTemplate("new/discount_useradd.htm");

	if($_POST['act']=="true"){
		$odiscount = new Discount();
	}
}

$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();


$tpl->assign('nowcityname',$cityarray['cityname']);
$tpl->assign('citylist',$citylist);

$phpwind = new PHPWIND();
$indexlist = $phpwind->getBBSNotify(50);
$tpl->assign("indexlist", $indexlist);


$winduser = P_GetCookie("winduser");
if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}



$tpl->displayTemplate();
?>
