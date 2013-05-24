<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.discount.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once "Cache/Lite.php";
$options = array(
    'cacheDir' => __FILE_FULLPATH.'cache/discount/',
    'lifeTime' => 0.25*60*60,
    'pearErrorMode' => CACHE_LITE_ERROR_DIE
);
$cache = new Cache_Lite($options);

//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();


$tpl = new sTemplate();
$tpl->setTemplate("new/discount.htm");

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$odiscount = new Discount();

$date = getDateTime("Y-m-d");
$date1 = getDateTime("Y-m-d",time()+24*60*60);
$date2 = getDateTime("Y-m-d",time()+48*60*60);

//get hotcategorycouponlist
$disountcacheid = "disountcacheid".$date.$_COOKIE['cityid'];
if ($list1 = $cache->get($disountcacheid)) {
	$list1 = unserialize($list1);//use cache
} else {
	$list1 = $odiscount->getDiscountList($date,$_COOKIE['cityid'],"50");
	@$cache->save(serialize($list1),$disountcacheid);
}

$disountcacheid = "disountcacheid".$date1.$_COOKIE['cityid'];
if ($list2 = $cache->get($disountcacheid)) {
	$list2 = unserialize($list2);//use cache
} else {
	$list2 = $odiscount->getDiscountList($date1,$_COOKIE['cityid'],"30");
	@$cache->save(serialize($list2),$disountcacheid);
}

$disountcacheid = "disountcacheid".$date2.$_COOKIE['cityid'];
if ($list3 = $cache->get($disountcacheid)) {
	$list3 = unserialize($list3);//use cache
} else {
	$list3 = $odiscount->getDiscountList($date2,$_COOKIE['cityid'],"20");
	@$cache->save(serialize($list3),$disountcacheid);
}

if(!empty($list1)){
	foreach($list1 as $key=>$value){
		$list1[$key]['tagname'] = Utilities::getTagSrcForDiscount($value['tagname']);
		$list1[$key]['ImageURL'] = __IMAGE_SRC.Utilities::getSmallImageURL($value['Coupon_']);
		$list1[$key]['DescriptCut'] = Utilities::cutString($value["Descript"],34);
		$list1[$key]['Detail'] = strip_tags($value["Detail"]);
		$list1[$key]['Detail'] = Utilities::cutString($list1[$key]['Detail'],154);
	}
}

if(!empty($list2)){
	foreach($list2 as $key=>$value){
		$list2[$key]['tagname'] = Utilities::getTagSrcForDiscount($value['tagname']);
		$list2[$key]['ImageURL'] = __IMAGE_SRC.Utilities::getSmallImageURL($value['Coupon_']);
		$list2[$key]['DescriptCut'] = Utilities::cutString($value["Descript"],34);
		$list2[$key]['Detail'] = strip_tags($value["Detail"]);
		$list2[$key]['Detail'] = Utilities::cutString($list2[$key]['Detail'],154);
	}
}

if(!empty($list3)){
	foreach($list3 as $key=>$value){
		$list3[$key]['tagname'] = Utilities::getTagSrcForDiscount($value['tagname']);
		$list3[$key]['ImageURL'] = __IMAGE_SRC.Utilities::getSmallImageURL($value['Coupon_']);
		$list3[$key]['DescriptCut'] = Utilities::cutString($value["Descript"],34);
		$list3[$key]['Detail'] = strip_tags($value["Detail"]);
		$list3[$key]['Detail'] = Utilities::cutString($list3[$key]['Detail'],154);
	}
}


$dateweek = getDateTime("Y-m-d",time()+7*24*60*60);
if(empty($list1) && empty($list2) && empty($list3)){
	$disountcacheid = "disountcacheweek".$dateweek.$_COOKIE['cityid'];
	if ($list4 = $cache->get($disountcacheid)) {
		$list4 = unserialize($list4);//use cache
	} else {
		$list4 = $odiscount->getDiscountListForWeek($dateweek,$_COOKIE['cityid'],"100");
		@$cache->save(serialize($list4),$disountcacheid);
	}
}

if(!empty($list4)){
	foreach($list4 as $key=>$value){
		$list4[$key]['tagname'] = Utilities::getTagSrcForDiscount($value['tagname']);
		$list4[$key]['ImageURL'] = __IMAGE_SRC.Utilities::getSmallImageURL($value['Coupon_']);
		$list4[$key]['DescriptCut'] = Utilities::cutString($value["Descript"],34);
		$list4[$key]['Detail'] = strip_tags($value["Detail"]);
		$list4[$key]['Detail'] = Utilities::cutString($list4[$key]['Detail'],154);
	}
}

$tpl->assign("list1",$list1);
$tpl->assign("list2",$list2);
$tpl->assign("list3",$list3);
$tpl->assign("list4",$list4);





$weekarray = array("","一","二","三","四","五","六","天");
$weekid = getDateTime("N",time());
$week1 = $weekarray[$weekid];
$tpl->assign("date1",getDateTime("n月d日",time()));
$tpl->assign("week1",$week1);

$weekid2 = getDateTime("N",time()+24*60*60);
$week2 = $weekarray[$weekid2];
$tpl->assign("date2",getDateTime("n月d日",time()+24*60*60));
$tpl->assign("week2",$week2);

$weekid3 = getDateTime("N",time()+48*60*60);
$week3 = $weekarray[$weekid3];
$tpl->assign("date3",getDateTime("n月d日",time()+48*60*60));
$tpl->assign("week3",$week3);




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
$lastday = date('t',mktime(0,0,0,$selectedMonth,1,$selectedYear));//给定月份所应有的天数
if($selectedDay>$lastday){
	$selectedDay = $lastday;
}

//获得当月第一天数据型星期几
$firstday = date('w',mktime(0,0,0,$selectedMonth,1,$selectedYear));

//获取当月最后一天
$lastday = getDateTime('t');//给定月份所应有的天数
do{
$monthOrig = date('m',mktime(0,0,0,$selectedMonth,1,$selectedYear));
$monthTest = date('m',mktime(0,0,0,$selectedMonth,$lastday,$selectedYear));
if($monthTest != $monthOrig){$lastday -= 1;}
}while($monthTest != $monthOrig);
//获得当月对应的英文名
$monthName = date('F',mktime(0,0,0,$selectedMonth,1,$selectedYear));
//显示日历头
$days = array("周日","周一","周二","周三","周四","周五","周六");
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

//空出当月第一天的位置
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
	//当天用红色表示
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
$tpl->assign("selectedDay",$selectedDay);



$lasturl = "?d=".base64_encode($selectedYear."-".($selectedMonth-1)."-".$selectedDay);
$tpl->assign("lasturl",$lasturl);
$nexturl = "?d=".base64_encode($selectedYear."-".($selectedMonth+1)."-".$selectedDay);
$tpl->assign("nexturl",$nexturl);

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

$tpl->assign('__ADHOST', __ADHOST);

$tpl->displayTemplate();

?>
