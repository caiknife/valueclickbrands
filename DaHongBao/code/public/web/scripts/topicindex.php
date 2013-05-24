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
require_once(__INCLUDE_ROOT."lib/classes/class.topic.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();


$otopic = new Topic();
$topiclist = $otopic->getTopicByType();

$topiclistnewarray = array();
$topicmainarray = array();
foreach($topiclist as $key=>$value){
	//big one
	if($value['topicweight']==1){
		$value['smalltitle'] = Utilities::cutString($value['title'],22);
		$value['topicdetail'] = Utilities::cutString($value['topicdetail'],160);
		$nk = $value['topictype'];
		$topicmainarray[$nk] = $value;
		continue;
	}

	$value['smalltitle'] = Utilities::cutString($value['title'],15);

	if(array_key_exists($value['topictype'],$topiclistnewarray)){
		//$topiclistnewarray[$key] = array();
		$k = $value['topictype'];
		array_push($topiclistnewarray[$k],$value);
	}else{
		$k = $value['topictype'];
		$topiclistnewarray[$k][0] = $value;
	}
}


$tpl = new sTemplate();
$tpl->setTemplate("new/topicindex.htm");

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$tpl->assign("topiclistnewarray",$topiclistnewarray);
$tpl->assign("topicmainarray",$topicmainarray);



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