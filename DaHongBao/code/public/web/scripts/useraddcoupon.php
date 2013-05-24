<?php
require_once ("../etc/const.inc.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once (__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once (__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once (__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once (__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
include_once (__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once (__INCLUDE_ROOT."lib/classes/class.thumb.php");
require_once (__INCLUDE_ROOT."lib/util/class.PathManager.php");
require_once (__INCLUDE_ROOT."lib/classes/class.AuthNum.php");

require_once (__INCLUDE_ROOT."lib/util/class.FileDistribute.php");
$winduser = P_GetCookie("winduser");

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");

//$hotcategorycouponlist = $oCategory->gethotcategorycouponlist($coupcat);
for ($j = 0; $j < count($categoryList); $j ++) {
	$categoryForShow[$j]["category_url"] = Utilities :: getURL("category", array ("NameURL" => $categoryList[$j]["NameURL"], "Cid" => $categoryList[$j]["Category_"], "Page" => 1));
	$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
}

$oPage = new Page();
$oPage->find("HOTMERCHANT_INCLUDE_IN");
$hotmerchantin = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("HOTCOUPON_INCLUDE_IN");
$hotcouponin = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl = new sTemplate();

$path = new PathManager();
$tpl->assign("AuthNumUrl", $path->getAuthNumUrl()); //验证码

if ($_POST['act'] == "submit") {

	$ocoupon = new Coupon();
	$oAuthNum = new AuthNum();
	$tp = array ("image/gif", "image/pjpeg", "image/jpeg");
	list($imageWidth, $imageHeight, $imageType, $imageAttr) = getimagesize($_FILES['Picurl']['tmp_name']);

	$Start = trim($_POST['c']['Start']);
	$End = trim($_POST['c']['End']);
	$authNum = $_POST['authNum'];

	if(!$Start || !isDate($Start)) {
		$returnvalue = $_POST['c'];
		$tpl->assign('returnvalue', $returnvalue);
		$tpl->assign('error', "请输入正确的开始时间！");
	}elseif(!$End || (!isDate($End) && $End != '永久有效')) {
		$returnvalue = $_POST['c'];
		$tpl->assign('returnvalue', $returnvalue);
		$tpl->assign('error', "请输入正确的结束时间！");
	}elseif(!$oAuthNum->doValidate($authNum)) {
		$returnvalue = $_POST['c'];
		$tpl->assign('returnvalue', $returnvalue);
		$tpl->assign('error', "请输入正确的验证码！");
	}elseif ($_FILES['Picurl']['tmp_name'] && in_array($_FILES['Picurl']['type'], $tp) && in_array($imageType, array('1', '2')) && $_FILES['Picurl']['error'] == 0) {
		if ($newid = $ocoupon->addUserCoupon($_POST['c'])) {
			//检查上传文件是否在允许上传的类型
			if (in_array($_FILES['Picurl']['type'], $tp)) {
				$t = $newid % 100;

				//$b = createdir(__IMAGE_ADD."images");
				$b = createdir(__IMAGE_ADD."add");
				$b = createdir(__IMAGE_ADD."add/".$t);

				if (copy($_FILES['Picurl']['tmp_name'], __IMAGE_ADD."add/".$t."/".$newid.".jpg")) {
					$src = __IMAGE_ADD."add/".$t."/".$newid.".jpg";
					$outsrc = __IMAGE_ADD."add/".$t."/".$newid."_small.jpg";

					$th = new ThumbHandler();
					@ $th->setSrcImg($src);
					@ $th->setDstImg($outsrc);
					@ $th->createImg(70, 82); //discount small pic
					$re = $ocoupon->updateUserCouponPic($newid);

					$files1 = "add/".$t."/".$newid.".jpg";
					$files2 = "add/".$t."/".$newid."_small.jpg";
					FileDistribute :: syncImages($files1);
					FileDistribute :: syncImages($files2);
				}
			}
			echo "<script>alert('添加成功,我们会尽快审核您添加的优惠券!感谢您对大红包的支持!')</script>";
			echo "<script>window.location.href='/'</script>";
		}
	}else{
		//echo "<script>alert('请上传优惠券图片!')</script>";
		$returnvalue = $_POST['c'];
		$tpl->assign('returnvalue', deepHtmlspecialchars($returnvalue));
		$tpl->assign('error', "请上传正确的优惠券图片！");
	}
}

function deepHtmlspecialchars($val){
    if(is_array($val)){
        foreach ($val as $k=>$v){
            $val[$k] = deepHtmlspecialchars($v);
        }
    }else{
        return htmlspecialchars($val);
    }
    return $val;
}

function isDate( $str )	{
	$stamp = strtotime($str);
	if (!is_numeric($stamp)) {
		return false;
	}
	$month = date('m', $stamp);
	$day   = date('d', $stamp);
	$year  = date('Y', $stamp);
	if (checkdate($month, $day, $year)) {
		return true;
	}
	return false;
}

$phpwind = new PHPWIND();

$indexlist = $phpwind->getforumlist("50");
if (empty ($winduser)) {
	$tpl->assign('islogon', 0);
} else {
	$user = explode("\t", $winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign('userid', $user[0]);
	$tpl->assign("userinfo", $userinfo[0]);
	$tpl->assign('islogon', 1);
}

$bbslist = $phpwind->gethotrealbbs();

$tpl->assign('hotkeywords', $hotkeywords);
$tpl->assign("bbslist", $bbslist);
$tpl->assign("hotcouponin", $hotcouponin);
$tpl->assign("hotmerchantin", $hotmerchantin);
$tpl->assign('indexlist', $indexlist);
$tpl->setTemplate("new/useraddcoupon.htm");
$tpl->assign("category", $categoryForShow);

$cityid = empty ($_COOKIE['cityid']) ? 21 : $_COOKIE['cityid']; //if city id is empty ..default 21
$tpl->assign('cityid', $cityid);
$cityname = $phpwind->getNowCityName($cityid); //get city name by city id
$tpl->assign('nowcityname', $cityname);
$citylist = $phpwind->getCityList(); //city list for city select
$tpl->assign('citylist', $citylist);

$tpl->assign('today', getDateTime("Y-m-d"));
$tpl->assign('userid', $user[0]);

$tpl->displayTemplate();
?>

