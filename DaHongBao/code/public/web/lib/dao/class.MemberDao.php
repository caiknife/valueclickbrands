<?PHP
	
class MemberDao {
	function __construct() {
		
	}
	
	function getuserinfo($id) {
		$sql = "select pm.username,pm.uid,pm.icon,pw_memberdata.lastvisit,pw_memberdata.money ";
		$sql .= "FROM pw_members pm ";
		$sql .= "LEFT JOIN pw_memberdata ON (pw_memberdata.uid = pm.uid) ";
		$sql .= "WHERE pm.uid = '$id'";
		$r = DBQuery :: instance()->executeQuery($sql);
		return $r;
	}


}