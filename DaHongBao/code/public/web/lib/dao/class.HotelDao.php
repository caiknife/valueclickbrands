<?php
/*
 * Created on 2009-12-10
 * class.TourDao.php
 * -------------------------
 * deal with hotels 
 * 
 * 
 * @author Thomas FU
 * @email	thomas_fu@mezimedia.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.HotelDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class HotelDao {
	const CHILD = 14;
	const CACHE_TIME = 21600;
	static $_START = array('五星级酒店' => 5, '四星级酒店' => 4, '三星级酒店' => 3, '经济型酒店' => 2);
	private function __parseHotelInfo($productInfo) {
		$productInfo['DetailURL'] = PathManager::getHotelProductUrl(self::CHILD, $productInfo['ProductID']);
		$productInfo['ImageURL'] = PathManager::getProductImage(self::CHILD, $productInfo['ProductID'], "small", 
			$productInfo['HasImage']);
		$productInfo['LoginDate'] = getDateTime('Y-m-d', $productInfo['r_AddDate']);
		if ($productInfo['CategoryName']) {
			$productInfo["CategoryImage"] = "<img src='/images/travel/star".self::$_START[$productInfo['CategoryName']].".gif'>";
		}
		$productInfo["MetaDes"] = Utilities::cutString($productInfo["Description"], 150);
		list($productInfo['Address'], $productInfo['Tel']) = explode("|", $productInfo['Brief']);
		return $productInfo;	
	}
	
	//获取单条线路信息
	public function getHotelInfo($hotelID, $select = "*") {
		$hotelID = $hotelID * 1;
		$where = " AND A.ProductID = {$hotelID}";
		$rs = $this->getHotelList(false, $where, '0,1');
		$rs['ImageURL'] = PathManager::getProductImage(self::CHILD, $rs['ProductID'], "big", $rs['HasImage']);
		return $rs;
	}
	
	//获取商家信息
	public function getHotelMerchant($hotelID) {
		$hotelID = $hotelID * 1;
		$mbpTable = CommonDao::channel(self::CHILD, "MerBidProdTable");
		$sql = sprintf("SELECT MerchantID, MerchantName, Spec FROM {$mbpTable} M WHERE M.ProductID = %d", $hotelID);
		$rs = DBQuery::instance()->executeQuery($sql);
		return $rs;
	}
	
	//获取线路信息列表
	public function getHotelList($selectCount = false, $where = '', $limiter = "0,20", $select = '',
		$orderby = "A.ProductID DESC", $groupby = "", $isJoinTable = false) {
		$productTable = CommonDao::channel(self::CHILD, "ProductTable");
		$catProdTable = CommonDao::channel(self::CHILD, "CatProdTable");
		$mbpTable = CommonDao::channel(self::CHILD, "MerBidProdTable");
		$defaultSelect = "A.ProductID, A.Name AS ProductName, A.Description, A.Brief, A.City,".
			" A.CategoryName, A.HasImage, A.r_AddDate, A.r_LowestPrice";
		
		if ($select) {
			$defaultSelect = $select == "*" ? "*" : "{$defaultSelect}, {$select}";
		}
		if ($isJoinTable) {
			$innerJoin = " INNER JOIN {$mbpTable} AS M ON M.ProductID = A.ProductID ";
		}
		
		$sql = "SELECT {$defaultSelect} FROM {$productTable} A  {$innerJoin}  WHERE 1 {$where} ";
		//vaild price 
		$sql .= " AND A.r_LowestPrice > 0";
		if ($selectCount) {
			$sql = preg_replace("/SELECT.*?FROM/As", 'SELECT COUNT(*) AS count FROM', $sql);
			return DBQuery::instance()->getOne($sql);
		}
		if ($groupby) {
			$groupby = str_ireplace("GROUP BY", "", $groupby);
			$sql .= " GROUP BY $groupby "; 
		}
		if ($orderby) {
			$orderby = str_ireplace("ORDER BY", "", $orderby);
			$sql .= " ORDER BY $orderby "; 
		}
		if ($limiter) {
			$sql .= " LIMIT $limiter";
		}
		$tmpRs = DBQuery::instance()->executeQuery($sql);
		if ($tmpRs) {
			foreach ($tmpRs as $k => $v) {
				$rs[$k] = $this->__parseHotelInfo($v);
			}
		}
		if ($limiter == "0,1") {
			return $rs[0];
		}
		return $rs;
	}
	
	/**
	 * 频道首页热门城市酒店
	 *
	 * @param string $cityName
	 * @param string $limit
	 * @return  array 
	 */
	public function getHotCityHotel($cityName = null, $count = 2) {
		$fileName = md5($cityName.$count);
		$subDir = 'travel/HotelHotList';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}	
		$where = " AND A.City = '".DBQuery::filter($cityName)."'";
		$max = $count * 20;
		$hotelList = $this->getHotelList(false, $where, "0, $max", '', 'COUNT(A.ProductID) DESC, CHAR_LENGTH(GROUP_CONCAT(Spec)) DESC',
			'A.ProductID', true);
		if ($hotelList) {
			$rs = array_slice($hotelList, 0, $count);
			FileCache::setDateCache($subDir, $fileName, serialize($rs));
			return $rs;
		}
		return null;
	}
	
	//频道首页推荐酒店
	public function getPriorityHotel($limit = '0,8') {
		$fileName = "HotelPriorityList";
		$subDir = 'travel';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}	
		$select = "COUNT(A.ProductID) as ProductCount";
		$where  = " AND A.HasImage = 'YES'";
		//@todo optimize
		$orderBy = "RAND()";
		$groupBY = "A.ProductID HAVING ProductCount > 1";
		$rs = $this->getHotelList(false, $where, $limit, $select, $orderBy, $groupBY, true);
		FileCache::setDateCache($subDir, $fileName, serialize($rs));
		return $rs;
	}
	
	//低价酒店
	public function lowerPriceHotelList($cityName = '上海', $categoryName = '四星级酒店', $limit = '0,6') {
		$fileName = md5($cityName.$limit);
		$subDir = 'travel/HotelLowerPriceList';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}	
		$where = $cityName ? "AND A.City = '".DBQuery::filter($cityName)."'" : "";
		$where .= $categoryName ? " AND A.CategoryName = '".DBQuery::filter($categoryName)."'" : "";
		$hotelList = $this->getHotelList(false, $where, $limit, '', "A.r_LowestPrice ASC");
		FileCache::setDateCache($subDir, $fileName, serialize($hotelList));
		return $hotelList;
	}
	
	/**
	 * @param array $paramater //参数数组 
	 * @return array
	 *
	 */
	public function getSearchList($selectCount = false, $paramater, $count = 10) {
		@extract($paramater);
		if($pn < 1) $pageNo = 1;
		else $pageNo = $pn;
		$offset = ($pageNo - 1) * $count;
		$limit = "{$offset},{$count}";		
		$where .= $hotelCity ? " AND A.City = '".DBQuery::filter($hotelCity)."'" : "";
		$where .= $hotelStar ? " AND A.CategoryName = '".DBQuery::filter($hotelStar)."'" : "";
		$where .= $hotelName ? " AND A.Name Like '%%".DBQuery::filter($hotelName)."%%'" : "";
		if ($hotelPrice) {
			list($minPrice, $maxPrice) = explode("-", $hotelPrice);
			$where .= $minPrice ? " AND A.r_LowestPrice >= '".intval($minPrice)."'" : "";
			$where .= $maxPrice ? " AND A.r_LowestPrice <= '".intval($maxPrice)."'" : "";
		}
		$hotelList = $this->getHotelList($selectCount, $where, $limit);
		return $hotelList;
	}
}
?>