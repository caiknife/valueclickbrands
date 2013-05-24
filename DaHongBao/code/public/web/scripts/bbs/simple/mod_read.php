<?php
!function_exists('readover') && exit('Forbidden');

require_once(R_P.'require/forum.php');
require_once(R_P.'require/bbscode.php');

(!is_numeric($page) || $page < 1) && $page=1;
if($page>1){
	$S_sql = $J_sql = '';
}else{
	!$page && $page = 1;
	$start_limit = 0;
	$pw_tmsgs = GetTtable($tid);
	$S_sql = ',tm.*';
	$J_sql = "LEFT JOIN $pw_tmsgs tm ON t.tid=tm.tid";
}
$read = $db->get_one("SELECT t.* $S_sql FROM pw_threads t $J_sql WHERE t.tid='$tid'");
if(!$read){
	Showmsg('illegal_tid');
}
$pw_posts = GetPtable($read['ptable']);

$fid=$read['fid'];

$foruminfo = $db->get_one("SELECT * FROM pw_forums f LEFT JOIN pw_forumsextra fe USING(fid) WHERE f.fid='$fid'");
$forumset  = unserialize($foruminfo['forumset']);
if(!$foruminfo){
	Showmsg('data_error');
}
wind_forumcheck($foruminfo);

if ($groupid != 3 && !$foruminfo['allowvisit'] && admincheck($foruminfo['forumadmin'],$foruminfo['fupadmin'],$windid)){
	list($db_moneyname,,$db_rvrcname,,$db_creditname,)=explode("\t",$db_credits);
	forum_creditcheck();
}

$subject=$read['subject'];

$forumname=$forum[$fid]['name'];

if(!$foruminfo['allowvisit'] && $gp_allowread==0 && $_COOKIE){
	Showmsg('read_group_right');
}

if($windid==$manager){
	$admincheck=1;
} elseif(admincheck($foruminfo['forumadmin'],$foruminfo['fupadmin'],$windid)){
	$admincheck=1;
} else{
	$admincheck=0;
}
!$windid && $admincheck=0;

$db_readperpage=50;/*perpage*/

$count= $read['replies']+1;
if ($count%$db_readperpage==0){
	$numofpage=$count/$db_readperpage;
} else{
	$numofpage=floor($count/$db_readperpage)+1;
}
if ($page>$numofpage){
	$page=$numofpage;
}

Update_ol();
$readdb=array();
if($page==1){
	$readdb[]=viewread($read,0);
}

$pages=PageDiv($count,$page,$numofpage,"{$DIR}t$tid");

if($read['ifcheck']==0 && $foruminfo['f_check']){
	Showmsg('read_check');
}

if ($read['locked']==2 && !$admincheck && !$SYSTEM['viewclose']){
	Showmsg('read_locked');
}
if ($forumset['lock']&& !$admincheck && $timestamp - $read['postdate'] > $forumset['lock'] * 86400){
	Showmsg('forum_locked');
}
if(!$db_hithour){
	$db->update("UPDATE pw_threads SET hits=hits+1 WHERE tid='$tid'");
} else{
	writeover(D_P."data/bbscache/hits.txt",$tid."\t",'ab');
}

if($read['replies']>0){
	$start_limit=($page-1)*$db_readperpage;
	if($page==1){
		$readnum=$db_readperpage-1;
	} else{
		$readnum=$db_readperpage;
		$start_limit-=1;
	}
	$query = $db->query("SELECT p.*,m.groupid FROM $pw_posts p LEFT JOIN pw_members m ON p.authorid=m.uid WHERE p.tid='$tid' AND p.ifcheck='1' ORDER BY p.postdate LIMIT $start_limit, $readnum");
	$start_limit++;
	while($read=$db->fetch_array($query)){
		$readdb[]=viewread($read,$start_limit);
		$start_limit++;
	}
	$db->free_result($query);
}


function viewread($read,$start_limit){
	global $SYSTEM,$groupid,$admincheck,$attach_url,$attachper,$winduid,$tablecolor,$tpc_author,$tpc_buy,$count,$timestamp,$db_onlinetime,$attachpath,$gp_allowloadrvrc,$readcolorone,$readcolortwo,$lpic,$ltitle,$imgpath,$db_ipfrom,$db_showonline,$stylepath,$db_windpost,$db_windpic,$db_signwindcode,$fid,$tid,$pid,$pic_a,$db_shield;
	$tpc_buy=$read['buy'];
	$tpc_author=$read['author'];
	$read['ifsign']<2 && $read['content']=str_replace("\n","<br>",$read['content']);

	$read['postdate']=get_date($read['postdate']);

	if($read['ifshield']){
		$read['subject'] = $groupid=='3' ? shield('shield_title') : '';
		$groupid!='3' && $read['content'] = shield('shield_article');
	}elseif($read['groupid'] == 6 && $groupid != 3 && $db_shield){
		$read['subject'] = '';
		$read['content'] = shield('ban_article');
	}elseif($read['ifconvert']==2){
		$read['content']=convert($read['content'],$db_windpost);
	}
	$GLOBALS['foruminfo']['copyctrl'] && $read['content'] = preg_replace("/<br>/eis","copyctrl('#FFFFFF')",$read['content']);
	/**
	* convert the post content
	*/
	//$read['content']=stripslashes($read['content']);
	$read['anonymous'] && !$admincheck && $winduid!=$read['authorid'] && $read['author']=$db_anonymousname;
	return $read;
}
require_once PrintEot('simple_header');

require_once PrintEot('simple_read');
?>