<?php
//session_start();
require_once("../etc/const.inc.php");
require_once "Cache/Lite.php";
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
$options = array(
    'cacheDir' => __FILE_FULLPATH.'cache/',
    'lifeTime' => 0.5*60*60,
    'pearErrorMode' => CACHE_LITE_ERROR_DIE
);
$cache = new Cache_Lite($options);





$url = base64_decode($_GET['id']);

preg_match_all ("/query\=(.*)&page/",$url,$output);
$searchText = urldecode($output[1][0]);
//echo $_GET['id'];
//echo $url;


$contents = file_get_contents($url);

//echo $contents;
@$cache->save($contents,base64_encode($url));
$ads = @simplexml_load_string($contents);


//var_dump($ads);
//exit();
//if(count($ads)==0){
//	$tpl->assign("kjjcount", "0");
//}else{
//	$tpl->assign("kjjcount", "1");
//}
//$maxcount4 = count($ads)>5?5:count($ads);

$i=0;
$string="";


if(count($ads)==0 || $ads === false){
	//echo "";
	exit();
}
foreach ($ads as $key=>$o){
	if($i>4) break;
	$o->title = str_replace($searchText,"<strong>".$searchText."</strong>",$o->title);
	//$o->description = Utilities::cutString($o->description,86);

	$o->description = chinesesubstr($o->description,80)."..";
	$o->description = str_replace($searchText,"<strong>".$searchText."</strong>",$o->description);
	//$o->title = iconv( "UTF-8", "gb2312//IGNORE" , $o->title);
	//$o->description = iconv( "UTF-8", "gb2312//IGNORE" , $o->description);
	//$o->description = Utilities::cutString($o->description,86);
	//$o->description = str_replace($searchText,"<strong>".$searchText."</strong>",$o->description);
	$o->createdTime = get_date($o->createdTime);

	$string .= "<div class='news_box'><ul><li class='lititle'><a href='/life/view.php?id=";
	$string .= $o->id;
	$string .= "' target='_blank'>";
	$string .= $o->title;
	$string .= "</a></li><li class='litext'>";
	$string .= $o->description;
	$string .= "</li><li class='litime1'>发表时间�?";
	$string .= $o->createdTime;
	$string .= "</li></ul></div>";

	$i++;
}
$searchText = iconv( "UTF-8", "gb2312//IGNORE" , $searchText);
$searchText = trim(Utilities::encode($searchText)); //解码;
$string .= "<div class='search_bottom'><strong>�? </strong> <a href='/se-".$searchText."-1-4/'>同城生活</a> 搜索结果 显示1-";
if(count($ads)>5){
	$string .=5;
}else{
	$string .= count($ads);
}
$string .="�? <a href='/se-".$searchText."-1-4/'>更多>></a></div>";
//$string = iconv( "UTF-8", "gb2312//IGNORE" , $string);
if(count($ads)==0){
	echo "";
}else{
	echo $string;
}

?>
