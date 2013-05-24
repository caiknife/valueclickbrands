<?PHP
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.discount.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/dao/class.UserScoreDao.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once(__ROOT_PATH . "lib/functions/func.Common.php");
require_once "Cache/Lite.php";
$options = array(
    'cacheDir' => __FILE_FULLPATH.'cache/discount/',
    'lifeTime' => 0.25*60*60,
    'pearErrorMode' => CACHE_LITE_ERROR_DIE
);
$cache = new Cache_Lite($options);
$phpwind = new PHPWIND();



//if city id is empty .. default 21 and set cookie
$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();


$tpl = new sTemplate();
$tpl->setTemplate("new/discount_detail.htm");

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$id = $_GET['id'];
$odiscount = new Discount();
$discountDetail = $odiscount->getDiscountDetail($id);

if(empty($discountDetail)){
	include_once(__INCLUDE_ROOT."/scripts/notfound.php");
    exit;
}

if($discountDetail["ImageDownload"] == 1) {
	$discountDetail["ImageURL"] = Utilities::getImageURL($discountDetail["Coupon_"]);
	$discountDetail["MiddleImageURL"] = Utilities::getMiddleImageURL($discountDetail["Coupon_"]);
}
$discountDetail['tagname'] = Utilities::getTagSrcForDiscount($discountDetail['tagname']);


//add score
$oUserScoreDao = new UserScoreDao($discountDetail['authorid']);
$oUserScoreDao->addScore("REFRESH",$discountDetail['Coupon_'],"discount","","","");



if(empty($discountDetail)){
	redirect301("notfound.php");
	exit;
}

$weekarray = array("","一","二","三","四","五","六","天");
$startdate = $discountDetail['StartDate'];
$startdate = explode("-",$startdate);
$weekid = date("N",mktime(0, 0, 0, $startdate[1], $startdate[2], $startdate[0]));
$week1 = $weekarray[$weekid];
$discountDetail['StartWeek'] = $week1;

$expiredate = $discountDetail['ExpireDate'];
$expiredate = explode("-",$expiredate);
$weekid = date("N",mktime(0, 0, 0, $expiredate[1], $expiredate[2], $expiredate[0]));
$week1 = $weekarray[$weekid];
$discountDetail['EndWeek'] = $week1;


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

$tpl->assign('datastr',$datastr);
$tpl->assign('discountDetail',$discountDetail);
$tpl->assign("selectedMonth",$selectedMonth);
$tpl->assign("selectedYear",$selectedYear);
$lasturl = "?d=".base64_encode($selectedYear."-".($selectedMonth-1)."-".$selectedDay);
$tpl->assign("lasturl",$lasturl);
$nexturl = "?d=".base64_encode($selectedYear."-".($selectedMonth+1)."-".$selectedDay);
$tpl->assign("nexturl",$nexturl);


$discountReply = $odiscount->getDiscountReply($discountDetail['tid']);
$discountReplyCount = $odiscount->getDiscountReplyCount($discountDetail['tid']);

foreach($discountReply as $key=>$value){
	$discountReply[$key]['postdate'] = get_date($value['postdate']);
	$icon = $value['icon'];
	if(empty($icon)){
		$discountReply[$key]['touxiang'] = "/bbs/images/face/none.gif";
	}else{
		$icon = explode("|",$icon);
		if(empty($icon[1])){
			$discountReply[$key]['touxiang'] = "/bbs/images/face/".$icon[0];
		}else{
			$checkout = $icon[1];
			$checkout = explode("/",$checkout);
			if(count($checkout)==1){
				$discountReply[$key]['touxiang'] = __IMAGE_SRC."attachment/upload/".$icon[1];
			}else{
				$discountReply[$key]['touxiang'] = $icon[1];
			}
		}
	}

}
$tpl->assign('discountReply',$discountReply);

$pageCount = ceil($discountReplyCount/ 10);

$pageString = $phpwind->getNewPageStr("1",$pageCount);
if($pageCount==0){
	$pageString="";
}
$tpl->assign("pageString", $pageString);
$tpl->assign("pageAll", $pageCount);


$tpl->assign('nowcityname',$cityarray['cityname']);
$tpl->assign('citylist',$citylist);


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

//add google ads by thomas.fu 07/14/09
//$params = array();
//$adsWords = new AdWordsDao($discountDetail["Descript"], 8);
//$adsResult = $adsWords -> dispatch($params);

$splitCountArr = array(8);
$baiduParams = array('splitCountArr' => array(-8));
$adsParams = array('splitCountArr' => $splitCountArr, 'keyword' => $discountDetail["Descript"], "IsHighlight" => true);
$adsResult = AdWordsDao::getAdsScript($adsParams, $baiduParams);
$tpl->assign("adsResult", $adsResult);
if(substr($_SERVER['REQUEST_URI'],-3)=="tui"){
	$tpl->assign("tui",1);
}


$tpl->displayTemplate();

?>
