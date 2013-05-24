<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/dao/class.UserScoreDao.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.topic.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once(__INCLUDE_ROOT."lib/classes/class.Award.php");

$tpl = new sTemplate();
$tpl->setTemplate("new/pricemakesure.htm");

$phpwind = new PHPWIND();

$winduser = P_GetCookie("winduser");
if(empty($winduser)){
	Header("location:/bbs/login.php");
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}


if($_POST['act']=="go"){
	$array['ID'] = $_POST['uaid'];
	$array['ApplyTrueName'] = $_POST['ApplyTrueName'];
	$array['City'] = $_POST['City'];
	$array['Address'] = $_POST['Address'];
	$array['Zip'] = $_POST['Zip'];
	$array['TelNumber'] = $_POST['TelNumber'];
	$array['Type'] = 'YES';

	$uid = $_POST['uid'];
	$needhb = $_POST['needhb'];
	$awardid = $_POST['awardid'];

	if(Award::updateUserAwardDetail($array)){
		$oUserScoreDao = new UserScoreDao($uid);
		$oUserScoreDao->addScore("OTHER",'','','','兑奖扣分',-$needhb*100,$db="");
		Award::updateAwardNum($awardid,'');
		echo "<script>alert('您的兑奖申请已经成功提交，请等待我们工作人员的审核，审核后我们会和你取得联系')</script>";
		echo "<script>window.location.href='price.php'</script>";
		exit();
	}


}

$uaid = $_GET['id'];
$uarow = Award::getUserAwardDetail($uaid);

if($uarow['UserID']!=$userinfo[0]['uid']){
	Header("location:/price.php");
}

$userdetail = Award::getUserDetail($uarow['UserID']);
$tpl->assign('userdetail',$userdetail);
$tpl->assign('uarow',$uarow);
$tpl->assign('uaid',$uaid);

$tpl->assign('__IMAGE_SRC',__IMAGE_SRC);






$tpl->displayTemplate();


?>