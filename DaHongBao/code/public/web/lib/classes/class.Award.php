<?PHP

class Award {
	
	public function getAwardList(){
		$sql = "SELECT ID,AwardName,Description,NeedHB,ImageSrc,Sort FROM ";
		$sql .= "Award WHERE IsActive='YES' ORDER BY Sort";
		return DBQuery::instance()->executeQuery($sql);	
	}
	
	public function getAwardDetail($id){
		$sql = "SELECT * FROM Award WHERE ID=$id";
		return DBQuery :: instance()->getRow($sql);
	}
	
	public function getUserHB($uid){
		$sql = "SELECT pw_memberdata.money FROM pw_memberdata WHERE uid = $uid";
		return DBQuery :: instance()->getOne($sql);
	}
	
	public function getUserDetail($uid){
		$sql = "SELECT * from pw_members LEFT JOIN pw_memberinfo ON (pw_members.uid = pw_memberinfo.uid) WHERE pw_members.uid = $uid";
		return DBQuery :: instance()->getRow($sql);
	}
	
	public function addUserAward($uid,$aid,$needhb){
		$sql = "INSERT INTO AwardUser (`ID`,`AwardID`,`UserID`,`AddTime`,`Type`,`NeedHB`) VALUES ('','$aid','$uid',NOW(),'NO','$needhb')";
		$re = DBQuery::instance()->executeQuery($sql);	
		return DBQuery :: instance(__DAHONGBAO_Master)->getInsertID();
	}
	
	public function getUserAwardDetail($id){
		$sql = "SELECT * FROM ";
		$sql .= "AwardUser WHERE ID='$id'";
		return DBQuery::instance()->getRow($sql);			
	}
	
	public function updateUserAwardDetail($array){
		return DBQuery :: instance()->autoExecute('AwardUser', $array, DB_AUTOQUERY_UPDATE, "ID=".$array['ID']);	
	}
	
	public function updateAwardNum($id,$type){
		if(empty($id)) return;
		$sql = "UPDATE Award SET ApplyNum = ApplyNum+1 WHERE ID=$id";	
		return DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
	}

}

?>