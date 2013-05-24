<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Award.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$tpl = new sTemplate();

$id = $_GET['id'];

$phpwind = new PHPWIND();

$winduser = P_GetCookie("winduser");
if(empty($winduser)){
	Header("location:/bbs/login.php");
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	$userhb = Award::getUserHB($userinfo[0]['uid']);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}


$awarddetail = Award::getAwardDetail($id);

if($userhb>$awarddetail['NeedHB']){

	$useraddawardid = Award::addUserAward($userinfo[0]['uid'],$id,$awarddetail['NeedHB']);

	$tpl->assign('useraddawardid',$useraddawardid);
	$tpl->assign('awarddetail',$awarddetail);
	$tpl->assign('canaward',1);
}

$tpl->setTemplate("new/priceuseradd.htm");
$tpl->displayTemplate();


?>