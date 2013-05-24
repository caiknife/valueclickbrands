<?php
!function_exists('adminmsg') && exit('Forbidden');
$basename="$admin_file?adminjob=setbwd";
require_once(R_P.'require/forum.php');

$db_perpage = 20;
if(!$action){
	(!is_numeric($page) || $page<1) && $page = 1;
	$limit = "LIMIT ".($page-1)*$db_perpage.",$db_perpage";
	$type!='1' && $type='0';
	$rt = $db->get_one("SELECT COUNT(*) AS sum FROM pw_wordfb WHERE type='$type'");
	$pages = numofpage($rt['sum'],$page,ceil($rt['sum']/$db_perpage),"$basename&type=$type&");

	$replacedb = array();
	$query = $db->query("SELECT * FROM pw_wordfb WHERE type='$type' ORDER BY id $limit");
	while($rt = $db->fetch_array($query)){
		HtmlConvert($rt);
		$replacedb[$rt['id']]=$rt;
	}
	include PrintEot('setbwd');exit;

} elseif($action=='add'){
	if(!$_POST['step']){
		include PrintEot('setbwd');exit;
	} else{
		$basename.="&type=$type";
		if(empty($word) || empty($rep) || !is_numeric($type)){
			adminmsg('operate_fail');
		}
		foreach($word as $key=>$value){
			if($value && $rep[$key]){
				$db->update("INSERT INTO pw_wordfb(word,wordreplace,type) VALUES('$value','$rep[$key]','$type')");
			}
		}
		if($update){
			($db_wordsfb > 0 && $db_wordsfb < 9) ? $db_wordsfb++ : $db_wordsfb = 1;
			$db->update("REPLACE INTO pw_config(db_name,db_value) VALUES ('db_wordsfb','$db_wordsfb')");
			updatecache_c();
		}
		updatecache_w();
		adminmsg('operate_success');
	}
} elseif($_POST['action']=='edit'){
	$basename.="&type=$type";
	if(is_array($selid)){
		$ids = '';
		foreach($selid as $key => $value){
			is_numeric($value) && $ids .= $value.',';
		}
		$ids = substr($ids,0,-1);
		$db->update("DELETE FROM pw_wordfb WHERE id IN($ids)");
	}
	if(is_array($word)){
		foreach($word as $key=>$value){
			$db->update("UPDATE pw_wordfb SET word='$value',wordreplace='$replace[$key]' WHERE id='$key'");
		}
	}
	if($update){
		($db_wordsfb > 0 && $db_wordsfb < 9) ? $db_wordsfb++ : $db_wordsfb = 1;
		$db->update("REPLACE INTO pw_config(db_name,db_value) VALUES ('db_wordsfb','$db_wordsfb')");
		updatecache_c();
	}
	updatecache_w();
	adminmsg('operate_success');
} elseif($action=='renew'){
	if(!$_POST['step']){
		$desvalue = ($db_wordsfb > 0 && $db_wordsfb < 9) ? $db_wordsfb+1 : 1;
		include PrintEot('setbwd');exit;
	} else{
		(!is_numeric($desvalue) || $desvalue < 1 || $desvalue > 9) && $desvalue = 1;
		$db->update("REPLACE INTO pw_config(db_name,db_value) VALUES ('db_wordsfb','$desvalue')");
		updatecache_c();
		adminmsg('operate_success');
	}
}
?>