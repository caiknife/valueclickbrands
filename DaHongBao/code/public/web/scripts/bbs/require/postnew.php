<?php
!function_exists('readover') && exit('Forbidden');

##�������
$t_typedb=array();
$t_per=0;$t_exits=0;
$t_db=$foruminfo['t_type'];
if($t_db){
	$t_typedb = explode("\t",$t_db);
	$t_typedb = array_unique ($t_typedb);
	$t_per=$t_typedb[0];unset($t_typedb[0]);
	foreach($t_typedb as $value){
		if($value) $t_exits=1;
	}
}
$db_forcetype = $t_exits && $t_per=='2' && !$admincheck ? 1 : 0; // �Ƿ���Ҫǿ���������

if($foruminfo['allowpost'] && !allowcheck($foruminfo['allowpost'],$groupid,$winddb['groups'],$fid,$winddb['post']) && !$admincheck){
	Showmsg('postnew_forum_right');
}
if($special=='1' && !$foruminfo['allowpost'] && !$admincheck && $gp_allownewvote==0){
	Showmsg('postnew_group_vote');
}elseif($special=='2'){
	!($forumset['allowactive'] && $gp_allowactive) && showmsg('postnew_group_active');
	$sel_0='checked';$sel_1=$sel_2='';
}
if(!$foruminfo['allowpost'] && !$admincheck && $gp_allowpost==0){
	Showmsg('postnew_group_right');
}

if(!$_POST['step']){
	
	if($special==3 && $foruminfo['allowreward'] && $_G['allowreward']){
		$creditselect = '<option value="rvrc">'.$db_rvrcname.'</option>';
		$creditselect.= '<option value="money">'.$db_moneyname.'</option>';
		$creditselect.= '<option value="credit">'.$db_creditname.'</option>';
		require_once(D_P.'data/bbscache/creditdb.php');
		foreach($_CREDITDB as $key=>$val){
			$creditselect.="<option value=\"$key\">$val[0]</option>";
		}
	}
	/******** ���� ********/

	require_once(R_P.'require/header.php');
	$guidename = forumindex($foruminfo['fup']);
	$guidename = createNewBarArray($_GET,$guidename,$foruminfo['t_type']);
	list($msg_guide,$forumlist) = headguide($guidename);
	if($winddb['payemail']){
		list(,$payemail) = explode("\t",$winddb['payemail']);
		$winddb['email'] = $payemail;
	}
	require_once PrintEot('post');footer();
}elseif($_POST['step']==2){
	##�������
	//ǿ�Ʒ���
	if(!$p_type || empty($t_typedb[$p_type]) || ($t_per==0 && !$admincheck)){
		$w_type=0;
	}else{
		$w_type=$p_type;
	}
	$db_forcetype && $w_type=='0' && Showmsg('force_tid_select');
	
	list($atc_title,$atc_content,$ifconvert,$ifwordsfb)=check_data($action);
	
	require_once(R_P.'require/postupload.php');
	$rewardinfo='';  //����
	
	if($special=="1"){
		!$vt_select && Showmsg('postfunc_noempty');
		$votearray = array();
		$vt_select = Char_cv($vt_select);
		$vt_select = explode("\n",$vt_select);
		foreach($vt_select as $voteoption){
			$voteoption = trim($voteoption);
			if($voteoption){
				$votearray['options'][] = array($voteoption,0,array());
			}
		}
		if(count($vt_select) > $db_selcount){
			Showmsg('vote_num_limit');
		}
		if($mostvotes && is_numeric($mostvotes)){
			$mostvotes>count($vt_select) && $mostvotes=count($vt_select);
		} else{
			$mostvotes=count($vt_select);
		}
		$timelimit < 0 && $timelimit = 0;
		$votearray['multiple'] = array($multiplevote,$mostvotes);
		$voteopts = addslashes(serialize($votearray));
	} elseif($special=='2'){
		!($act_subject && $act_starttime && $act_deadline) && Showmsg('active_data_empty');
		$act_starttime= PwStrtoTime($act_starttime);
		$act_endtime  = PwStrtoTime($act_endtime);
		$act_deadline = PwStrtoTime($act_deadline);
		$act_deadline < $timestamp && Showmsg('deadline_limit');
		$act_deadline > $act_starttime && Showmsg('starttime_limit');
		$act_endtime && $act_starttime>$act_endtime && Showmsg('endtime_limit');
		$act_subject  = Char_cv($act_subject);
		$act_location = Char_cv($act_location);
		(!is_numeric($act_num) || $act_num<0) && $act_num=0;
		(!is_numeric($act_costs) || $act_costs<0) && $act_costs=0;
	} elseif($special=='3' && $foruminfo['allowreward'] && $_G['allowreward']){
		require_once(R_P.'require/postreward.php');
	} elseif($special=='4' && $forumset['allowsale']!=2 && $seller && $subject && $price){
		$seller       = Char_cv($seller);
		$subject      = Char_cv($subject);
		$contact      = Char_cv($contact);
		$demo         = Char_cv($demo);
		$price        = (int)$price;
		$ordinary_fee = (int)$ordinary_fee;
		$express_fee  = (int)$express_fee;
		if(!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$seller)){
			Showmsg('seller_error');
		}
		$ifconvert   = 2;
		$atc_content = "[payto]
(seller)$seller(/seller)
(subject)$subject(/subject)
(body)$atc_content(/body)
(price)$price(/price)
(ordinary_fee)$ordinary_fee(/ordinary_fee)
(express_fee)$express_fee(/express_fee)
(contact)$contact(/contact)
(demo)$demo(/demo)
(method)$method(/method)
[/payto]";

	} elseif(strpos($atc_content,"[payto]")!==false && strpos($atc_content,"[/payto]")!==false){
		$special = 4;
	} else{
		$special = 0;
	}
	
	$db_tcheck && $winddb['postcheck'] == tcheck($atc_content) && Showmsg('content_same'); //������֤
	
	if(!$SYSTEM['typeadmin']){
		$digest=0;
	}
	if(($foruminfo['f_check'] == 1 || $foruminfo['f_check'] == 3) && $_G['atccheck'] && !$admincheck){
		$ifcheck = 0;
	} else {
		$ifcheck = 1;
	}
	$anonymous = ($forumset['anonymous'] && $_G['anonymous'] && $atc_anonymous) ? 1 : 0;
	$lastposter= $anonymous ? $db_anonymousname : $windid;
	$atc_iconid= (int)$atc_iconid;
	$atc_email = (int)$atc_email;
	
	//����ͨ�û�groupid==-1������bbcode���ˡ�ȥ��url����
	if($winddb['groupid'] == -1){
		$atc_content = preg_replace(array("/\[url\]/i","/\[\/url\]/i","/\[url=(.*)\]/i"),"",$atc_content);
	}
	
	//ͼƬ���ش���
	if($_POST['save_picture']){
		preg_match_all("/\[img\](.*)\[\/img\]/U",$atc_content,$picture);
		
		//ѭ��������Ҫ���ص�ͼƬ
		foreach($picture[1] as $key=>$value){
			$picsrc = $value;
			
			//���ͼƬ�ߴ�
			$picsize = @file_get_contents($picsrc); 
			$picsize = strlen($picsize); 
			if($picsize>204800){
				continue;
			}
			$picinfo = getimagesize($picsrc); 
			$time = time()+$key;
			$randvar=substr(md5($time),10,15);
			$picname = "down_".$randvar;
			if($picinfo[2]!=1 && $picinfo[2]!=2 && $picinfo[2]!=3) continue;
			switch($picinfo[2]){//ȡ�ñ���ͼƬ�ĸ�ʽ  
				case 1:$picname = $picname.".gif";break; 
				case 2:$picname = $picname.".jpg";break; 
				case 3:$picname = $picname.".png";break; 
				default:
				continue;
			} 

			if(copy($value,__IMAGE_ADD."attachment/".$picname)){
				$glbDownloadImage = true;
				$replacevalue = '`' . preg_quote($value,'`') . '`';
				$atc_content = preg_replace($replacevalue,__IMAGE_SRC."attachment/".$picname,$atc_content,1);
				require_once("../../lib/util/class.FileDistribute.php");
				FileDistribute::syncImages("attachment/".$picname);
			}
		}	
	}
	
	$db->update("INSERT INTO pw_threads (fid,icon,author,authorid,subject,ifcheck,type,postdate,lastpost,lastposter,hits,replies,topped,digest,special ,ifupload,ifmail,rewardinfo,anonymous,ptable) VALUES ('$fid','$atc_iconid','".addslashes($windid)."','$winddb[uid]','$atc_title','$ifcheck','$w_type','$timestamp','$timestamp','".addslashes($lastposter)."','1','0','0','".(int)$digest."','$special','$ifupload','$atc_email','$rewardinfo','$anonymous','$db_ptable')");
	$tid = $db->insert_id();
	$pw_tmsgs = GetTtable($tid);
	$db->update("INSERT INTO $pw_tmsgs (tid,aid,userip,ifsign,buy,ipfrom,ifconvert,ifwordsfb,content) VALUES('$tid','$attachs','$onlineip','$atc_usesign','','$ipfrom','$ifconvert','$ifwordsfb','$atc_content')");

	require_once("../../lib/dao/class.UserScoreDao.php");
	$oUserScoreDao = new UserScoreDao($winddb['uid']);
	$oUserScoreDao->addScore("POST",$tid,"bbs","","","",$db);

	$digest && $db->update("UPDATE pw_memberdata SET digests=digests+1 WHERE uid='$winduid'");
	if($aids){
		$db->update("UPDATE pw_attachs SET tid='$tid' WHERE aid IN($aids)");
	}
	if($special==1){
		$db->update("INSERT INTO pw_polls (tid,voteopts,modifiable,previewable,timelimit) VALUES ('$tid','$voteopts','$modifiable','$previewable','$timelimit')");
	} elseif($special==2){
		$db->update("INSERT INTO pw_activity (tid,subject,admin,starttime,endtime,location,num,sexneed,costs,deadline) VALUES ('$tid','$act_subject','$winduid','$act_starttime','$act_endtime','$act_location','$act_num','$act_sex','$act_costs','$act_deadline')");
	}

	



	if($foruminfo['cms']){
		include_once(R_P.'require/c_search.php');
		insert_key($tid,$keyword);
	}
	$top_post=1;
	$t_date=$timestamp;//���ⷢ��ʱ�� bbspostguide ���õ�
	bbspostguide();

	unset($j_p);
	if($ifcheck==1){
		if($foruminfo['allowhtm'] && !$foruminfo['cms']){
			include_once(R_P.'require/template.php');
		}
		lastinfo($fid,$foruminfo['allowhtm'],'new',$foruminfo['cms'].'B');
	}
	if($modify){
		ObHeader("post.php?action=modify&fid=$fid&tid=$tid&pid=tpc&article=0");
	} else{
		if(empty($j_p) || $foruminfo['cms']) $j_p="read.php?tid=$tid";
		refreshto($j_p,'enter_thread','1',$glbDownloadImage);
	}
}
?>