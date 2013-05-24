<?php
!function_exists('adminmsg') && exit('Forbidden');
$basename="$admin_file?adminjob=diyoption";

if(!$_POST['action']){
	require GetLang('left2');
	$rightdb = $nav_left;
	$db_diy = $db_diy ? explode(',',$db_diy) : array('setforum','setuser','level','postcache','article');
	unset($nav_left);
	$htm = '';
	foreach($rightdb as $k=>$v){
		foreach($v as $k1=>$v1){
			$htm .= "<tr class=\"head_2\"><td>$v1[name]</td></tr><tr class=\"b\"><td>";
			foreach($v1['option'] as $k2=>$v2){
				$key_name = isset($v2[0]) ? $v2[0] : $v1['name'];
				$k_check  = in_array($k2,$db_diy) ? 'checked' : '';
				$htm .= "<div style=\"width:25%;float:left\"><input type=\"checkbox\" name=\"diy[]\" value=\"$k2\" $k_check />$key_name</div>";
			}
			$htm .= '</td></tr>';
		}
	}
	include PrintEot('diyoption');exit;
} else{
	$diy = is_array($diy) ? implode(',',$diy) : '';
	$db->pw_update(
		"SELECT db_name FROM pw_config WHERE db_name='db_diy'",
		"UPDATE pw_config SET db_value='$diy' WHERE db_name='db_diy'",
		"INSERT INTO pw_config(db_name,db_value) VALUES ('db_diy','$diy')"
	);
	updatecache_c();
	adminmsg('operate_success');
}
?>