<?php
/*
 * Created on 2009-12-9
 * TicketDao.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.TicketDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class TicketDao {
	private static $ticketSearchResult = "/TicketSearchResult";
	private static $ticketSearchStartup = '/TicketSearchStartup';
	const CACHE_TIME = 21600;
	
	/**
	 * 搜索特价机票的接口
	 */
	public static function searchTicket($keys) {
		$params = array (
			'resultKeys' => $keys
		);
		return self::queryResource(self::$ticketSearchResult, $params);
	}
	
	/**
	 * 得到特价机票搜索结果 ELONG|SHA|PEK|2009-12-29
	 */
	public static function searchStartup($startCity, $endCity, $flyDate) {
		$params = array(
			'startCity' => $startCity,
			'endCity' => $endCity,
			'flyDate' => $flyDate,
		);
		return self::queryResource(self::$ticketSearchStartup, $params);
	}
	
	protected static function queryResource($resourceName, $params) {
		foreach(array_keys($params) as $key) {
			$params[$key] = iconv("GBK", "UTF-8", $params[$key]);
		}
		$url = __TICKET_SERVICE_ADDRESS . $resourceName."?".http_build_query($params);
		$curl = new CURL();
		$content = $curl->get_contents($url);
		//$content = iconv("UTF-8", "GBK//IGNORE", $content);
		return self::convert(json_decode($content));
	}
	
	protected static function convert($jsonData) {
		if(is_object($jsonData)) {
			$jsonData = (array) $jsonData;
		}
		if(is_array($jsonData)) {
			foreach(array_keys($jsonData) as $key) {
				if(is_string($jsonData[$key])) {
					$jsonData[$key] = $jsonData[$key];
				} else {
					$jsonData[$key] = self::convert($jsonData[$key]);
				}
			}
		} else {
			return $jsonData; 
		}
		return $jsonData;
	}
	
	//特价机票
	public  static function lowerPriceTicket($cityName = '上海', $limit = '0,6') {
		$fileName = md5($cityName.$limit);
		$subDir = 'travel/FlightsLowerPrcieList';
		if ($data = FileCache::getDateCache($subDir, $fileName, self::CACHE_TIME)) {
			return unserialize($data);
		}	
		$limit = $limit ? " LIMIT {$limit} " : "";
		$sql = "SELECT FromCity, ToCity, FlightNo, Price, DATE_FORMAT(StartTime, '%Y-%m-%d') AS StartTime,".
			" DisCount FROM TourFlight WHERE FromCity = '".DBQuery::filter($cityName)."'  ORDER BY Price ASC $limit";
		$rs = DBQuery::instance()->executeQuery($sql);
		FileCache::setDateCache($subDir, $fileName, serialize($rs));
		return $rs;	
	}
}
?>
