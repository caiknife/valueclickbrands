<?php
/*
 * Created on 2009-12-02
 * class.TourDao.php
 * -------------------------
 * deal with tours 
 * 
 * 
 * @author Thomas FU
 * @email	thomas_fu@mezimedia.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.TourDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class TourDao {
	
	const CACHE_TIME = 21600;
	
	private  function __parseTourInfo($tourInfo) {
		$tourInfo["DetailURL"] = $tourInfo["TourID"] ? PathManager::getTourUrl($tourInfo["TourID"]):"";
		$tourInfo['ImageURL'] = $tourInfo['TourPictureUrl'];
		$tourInfo["tagUrl"] = $tourInfo["RegionName"] ? PathManager::getTourTag($tourInfo) : "";
		$tourInfo["0fferUrl"] = $tourInfo["TourUrl"] ? PathManager::getTravelOfferUrl($tourInfo["TourUrl"], "tour") : "";
		return $tourInfo;	
	}
	
	//��ȡ������·��Ϣ
	public function getTourInfo($tourID, $select = "*") {
		$tourID = $tourID * 1;
		$where = " AND t.TourID = {$tourID}";
		$rs = $this->getTourList(false, $where, '0,1', $select);
		return $rs[0];
	}
	
	//��ȡ��·��Ϣ�б�
	public function getTourList($selectCount = false, $where = '', $limiter = "0,20", 
		$select = 't.TourID, Name, TourUrl, DepartRegionName, Price, TourPictureUrl, 
		r_StartTime, Info, PlaceSights, DestinationType', $orderby = "t.TourID DESC", 
		$groupby = "", $isJoinTable = false) {			
		if ($isJoinTable) {
			$select = $select == '*' ? '*' : "{$select}, RegionName, RegionType";
			$innerJoin = " INNER JOIN TourRegionTag AS r ON r.TourID = t.TourID ";
		}
		$sql = "SELECT {$select} FROM TourTbl AS t {$innerJoin}  WHERE 1 {$where}";
		
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
				$rs[$k] = $this->__parseTourInfo($v);
			}
		}
		return $rs;
	}
	
	/**
	 * �õ�����������·
	 *
	 * @param string $cityName ������·��������
	 * @param int $type 1���ǹ����� 2���Ǿ�����
	 * @param string $limit
	 * @param string $select
	 * @return array $tourList or false
	 */
	public function getHotTour($cityName = '', $type = 0, $limit = '0,6') {
		$type = $type * 1;
		$fileName = md5($cityName.$type.$limit);
		$subDir = 'travel/TourHotList';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}	
		$where = " AND IsHot = 'YES' ";
		if ($type)  {
			$where .= $type == 1 ? " AND DestinationType = 'china'" : " AND DestinationType='world'";
		}
		$where .= $cityName ? " AND DepartRegionName = '".DBQuery::filter($cityName)."'":"";
		$rs = $this->getTourList(false, $where, $limit);
		FileCache::setDateCache($subDir, $fileName, serialize($rs));
		return $rs;
	}
	
	//�õ��Ƽ���·
	public function getPriorityTour($cityName = '', $type = 0, $limit = '0,2') {
		$type = $type * 1;
		$fileName = md5($cityName.$type.$limit);
		$subDir = 'travel/TourPriorityList';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}
		$where = " AND IsPriority = 'YES' ";
		if ($type)  {
			$where .= $type == 1 ? " AND DestinationType = 'china'" : " AND DestinationType='world'";
		}
		if ($cityName) {
			$where .= " AND RegionName = '".DBQuery::filter($cityName)."'";
			$rs = $this->getTourList(false, $where, $limit, 't.TourID, Name, TourUrl, Price, TourPictureUrl, Info', '', '', true);
		}
		else {
			$rs = $this->getTourList(false, $where, $limit);
		}
		FileCache::setDateCache($subDir, $fileName, serialize($rs));
		return $rs;
	}
	
	/**
	 * �õ�����Ŀ�ĵ�
	 *
	 * @param int $regionType 1������ 2������
	 * @param int $destinationType  ����Ŀ�ĵ�����  1������Ŀ�ĵ� 2������Ŀ�ĵ�
	 * @param  string $limit
	 * @return array
	 */
	public function getHotRegion($regionType = 0, $destinationType = 0, $limit = "0,20") {
		$regionType = $regionType * 1;
		$destinationType = $destinationType * 1;
		$fileName = "{$regionType}-{$destinationType}";
		$subDir = 'travel/hotRegion';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}	
		if ($destinationType) {
			$where .= $destinationType == 1 ? " AND DestinationType = 'china'" : " AND DestinationType='world'";
		}
		$where .= $regionType == 1 ? " AND RegionType = 1 ":" AND RegionType = 0";
		$startTime = getDateTime("Y-m-d");
		$where .= " AND t.r_EndTime >='$startTime'";
		$rs = $this->getTourList(false, $where, $limit, "t.TourID, t.DestinationType", 
			"COUNT(RegionName) DESC", "RegionName", true);	
		FileCache::setDateCache($subDir, $fileName, serialize($rs));
		return $rs; 
	}
	
	//����Ŀ�ĵز��ֳ��� ����
	public function getDestinationRegion($departCity = '�Ϻ�', $limit = '0,20') {
		$fileName = md5($departCity);
		$subDir = 'travel/TourDestinationRegion';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}
		$departTime = getDateTime("Y-m-d");
		$where = " AND t.DepartRegionName = '".DBQuery::filter($departCity)."' AND t.r_EndTime >= '{$departTime}'".
			" AND r.RegionName != '".DBQuery::filter($departCity)."' AND r.RegionType = 1";		//ȡ���м���
		$rs = $this->getTourList(false, $where, $limit, "t.TourID", 
				"COUNT(RegionName) DESC", "RegionName", true);
		FileCache::setDateCache($subDir, $fileName, serialize($rs));
		return $rs;
	}
	
	//�õ�������������
	public function getDepartRegion() {
		$fileName = 'DepartCity';
		$subDir = 'travel';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}
		$departTime = getDateTime("Y-m-d");
		$where = " AND t.r_EndTime >= '{$departTime}'";
		$rs = $this->getTourList(false, $where, '', 'DepartRegionName', '', 'DepartRegionName');
		FileCache::setDateCache($subDir, $fileName, serialize($rs));
		return $rs;
	}
	
	/**
	 *����ʱ���Ŀ��search�б�
	 *
	 * @param array $paramater //�������� 
	 * @return array
	 */
	public function getSearchList($selectCount = false, $paramater, $count = 10) {
		@extract($paramater);
		if($pn < 1) $pageNo = 1;
		else $pageNo = $pn;
		$offset = ($pageNo - 1) * $count;
		$limit = "{$offset},{$count}";
		$isJoinTable = false;
		$fromTime = $fromTime ? $fromTime : getDateTime("Y-m-d");
		$where .= $fromCity ? " AND DepartRegionName = '".DBQuery::filter($fromCity)."'" : "";
		if ($toCity) {
			$where .= " AND r.RegionName = '".DBQuery::filter($toCity)."'";
			$isJoinTable = true;
		}
		if (empty($toTime)) {
			$where .= " AND r_EndTime >= '{$fromTime}'";
		}
		else {
			$where .= " AND r_StartTime >= '{$fromTime}' ";
			$where .= $toTime ? " AND r_EndTime <= '{$toTime}'" : "";
		}
	
		if ($regionType >= 0 && $regionType != NULL) {
			$where .= $regionType == 1 ? " AND RegionType = 1 ":" AND RegionType = 0";
			$isJoinTable = true;
		}
		if ($destinationType) {
			$where .= " AND DestinationType = '{$destinationType}'";
		}
		if ($IsPriority) {
			$where .= " AND IsPriority = '{$IsPriority}'";
		}
		
		$fileName = md5($selectCount.$where.$limit.$select);
		$subDir = 'travel/ToursearchResult';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}
		$select = "t.TourID, t.Name, t.Price, t.IsHot, t.Days, t.IsPriority, t.Info, t.TourPictureUrl,
			t.PlaceSights, DestinationType";
		$tourList = $this->getTourList($selectCount, $where, $limit, $select, '', '', true);
		FileCache::setDateCache($subDir, $fileName, serialize($tourList));
		return $tourList;
	}
	
	/**
	 * �õ���ؾ�����ݾ���ids
	 *
	 * @param array $sightID ����id
	 * @param int $limit ����ID����
	 * @return array
	 */
	public function getPlacesBySightID($sightID, $limit = 10) {
		$sightID = @array_unique($sightID);
		for ($i = 0; $i <= count($sightID); $i++) {
			if (intval($sightID[$i]) && $loop < $limit) {
				$placeSights .= ", {$sightID[$i]}";
				$loop++;
			}
		}
		if ($loop) {
			$sql = sprintf("SELECT PlaceName, PlaceUrl, PlaceImageUrl, PlaceID FROM TourPlaces 
				WHERE PlaceID IN (%s) ", trim($placeSights, ","));
			$rs = DBQuery::instance()->executeQuery($sql);
			foreach ($rs as $tmpK => $tmpV) {
				$rs[$tmpK]['PlaceUrl'] = PathManager::getTourPlaces($tmpV["PlaceUrl"]);
				$tmpV["RegionName"] = $tmpV["PlaceName"];
				$rs[$tmpK]['url'] = PathManager::getTourTag($tmpV);
			}
			return $rs;
		}
		return null;
	}
	
	public function getPlacesBySightName($cityName, $limit = '0,8') {
		if (empty($cityName)) return NULL;
		$fileName = md5($cityName.$limit);
		$subDir = 'travel/ToursearchSights';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}
		$sql = sprintf("SELECT PlaceName, PlaceUrl, PlaceImageUrl, PlaceID FROM TourPlaces 
				WHERE PlaceName = '%s' OR PlaceRegionName = '%s' LIMIT $limit ", DBQuery::filter($cityName), DBQuery::filter($cityName));
		$rs = DBQuery::instance()->executeQuery($sql);
		foreach ($rs as $tmpK => $tmpV) {
				$tmpV["RegionName"] = $tmpV["PlaceName"];
				$rs[$tmpK]['url'] = PathManager::getTourTag($tmpV);
		}
		FileCache::setDateCache($subDir, $fileName, serialize($rs));
		return $rs;	
	}
}
?>