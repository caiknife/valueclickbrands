<?PHP
session_start();
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.topic.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$tplname = "";
switch ($_GET['id']){
	case 1:
		$tplname = "new/topicindexspring.htm";
		break;
	case 2:
		$tplname = "new/topicindexsummer.htm";
		break;
	case 3:
		$tplname = "new/topicindexautumn.htm";
		break;
	case 4:
		$tplname = "new/topicindexwinter.htm";
		break;
	case 5:
		$tplname = "new/topicindexspecial.htm";
		break;
	case 6:
		$tplname = "new/topicindexholiday.htm";
		break;
	default:
		redirect301("/");
		exit;
		break;

}
$tpl = new sTemplate();
$tpl->setTemplate($tplname);

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$otopic = new Topic();
$topiclist = $otopic->getTopicMoreByType($_GET['id']);
foreach($topiclist as $key=>$value){
	$topiclist[$key]['smalltitle'] = Utilities::cutString($value['title'],16);
	$topiclist[$key]['topicdetail'] = Utilities::cutString($value['topicdetail'],100);
}
$tpl->assign("topiclist", $topiclist);



$tpl->assign('__IMAGE_SRC',__IMAGE_SRC);

$tpl->assign('nowcityname',$cityarray['cityname']);
$tpl->assign('citylist',$citylist);


$phpwind = new PHPWIND();

$winduser = P_GetCookie("winduser");
if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}
$indexlist = $phpwind->getBBSNotify(50);
$tpl->assign("indexlist", $indexlist);


$tpl->displayTemplate();


?>