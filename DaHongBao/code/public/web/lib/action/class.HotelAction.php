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
 * @version    CVS: $Id: class.HotelAction.php,v 1.1 2013/04/15 10:57:53 rock Exp $
 * @link       http://www.smarter.com/
 */

class HotelAction extends BaseAction {
	
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
				$this->doHotelSearch($request, $response);
				break;
			default :
				$this->doHotelAction($request, $response);
				brak;
		}
	}
	
	function doHotelAction($request, $response) {
		$tourDao = new TourDao();
		$tourHotList = $tourDao->getHotTour('上海');	//热门线路
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		
		$oPage = new PageDao();
		$couponList = $oPage->getPage('HOTMERCHANT_INCLUDE_INDEX');	//推荐优惠券
		$hotelDao = new HotelDao();
		require_once(__ROOT_PATH."etc/travel_config.php");
		$hotCity = array_slice($_HOTEL_CITY, 0, 4);
		foreach ($hotCity as $city) {
			$priorityList[$city] = $hotelDao->getHotCityHotel($city);	//热门城市列表
		}
		$hotCityHotel = $hotelDao->getPriorityHotel();	//推荐酒店
		$lowerPriceFlightsList = TicketDao::lowerPriceTicket(); //超值机票
		
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplValue("hotCityHotel", $hotCityHotel);
		$response->setTplValue("priorityList", $priorityList);
		$response->setTplValue("couponList", $couponList);
		$response->setTplValue("tourHotList", $tourHotList);
		$response->setTplValue("searchType", 'hotel');
		$response->registerFun('cutString', 'modifier', array('Utilities','cutString'));
		$response->setTplName("travel/hotel");
	}

	public function doHotelSearch($request, $response) {
		$hotelCity = Utilities::filterHtmlCode($request->getParameter("hotelCity"));
		$hotelStar = Utilities::filterHtmlCode($request->getParameter("hotelStar"));
		$hotelPrice = Utilities::filterHtmlCode($request->getParameter("hotelPrice"));
		$hotelName = Utilities::filterHtmlCode($request->getParameter("hotelName"));
		$pn = $request->getParameter("page") * 1;
		try {
			$hotelDao = new HotelDao();
			if (empty($hotelCity)) {
				throw new Exception("入住酒店城市不能为空.");
			}
			$paramater["hotelCity"] = $hotelCity;
			$paramater["hotelStar"] = $hotelStar;
			$paramater["hotelName"] = $hotelName;
			$paramater["hotelPrice"] = $hotelPrice;
			$paramater["pn"] = $pn;
			$counter = $hotelDao->getSearchList(true, $paramater);
			if ($counter) {
				$pageUrl = "/travel_search.php?hotelCity={$hotelCity}&hotelStar={$hotelStar}&".
					"hotelName={$hotelName}&hotelPrice={$hotelPrice}&hotel=hotel&page=%d";
				$pageStr = Utilities::getPageStr($paramater, $pageUrl, $counter);
				$searchList = $hotelDao->getSearchList(false, $paramater);
			}
			$response->setTplValue("searchList", $searchList);
		}
		catch (Exception $e) {
			$errorMessage = $e->getMessage();
			$response->setTplValue("error", $errorMessage);
		}
		$oPage = new PageDao();
		$couponList = $oPage->getPage('HOTMERCHANT_INCLUDE_INDEX');	//推荐优惠券
		
		$tourDao = new TourDao();
		$tourHotList = $tourDao->getHotTour($hotelCity);	//热门线路
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		$lowerPriceHotelList = $hotelDao->lowerPriceHotelList($hotelCity);	//超值线路
		$lowerPriceFlightsList = TicketDao::lowerPriceTicket($hotelCity); //超值机票
		
		$response->setTplValue("couponList", $couponList);
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("pageStr", $pageStr);
		$response->setTplValue("hotelCity", $hotelCity);
		$response->setTplValue("hotelStar", $hotelStar);
		$response->setTplValue("hotelName", $hotelName);
		$response->setTplValue("hotelPrice", $hotelPrice);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		$response->setTplValue("searchType", 'hotel');
		$response->setTplValue("tourHotList", $tourHotList);
		$response->registerFun('cutString', 'modifier', array('Utilities','cutString'));
		$response->setTplName("travel/searchHotel");
	}
	
	public function doDetailAction($request, $response) {
		$hotelID = $request->getParameter("hotelid") * 1;
		$hotelDao = new HotelDao();
		$hotelInfo = $hotelDao->getHotelInfo($hotelID);
		if (strlen($hotelInfo["Description"]) > 350) {
			$hotelInfo["moreDes"] = $hotelInfo["Description"];
			$hotelInfo["lessDes"] = Utilities::cutString($hotelInfo["Description"], 350);
		}
		else {
			$hotelInfo["lessDes"] = $hotelInfo["Description"];
		}
		$tourDao = new TourDao();
		$departRegion = $tourDao->getDepartRegion();	//出发城市
		$tourHotList = $tourDao->getHotTour($hotelInfo['City'], 0, '0,6');	//热门线路
		
		if ($hotelMerchantInfo = $hotelDao->getHotelMerchant($hotelID)) {
			foreach ($hotelMerchantInfo as $hotelMerchantArr) {
				$hotelStyle = explode("\n", $hotelMerchantArr["Spec"]);
				$tmpRoom = array();
				foreach ($hotelStyle as $hotelStyleString) {
					list($tmpHotel["styleRoom"], $tmpHotel["offerURL"], $tmpHotel["price"]) = explode("|", $hotelStyleString);
					$tmpHotel['price'] = Utilities::formatPrice($tmpHotel['price']);
					$tmpHotel["offerURL"] = $tmpHotel["offerURL"]?PathManager::getTravelOfferUrl($tmpHotel["offerURL"], "hotel"):"";
					if ($tmpHotel["styleRoom"] && !in_array($tmpHotel["styleRoom"], $tmpRoom)) {	//一个酒店只有一种房型
						$tmpHotel["merchantName"] = $hotelMerchantArr["MerchantName"];
						$hotelStyleArr[$tmpHotel["styleRoom"]][] = $tmpHotel;
						$tmpRoom[] = $tmpHotel["styleRoom"];
					}
				}
			}
		}
		$lowerPriceHotelList = $hotelDao->lowerPriceHotelList($hotelInfo['City']);	//超值线路
		$lowerPriceFlightsList = TicketDao::lowerPriceTicket($hotelInfo['City']); //超值机票
		
		$response->setTplValue("lowerPriceFlightsList", $lowerPriceFlightsList);
		$response->setTplValue("hotelsStyle", $hotelStyleArr);
		$response->setTplValue("lowerPriceHotelList", $lowerPriceHotelList);
		$response->setTplValue("searchType", 'hotel');
		$response->setTplValue("hotelInfo", $hotelInfo);
		$response->setTplValue("tourHotList", $tourHotList);
		$response->setTplValue("departRegion", Utilities::convertSimpleArray($departRegion, 'DepartRegionName'));
		
		$response->setTplName("travel/hotelDetail");
	}
}
?>