<?php
!function_exists('adminmsg') && exit('Forbidden');

isset($adskin) ? Cookie('adskin',$adskin) : $adskin = GetCookie('adskin');
if($adskin){
	include PrintEot('index');exit;
}
require GetLang('left2');

$leftdb = $nav_left;
$headdb = array();
$guides = 'var guides={';
$titles = 'var titles={';
$dfcate = $gd_diy = '';
$db_diy = $db_diy ? explode(',',$db_diy) : array('setforum','setuser','level','postcache','article');

if(If_manager){
	$headdb['manager'] = $nav_head['manager'];
	$output1 = '';
	foreach($nav_manager['option'] as $key=>$val){
		$output1 .= "'$key' : ['$val[0]','$val[1]'],";
	}
	$output1 = substr($output1,0,-1);
	$guides .= "\r\n\t'manager' : {'manager' : {{$output1}}},";
	$titles .= "'manager' : '$nav_manager[name]',";
}
foreach($leftdb as $cate=>$left){
	$output1 = '';
	foreach($left as $title=>$value){
		$output2 = '';
		foreach($value['option'] as $key=>$val){
			if($rightset[$key]){
				if(in_array($key,$db_diy)){
					$dfcate = 'common';
					if(isset($val[0])){
						$gd_diy .= "'$key' : ['$val[0]','$val[1]'],";
					} else{
						foreach($val as $k=>$v){
							$gd_diy .= "'$key' : ['$value[name]','$v[1]'],";break;
						}
					}
				}
				if(isset($val[0])){
					$output2 .= "'$key' : ['$val[0]','$val[1]'],";
				} else{
					foreach($val as $k=>$v){
						$output2 .= "'$k' : ['$v[0]','$v[1]'],";
					}
				}
			}
		}
		if(!$output2) continue;
		$titles .= "'$title' : '$value[name]',";
		$output2 = substr($output2,0,-1);
		$output1.= "'$title' : {{$output2}},";
	}
	if(!$output1) continue;
	$output1 = substr($output1,0,-1);
	$guides .= "\r\n\t'$cate' : {{$output1}},";
	$nav_head[$cate] && $headdb[$cate] = $nav_head[$cate];
	!$dfcate && $dfcate = $cate;
}
if($dfcate == 'common'){
	$gd_diy = substr($gd_diy,0,-1);
	$guides .= "\r\n\t'common' : {'diy' : {{$gd_diy}}},";
	$titles .= "'diy' : '$_diy',";
}
$guides = substr($guides,0,-1)."\n}";
$titles = substr($titles,0,-1)."}";

include PrintEot('index2');exit;
?>