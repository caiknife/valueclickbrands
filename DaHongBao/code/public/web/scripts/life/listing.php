<?php
require_once("../../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/functions/func.phpwindplugin.php");

require_once('lib/oem.php');
global $oem;

//echo $_COOKIE['cityID'];
if($_GET['cityId']!==$_COOKIE['cityid']){
	Header("Location: cityhome.php");
	exit();
}

$cityId = empty($_GET['cityId']) ? 21 : trim($_GET['cityId']);
$categoryId = empty($_GET['categoryId']) ? 2104 : trim($_GET['categoryId']);
$areaId = empty($_GET['areaId']) ?  trim($_GET['cityId']): trim($_GET['areaId']);
$page = empty($_GET['page']) ? 1 : trim($_GET['page']);
$query = empty($_GET['query']) ? '' : trim($_GET['query']);


$tpl = new sTemplate();
$tpl->setTemplate("new/listkjj.htm");

$oPage = new Page();
$oPage->find("hotkeywords");
$hotkeywords = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
$tpl->assign('hotkeywords',$hotkeywords);

$city = $oem->getService('areaLoad', array('id' => $cityId));
$nowcityname = $city->data->name;
$nowcityname = iconv( "UTF-8", "gb2312//IGNORE" , $nowcityname);
$tpl->assign("nowcityname",$nowcityname);

$tpl->assign("nowareaId",$areaId);

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
//print_r($categorys);
$popvalue = array_pop($newarray);

//print_r($popvalue);

array_unshift($newarray,$popvalue);
$tpl->assign("categorys",$newarray);



$tpl->assign("maxid",$maxid);


$city = $oem->getService('areaLoad', array('id' => $cityId));

//echo ('<h2>当前城市：<a href="cityhome.php?id='.$city->data->id.'">'.$city->data->name.'</a><span><a href="http://www.kijiji.cn/fabu" target="_blank">发布信息</a></span></h2>');

$searchs = $oem->getService('categoryChildren', array('id' => $cityId));
/*
echo ('<form id="searchform" name="searchform" method="get" action="listing.php">
      搜索：<input class="input" name="query" size="40" value="'.$query.'" />
      <input class="input" name="cityId" type="hidden" value="'.$cityId.'" />
      <select name="categoryId" ><option value="'.$cityId.'">所有类目</option>');
		foreach ($searchs as $o){
			if ($o->id == $categoryId){
				echo (' <option  selected="selected" value="'.$o->id.'">&nbsp;'.$o->name.'</option>');
			} else {
				echo (' <option value="'.$o->id.'">&nbsp;'.$o->name.'</option>');
			}
		}
		echo ('</select>
      <input type="submit" value="搜索" class="button2" />
    </form>');
*/
if (!empty($query)){
	//search
	/*
	echo ('<h4>您搜索的关键字是：'.$query.'</h4>');
	$ads = $oem->getService('searchLoad', array('categoryId' => $categoryId , 'query' => urlencode($query), 'page' => $page));
	echo ('<ul>');
	foreach ($ads as $o){
		echo ('<li><a href="view.php?id='.$o->id.'" target="_blank">'.$o->title.'</a></li>');
	}
	echo ('</ul>');
	echo ('<hr />');
	echo ('<span><a href="listing.php?cityId='.$cityId.'&categoryId='.$categoryId.'&query='.urlencode($query).'&page='.($page-1).'">上一页</a></span>');
	echo ('<span><a href="listing.php?cityId='.$cityId.'&categoryId='.$categoryId.'&query='.urlencode($query).'&page='.($page+1).'">下一页</a></span>');
	require_once('footer.php');
	exit();
	*/
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
$tpl->assign("categoryall",$categoryall);

foreach ($cates as $o){
	$o->name = trim(iconv( "UTF-8", "gb2312//IGNORE" , $o->name));
	$o->name = str_replace("@","",$o->name);
}
$tpl->assign("cates",$cates);
//print_r($cates);
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

$ads = $oem->getService('adsLoad', array('categoryId' => $categoryId, 'areaId' => $areaId, 'page' => $page));


//echo ('<ul>');
foreach ($ads as $o){
	$o->title = iconv( "UTF-8", "gb2312//IGNORE" , $o->title);
	$o->description = chinesesubstr(trim(iconv( "UTF-8", "gb2312//IGNORE" , $o->description)),180);
	$o->createdTime = get_date($o->createdTime);

}


$adscount = count($ads);
$tpl->assign("adscount",$adscount);
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

		$ads = $oem->getService('adsLoad', array('categoryId' => $categoryId, 'areaId' => $areaId, 'page' => "2"));
		if(empty($ads)){
			$tpl->assign("hiddennext","1");
			$tpl->assign("showpage","1");
		}else{
			$tpl->assign("nextpage","2");
		}
	}else{


		$nextpage = $page+1;
		$ads = $oem->getService('adsLoad', array('categoryId' => $categoryId, 'areaId' => $areaId, 'page' => $nextpage));
		if(empty($ads)){
			$tpl->assign("hiddennext","1");
		}
		$lastpage = $page-1;
		$tpl->assign("nextpage",$nextpage);
		$tpl->assign("lastpage",$lastpage);
	}
}

$phpwind = new PHPWIND();
$winduser = P_GetCookie("winduser");
if(empty($winduser)){
	$tpl->assign('islogon',0);
}else{
	$user = explode("\t",$winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	//print_r($userinfo);
	$tpl->assign("userinfo",$userinfo[0]);
	$tpl->assign('islogon',1);
}

$oCity = new City($_COOKIE['cityid']);
$cityarray = $oCity->getCityArray();
$citylist = $oCity->getCityList();

$tpl->assign('nowcityname', $cityarray['cityname']);
$tpl->assign('citylist', $citylist);

$citys = $oem->getService('areaChildren',array('id' => 1));
foreach ($citys as $b){
	$b->name = iconv( "UTF-8", "gb2312//IGNORE" , $b->name);
}
$tpl->assign("city",$citys);

$tpl->displayTemplate();
?>