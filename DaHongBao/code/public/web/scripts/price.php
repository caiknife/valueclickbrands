<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once(__INCLUDE_ROOT.'lib/classes/class.Award.php');


$oAward = new Award();
$awardlist = $oAward->getAwardList();



$tpl = new sTemplate();
$tpl->setTemplate("new/priceindex.htm");

$newawardlist = array();
foreach($awardlist as $key=>$value){
	$k = $value['Sort'];
	if($k) $newawardlist[$k] = $value; 
}
//print_r($newawardlist);

$tpl->assign('awardlist',$newawardlist);



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
	
	$userhb = Award::getUserHB($userinfo[0]['uid']);
	
	$tpl->assign("userhb",$userhb);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}


$indexlist = $phpwind->getBBSNotify(50);
$tpl->assign("indexlist", $indexlist);

$tpl->displayTemplate();


?>