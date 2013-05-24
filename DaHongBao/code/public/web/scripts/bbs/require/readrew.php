<?php
!function_exists('readover') && exit('Forbidden');

$reward    = explode("\n",$read['rewardinfo']);
$rewardtype= $reward['0'];
list($rw_b_name,$rw_b_val,$rw_a_name,$rw_a_val)=explode('|',$reward['1']);
$endinfo   = strpos($reward['2'],'|')!==false ? explode('|',$reward['2']) : $reward['2'];
$rw_b_name = is_numeric($rw_b_name) ? $_CREDITDB[$rw_b_name][0] : ${'db_'.$rw_b_name.'name'};
$rw_a_name = is_numeric($rw_a_name) ? $_CREDITDB[$rw_a_name][0] : ${'db_'.$rw_a_name.'name'};

function Getrewhtml($lou,$ifreward,$pid){
	global $rewardtype,$rw_b_name,$rw_b_val,$rw_a_name,$rw_a_val,$groupid,$admincheck,$authorid,$winduid,$lang,$tid,$endinfo,$left_num;
	require_once GetLang('bbscode');

	$html = "<div class=\"tips\" style=\"width:auto;\">";
	if($lou==0){
		if($rewardtype=='1'){
			$html .= "<span class=\"s3\">$lang[rewarding]</span>";
			$html .= "<div class=\"tac\">$lang[reward_bestanswer]: $rw_b_val&nbsp;$rw_b_name</div>";
			if(is_numeric($rw_a_val)){
				$html .= "<div class=\"tac\">$lang[reward_hlp]: $rw_a_val $rw_a_name</div>";
			}
			if($groupid=='3' || $admincheck){
				$html .= "<div class=\"tac\"><a href=\"job.php?action=endreward&tid=$tid\">$lang[reward_cancle]</a>&nbsp;</div>";
			} elseif($authorid==$winduid){
				$html .= "<div class=\"tac\"><a href=\"job.php?action=rewardmsg&tid=$tid\" title=\"$lang[reward_title]\" onClick=\"javascript:if(confirm('$lang[reward_msgtoadmin]')){return true;}else{return false;}\">$lang[reward_toadmin]</a>&nbsp;</div>";
			}
		} elseif($rewardtype=='2'){
			$html .= "<span class=\"s3\">$lang[reward_finished]</span><div class=\"tac\">$lang[reward_bestanswer]: $rw_b_val&nbsp;$rw_b_name</div>";
		
			if(is_array($endinfo)){
				$html .= "<div class=\"tac\">$lang[reward_author]: $endinfo[0]</div>";
			} else{
				$html .= "<div class=\"tac\">$endinfo</div>";
			}
		}
	} else{
		if($rewardtype=='2' && $ifreward>1){
			$html .= "<span class=\"s3\">$lang[reward_best_get]</span>: (+$rw_b_val)&nbsp;$rw_b_name";
		}elseif($ifreward=='1'){
			$html .= "<span class=\"s3\">$lang[reward_help_get]</span>: (+1)&nbsp;$rw_a_name";
		}elseif($authorid==$winduid && $rewardtype=='1' && $ifreward==0){
			$html .= "<span class=\"s3\">$lang[reward_manager]</span>: [<a href=\"job.php?action=reward&tid=$tid&pid=$pid&type=1\">$lang[reward_bestanswer]</a>]";
			$rw_a_val>0 && $html .= "[<a href=\"job.php?action=reward&tid=$tid&pid=$pid&type=2\">$lang[reward_help]</a>]";
		}
	}
	$html .= "</div><div class=\"c\"></div>";
	return $html;
}
?>