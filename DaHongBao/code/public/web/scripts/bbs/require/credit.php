<?php
!function_exists('readover') && exit('Forbidden');

function GetCredit($uid){
	global $db,$_CREDITDB;
	$credit = array();
	isset($_CREDITDB) || @include(D_P.'data/bbscache/creditdb.php');
	foreach($_CREDITDB as $key => $value){
		$credit[$key] = array($value[0],0);
	}
	$query = $db->query("SELECT cid,value FROM pw_membercredit WHERE uid='$uid'");
	while($rt = $db->fetch_array($query)){
		$credit[$rt['cid']]=array($_CREDITDB[$rt['cid']][0],$rt['value']);
	}
	return $credit;
}
function GetCreditType(){
	global $db_credits,$db_currencyname,$_CREDITDB;
	list($moneyname,$moneyunit,$rvrcname,$rvrcunit,$creditname,$creditunit)=explode("\t",$db_credits);
	$credittype=array(
		'money'=>$moneyname,
		'rvrc'=>$rvrcname,
		'credit'=>$creditname,
		'currency'=>$db_currencyname,
	);
	isset($_CREDITDB) || @include(D_P.'data/bbscache/creditdb.php');
	foreach($_CREDITDB as $key=>$val){
		$credittype[$key]=$val[0];
	}
	return $credittype;
}
function CreditName($type){
	global $db_credits,$db_currencyname,$_CREDITDB;
	isset($_CREDITDB) || @include(D_P.'data/bbscache/creditdb.php');
	list($db_moneyname,,$db_rvrcname,,$db_creditname,)=explode("\t",$db_credits);
	return is_numeric($type) ? $_CREDITDB[$type][0] : ${'db_'.$type.'name'};
}
function UserCredit($uid,$type,$method='get',$point=0){
	global $db,$_CREDITDB;
	if(in_array($type,array('money','rvrc','credit','currency'))){
		if($method=='get'){
			$rt=$db->get_one("SELECT $type FROM pw_memberdata WHERE uid='$uid'");
			return $type=='rvrc' ? intval($rt[$type]/10) : $rt[$type];
		} else{
			$type=='rvrc' && $point *= 10;
			$db->update("UPDATE pw_memberdata SET $type=$type+'$point' WHERE uid='$uid'");
			return true;
		}
	}
	if(!is_numeric($type)) return false;
	isset($_CREDITDB) || include(D_P.'data/bbscache/creditdb.php');
	if(isset($_CREDITDB[$type])){
		if($method=='get'){
			$rt=$db->get_one("SELECT value FROM pw_membercredit WHERE uid='$uid' AND cid='$type'");
			return $rt['value'] ? $rt['value'] : 0;
		} else{
			$db->pw_update(
				"SELECT value FROM pw_membercredit WHERE uid='$uid' AND cid='$type'",
				"UPDATE pw_membercredit SET value=value+'$point' WHERE uid='$uid' AND cid='$type'",
				"INSERT INTO pw_membercredit(uid,cid,value) VALUES('$uid','$type','$point')"
			);
			return true;
		}
	}
	return false;
}
?>