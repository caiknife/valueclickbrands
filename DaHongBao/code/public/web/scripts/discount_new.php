<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.discount.php");
require_once(__INCLUDE_ROOT."lib/functions/func.PageStr.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$tpl = new sTemplate();
$tpl->setTemplate("new/discount_new.htm");

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

require_once "Cache/Lite.php";
$options = array(
    'cacheDir' => __FILE_FULLPATH.'cache/discount/',
    'lifeTime' => 0.25*60*60,
    'pearErrorMode' => CACHE_LITE_ERROR_DIE
);
$cache = new Cache_Lite($options);

if(empty($_GET['pageid'])){
	$pageid = 1;
}else{
	$pageid = $_GET['pageid'];
}

//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();


$datastr = "";

if(!empty($_GET['d'])){
	$data = $_GET['d'];
	$data = base64_decode($data);
	$data = explode("-",$data);
	$selectedDay = $data[2];
	$selectedMonth = $data[1];
	$selectedYear = $data[0];
}else{
	$selectedDay = getDateTime('d');
	$selectedMonth = getDateTime('m');
	$selectedYear = getDateTime('Y');
}
$lastday = date('t',mktime(0,0,0,$selectedMonth,1,$selectedYear));//�����·���Ӧ�е�����
if($selectedDay>$lastday){
	$selectedDay = $lastday;
}

//��õ��µ�һ�����������ڼ�
$firstday = date('w',mktime(0,0,0,$selectedMonth,1,$selectedYear));

//��ȡ�������һ��
$lastday = getDateTime('t');//�����·���Ӧ�е�����
do{
$monthOrig = date('m',mktime(0,0,0,$selectedMonth,1,$selectedYear));
$monthTest = date('m',mktime(0,0,0,$selectedMonth,$lastday,$selectedYear));
if($monthTest != $monthOrig){$lastday -= 1;}
}while($monthTest != $monthOrig);
//��õ��¶�Ӧ��Ӣ����
$monthName = date('F',mktime(0,0,0,$selectedMonth,1,$selectedYear));
//��ʾ����ͷ
$days = array("����","��һ","�ܶ�","����","����","����","����");
$dayRow = 0;
$datastr.= "<div class='calendar_week'>";

$datastr.= "<ul>";
for($i=0;$i<=6;$i++){
	if($i==0 || $i==6){
		$datastr.= "<li class='bring'>$days[$i]</li>\n";
	}else{
		$datastr.= "<li>$days[$i]</li>\n";
	}
}

//�ճ����µ�һ���λ��
while($dayRow < $firstday){
	$datastr.= "<li>&nbsp;</li>\n";
	$dayRow += 1;
}
$day = 0;
while($day < $lastday){
	if(($dayRow % 7) == 0){
		$datastr.= "";
	}
	$adjusted_day = $day + 1;
	//�����ú�ɫ��ʾ
	if($adjusted_day == $selectedDay){
		$datastr.= "<li><strong>$adjusted_day</strong></li>\n";
	}else if( ($dayRow%7)==0 || (($dayRow+1)%7)==0){
		//$datastr.= "<li class='bring'><a href='?d=".base64_encode($selectedYear."-".$selectedMonth."-".$adjusted_day)."'>$adjusted_day</a></li>\n";
		$datastr.= "<li class='bring'>$adjusted_day</li>\n";
	}else {
		//$datastr.= "<li><a href='?d=".base64_encode($selectedYear."-".$selectedMonth."-".$adjusted_day)."'>$adjusted_day</a></li>\n";
		$datastr.= "<li>$adjusted_day</li>\n";
	}

	$day += 1;
	$dayRow += 1;
}
$datastr.= "</ul></div>";


$tpl->assign("datastr",$datastr);
$tpl->assign("selectedMonth",$selectedMonth);
$tpl->assign("selectedYear",$selectedYear);










$odiscount = new Discount();

$disountcacheid = "disountnewcacheid".$pageid."-".$_COOKIE['cityid'];
if ($discountlist = $cache->get($disountcacheid)) {
	$discountlist = unserialize($discountlist);//use cache
} else {
	$discountlist = $odiscount->getNewDiscountList($_COOKIE['cityid'],$pageid);
	@$cache->save(serialize($discountlist),$disountcacheid);
}

foreach($discountlist as $key=>$value){
	$discountlist[$key]['DescriptCut'] = Utilities::cutString($value["Descript"],40);
}


$countpage = $odiscount->getNewDiscountListCount($_COOKIE['cityid']);

$pageStr = getPageStrs($countpage,20,($pageid-1)*20,'discount_new','Pg','');
$tpl->assign("pageStr",$pageStr);
//echo $pageStr;


require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
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


$tpl->assign('pageall',ceil($countpage/20));
$tpl->assign('pageid',$pageid);

$tpl->assign("discountlist",$discountlist);



$tpl->assign('__ADHOST', __ADHOST);


$tpl->displayTemplate();

?>