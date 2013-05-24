<?PHP
require_once(__INCLUDE_ROOT."lib/dao/class.CityDao.php");	
	
class City extends CityDao{
	
	const DEFAULT_CITYID = 21;    //default cityid 21.SHANGHAI
	const DEFAULT_CITYNAME = '上海';    //default cityid 21.SHANGHAI
	
	var $cityid;
	//var $cityarray;

	function __construct($cityid) {
		if(empty($cityid)){
			$this->cityid = self::DEFAULT_CITYID;
			setcookie("cityid",self::DEFAULT_CITYID,time()+9999999,"/",__COOKIE_DOMAIN);
		}else{
			$this->cityid = $cityid;
		}
	}

	/*
	*	getcityarray  return city array();
	*/
	function getCityArray() {
		if ($this->cityid == 'all') {
		    return array('cityid' => 'all', 'cityname' => '全国');
		}
	    
	    $cityid = $this->cityid;

		$cityname = $this->getCityName($cityid);

		if($cityname == ""){     //error cityid in cookies . can not get this cityname from DB .  SO default 上海 and set real cookieid
			setcookie("cityid",self::DEFAULT_CITYID,time()+9999999,"/",__COOKIE_DOMAIN);
			$cityname = self::DEFAULT_CITYNAME;
		}
		
		$cityarray = array();

		$cityarray['cityid'] = $cityid;
		$cityarray['cityname'] = $cityname;

		return $cityarray;

	}

	/*
	*	getallcityarray  return all city array();
	*/
	function getCityList(){ 
		return $this->getCityListDao();
		
	}
	

}