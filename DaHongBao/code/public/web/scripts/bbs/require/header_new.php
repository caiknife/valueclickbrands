<?php
!function_exists('readover') && exit('Forbidden');

if(file_exists(D_P."data/style/$skin.php") && strpos($skin,'..')===false){
	@include Pcv(D_P."data/style/$skin.php");
}elseif(file_exists(D_P."data/style/$db_defaultstyle.php") && strpos($db_defaultstyle,'..')===false){
	@include Pcv(D_P."data/style/$db_defaultstyle.php");
}else{
	@include(D_P."data/style/wind.php");
}

$msgsound=$head_pop='';
if($groupid=='guest' && $db_regpopup=='1'){
	$head_pop='head_pop';
} else{
	if($winddb['newpm']>0 && !$_COOKIE['msghide']){
		if($db_msgsound && $secondurl!='message.php'){
			$msgsound="<bgsound src=\"$imgpath/$stylepath/msg/msg.wav\" border=\"0\">";
		}
		$head_pop='newmsg';
	}
}
$db_yh && $db_yh = explode(',',$db_yh);

if($db_union){
	$db_union=explode("\t",stripslashes($db_union));
	!$db_union[5] && $db_siteifopen=0;
	$db_union[0] && $db_hackdb=array_merge($db_hackdb,unserialize($db_union[0]));
}
$lastvisittime =  get_date($winddb['lastvisit']);
require PrintEot('header_new');
?>