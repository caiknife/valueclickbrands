<?php
!function_exists('adminmsg') && exit('Forbidden');
$basename="$admin_file?adminjob=ptable";

if(!$action){
	if(!$_POST['step']){
		$tmsgdb  = $postdb = array();
		$tlistdb = unserialize($db_tlist);
		$query = $db->query("SHOW TABLE STATUS LIKE 'pw_tmsgs%'");
		while($rs=$db->fetch_array($query)){
			$rs['Data_length'] = round(($rs['Data_length']+$rs['Index_length'])/1048576,2);
			$key = substr(str_replace($GLOBALS['PW'],'pw_',$rs['Name']),8);
			$pw_tmsgs = 'pw_tmsgs'.$key;
			@extract($db->get_one("SELECT MIN(tid) AS tmin,MAX(tid) AS tmax FROM $pw_tmsgs"));
			$rs['tmin'] = $tmin;
			$rs['tmax'] = $tmax;
			list($rs['tidmin'],$rs['tidmax'])=maxmin($key);
			$tmsgdb[$key] = $rs;
		}

		$query = $db->query("SHOW TABLE STATUS LIKE 'pw_posts%'");
		while($rs=$db->fetch_array($query)){
			$rs['Data_length'] = round(($rs['Data_length']+$rs['Index_length'])/1048576,2);
			$rs['key'] = substr(str_replace($GLOBALS['PW'],'pw_',$rs['Name']),8);
			$rs['sel'] = $rs['key']==$db_ptable ? 'checked' : '';
			$pw_posts  = GetPtable($rs['key']);
			@extract($db->get_one("SELECT MIN(tid) AS tmin,MAX(tid) AS tmax FROM $pw_posts"));
			$rs['tmin'] = $tmin;
			$rs['tmax'] = $tmax;
			$postdb[] = $rs;
		}
		require_once PrintEot('ptable');
	} elseif($_POST['step']=='3'){
		!is_array($ttable) && adminmsg('numerics_checkfailed');
		arsort($ttable);
		foreach($ttable as $key=>$value){
			!is_numeric($value) && adminmsg('numerics_checkfailed');
		}
		$ttable = serialize($ttable);
		$db->pw_update(
			"SELECT db_name FROM pw_config WHERE db_name='db_tlist'",
			"UPDATE pw_config SET db_value='$ttable' WHERE db_name='db_tlist'",
			"INSERT INTO pw_config(db_name,db_value) VALUES ('db_tlist','$ttable')"
		);
		updatecache_c();
		adminmsg('operate_success');
	} elseif($_POST['step']=='5'){
		$db->pw_update(
			"SELECT db_name FROM pw_config WHERE db_name='db_ptable'",
			"UPDATE pw_config SET db_value='$ktable' WHERE db_name='db_ptable'",
			"INSERT INTO pw_config(db_name,db_value) VALUES ('db_ptable','$ktable')"
		);
		$plist = '';
		$query = $db->query("SHOW TABLE STATUS LIKE 'pw_posts%'");
		while($rs=$db->fetch_array($query)){
			$j = str_replace($PW.'posts','',$rs['Name']);
			$j && $plist .= $j.',';
		}
		$plist = substr($plist,0,-1);
		$db->pw_update(
			"SELECT db_name FROM pw_config WHERE db_name='db_plist'",
			"UPDATE pw_config SET db_value='$plist' WHERE db_name='db_plist'",
			"INSERT INTO pw_config(db_name,db_value) VALUES ('db_plist','$plist')"
		);
		updatecache_c();
		adminmsg('operate_success');
	}
}elseif($action=='create'){
	$num_a = array();
	if($type==1){
		$query = $db->query("SHOW TABLE STATUS LIKE 'pw_tmsgs%'");
		while($rs=$db->fetch_array($query)){
			$j=str_replace($PW.'tmsgs','',$rs['Name']);
			$num_a[]=(int)$j;
		}
		$num = max($num_a)+1;
		$table = 'pw_tmsgs'.$num;
		$CreatTable = $db->get_one("SHOW CREATE TABLE pw_tmsgs");
		$sql = str_replace($CreatTable['Table'],$table,$CreatTable['Create Table']);
		$db->query($sql);
		if($db_tlist){
			$tlistdb = unserialize($db_tlist);
			$tidmax  = max($tlistdb);
		} else{
			$tlistdb = array();
			$tidmax  = 0;
		}
		@extract($db->get_one("SELECT MAX(tid) AS tid FROM pw_threads"));
		$tidmax = max($tidmax,$tid);
		$tlistdb[$num] = $tidmax + 100;
		arsort($tlistdb);
		$db_tlist = serialize($tlistdb);
		$db->pw_update(
			"SELECT db_name FROM pw_config WHERE db_name='db_tlist'",
			"UPDATE pw_config SET db_value='$db_tlist' WHERE db_name='db_tlist'",
			"INSERT INTO pw_config(db_name,db_value) VALUES ('db_tlist','$db_tlist')"
		);
	} else{
		$i = 0;
		$plist = '';
		$query = $db->query("SHOW TABLE STATUS LIKE 'pw_posts%'");
		while($rs=$db->fetch_array($query)){
			$i++;
			$j=str_replace($PW.'posts','',$rs['Name']);
			$j && $plist .= $j.',';
			$num_a[]=$j;
		}
		if($i==1){
			extract($db->get_one("SELECT MAX(pid) AS pid FROM pw_posts"));
			$db->update("REPLACE INTO pw_pidtmp (pid) VALUES ('$pid')");
			$num=1;
		}else{
			$num=max($num_a)+1;
		}
		$table='pw_posts'.$num;
		$CreatTable = $db->get_one("SHOW CREATE TABLE pw_posts");
		$sql=str_replace($CreatTable['Table'],$table,$CreatTable['Create Table']);
		$db->query($sql);

		$plist .= $num;
		$db->pw_update(
			"SELECT db_name FROM pw_config WHERE db_name='db_ptable'",
			"UPDATE pw_config SET db_value='$num' WHERE db_name='db_ptable'",
			"INSERT INTO pw_config(db_name,db_value) VALUES ('db_ptable','$num')"
		);
		$db->pw_update(
			"SELECT db_name FROM pw_config WHERE db_name='db_plist'",
			"UPDATE pw_config SET db_value='$plist' WHERE db_name='db_plist'",
			"INSERT INTO pw_config(db_name,db_value) VALUES ('db_plist','$plist')"
		);		
	}
	updatecache_c();
	adminmsg('operate_success');
}elseif($action=='movedata'){
	if(!$step){
		$table_sel='';
		$query = $db->query("SHOW TABLE STATUS LIKE 'pw_posts%'");
		while($rs=$db->fetch_array($query)){
			$key = substr(str_replace($GLOBALS['PW'],'pw_',$rs['Name']),8);
			$table_sel .= "<option value=\"$key\">$rs[Name]</option>";
		}
		require_once PrintEot('ptable');
	} else{
		$step>1 && PostCheck($verify);
		set_time_limit(0);
		$db_bbsifopen=='1' && adminmsg('bbs_open');

		$tfrom = (int) $tfrom;
		$tto   = (int) $tto;
		if($tfrom==$tto){
			adminmsg('table_same');
		}
		!$lines && $lines=200;
		!$tstart && $tstart=0;

		$ftable = $tfrom ? 'pw_posts'.$tfrom : 'pw_posts';
		$ttable = $tto   ? 'pw_posts'.$tto   : 'pw_posts';
		if(!$tend){
			@extract($db->get_one("SELECT MAX(tid) AS tend FROM $ftable"));
		}
		$end = $tstart + $lines;
		$end > $tend && $end = $tend;
		$db->update("INSERT INTO $ttable SELECT * FROM $ftable WHERE tid>'$tstart' AND tid<='$end'");
		$db->update("DELETE FROM $ftable WHERE tid>'$tstart' AND tid<='$end'");
		$db->update("UPDATE pw_threads SET ptable='$tto' WHERE tid>'$tstart' AND tid<='$end' AND ptable='$tfrom'");

		if($end<$tend){
			$step++;
			$j_url="$basename&action=$action&step=$step&tstart=$end&tend=$tend&tfrom=$tfrom&tto=$tto&lines=$lines";
			adminmsg('table_change',EncodeUrl($j_url),2);
		}else{
			adminmsg('operate_success');
		}
	}
}elseif($action=='movetmsg'){
	$tlistdb = unserialize($db_tlist);
	if(!$step){
		$id<1 && $id='';
		$pw_tmsgs = 'pw_tmsgs'.$id;
		@extract($db->get_one("SELECT MIN(tid) AS tmin,MAX(tid) AS tmax FROM $pw_tmsgs"));
		list($tidmin,$tidmax)=maxmin($id);
		$tiderror = '';
		$tmin<=$tidmin && $tiderror .= "$tmin - ".($tmax > $tidmin ? $tidmin : $tmax)." &nbsp;&nbsp;";
		$tidmax && $tmax > $tidmax && $tiderror .= ($tidmax+1)." - $tmax";
		$tiderror=='' && adminmsg('operate_undefined');

		require_once PrintEot('ptable');
	} else{
		$step>1 && PostCheck($verify);
		set_time_limit(0);
		$db_bbsifopen=='1' && adminmsg('bbs_open');
		list($tidmin,$tidmax)=maxmin($id);
		!$lines && $lines=5000;
		
		if($tmin<=$tidmin && $step<3){
			!$tstart && $tstart = $tmin-1;
			$end = $tstart + $lines;
			$tend= $tmax > $tidmin ? $tidmin : $tmax;
			$end > $tend && $end = $tend;
			$ttable = GetTtable($end);
			$step = 2;
		} else{
			!$tstart && $tstart = $tidmax;
			$end = $tstart + $lines;
			$tend = $tmax;
			$end > $tend && $end = $tend;
			$ttable = GetTtable($tstart+1);
			$step = 3;
		}
		$ftable = 'pw_tmsgs'.$id;
		$ftable == $ttable && adminmsg('table_same');

		$db->update("INSERT INTO $ttable SELECT * FROM $ftable WHERE tid>'$tstart' AND tid<='$end'");
		$db->update("DELETE FROM $ftable WHERE tid>'$tstart' AND tid<='$end'");
		
		if($end<$tend){
			$j_url = "$basename&action=$action&step=$step&tstart=$end&lines=$lines&tmax=$tmax&tmin=$tmin&id=$id";
			adminmsg('table_change',EncodeUrl($j_url),2);
		} elseif($step==2 && $tidmax && $tmax > $tidmax){
			$step = 3;
			$j_url = "$basename&action=$action&step=$step&lines=$lines&tmax=$tmax&tmin=$tmin&id=$id";
			adminmsg('table_change',EncodeUrl($j_url),2);
		} else{
			adminmsg('operate_success');
		}
	}
}
?>