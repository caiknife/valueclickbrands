<?PHP
	
class CityDao {
	function __construct() {
		
	}
	
	function getCityName($cityid){
		$sql = "SELECT CityName
				FROM City 
				WHERE CityID = '$cityid' ";
		return DBQuery::instance()->getOne($sql);
	}

	function getCityListDao(){
		 $sql = "select CityName,CityID 
			     FROM City 
			     ORDER BY CityID ";
		 return DBQuery::instance()->executeQuery($sql);
	}

	/* get city name */
	public function getNowCityName($id) {
		if ($id=="all") {
		    setcookie("cityid", "all", time() + 9999999, "/",__COOKIE_DOMAIN);
            return "ȫ��";
		}
	    if ($id == "") {
			setcookie("cityid", "21", time() + 9999999, "/",__COOKIE_DOMAIN);
			return "�Ϻ�";
		}
		$sql = "select CityName FROM City WHERE CityID='$id'";
		$result = DBQuery :: instance()->getOne($sql);
		if (empty ($result)) {
			setcookie("cityid", "21", time() + 9999999, "/",__COOKIE_DOMAIN);
			return "�Ϻ�";
		}
		return $result;
	}

	/* get city list */
	public function getCityList() {
		$sql = "select CityName,CityID FROM City ORDER BY CityID";
		$result = DBQuery :: instance()->executeQuery($sql);
		return $result;
	}
	

}