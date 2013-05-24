<?php
require_once("../../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");

require_once('lib/oem.php');
global $oem;


if(empty($_GET['query'])){
	$return = $_SERVER['HTTP_REFERER'];
	setcookie("cityid",$_GET['id'],time()+9999999);
	Header("Location: ".$return."");
	exit();
}


$cityId = empty($_GET['cityId']) ? 21 : trim($_GET['cityId']);
$categoryId = empty($_GET['categoryId']) ? 2104 : trim($_GET['categoryId']);
$areaId = empty($_GET['areaId']) ?  trim($_GET['cityId']): trim($_GET['areaId']);
$page = empty($_GET['page']) ? 1 : trim($_GET['page']);
$query = empty($_GET['query']) ? '' : trim($_GET['query']);



$tpl = new sTemplate();
$tpl->setTemplate("new/searchkjj.htm");


$cityId=$_GET['cityId'];
$city = $oem->getService('areaLoad', array('id' => $cityId));
$nowcityname = $city->data->name;
$nowcityname = iconv( "UTF-8", "gb2312//IGNORE" , $nowcityname);
$tpl->assign("nowcityname",$nowcityname);
$tpl->assign("nowareaId",$areaId);
$tpl->assign("query",$query);
$tpl->assign("querybase64",base64_encode($query));

$tpl->assign("queryleft",urlencode($query));
//echo urlencode($query);
//echo $categoryId;
$tpl->assign("searchcategory",$categoryId);


$nowcategory = $oem->getService('categoryLoad', array('id' => $categoryId));
$nowcategory->data->name = iconv( "UTF-8", "gb2312//IGNORE" , $nowcategory->data->name);
$tpl->assign("nowcategoryname",$nowcategory->data->name);
//print_r($nowcategory);

$i=0;
$categorys = $oem->getService('categoryChildren', array('id' => $cityId));
foreach ($categorys as $key=>$o){
	$newarray[$i]['name'] = trim(iconv( "UTF-8", "gb2312//IGNORE" , $o->name));
	$newarray[$i]['count'] = $o->adsCount;
	$newarray[$i]['id'] = $o->id;

	if($i>0){
		if($newarray[$i]['count']>$maxcount){
			$maxid = $o->id;
			$maxcount = $o->adsCount;
		}
	}else{
		$maxcount = $o->adsCount;
		$maxid = $o->id;
	}

	$i++;
}
//print_r($newarray);
$tpl->assign("categorys",$newarray);
$tpl->assign("maxid",$maxid);


//$city = $oem->getService('areaLoad', array('id' => $_GET['cityId']));

//echo ('<h2>当前城市：<a href="cityhome.php?id='.$city->data->id.'">'.$city->data->name.'</a><span><a href="http://www.kijiji.cn/fabu" target="_blank">发布信息</a></span></h2>');

$searchs = $oem->getService('categoryChildren', array('id' => $cityId));

if (!empty($query)){
}


if($categoryId>1000000){
	$leftcategoryid = substr($categoryId,0,-3);
	$category = $oem->getService('categoryLoad', array('id' => $leftcategoryid));
	$cates = $oem->getService('categoryChildren', array('id' => $leftcategoryid));
	$category->data->name = trim(iconv( "UTF-8", "gb2312//IGNORE" , $category->data->name));
	$category1 = $oem->getService('categoryLoad', array('id' => $categoryId));
	$category1->data->name = trim(iconv( "UTF-8", "gb2312//IGNORE" , $category1->data->name));
	$tpl->assign("hasnextcat","1");

	$categoryall = $leftcategoryid;
}else{
	$cates = $oem->getService('categoryChildren', array('id' => $categoryId));
	$category = $oem->getService('categoryLoad', array('id' => $categoryId));
	$category->data->name = trim(iconv( "UTF-8", "gb2312//IGNORE" , $category->data->name));

	$categoryall = $categoryId;
}
//print_r($cates);
$tpl->assign("categoryall",$categoryall);

foreach ($cates as $o){
	$o->name = trim(iconv( "UTF-8", "gb2312//IGNORE" , $o->name));
}
$tpl->assign("cates",$cates);
$categoryname = $category->data->name;



//print_r($cates);

//echo ('<hr />');
$area = $oem->getService('areaLoad', array('id' => $areaId));

if($areaId>1000){
	$leftareaId = substr($areaId,0,-3);
	$areas = $oem->getService('areaChildren', array('id' => $leftareaId));
	$areas1 = $oem->getService('areaChildren', array('id' => $areaId));
	$match1 = $areaId;
}else{
	$areas = $oem->getService('areaChildren', array('id' => $areaId));
}
if($areaId>1000000){
	$leftareaId = substr($areaId,0,-7);
	$areaId1 = substr($areaId,0,-4);
	$areas = $oem->getService('areaChildren', array('id' => $leftareaId));
	$areas1 = $oem->getService('areaChildren', array('id' => $areaId1));
	$match2 = $areaId;
	$match1 = $areaId1;
}
foreach ($areas as $o){
	$o->name = trim(iconv( "UTF-8", "gb2312//IGNORE" , $o->name));
}
foreach ($areas1 as $o){
	$o->name = trim(iconv( "UTF-8", "gb2312//IGNORE" , $o->name));
}
//print_r($areas);
$tpl->assign("categoryId",$categoryId);
$tpl->assign("cityId",$cityId);
$tpl->assign("nowcityid",$cityId);
$tpl->assign("match1",$match1);
$tpl->assign("match2",$match2);
//echo ('<hr />');

$query = iconv( "gb2312", "UTF-8" , $_GET['query']);
$ads = $oem->getService('searchLoad', array('categoryId' => $_GET['categoryId'] , 'query' => urlencode($query), 'page' => $page));
//print_r($ads);
$adscount = count($ads);
$tpl->assign("adscount",$adscount);
//echo ('<ul>');
foreach ($ads as $o){
	$o->title = iconv( "UTF-8", "gb2312//IGNORE" , $o->title);
	$o->description = chinesesubstr(trim(iconv( "UTF-8", "gb2312//IGNORE" , $o->description)),180);
	$o->createdTime = get_date($o->createdTime);

}
//echo ('</ul>');
//echo ('<hr />');

function chinesesubstr($arg_strContent,$arg_intTrimLength)
{
    $strReturnString = "";
    $intLoopCount = 0;
    while ($intLoopCount < $arg_intTrimLength)
    {
        $chrSingle = substr($arg_strContent,$intLoopCount,1);
        if(ord($chrSingle) > 0x80)
        {
            $intLoopCount++;
            $arg_intTrimLength++;
        }
        $intLoopCount++;
    }
    $strReturnString = substr($arg_strContent,0,$intLoopCount);

    return $strReturnString;
}
function get_date($timestamp,$timeformat=''){
	$db_datefm="Y-m-d H:i:s";
	$db_timedf="8";
	$date_show=$timeformat ? $timeformat : ($_datefm ? $_datefm : $db_datefm);
	if($_timedf){
		$offset = $_timedf=='111' ? 0 : $_timedf;
	}else{
		$offset = $db_timedf=='111' ? 0 : $db_timedf;
	}
	return gmdate($date_show,$timestamp+$offset*3600);
}


$tpl->assign("ads",$ads);

$tpl->assign("areas",$areas);
$tpl->assign("areas1",$areas1);

$tpl->assign("categoryname",$categoryname);
$tpl->assign("category1name",$category1->data->name);
$tpl->assign("nowpage",$page);

if(empty($ads)){
	$tpl->assign("hiddennext","1");
	$tpl->assign("hiddenlast","1");
	//$tpl->assign("","1");
}else{
	if($page==1){
		$tpl->assign("hiddenlast","1");

		$ads = $oem->getService('searchLoad', array('categoryId' => $categoryId , 'query' => urlencode($query), 'page' => '2'));
		if(empty($ads)){
			$tpl->assign("hiddennext","1");
			$tpl->assign("showpage","1");
		}else{
			$tpl->assign("nextpage","2");
		}
	}else{


		$nextpage = $page+1;
		$ads =  $oem->getService('searchLoad', array('categoryId' => $categoryId , 'query' => urlencode($query), 'page' => $nextpage));
		if(empty($ads)){
			$tpl->assign("hiddennext","1");
		}
		$lastpage = $page-1;
		$tpl->assign("nextpage",$nextpage);
		$tpl->assign("lastpage",$lastpage);
	}
}



$citys = $oem->getService('areaChildren',array('id' => 1));
foreach ($citys as $b){
	$b->name = iconv( "UTF-8", "gb2312//IGNORE" , $b->name);
}
$tpl->assign("city",$citys);

$tpl->displayTemplate();
?>