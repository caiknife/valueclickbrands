<?php
require_once('global.php');
require_once(R_P.'require/header.php');
require_once(R_P.'require/forum.php');
include_once(D_P.'data/bbscache/forumcache.php');
include_once(D_P.'data/bbscache/forum_cache.php');

!$windid && Showmsg('not_login');
!$_G['show'] && Showmsg('groupright_show');
$db_showperpage = 16;

$fidoff= array();
$query = $db->query("SELECT fid,password,allowvisit FROM pw_forums WHERE type!='category'");
while($rt = $db->fetch_array($query)){
	if($db_recycle && $rt['fid'] == $db_recycle || $rt['password'] || $rt['allowvisit'] && strpos($rt['allowvisit'],",$groupid,") === false){
		is_numeric($rt['fid']) && $fidoff[] = $rt['fid'];
	}
}
$sqladd = '1';
if($pwuser || is_numeric($uid)){
	if($pwuser){
		$rt  = $db->get_one("SELECT uid,username FROM pw_members WHERE username='$pwuser'");
	}elseif(is_numeric($uid)){
		$rt  = $db->get_one("SELECT uid,username FROM pw_members WHERE uid='$uid'");
	}
	if(!$rt){
		$errorname = Char_cv($pwuser);
		Showmsg('user_not_exists');
	}else{
		$uid     = $rt['uid'];
		$owner   = $rt['username'];
		$sqladd .= " AND a.uid='$uid'";
	}
}

if(is_numeric($fid) && $fid > 0){
	$sqladd .= " AND a.fid='$fid'";
	$forumcache = str_replace("<option value=\"$fid\">","<option value=\"$fid\" selected>",$forumcache);
}
$type_1 = $type_2 = '';
if($type == 1){
	$sqladd .= " AND a.type='img'";
	$type_1  = "selected";
} elseif($type == 2) {
	$sqladd .= " AND a.type!='img'";
	$type_2  = "selected";
}
if(!$action){
	$url = "show.php?uid=$uid&fid=$fid&type=$type&";
	(!is_numeric($page) || $page<1) && $page = 1;
	$limit = "LIMIT ".($page-1)*$db_showperpage.",$db_showperpage";
	$rt    = $db->get_one("SELECT COUNT(*) AS sum FROM pw_attachs a WHERE $sqladd");
	$pages = numofpage($rt['sum'],$page,ceil($rt['sum']/$db_showperpage),$url);

	$showdb= $ttable_a = $read = array();
	$query = $db->query("SELECT a.aid,a.uid,a.attachurl,a.type,a.fid,a.tid,a.pid,a.name,a.descrip,m.username FROM pw_attachs a LEFT JOIN pw_members m ON a.uid=m.uid WHERE $sqladd ORDER BY aid DESC $limit");
	while($rt = $db->fetch_array($query)){
		$showdb[] = $rt;
		$ttable_a[GetTtable($rt['tid'])] .= $rt['tid'].',';
	}
	foreach($ttable_a as $pw_tmsgs=>$value){
		$value = substr($value,0,-1);
		$query = $db->query("SELECT t.tid,t.subject,t.ifcheck,tm.content FROM pw_threads t LEFT JOIN $pw_tmsgs tm ON t.tid=tm.tid WHERE t.tid IN($value)");
		while($rt = $db->fetch_array($query)){
			$read[$rt['tid']] = $rt;
		}
	}
	foreach($showdb as $key=>$rt){
		$read[$rt['tid']] && $rt = $rt + $read[$rt['tid']];
		if(in_array($rt['fid'],$fidoff) || $groupid!='3' && $groupid!='4' && (!$rt['ifcheck'] || strpos($rt['content'],"[post]") !== false && strpos($rt['content'],"[/post]") !== false || strpos($rt['content'],"[hide") !== false && strpos($rt['content'],"[/hide]") !== false || strpos($rt['content'],"[sell") !== false && strpos($rt['content'],"[/sell]") !== false)){
			$rt['a_url']="none";
		} else{
			$a_url = geturl($rt['attachurl'],'show');
			$rt['a_url'] = is_array($a_url) ? $a_url[0] : $a_url;
		}
		!$rt['pid'] && $rt['pid'] = 'tpc';
		!$rt['descrip'] && $rt['descrip'] = substrs($rt['subject'],20);
		$rt['fname'] = $forum[$rt['fid']]['name'];
		$showdb[$key] = $rt;
	}
	require_once PrintEot('show');footer();
} else{
	$pw_tmsgs = GetTtable($tid);
	$rt=$db->get_one("SELECT a.aid,a.uid,a.attachurl,a.type,a.fid,a.tid,a.pid,a.name,a.descrip,t.subject,tm.content,m.username FROM pw_attachs a LEFT JOIN pw_threads t ON t.tid=a.tid LEFT JOIN $pw_tmsgs tm ON tm.tid=a.tid LEFT JOIN pw_members m ON m.uid=a.uid WHERE a.aid='$aid' AND a.tid='$tid' AND t.ifcheck='1'");
	if($rt){
		$a_url = geturl($rt['attachurl'],'show');
		$rt['a_url'] = is_array($a_url) ? $a_url[0] : $a_url;
		if(in_array($rt['fid'],$fidoff) || $groupid!='3' && $groupid!='4' && (strpos($rt['content'],"[post]") !== false && strpos($rt['content'],"[/post]") !== false || strpos($rt['content'],"[hide") !== false && strpos($rt['content'],"[/hide]") !== false || strpos($rt['content'],"[sell") !== false && strpos($rt['content'],"[/sell]") !== false)){
			Showmsg('pic_not_exists');
		}
	} else{
		Showmsg('pic_not_exists');
	}
	$uid  = $rt['uid'];
	$type = 1;
	$owner = $rt['username'];
	!$rt['pid'] && $rt['pid']='tpc';
	!$rt['descrip'] && $rt['descrip'] = substrs($rt['subject'],20);
	require_once PrintEot('show');footer();
}
?>