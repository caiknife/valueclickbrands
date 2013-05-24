<?php
/*
 * Created on 2009-12-17
 * class.TicketAction.php
 * -------------------------
 * display travel flights 
 * 
 * 
 * @author Thomas FU
 * @email thomas_fu@mezimedia.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.TicketAction.php,v 1.1 2013/04/15 10:57:53 rock Exp $
 * @link       http://www.smarter.com/
 */

class TicketAction extends BaseAction {

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
			case "search":
				$this->doSearchAction($request, $response);
				break;
			case "ticketSearchResult":
				$this->doSearchResult($request, $response);
				break;
			default :
				$this->doTicketAction($request, $response);
				break;
		}
	}
	
	public function doTicketAction($request, $response) {
		$tourDao = new TourDao();
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		$tourHotList = $tourDao->getHotTour('上海', 0, '0,6');	//热门线路
		
		$hotelDao = new HotelDao();
		$lowerPriceHotelList = $hotelDao->lowerPriceHotelList();	//超值酒店
		
		
		require_once(__ROOT_PATH."etc/travel_config.php");
		$hotCity = array_slice($_FLIGHTS_CITY, 0, 4);
		foreach ($hotCity as $city) {
			$lowerPriceTicketList[$city] = TicketDao::lowerPriceTicket($city);	//特价机票
		}
		$oPage = new PageDao();
		$couponList = $oPage->getPage('HOTMERCHANT_INCLUDE_INDEX');	//推荐优惠券
		
		$response->setTplValue("couponList", $couponList);
		$response->setTplValue("lowerPriceTicketList", $lowerPriceTicketList);
		$response->setTplValue("tourHotList", $tourHotList);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplValue("searchType", 'flight');
		$response->setTplName("travel/ticket");
	}
	
	public function doSearchAction($request, $response) {
		$tourDao = new TourDao();
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		
		
		$fromCity = Utilities::filterHtmlCode($request->getParameter("fromCity"));
		$toCity = Utilities::filterHtmlCode($request->getParameter("toCity"));
		$flightStartTime = Utilities::filterHtmlCode($request->getParameter("FlightStartTime"));
		
		try {
			if (empty($fromCity) || empty($toCity)) {
				throw new Exception("出发城市或者目的地城市不能为空");
			}
			if (empty($flightStartTime)) {
				$flightStartTime = getDateTime("Y-m-d", time()+3600*72);
			}
			
			$tourHotList = $tourDao->getHotTour($fromCity, 0, '0,6');	//热门线路
			$hotelDao = new HotelDao();
			$lowerPriceHotelList = $hotelDao->lowerPriceHotelList($fromCity);	//超值酒店
			$lowerPriceFlightsList = TicketDao::lowerPriceTicket($fromCity); //超值机票
		}
		catch (Exception $e) {
			$errorMessage = $e->getMessage();
			$response->setTplValue("error", $errorMessage);
		}
		
		$response->setTplValue("fromCity", $fromCity);
		$response->setTplValue("toCity", $toCity);
		$response->setTplValue("flightStartTime", $flightStartTime);
		
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("tourHotList", $tourHotList);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplValue("searchType", 'flight');
		$response->setTplName("travel/searchTicket");
	}
	
	public function doSearchResult($request, $response) {
		//header("Content-type: text/html; charset=gbk");
		$this->setDisplayDisabled(true);
		$fromCity = Utilities::filterHtmlCode($request->getParameter("fromCity"));
		$toCity = Utilities::filterHtmlCode($request->getParameter("toCity"));
		$flightStartTime = Utilities::filterHtmlCode($request->getParameter("flightStartTime"));
		if (empty($flightStartTime)) $flightStartTime = getDateTime("Y-m-d");
		
		set_time_limit(60 * 60);
		ob_end_clean();
		//返回key
		$searchResultKey = TicketDao::searchStartup($fromCity, $toCity, $flightStartTime);
		$keys = $searchResultKey["resultKeys"];
		if ($keys) {
			while($keys) {
				$searchResult = TicketDao::searchTicket($keys);
				if ($searchResult["result"]) {
					foreach ($searchResult["result"] as $key => $result) {
						$result['clickURL'] = PathManager::getTravelOfferUrl($result["tickets"][0]['clickURL'], 'ticket');
						echo "<script>parent.searchTicketResult(".json_encode($result)."); </script>";
						flush();
					}
				}
				if (!$searchResult["nextCount"]) break;
				//得到下一个key
				if ($searchResult["nextParams"]["status"] == "OK") {
					$keys = $searchResult["nextParams"]["resultKeys"];
				}
			}
		}
		else {
			echo "<script>parent.searchTicketResult({}); </script>";
			flush();
		}
		return true;
	}
}
?>