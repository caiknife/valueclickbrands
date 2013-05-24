<?php
/*
 * Created on 2009-12-02
 * TravelAction.php
 * -------------------------
 * display travel channel homepage
 * 
 * 
 * @author Thomas FU
 * @email thomas_fu@mezimedia.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.TravelAction.php,v 1.1 2013/04/15 10:57:53 rock Exp $
 * @link       http://www.smarter.com/
 */

class TravelAction extends BaseAction {

	/**
	 * 入力check
	 */
	protected function check($request, $response) {
	}

	/**
	 * 分支
	 */
	protected function service($request, $response) {
		switch($request->getSwitch()) {
			case "detail":
				$this->doDetailAction($request, $response);
				break;
			case "search":
				$this->doTravelSearch($request, $response);
				break;
			case "tag":
				$this->doTravelTag($request, $response);
				break;
			case "tourChannel":
				$this->doTravelChannel($request, $response);
				break;
			case "hotRegionAjax":
				$this->getHotRegionAjax($request, $response);
				break;
			default :
				$this->doTravelAction($request, $response);
				break;
		}
	}

	public function doTravelAction($request, $response) {
		$tourDao = new TourDao();
		$tourIn = $tourDao->getHotTour('上海', 1, '0,2');	//热门国内游
		$tourOut = $tourDao->getHotTour('上海', 2, '0,2');	//热门境外游
		$hotRegionIn = $tourDao->getHotRegion(1, 1);	//热门国内目的地
		$hotRegionOut = $tourDao->getHotRegion(1, 2);	//热门国外目的地
		$departRegion = $tourDao->getDepartRegion();	//得到出发城市
		$oPage = new PageDao();
		$couponList = $oPage->getPage('HOTMERCHANT_INCLUDE_INDEX');	//推荐优惠券
		
		//得到超值酒店
		require_once(__ROOT_PATH."etc/travel_config.php");
		$hotelDao = new HotelDao();
		foreach ($_HOTEL_CITY AS $hotCity) {
			$lowerPriceHotelList[$hotCity] = $hotelDao->lowerPriceHotelList($hotCity, '', '0,12');
		}
		foreach ($_FLIGHTS_CITY AS $hotCity) {
			$lowerPriceFlightsList[$hotCity] = TicketDao::lowerPriceTicket($hotCity, '0,12');
		}
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("flightsCityList", $_FLIGHTS_CITY);
		$response->setTplValue("hotelCityList", $_HOTEL_CITY);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);	
		$dpartEndDate = getDateTime("Y-m-d", time()+3600*72);
		$response->setTplValue("dpartEndDate", $dpartEndDate);
		$response->setTplValue("couponList", $couponList);
		$response->setTplValue("tourIn", $tourIn);
		$response->setTplValue("tourOut", $tourOut);
		$response->setTplValue("hotRegionOut", $hotRegionOut);
		$response->setTplValue("hotRegionIn", $hotRegionIn);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplName("travel/travel");
	}
	
	public function doTravelChannel($request, $response) {
		$tourDao = new TourDao();
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		$hotPlacesList = $tourDao->getHotRegion(2);	//热门景点
		$priorityTourList = $tourDao->getPriorityTour(); //推荐路线
		$hotTourList = $tourDao->getHotTour('上海', 0, '0,10');	//热门线路
		foreach ((array)$hotTourList as $arrk => $arrV) {
			$placeSights .= ",".$arrV["PlaceSights"];
		}
		//得到相关景点
		$placeSights = explode(",", trim(str_replace("||", ",", $placeSights), ","));
		$placeSights = $tourDao->getPlacesBySightID($placeSights);
		
		//得到最新国内游线路
		$whereIn = " AND DestinationType = 'china'"; 
		$newTourInList = $tourDao->getTourList(false, $whereIn, "0,10", 'Name, TourID, Price');
		
		//得到最新境外游线路
		$whereOut = "AND DestinationType = 'world'"; 
		$newTourOutList = $tourDao->getTourList(false, $whereOut, "0,10", 'Name, TourID, Price');
		
		$hotelDao = new HotelDao();
		$lowerPriceHotelList = $hotelDao->lowerPriceHotelList();	//超值线路
		$lowerPriceFlightsList = TicketDao::lowerPriceTicket('上海'); //超值机票
		
		$oPage = new PageDao();
		$couponList = $oPage->getPage('HOTMERCHANT_INCLUDE_INDEX');	//推荐优惠券
		
		$response->setTplValue("couponList", $couponList);
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("newTourOutList", $newTourOutList);	
		$response->setTplValue("newTourInList", $newTourInList);	
		$response->setTplValue("placeSights", $placeSights);	
		$response->setTplValue("priorityList", $priorityTourList);
		$response->setTplValue("hotTourList", $hotTourList);
		$response->setTplValue("hotPlacesList", $hotPlacesList);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplValue("placeSights", $placeSights);
		$response->setTplValue("searchType", 'line');
		$response->registerFun('cutString', 'modifier', array('Utilities','cutString'));
		$response->setTplName("travel/tour");
	}
	
	public function doTravelSearch($request, $response) {
		$tourDao = new TourDao();
		$departCity = Utilities::filterHtmlCode($request->getParameter("departCity"));
		$destinationCity = Utilities::filterHtmlCode($request->getParameter("destination"));
		$departStartTime = Utilities::filterHtmlCode($request->getParameter("TourStartTime"));
		$departEndTime = Utilities::filterHtmlCode($request->getParameter("TourEndTime"));
		$pn = $request->getParameter("page") * 1;
		try {
			if (empty($departCity) && empty($destinationCity)) {
				throw new Exception("出发城市和目的地城市不能同时为空.");
			}
			if (empty($departStartTime)) {
				$departStartTime = getDateTime("Y-m-d");
			}
			else {
				if (preg_match("/[^0-9-]/", $departStartTime)) {
					throw new Exception("出发时间格式错误.");
				}
			}
			if ($departEndTime && preg_match("/[^0-9-]/", $departEndTime)) {
					throw new Exception("出发结束时间格式错误.");
			}	
			if ($departEndTime && strtotime($departEndTime) < strtotime($departStartTime)) {
				throw new Exception("出发开始时间不能大于出发结束时间.");
			}
			
			$paramater["fromTime"] = $departStartTime;
			$paramater["toTime"] = $departEndTime;
			$paramater["fromCity"] = $departCity;
			$paramater["toCity"] = $destinationCity;
			$paramater["pn"] = $pn;
			
			$counter = $tourDao->getSearchList(true, $paramater);
			if ($counter) {
				$pageUrl = "/travel_search.php?departCity={$departCity}&destination={$destinationCity}&".
					"TourStartTime={$departStartTime}&TourEndTime={$departEndTime}&tour=tour&page=%d";
				$pageStr =Utilities::getPageStr($paramater, $pageUrl, $counter, 15);
				$searchList = $tourDao->getSearchList(false, $paramater, 15);
			}
			//得到推荐线路
			if ($pn <= 1) {
				$paramater["IsPriority"] = 'YES';
				$priorityList = $tourDao->getSearchList(false, $paramater, 2);
			}
			//相关景点
			$placeSights = $tourDao->getPlacesBySightName($destinationCity);
			$response->setTplValue("placeSights", $placeSights);
			$response->setTplValue("searchList", $searchList);
		}
		catch (Exception $e) {
			$errorMessage = $e->getMessage();
			$response->setTplValue("error", $errorMessage);
		}
		
		$tourHotList = $tourDao->getHotTour($departCity, 0, '0,6');	//热门线路
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		
		$hotelDao = new HotelDao();
		$lowerPriceHotelList = $hotelDao->lowerPriceHotelList($departCity);	//超值酒店
		$lowerPriceFlightsList = TicketDao::lowerPriceTicket($departCity); //超值机票
		$oPage = new PageDao();
		$couponList = $oPage->getPage('HOTMERCHANT_INCLUDE_INDEX');	//推荐优惠券
		
		$response->setTplValue("couponList", $couponList);
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("searchType", 'line');
		$response->registerFun('cutString', 'modifier', array('Utilities','cutString'));
		$response->setTplValue("departStartTime", $departStartTime);
		$response->setTplValue("departEndTime", $departEndTime);
		$response->setTplValue("departCity", $departCity);
		$response->setTplValue("destinationCity", $destinationCity);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplValue("priorityList", $priorityList);	//推荐线路
		$response->setTplValue("tourHotList", $tourHotList);
		$response->setTplValue("pageStr", $pageStr);
		$response->setTplName("travel/searchTourList");
	}
	
	public function doTravelTag($request, $response) {
		$tourDao = new TourDao();
		$destinationType = Utilities::filterHtmlCode($request->getParameter("dtype"));
		$regionType = $request->getParameter("rtype") * 1;
		$regionName = Utilities::filterHtmlCode($request->getParameter("name"));
		$pn = $request->getParameter("page") * 1;
		
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		$tourHotList = $tourDao->getHotTour('上海', 0, '0,6');	//热门线路
		
		$hotelDao = new HotelDao();
		$lowerPriceHotelList = $hotelDao->lowerPriceHotelList('上海');	//超值线路
		$lowerPriceFlightsList = TicketDao::lowerPriceTicket('上海'); //超值机票
		
		if (in_array($destinationType, array('world', 'china'))) {
			$paramAlias["DestinationType"] = $paramater["destinationType"] = $destinationType;
		}
		$paramAlias["RegionType"] = $paramater["regionType"] = $regionType;
		$paramAlias["RegionName"] = $paramater["toCity"] = $regionName;
		$paramater["pn"] = $pn;
		$counter = $tourDao->getSearchList(true, $paramater);
		
		if ($counter) {
			$paramAlias["pn"] = '%d';
			$pageUrl = PathManager::getTourTag($paramAlias);
			$pagerStr = Utilities::getPageStr($paramater, $pageUrl, $counter);
			$searchList = $tourDao->getSearchList(false, $paramater);
		}
		$oPage = new PageDao();
		$couponList = $oPage->getPage('HOTMERCHANT_INCLUDE_INDEX');	//推荐优惠券
		
		//meta seo require 
		if ($destinationType == "world") {
			$metaTitle = "热门的境外旅游景点 - {$regionName}旅游线路报价";
			$metaDes = "最热门的{$regionName}出境旅游线路报价及价格服务 - {$searchList[0]['Name']} 。";
		}
		else {
			$metaTitle = "国内热门旅游景点 - {$regionName}旅游线路报价";
			$metaDes = "{$searchList[0]['Name']}， 最热门的{$regionName}国内旅游线路报价及价格服务。";
		}
		
		$response->setTplValue("metaTitle", $metaTitle);
		$response->setTplValue("metaDes", $metaDes);
		
		$response->setTplValue("couponList", $couponList);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("destinationCity", $regionName);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("searchList", $searchList);
		$response->setTplValue("pagerStr", $pagerStr);
		$response->setTplValue("regionName", $regionName);
		$response->setTplValue("tourHotList", $tourHotList);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplValue("searchType", 'line');
		$response->registerFun('cutString', 'modifier', array('Utilities','cutString'));
		$response->setTplName("travel/searchTagList");
	}
	
	public function doDetailAction($request, $response) {
		$tourDao = new TourDao();
		$tourId = $request->getParameter("tourid") * 1;
		$iconImage = array(
			'<img class="tourPlanTrafficIcon" alt="plain" src="/images/travel/plain.gif"/>',
			'<img class="tourPlanTrafficIcon" alt="bus" src="/images/travel/bus.gif"/>',
			'<img class="tourPlanTrafficIcon" alt="train" src="/images/travel/train.gif"/>',
			' '
		);
		$iconText = array('飞机', '汽车', '火车', '-');
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		$tourInfo = $tourDao->getTourInfo($tourId);
		$tourHotList = $tourDao->getHotTour($tourInfo['DepartRegionName'], 0, '0,6');	//热门线路
		
		$hotelDao = new HotelDao();
		$lowerPriceHotelList = $hotelDao->lowerPriceHotelList($tourInfo['DepartRegionName']);	//超值线路
		
		$lowerPriceFlightsList = TicketDao::lowerPriceTicket($tourInfo['DepartRegionName']); //超值机票
		
		if ($tourInfo["StartDate"]) {
			foreach (explode("##", $tourInfo["StartDate"]) as $startDate) {
				if (($pos = strrpos($startDate, "$")) !== false) {
					$startDateArr[] = date("m-d", strtotime(substr($startDate, $pos+1)));
				}
				else {
					$startDateArr[] = date("m-d", strtotime($startDate));
				}
			}
			array_pop($startDateArr);
			if (count($startDateArr) > 5) {
					$startDateLess = implode("、", array_slice($startDateArr, 0, 5));
			}
		}
		$longDistance = explode("||", str_replace($iconText, $iconImage, $tourInfo["LongDistance"]));
		
		$tourDes = explode("||", $tourInfo["Tour"]);
		$dineInfo = explode("||", str_replace("不含", "敬请自理", $tourInfo["DineInfo"]));
		$lives = explode("||", $tourInfo["Lives"]);
		foreach ((array)$dineInfo as $dineValue) {
			$dayDine[] = explode("%^&", $dineValue);
		}
		
		$sightsArray = explode("||", $tourInfo["PlaceSights"]);
		$placeSights = explode(",", trim(str_replace("||", ",", $tourInfo["PlaceSights"]), ","));
		$placeSights = $tourDao->getPlacesBySightID($placeSights);
		
		foreach ((array)$sightsArray as $k => $sights) {
			$daySightIDs = explode(",", trim($sights, ","));
			foreach ((array)$placeSights as $places) {
				if (in_array($places["PlaceID"], $daySightIDs)) {
					$daySights[$k][] = $places; 
				}
			}
		}
		
		$response->setTplValue("daySights", $daySights);
		$response->setTplValue("longDistance", $longDistance);
		$response->setTplValue("tourDes", $tourDes);
		$response->setTplValue("dineInfo", $dayDine);
		$response->setTplValue("lives", $lives);
		
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("startDateLess", $startDateLess);
		$response->setTplValue("startDateArr", implode("、", $startDateArr));
		$response->setTplValue("startDate", $startDate);
		$response->setTplValue("tourInfo", $tourInfo);
		$response->setTplValue("searchType", 'line');
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplValue("tourHotList", $tourHotList);
		$response->setTplName("travel/tourDetail");
	}
	
	public function getHotRegionAjax($request, $response) {
		header("Content-type: text/html; charset=gb2312");
		$this->setDisplayDisabled(true);
		$departCity = Utilities::filterHtmlCode($request->getParameter("departCity"));
		$departCity = iconv("UTF-8", "gbk//IGNORE", $departCity); 
		if (empty($departCity)) {
			$departCity = '上海';
		}
		$tourDao = new TourDao();
		$hotRegionList = $tourDao->getDestinationRegion($departCity);
		foreach ((array)$hotRegionList as $hotRegion) {
			$output .= "<a target='_blank' href='#'>{$hotRegion['RegionName']}</a>";
		}
		echo $output;
		return true;
	}
	
}
?>