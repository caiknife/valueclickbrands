<?php
/*
 * Created on 2006-6-23
 * class.CommonDao.php
 * -------------------------
 *
 *
 *
 * @author Fan Xu
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.CommonDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class CommonDao {
	private static $channels = NULL;
	private static $categorys = NULL;
	private static $channel_HotProduct = NULL;
	private static $channel_PageContent = NULL;
	private static $channel_PageEntrance = NULL;
	private static $channel_Mer = NULL;
	private static $channel_Mer_Logo = NULL;
	private static $channel_Category = NULL;
	private static $channel_Links = NULL;
	private static $channel_Ad = NULL;


	private static $adsCount = 0;
	private static $adsNeedJS = false;
	private static $adsRequestHandle = null;
	private static $adsOvertime = false;
	private static $adsAsyncURL = "";

	public static $adsTplFlag = null; //传递UI样标志
	public static $adsStyleFlag = null;
	public static $adsVisitHost = null; //传递请求广告的站点
	public static $adsTestFlag  = "off"; //是否是测试广告


	//private static $category[$chid] = NULL;
	/**
	 * 将FrontEnd.Channel表的内容映射到/etc/cache/common_channels.php
	 * @author cooc
	 */
	public static function updateEnv() {
		CacheDao::init();
		$sql = "SELECT * FROM Channel WHERE IsValid='YES'";
		$rs = DBQuery::instance(__ETL)->executeQuery($sql);

		$filename = __SETTING_FULLPATH . "config/common_channels.php";
		if(file_exists(dirname($filename)) == false) {
			mkdir(dirname($filename), 0755, true);
		}
		Utilities::setArrayCache($filename, $rs);
		FileDistribute::syncFile($filename);
		return;
	}

	public static function getChannel() {
		$sql = "SELECT * FROM Channel WHERE IsValid='YES'";
		$rs = DBQuery::instance(__ETL)->executeQuery($sql);
		return $rs;
	}

	/**
	 * 由ChannelID取得ID, ChannelEnName，ChannelName
	 * @return $result
	 * @author cooc
	 */
	public static function channel($chid = NULL, $field = NULL) {
		//初始化
		if(self::$channels == NULL) {
			//$cacheFile = __SETTING_FULLPATH."config/common_channels.php";
			//self::$channels = Utilities::getArrayCache($cacheFile);
			self::$channels = FastCacheRead::getChannelCache();
		}

		if($chid == NULL) {
			foreach(self::$channels as &$row){
				$newchannel[] = $row;
			}
			if($field == NULL) { //取得全部表设置
				return $newchannel;
			} else { //取得整列
				return Utilities::convertSimpleArray($newchannel, $field);
			}
		}
		/*
		$num = count(self::$channels);
		for($i = 0; $i < $num; $i++) {
			if(self::$channels[$i]["ID"] == $chid) {
				break;
			}
		}
		if($i == $num) { //ChannelID不存在
			return NULL;
		}
		*/
		if(isset(self::$channels[$chid]) == false) {
			return NULL;
		}
		if($field == NULL) { //取得整行
			return self::$channels[$chid];
		}

		if(isset(self::$channels[$chid][$field])) { //取得Channel表设置了表名
			return self::$channels[$chid][$field];
		}
		//未找到对应的字段名,则作特殊处理
		if(self::$channels[$chid]["Type"] == "CE") {
			return "C".$chid.$field;
		}
		if($field == "Attribute") {
			return "r_Menus";
		}
		if($field == "AttributeValue") {
			return "r_MenuPickList";
		}
		throw new Exception("unknown field($field).");
	}
	/**
	 * 由ChannelID取得channel contant
	 * @return $result
	 * @author cooc
	 */
	public static function channelContant($chid, $field) {
		if ($field == "channel_HotProduct") {
			if(self::$channel_HotProduct[$chid] == NULL) {
				$csv = new CSV();
				return self::$channel_HotProduct[$chid] = $csv->loadToArray(__SETTING_FULLPATH."config/channel_HotProduct_$chid.csv", TRUE, -1);
			} else {
				return self::$channel_HotProduct[$chid];
			}
		//
		} elseif ($field == "channel_PageContent") {
			if(self::$channel_PageContent[$chid] == NULL) {
				$csv = new CSV();
				return self::$channel_PageContent[$chid] = $csv->loadToArray(__SETTING_FULLPATH."config/channel_PageContent_$chid.csv", TRUE, -1);
			} else {
				return self::$channel_PageContent[$chid];
			}
		//
		} elseif ($field == "channel_PageEntrance") {
			if(self::$channel_PageEntrance[$chid] == NULL) {
				$csv = new CSV();
				return self::$channel_PageEntrance[$chid] = $csv->loadToArray(__SETTING_FULLPATH."config/channel_PageEntrance_$chid.csv", TRUE, -1);
			} else {
				return self::$channel_PageEntrance[$chid];
			}
		//
		} elseif ($field == "channel_Mer") {
			if(self::$channel_Mer[$chid] == NULL) {
				$csv = new CSV();
				return self::$channel_Mer[$chid] = $csv->loadToArray(__SETTING_FULLPATH."config/channel_Mer_$chid.csv", TRUE, -1);
			} else {
				return self::$channel_Mer[$chid];
			}
		} elseif ($field == "channel_Mer_Logo") {
			if(self::$channel_Mer_Logo[$chid] == NULL) {
				$csv = new CSV();
				return self::$channel_Mer_Logo[$chid] = $csv->loadToArray(__SETTING_FULLPATH."config/channel_Mer_Logo_$chid.csv", TRUE, -1);
			} else {
				return self::$channel_Mer_Logo[$chid];
			}
		} elseif ($field == "channel_Category") {
			if(self::$channel_Category[$chid] == NULL) {
				$csv = new CSV();
				return self::$channel_Category[$chid] = $csv->loadToArray(__SETTING_FULLPATH."config/channel_Category_$chid.csv", TRUE, -1);
			} else {
				return self::$channel_Category[$chid];
			}
		} elseif ($field == "channel_PageLinks") {
			if(self::$channel_Links[$chid] == NULL) {
				$csv = new CSV();
				return self::$channel_Links[$chid] = $csv->loadToArray(__SETTING_FULLPATH."config/channel_Links_$chid.csv", TRUE, -1);
			} else {
				return self::$channel_Links[$chid];
			}
		} elseif($field == "channel_Ad") {
			if(self::$channel_Ad == NULL) {
				$csv = new CSV();
				self::$channel_Ad = $csv->loadToArray(__ROOT_PATH."etc/config/channel_Ad.csv", TRUE, -1);
			}
			for($i = 0,$cnt=count(self::$channel_Ad), $num = $cnt; $i < $num; $i++) {
				if(self::$channel_Ad[$i]["ID"] == $chid) {
					self::$channel_Ad[$i]["URL"] = PathManager::getBannerAdUrl(self::$channel_Ad[$i]["URL"]);
					break;
				}
			}
			if($i == $num) { //ChannelID不存在
				return NULL;
			}
			else {
				return self::$channel_Ad[$i];
			}
		} else {
			return NULL;
		}
	}

	/**
	 * 由ChannelID、Categoryid取得Category
	 * @return $result
	 * @author cooc
	 * @deprecated version - 2008-2-14
	 */
	public static function category($chid, $categoryid = NULL, $field = NULL) {
		if(self::$categorys[$chid] == NULL) {
			$csv = new CSV();
			self::$categorys[$chid] = $csv->loadToArray(__SETTING_FULLPATH."config/channel_$chid.csv", TRUE, -1);
		}
		if($num = count(self::$categorys[$chid])){
			if($categoryid != NULL and $field != NULL) {
				for($i = 0; $i < $num; $i++) {
					if(self::$categorys[$chid][$i]["Categoryid"] == $categoryid) {
						return self::$categorys[$chid][$i][$field];
					}
					if(self::$categorys[$chid][$i]["CategoryBid"] == $categoryid) {
						return self::$categorys[$chid][$i][$field];
					}
				}
			} elseif ($categoryid == NULL and $field != NULL) {
				for($i = 0; $i < $num; $i++) {
					if($field = self::$categorys[$chid][$i][$field]){
						$rs[] = $field;
					}
				}
				return $rs;
			} else {
				return self::$categorys[$chid];
			}
		}
		return NULL;
	}

	/**
	 * 由ProductID取得类别树
	 * @return Array[] 顺序是倒序(最低层的类别在第一行)
	 * @author cooc
	 */
	public static function getCategoryPathByProductID($chid, $prodid) {
		$categoryTable = self::channel($chid, "CategoryTable");
		$categoryProdTable = self::channel($chid, "CatProdTable");

		$sql = "SELECT MAX(C.CategoryID) CategoryID FROM " . $categoryTable
			. " C INNER JOIN " . $categoryProdTable . " CP ON C.CategoryID = CP.CategoryID WHERE CP.ProductID = "
			. $prodid ." ;";
		$rs = DBQuery::instance()->getOne($sql);
		if ($rs != NULL) {

			return self::getCategoryPathByCategoryID($chid, $rs);
		} else {
			throw new Exception ("get CategoryPath error！");
		}
	}

	/**
	 * 由CategoryID取得类别树
	 * @return Array[] 顺序是倒序(最低层的类别在第一行)
	 * @author cooc
	 */
	public static function getCategoryPathByCategoryID($chid, $catid) {
		$categoryTable = self::channel($chid, "CategoryTable");
		$categoryRshTable = self::channel($chid, "CatRshTable");
		if($chid == __CarChannelID) {	//car channel
			$carNav[0] = array("CategoryID"=>__CarChannelID, "CategoryName"=>"汽车", "CategoryEnName"=>"Car", "IsLeaf"=>"No", "IsTop"=>"No");
			if($catid == NULL or $catid == 0) {
				$carNav[1] = array("CategoryID"=>0, "CategoryName"=>"所有汽车", "CategoryEnName"=>"cate", "IsLeaf"=>"No", "IsTop"=>"No");
				return $carNav;
			}
			$sql = "SELECT CategoryParentID FROM $categoryRshTable WHERE CategoryChildID = $catid;";
			if($result = DBQuery::instance()->getOne($sql)){
				$sql = "SELECT CategoryID, CategoryName, CategoryEnName," .
						" IsLeaf, IsTop" .
						" FROM " . $categoryTable.
						" WHERE CategoryID IN($catid ,$result) ;";
				if($rs = DBQuery::instance()->executeQuery($sql)){
					$rs = array_merge($carNav, $rs);
					return $rs;
				}
			} else {
				$sql = "SELECT CategoryID, CategoryName, CategoryEnName," .
						" IsLeaf, IsTop" .
						" FROM " . $categoryTable.
						" WHERE CategoryID =  $catid;";
				if($rs = DBQuery::instance()->executeQuery($sql)){
					$rs = array_merge($carNav, $rs);
					return $rs;
				}
			}
			return NULL;
		}

		//add merchant cache
		$mycachefile = FastCacheRead::getCategoryCacheByChannel($chid);

		for($loop=0; $loop<10; $loop++) {
			if($catid == 0) {
				if(CommonDao::channel($chid, 'Type') == "CE") {
					//取Channel信息
					$row['CategoryID'] = $chid;
					$row['CategoryName'] = CommonDao::channel($chid, "Name");
					$row['CategoryEnName'] = CommonDao::channel($chid, "EnName");
					$row['IsLeaf'] = "NO";
					$row['IsTop'] = "YES";
					$row['CategoryParentID'] = "-1";
					$result[] = $row;
				}
				break;
			}

			/* use cache
		    $sql = "SELECT C.CategoryID, C.CategoryName, C.CategoryEnName," .
		    		" C.IsLeaf, C.IsTop, CR.CategoryParentID" .
		    		" FROM " . $categoryTable.
		    		" C INNER JOIN " . $categoryRshTable . " CR ON C.CategoryID = CR.CategoryChildID" .
					" WHERE CR.CategoryChildID = ". $catid;

			$row = DBQuery::instance()->getRow($sql);
			*/
			$row = $mycachefile[$catid];

			if(empty($row)) {
				break;
			}
			$result[] = $row;
			$catid = $row["CategoryParentID"];
		}

		unset($mycachefile);

		if($result != NULL){
			return array_reverse($result);
		} else {
			throw new Exception("get Category error！");
		}
	}

	public static function createProdIdTmpTbl() {
		$tblName = "tmp_product_id";
		$sql =
			"CREATE TEMPORARY TABLE $tblName (
			ID int(11) NOT NULL auto_increment, ProductID int(11) NOT NULL, PRIMARY KEY (ID)
			) TYPE=HEAP";
		DBQuery::instance(__FrontEnd_Slave)->executeUpdate($sql);
		return $tblName;
	}

	public static function dropTmpTbl($tblName) {
		if(substr($tblName, 0, 4) != "tmp_") {
			throw new Exception("Cann't drop the table($tblName).'");
		}
		$sql = "DROP TEMPORARY TABLE $tblName";
		DBQuery::instance(__FrontEnd_Slave)->executeUpdate($sql);
	}

	public static function startRequestAds($chid, $keyword, $page, $type="W", $secondKeyword="") {
		$sourceFlag = trim(strtoupper(Tracking_Session::getInstance()->getSourceGroup()));

		if($sourceFlag == "BAIDU" && Tracking_Session::getInstance()->getTrafficType() >= 0) {
			if(getRobotLimit() && getRobotType()==1 && self::$adsTestFlag=="off") {
				return false; //机器人,不显示广告
			}
		}

		if(__ADS_REQUEST_METHOD == "LOCAL") { //本机同步请求，返回将使用的URL
			$adsURL = self::getMeziAdsUrl($chid, $keyword, $page, $type, array("timeout"=>2));
			//request second keywords
			if(!empty($secondKeyword)) {
				$adsURL .= "&q2=".Utilities::encode($secondKeyword);
			}
			self::$adsAsyncURL = $adsURL;
			return true;
		}

		if(__ADS_REQUEST_METHOD != "PIPE") { //只有采用PIPE(CURL)方法请求广告的方式，允许通过
			return false;
		}
		if(self::$adsRequestHandle != null) {
			return false;
		}
		$extraParams = array("isPipe"=>"yes", "timeout"=>2,
			"ip"=>Utilities::onlineIP(),
			"useragent"=>Utilities::onlineUserAgent());
		//tracking relation
		global $nRedirCurRandStr;
		$extraParams['nRedirCurRandStr'] = $nRedirCurRandStr;

		$adsURL = self::getMeziAdsUrl($chid, $keyword, $page, $type, $extraParams);
		//change scripts name
		$adsURL = str_replace("/mezi_ads.php", "/async_mezi_ads.php", $adsURL);
		//request second keywords
		if(!empty($secondKeyword)) {
			$adsURL .= "&q2=".Utilities::encode($secondKeyword);
		}

		$cmd = "curl \"$adsURL\"";
		if(count($_COOKIE) > 0) {
			$cookieStr = "";
			foreach($_COOKIE as $key => $val) {
				if($cookieStr != "") {
					$cookieStr .= ";";
				}
				$cookieStr .= "{$key}={$val}";
			}
			$cmd .= " -b \"{$cookieStr}\"";
		}
		//echo $cmd;
		self::$adsRequestHandle = popen($cmd, "r");

    	return true;
	}

	public static function getMeziAdsUrl($chid, $keyword, $page, $type="W", $extraParams=null) {
		$typeH = $typeM = $typeV = "";
		if(substr($type, 0, 1) == "H") { //需取头三行
			$typeH = "&type=".$type;
		}
		if(substr($type, 0, 1) == "M") { //需取头三行
			$typeM = "&type=".$type;
		}
		if(substr($type, 0, 1) == "V") { //需取头三行
			$typeV = "&type=".$type;
		}
		if(defined("__MEZI_ADS")) {
			$adsURL = __MEZI_ADS;
		} else {
			$adsURL = "http://www.smarter.com.cn/mezi_ads.php";
		}
		$adsURL = $adsURL."?q=".
    		Utilities::encode($keyword)."&switch=".$page."&chid=$chid{$typeH}{$typeM}{$typeV}";
    	if($extraParams != null) {
    		foreach($extraParams as $key => $val) {
    			$adsURL .= "&{$key}=".urlencode($val);
    		}
    	}
    	//append Style Flag
    	if(self::$adsTplFlag != null) {
    		$adsURL .= "&tplFlag=".self::$adsTplFlag;
    	}
    	if(self::$adsStyleFlag != null) {
    		$adsURL .= "&styleFlag=".self::$adsStyleFlag;
    	}
    	//append Request Domain
    	if(self::$adsVisitHost != null) {
    		$adsURL .= "&visitHost=".self::$adsVisitHost;
    	}
    	//append Source Group name
    	$sourceFlag = trim(strtoupper(Tracking_Session::getInstance()->getSourceGroup()));
    	if($sourceFlag != "") {
    		$adsURL .= "&sourceFlag=".$sourceFlag;
    	}
		//adtest
		if(self::$adsTestFlag == "on") {
			$adsURL .= "&adtest=on";
		}
	    return $adsURL;
	}

	public static function isAdsNeedJS() {
		return self::$adsNeedJS;
	}

	/**
	 * 请求ADS,发生了超时
	 */
	public static function isAdsOvertime() {
		return self::$adsOvertime;
	}

	public static function getAdsCnt($ads,$uniqueFlg = true) {
		$uniqueAds = array();
		if(preg_match_all("@return _ss\('(.*?)'\)@",$ads, $uniqueAds) == false) {
			return 0;
		}
		if($uniqueFlg) {
		    return count(array_unique($uniqueAds[1]));
		} else {
			return count($uniqueAds[1]);
		}
	}

    public static function getAdsScript($chid, $keyword, $page, $type="W", $htmlID="") {
    	$str = "";
		$sourceFlag = trim(strtoupper(Tracking_Session::getInstance()->getSourceGroup()));
		if($sourceFlag == "BAIDU" && Tracking_Session::getInstance()->getTrafficType() >= 0) {
			if(getRobotLimit() && getRobotType()==1 && self::$adsTestFlag=="off") {
				return ''; //机器人,不显示广告
			}
		}
		self::$adsCount++;
		if(self::$adsCount == 1) {
	    	$adsContent = "";
	    	$startTime = time();
	    	if(__ADS_REQUEST_METHOD == "LOCAL") { //PHP进程本机同步调用
				//创建Controller层对象
				$action = new AdWordsAction();
				$action->setTrackingDisabled(true);
				$action->setDisplayDisabled(true);
				//调用执行函数
				if(self::$adsAsyncURL != "") {
					$adsURL = self::$adsAsyncURL;
				} else {
					$adsURL = self::getMeziAdsUrl($chid, $keyword, $page, $type);
				}

				$action->execute(NULL, $adsURL);
				$adsContent = $action->getSmartyData();
	    	} else if(__ADS_REQUEST_METHOD == "PIPE") { //PIPE/CURL进程异步请求
		    	if(self::$adsRequestHandle != null) {
		    		for($i=0; $i<100; $i++) {
		    			$tmp = fread(self::$adsRequestHandle, 4096);
		    			if($tmp == false) {
		    				break;
		    			}
		    			$adsContent .= $tmp;
		    		}
		    		$adsContent = trim($adsContent);
		    		pclose(self::$adsRequestHandle);
		    		self::$adsRequestHandle = null;
		    	}
	    	}
	    	if(stripos($adsContent, "redir.php") === false) { //无广告
		    	//超时或没有广告时，再用JS请求一遍
		    	if(time() - $startTime >= 2) { //超时
		    		self::$adsOvertime = true;
		    	}
		    	self::$adsNeedJS = true;
			}
			if(self::$adsNeedJS == false) {
		    	$str = "<script language='javascript'>{$adsContent}</script>\r\n";
	    	} else {
				$adsURL = self::getMeziAdsUrl($chid, $keyword, $page, $type);
				if(GoogleDNSDao::getDnslookup() == "IP") {
					$adsURL .= "&dnslookup=DOMAIN";
				} else if(self::$adsOvertime) {
					$adsURL .= "&dnslookup=IP";
				}
		    	$str = "<script language='javascript' src='{$adsURL}'></script>\r\n";
	    	}
		}
		$jsName = "_ads_".strtolower(substr($type, 0, 1));
		$str .= "<script language='javascript'>";
		$str .= "if(typeof($jsName)!='undefined')";
		if(empty($htmlID)) {
			$str .= "document.write($jsName);";
		} else {
			$str .= "if(document.getElementById(\"$htmlID\")!=null && $jsName!=\"\")" .
					"	document.getElementById(\"$htmlID\").innerHTML=$jsName;";
		}
		$str .= "</script>\r\n";
    	return $str;
    }

    /* method: create search key word */
	public static function createSearchkey($val) {

		$path = __SETTING_FULLPATH . "config/search_Keyword.csv";
		$csv = new CSV();
		$result = array();
		if($val && is_array($val)) {
			foreach($val as $item) {
                 $tmp = explode(',', $item);
				 if($tmp[1] && stripos($tmp[1],"http://") !==false) {
					 $tmp[1] = trim($tmp[1]);
				 } else {
					 $tmp[1]='';
				 }
				 $result[] = array($tmp[0],$tmp[1]);
			}
		} elseif($val) {
		    $val = explode(',', $val);
			foreach($val as $item) {
				$result[] = array($item,'');
			}
		}
		$csv->storeFromArray($path, $result);
		FileDistribute::syncFile(__SETTING_PATH . 'config/search_Keyword.csv');
		return NULL;
	}

	/* method: get search key word from csv */
	public static function getSearchKey() {

		$path = __SETTING_FULLPATH . "config/search_Keyword.csv";
		$csv = new CSV();
		$arr = $csv->loadToArray($path);
		$content = '';
		for ($i =0; $i < count($arr); $i++) {
			if(!$arr[$i][0]) {
				continue;
			}
			if($arr[$i][1] && stripos($arr[$i][1],"http://") !==false) {
                $target = '';
				if(stripos($arr[$i][1],__WEB_DOMAIN_NAME) === false) {
					$target = "target='_blank' rel='nofollow'";
				}
				$content .= "<a href='".$arr[$i][1]."' $target>".
					        $arr[$i][0]."</a>&nbsp;";
			} else {
			    $url = PathManager::getSearchUrl($arr[$i][0]);
			    $content .= "<a href='".$url."'>".$arr[$i][0]."</a>&nbsp;";
			}
		}
		return $content;
	}

	public static function getSearchKeyArr() {
		$path = __SETTING_FULLPATH . "config/search_Keyword.csv";
		$csv = new CSV();
		$arr = $csv->loadToArray($path);
		return $arr;
	}

	/* method: get category introduction */
	public static function getIntroduction($chid, $catid) {

		$path = FRONT_END_ROOT . "etc/config_seo/channel_Introduction_$chid.csv";
		$csv = new CSV();
		$arr = $csv->loadToArray($path, false, -1);
		$content = "";
		for ($i =0; $i < count($arr); $i++) {
			if ($arr[$i][0] == $catid) {
				$content = $arr[$i][1];
				break;
			}
		}
		return $content;
	}

	/* method: get hot category link */
	public static function getHotCategory($chid) {

		$path = __SETTING_FULLPATH . "config_seo/channel_HotCategory_$chid.csv";
		$csv = new CSV();
		$arr = $csv->loadToArray($path, true, -1);
		return $arr;
	}

	/* method: get getRelatedSearch array */
	public static function getRelatedSearch($chid, $cateid=0) {
		$content = '';
		$path = __SETTING_FULLPATH . "config_seo/channel_RelatedSearch_$chid.csv";
		$csv = new CSV();
		$arr = $csv->loadToArray($path, true, -1);
		for ($i =0; $i < count($arr); $i++) {
			if ($arr[$i]["categoryID"] == $cateid) {
				$content = $arr[$i]["keywords"];
				break;
			}
		}
		$rtn = split(",", $content);
		return $rtn;
	}

	/* method: get getPopularSearch array */
	public static function getPopularSearch($chid, $cateid=0) {

		$path = FRONT_END_ROOT . "etc/config_seo/channel_PopularSearch_$chid.csv";
		$csv = new CSV();
		$arr = $csv->loadToArray($path, true, -1);
		for ($i =0; $i < count($arr); $i++) {
			if ($arr[$i]["categoryID"] == $cateid) {
				$content = $arr[$i]["keywords"];
				break;
			}
		}
		$rtn = split(",", $content);
		return $rtn;
	}

	/**
	 * @功    能：get the relevant brank for product Detail Page
	 * @输入参数：chid cateid decbrand 去掉的品牌 prodid 产品ID
	 * @技术点：采用产品ID 作为初次的随机数种子
	 */
    public static function getRelevantBrand($chid,$cateid,$decBrand='',$prodid=0) {
		$path = FRONT_END_ROOT . "etc/config_seo/channel_RelevantBrand_$chid.csv";
		if(!file_exists($path)) {
			return array();
		}
		if(!$chid || !$prodid || !is_numeric($prodid)) {
			return array();
		}

		//to check if the cache file exist.if exist, return the cache array
		$cacheDir = __SE_CACHE_DIR."relevantbrand_product/".$chid."/".substr($prodid,-3)."/";
		$cacheFile = $cacheDir."A_".$prodid;

		if(file_exists($cacheFile)) {
			$result = file_get_contents($cacheFile);
			if($result) {
				$result = unserialize($result);
				if(is_array($result)) {
					return $result;
				}
			}
		}

		$csv = new CSV();
		$result = array();
		$brandArr = array();

		//get the brand from csv file and strip the conflict brand which gives by launch
		$arr = $csv->loadToArray($path, true, -1);
		for($i=0;$i<count($arr);$i++) {
			if($arr[$i]['CategoryID'] == $cateid) {
				  if(!$arr[$i]['BrandAliasList']) {
					  return $result;
				  }
				  $brandArr = explode(";",$arr[$i]['BrandAliasList']);
				  $brandCnt = count($brandArr);

				  //strip the last item if it's empty
				  if(!$brandArr[$brandCnt-1]) {
					  unset($brandArr[$brandCnt-1]);
				  }
				  //strip the brand
				  if($decBrand) {
					  if(preg_match("/(.*)\((.*)\)$/i",$decBrand,$matches)) {
						  $key=array_search($matches[0],$brandArr);
						  if($key !==false){
							  unset($brandArr[$key]);
						  }
						  $key=array_search($matches[1],$brandArr);
						  if($key !==false){
							  unset($brandArr[$key]);
						  }
					  } else {
						  $key=array_search($decBrand,$brandArr);
						  if($key !==false){
							  unset($brandArr[$key]);
						  }
					  }
				  }

				  //strip the first item if it's empty
				  if(!$brandArr[0]) {
					  unset($brandArr[0]);
				  }

				  /*reset the key for the array for it has use 'unset'
				   *and the key has not been sequential
				   */
				  if($brandArr) {
					  sort($brandArr);
				  }

				  //get the category alias name
				  if($arr[$i]['CategoryAliasName']) {
					  $cateAliasName = $arr[$i]['CategoryAliasName'];
				  } else {
					  $cateAliasName = $arr[$i]['CategoryName'];
				  }
			}
		}

		//store the result in the cachefile which will not expired forever unless  remove by manual
		if($brandArr) {
			  $brandCnt = count($brandArr);
			  $index = 0;
			  $preSeed = 0;
			  $index = $prodid % $brandCnt;
			  $preSeed = $prodid;
			  $cateEnName = CategoryDao::get($chid,$cateid, 'CategoryEnName');
			  $showNum = min(5,$brandCnt);
			  $selected = array();
			  $maxCircle = 0;
			  for($j=0;$j<$showNum;$j++) {
				  if(!$brandArr[$index] || $selected[$index] ==1) {
					  $j--;
					  $preSeed = mt_rand();
					  $index = $preSeed % $brandCnt;
					  if($maxCircle > 5000) {
						  break;
					  }
					  $maxCircle++;
					  continue;
				  }
				  $maxCircle = 0;
				  $selected[$index] = 1;
				  $mfName = ManufacturerDao::getStardardManuName($chid,$brandArr[$index], false);
				  if(!$mfName) {
					  $mfName = $brandArr[$index];
				  }
				  $result[$j]['Name'] = $brandArr[$index].$cateAliasName;
				  $result[$j]['Href'] = PathManager::getCategoryUrl($chid,$cateid,
																	$cateEnName,
															  array("atr_0"=>$mfName));
				  mt_srand($preSeed);
				  $preSeed = mt_rand();
				  $index =  $preSeed % $brandCnt;
			  }
		}

		//store the result to cache file
		if(!is_dir($cacheDir)) {
			mkdir($cacheDir, 0777,true);
		}
		file_put_contents($cacheFile, serialize($result));
		return $result;
	}

	public static function getChannelForSelect() {
		$channels = CommonDao::channel(null, "ID");
		foreach($channels as $index => $chid) {
			if($chid == 200) {
				continue;
			}
			$url = PathManager::getChannelUrl($chid);
			$channelForSelect[$url] = CommonDao::channel($chid, "Name");
		}
		$channelForSelect['/c2c/'] = '淘宝精品';
		return $channelForSelect;
	}
}
?>