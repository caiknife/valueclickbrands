<?php

/**
 * class.SearchAction.php
 *-------------------------
 *
 * The show the detail of search product
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive shopping engine
 * that helps consumers to make smarter buying decisions online. We empower consumers to compare
 * the attributes of over one million products in the common channels and common categories
 * and to read user product reviews in order to make informed purchase decisions. Consumers can then
 * research the latest promotional and pricing information on products listed at a wide selection of
 * online merchants, and read user reviews on those merchants.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2006, Mezimedia. All rights reserved.
 *
 * @author     cooc <cooc_luo@smater.com.cn>
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.SearchAction.php,v 1.1 2013/04/15 10:57:53 rock Exp $
 * @link       http://www.smarter.com.cn/
 * @deprecated File deprecated in Release 3.0.0
 */

class SearchAction extends BaseAction {

	/**
	 * 入力check
	 */
	protected function check($request, $response) {

		if ($request->getParameter("searchText") == NULL && $request->getParameter("keywords") == NULL &&
			$request->getParameter("q") == NULL) {
			throw new Exception("parameter error.");
		}

		//echo $request->isPost();
		$needjump = $request->getParameter("needjump");
		if($needjump=='true'){
			$keyword = $this->getKeyword($request);
			$url = PathManager :: getSearchUrl($keyword, $chid, $catid, $tmpParam);
			redirect301($url);
		}
	}

	/**
	 * 分支
	 */
	protected function service($request, $response) {
		$this->doSearchAction($request, $response);
	}

	/**
	 * 初始化
	 */


	protected function doSearchAction($request, $response) {

		$type = $request->getParameter("ci");
		$searchTextOri = $request->getParameter("searchText");
		$searchQ = $request->getParameter("q");
		$getprodcntFlag = $request->getParameter("getprodcnt");
//echo $searchTextOri;
		$searchText = trim(Utilities::decode($searchTextOri)); //解码;
		if (empty($searchText) && $searchQ) {
			$searchText = trim(Utilities::decode($searchQ)); //解码;
		}
//echo $searchText;
		if (BlockAdSenceDao::isBlockKeywords($searchText) == true) {
			throw new Exception("stop keyword({$searchText}).");
		}
		$searchCostTime = Utilities::getMicrotime();
		$oSearch = new SearchDao($searchText,$type);
		$oSearch->writeKeyLog();
		$searchlist = $oSearch->search_();
		$searchCostTime = Utilities::getMicrotime()-$searchCostTime;

		$sourceFlag = strtoupper(Tracking_Session::getInstance()->getSourceGroup());

		/* 
		$styleFlag = "NEW"; //for new style [NEW, OLD]
		if(Tracking_Session::getInstance()->getUiType() <= 16) { //80% (TplTypeNum in [1-20])
			$styleFlag = "OLD"; //for old style
		}
		*/
		$styleFlag = "OLD";//保留UI A， 全流量 去除UI B
// 		if(Tracking_Session::getInstance()->getUiType() > 10) { //50% (TplTypeNum in [1-20])
// 			$styleFlag = "NEW"; //for old style
// 		}
//		if($sourceFlag=='YAHOO'){
//			$asknum = 20;
//			//在yahoo流量中进行tpl测试
//			//tpl type 在[1-10]之间用新的没有头的页面
//			if($styleFlag == "NEW"){
//				$tplname = "new/search_allbaidu_yahoo";
//			}else{
//				$tplname = "new/search_allbaidu_yahoo_old";
//			}
//		}elseif($sourceFlag=='BAIDU' || $sourceFlag=='GOOGLE'){
//			$asknum = 20;
//			$tplname = "new/search_allbaidu_baidu";
//		}else{
//			$tplname = "new/search_allbaidu";
//			$asknum = 10;
//		}

    $params = $request->getAttribute("params");
		$startTime = Utilities::getMicrotime();
//	$baidu = new BaiduAdsDao();
//  stop baidu ads
//	$askBaidu = true;
//  $adsNum = 5;
		if (!empty($sourceFlag)) {
			if (strlen($sourceFlag) >= 4 && (substr($sourceFlag, 0, 5) == "SOGOU" 
				|| substr($sourceFlag, 0, 4) == "SOSO") && !$type) {
				$tplname = "search/Result_Source_SOGOU";
                /*
				$splitCountArr = array(3, 7);
                $baiduParams = array('splitCountArr' => array(-3, -7));
                /* sogou UI B:（50% sogou SEM traffic）
                if(substr($sourceFlag, 0, 5) == "SOGOU" && $styleFlag == "NEW")
                {
                	$tplname = "search/Result_Source_SOGOU_New";
                }
                */
				//$splitCountArr = array(6, 6);
                //$baiduParams = array('splitCountArr' => array(-6, -6));
//                 if($styleFlag == "OLD")
//                 {
                    $splitCountArr = array(6, 6);
                    $baiduParams = array('splitCountArr' => array(-6, -6));
//                 }else{
//                     $splitCountArr = array(3);
//                     $baiduParams = array('splitCountArr' => array(12));
//                     $tplname = "search/Result_Source_YODAO_New";
//                 }
			}
			else if ($sourceFlag == "BAIDU" && !$type) {
// 				$tplname = "search/Result_Source_SOGOU";
				//$splitCountArr = array(6, 6);
            	//$baiduParams = array('splitCountArr' => array(-6, -6));
//                 if($styleFlag == "OLD")
//                 {
//                     $splitCountArr = array(6, 6);
//                     $baiduParams = array('splitCountArr' => array(-6, -6));
//                 }else{
                    $splitCountArr = array(3);
                    $baiduParams = array('splitCountArr' => array(12));
                    $tplname = "search/Result_Source_YODAO_New";
                    // sem 取消百度广告
                    $splitCountArr = array(6, 6);
                    $tplname = "search/Result_Source_SOGOU";
//                 }
			}
			else if ($sourceFlag == "YODAO" && !$type) {
				$splitCountArr = array();
		        $baiduParams = array('splitCountArr' => array(9));//9条baidu sls  上3  下6
		        $adsParams = array('splitCountArr' => $splitCountArr, 'keyword' => $searchText);

		        $requestGoogle = array(
		        	'sourceFlag' => $sourceFlag,
		        	'adsParams'  => array('splitCountArr' => array(3), 'keyword' => $searchText)
		        );
		        $requestGoogle = Utilities::encode(serialize($requestGoogle));//3条google sls  鼠标有事件触发
				$response->setTplValue("requestGoogle", '../async_spr.php?params='.$requestGoogle); //页面赋值
				$tplname = "search/Result_Source_YODAO";
			}
			else {
                //当搜索结果为0的时候且source=google的时候，请求热门优惠卷来补
                if (count($searchlist) <= 3 && $sourceFlag == "GOOGLE") {
                    $pageDao = new PageDao();
                    $hotCouponList = $pageDao->getHotCoupon();
                    foreach ($hotCouponList as $hotKey => $hotCouponInfo) {
                        //get coupon image url
                        if ($hotCouponInfo['ImageDownload']){
                            $hotCouponList[$hotKey]['ImageURL'] = 
                            __IMAGE_SRC.Utilities::getSmallImageURL($hotCouponInfo['Coupon_']);
                        } else{
                            $hotCouponList[$hotKey]['ImageURL'] = "/images/dahongbao.gif";
                        }
                        //get click url
                        if ($hotCouponInfo['NameURL']){
                            $hotCouponList[$hotKey]['LinkURL'] = "/".$hotCouponInfo['NameURL']."/coupon-".$hotCouponInfo['Coupon_']."/";
                        } else {
                            $hotCouponList[$hotKey]['LinkURL'] = "/merchant/coupon-".$hotCouponInfo['Coupon_']."/";
                        }
                        if (empty($hotCouponInfo['ExpireDate']) || $hotCouponInfo['ExpireDate']=='0000-00-00') {
                            $hotCouponList[$hotKey]['ExpireDate'] = "永久有效";
                        }
                    }
                    $response->setTplValue("hotCouponList", $hotCouponList);
                }
                
                if ($sourceFlag == "GOOGLE") {
                    $tplname = 'search/Result_Source_GOOGLE';
                    $splitCountArr = array(4, 6);
                    $baiduParams = array('splitCountArr' => array(-4, -6));
                } else {
                    $tplname = 'search/Result_Source';
                    $splitCountArr = array(6, 6);
                    $baiduParams = array('splitCountArr' => array(-6, -6));
                }
			}
//			$adsNum = 12;
//          $splitCountArr = array(6, 6);
//          $baiduParams = array('splitCountArr' => array(-6, -6));
		}
		else {
			$tplname = "search/Result";
//			$adsNum = 10;
            $splitCountArr = array(3, 7);
            $baiduParams = array('splitCountArr' => array(-3, -7));
		}
//    $params["channelTag"] = "Search";
//    $adsWords = new AdWordsDao($searchText, $adsNum);
//    $adsResult = $adsWords -> dispatch($params);

	/*
	 * SOGOU google baidu上下各3条，不够不需填充
	 */
	/*
	if (!empty($sourceFlag) && substr($sourceFlag, 0, 5) == "SOGOU" && $styleFlag == "NEW" && !$type) {
		$splitCountArr = array(3, 3);
		$baiduParams = array('splitCountArr' => $splitCountArr);
	}
	*/
	// sem 取消百度广告
	$baiduParams = null;
	$adsParams = array('splitCountArr' => $splitCountArr, 'keyword' => $searchText);
	$adsResult = AdWordsDao::getAdsScript($adsParams, $baiduParams);
//    $askBaidu = $baidu->getBaiduAds ($searchText,$sourceFlag);
//		if($askBaidu == false){ //超时

//		}else{
//			//$adsbd = $baidu->parseBaiduAds();
//		}



		$costTime = Utilities::getMicrotime()-$startTime;

		if($getprodcntFlag == "yes") {  //测试广告请求
			$response->setTplValue("getprodcntFlag", "yes");
		}else{
			//tracking
			//$baidu->registerSponsorTransfer($searchText,$costTime, $adsbd->num,"","","Search");
		}


    $blockviewads = false;
//		if(defined("__TEST_ALLOW_KEYWORD") && (__TEST_ALLOW_KEYWORD == true)){
//			$todayformat = date("Ymd",time());
//			$filename = __ROOT_PATH."etc/allow_keyword_".$todayformat.".txt";
//			if(file_exists($filename)){
//				$testkeyword = file_get_contents($filename);
//				if($testkeyword){
//					$testkeywordarray = explode(",",$testkeyword);
//					if(in_array($searchText,$testkeywordarray)){
//						$blockviewads = false;
//					}else{
//						$blockviewads = true;
//					}
//				}
//			}
//		}

		if(!$blockviewads) {
			$response->setTplValue("adsResult", $adsResult);
		}

		/////////////////

		$page = $request->getParameter("p");
		if(empty($page)) $page = 1;

		$newsearchlist = array();
		$k = 0;
		$start = ($page-1)*10;
		for($i=$start;$i<($start+10) && $i<count($searchlist);$i++){
			$newsearchlist[$k] = $searchlist[$i];
			$k = $k+1;
		}

		//分别获得优惠券，折扣信息，论坛详细结果
		foreach($newsearchlist as $key=>$value){
			if($value['Type']==1){
				$r1[] = $value;
			} else if($value['Type']==2){
				$r2[] = $value;
			} else if($value['Type']==3) {
				$r3[] = $value;
			} else if($value['Type'] == 99) {
				$r99[] = $value;
			} else if($value['Type'] > 100) {
				${"r".$value['Type']}[] = $value;
			}
		}
		if($r1) $r1 = $oSearch->getCouponDetailList($r1);
		if($r2) $r2 = $oSearch->getCouponDetailList($r2);
		if($r3) $r3 = $oSearch->getInfoDetailList($r3);
		if($r99) $r99 = $oSearch->getTourDetailList($r99);
		if($r113) $r113 = $oSearch->getSmarterDetailList($r113, 13);
		if($r114) $r114 = $oSearch->getSmarterDetailList($r114, 14);


		//拼装详细搜索结果
		if(!empty($newsearchlist)){
			foreach($newsearchlist as $key=>$value){
				$k = $value['NewsId'];
				$newsearchlist[$key]['Type'] = $value['Type'];
				//优惠券拼装
				if($value['Type']==1){
					$value = $r1[$k];
					$newsearchlist[$key]['oriTitle'] = $value['Descript'];
					$newsearchlist[$key]['Title'] = str_replace($searchText,"<strong>".$searchText."</strong>",$value['Descript']);
					$value['Detail'] = strip_tags($value['Detail']);
					$value['Detail'] = Utilities::cutString($value['Detail'],82);
					$newsearchlist[$key]['Detail'] = str_replace($searchText,"<strong>".$searchText."</strong>",$value['Detail']);
					$newsearchlist[$key]['ImageDownload'] = $value['ImageDownload'];

					if($value['NameURL']){
						if($value['name1']){
							$newsearchlist[$key]['MerchantName'] = str_replace($searchText,"<strong>".$searchText."</strong>",$value['name1']);
						}else{
							$newsearchlist[$key]['MerchantName'] = str_replace($searchText,"<strong>".$searchText."</strong>",$value['name']);
						}
						$newsearchlist[$key]['MerchantURL'] = "/Me-".$value['NameURL']."--Mi-".$value['Merchant_'].".html";

						$newsearchlist[$key]['MerchantName'] = "【<a href='".$newsearchlist[$key]['MerchantURL']."' target=_blank>".$newsearchlist[$key]['MerchantName']."</a>】";
					}
					if($value['ImageDownload']){
						$newsearchlist[$key]['ImageURL'] = __IMAGE_SRC.Utilities::getSmallImageURL($value['Coupon_']);
					}else{
						$newsearchlist[$key]['ImageURL'] = "/images/dahongbao.gif";
					}
					$newsearchlist[$key]['tagname'] = Utilities::getTagSrcForSearch($value['tagname'],$searchText);

					if($value['NameURL']){
						$newsearchlist[$key]['LinkURL'] = "/".$value['NameURL']."/coupon-".$k."/";
					}else{
						$newsearchlist[$key]['LinkURL'] = "/merchant/coupon-".$k."/";
					}
					if($value['ExpireDate']=='0000-00-00') $value['ExpireDate'] = "永久有效";
					$newsearchlist[$key]['ExpireDate'] = $value['ExpireDate'];
					$newsearchlist[$key]['digest'] = $value['digest'];
					$newsearchlist[$key]['replies'] = $value['replies'];
				}
				//折扣信息拼装
				if($value['Type']==2){

					$value = $r2[$k];
					$newsearchlist[$key]['oriTitle'] = $value['Descript'];
					$newsearchlist[$key]['Title'] = str_replace($searchText,"<strong>".$searchText."</strong>",$value['Descript']);

					$newsearchlist[$key]['ImageDownload'] = $value['ImageDownload'];
					if($value['ImageDownload']){
						$newsearchlist[$key]['ImageURL'] = __IMAGE_SRC.Utilities::getSmallImageURL($value['Coupon_']);
					}else{
						$newsearchlist[$key]['ImageURL'] = "/images/dahongbao.gif";
					}
					$newsearchlist[$key]['tagname'] = Utilities::getTagSrcForSearch($value['tagname'],$searchText);

					$value['Detail'] = strip_tags($value['Detail']);
					$newsearchlist[$key]['Detail'] = Utilities::cutString($value['Detail'],182);
					$newsearchlist[$key]['Detail'] = str_replace($searchText,"<strong>".$searchText."</strong>",$newsearchlist[$key]['Detail']);

					if($value['ExpireDate']=='0000-00-00') $value['ExpireDate'] = "永久有效";
					$newsearchlist[$key]['ExpireDate'] = $value['ExpireDate'];

					$newsearchlist[$key]['LinkURL'] = "/discount_detail-".$k.".html";

					$newsearchlist[$key]['digest'] = $value['digest'];
					$newsearchlist[$key]['replies'] = $value['replies'];
				}
				//论坛拼装
				if($value['Type']==3){
					$value = $r3[$k];
					$newsearchlist[$key]['Title'] = str_replace($searchText,"<strong>".$searchText."</strong>",$value['subject']);
					$newsearchlist[$key]['postdate'] = Utilities::get_date($value['postdate']);
					$newsearchlist[$key]['Detail'] = preg_replace("/\[(.*)\]/","",$value['content']);
					$newsearchlist[$key]['Detail'] = preg_replace("/\n/","",$newsearchlist[$key]['Detail']);
					$newsearchlist[$key]['Detail'] = str_replace($searchText,"<strong>".$searchText."</strong>",$newsearchlist[$key]['Detail']);
					$newsearchlist[$key]['author'] = $value['author'];
					$newsearchlist[$key]['hits'] = $value['hits'];
					$newsearchlist[$key]['tid'] = $value['tid'];
					$newsearchlist[$key]['authorid'] = $value['authorid'];
				}
				//旅游频道
				//类Smarter频道
				if ($value['Type'] == 99 || $value['Type'] == 114) {
					$newsearchlist[$key] = $value['Type'] == 99 ? $r99[$k] : $r114[$k];
					$newsearchlist[$key]['Type'] = 99;
				}
				if($value['Type'] == 113) {
					$newsearchlist[$key] = $r113[$k];
					$newsearchlist[$key]['Type'] = $value['Type'];
				}
			}
		}

		$searchlistcount = $oSearch->doSearchCoupon();
		$searchlistcount[99] = $searchlistcount[99] + $searchlistcount[114];
		$mainlistcount = $searchlistcount[1]+$searchlistcount[2]+$searchlistcount[3]+$searchlistcount[113]+$searchlistcount[99];
//		if($mainlistcount){
//			$kijijilimittime = 2;
//		}else{
//			$kijijilimittime = 4;
//		}



		$nowkjjcityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];	//if city id is empty ..default 21


//		$baixingresult = BaixingDao::getBaixingSearch($nowkjjcityid,$searchText);
//
//		$kijijicount = count($baixingresult);
//
//		$kijijiresult = array();
//		foreach($baixingresult as $row){
//			$row['Type'] = 4;
//			$kijijiresult[] = $row;
//		}
//
//		$searchlistcount[4] = $kijijicount;
		$response->setTplValue("searchlistcount", $searchlistcount);

		$countall = $mainlistcount+$searchlistcount[4];

		$response->setTplValue('countall',$countall);

		//折扣信息翻页
		if($type==2){
			$pageall = ceil($searchlistcount[2]/10);
		}elseif($type==3){
			$pageall = ceil($searchlistcount[3]/10);
		}elseif($type==1){
			$pageall = ceil($searchlistcount[1]/10);
		}elseif($type == 113){
			$pageall = ceil($searchlistcount[113]/10);
		}elseif ($type == 99) {
			$pageall = ceil(($searchlistcount[99])/10);
		}
		else{
//			总页数在1-10之间才有可能出现kijiji
//			if($page<11){
//				$remain = floor(($mainlistcount)/10);
//				当前页存在主体内容
//				if($page<=$remain+1){
//					$k = $page*2-2;
//
//				}elseif($page==$remain+2 || $page==$remain+3){
//					$k = $searchlistcount[4]-($countall-12*($page-1));
//				}
//				$start = count($newsearchlist);
//				for($i=$start;$i<12;$i++){
//					$newsearchlist[] = $kijijiresult[$k];
//					$k++;
//				}
//			}

//			if($countall>120){
//				$pageall = ceil(($countall-120)/10)+10;
//			}else{
//				$pageall = ceil($countall/12);
//			}
			$pageall = ceil($countall/10);
		}


		if($getprodcntFlag == "yes") {  //测试广告请求
			$response->setTplValue("ProductCnt", $countall>12?12:$countall);
			$adsCnt = 0;
			foreach ($adsResult as $type) {
			    foreach ($type as $item) {
			        $adsCnt += count($item);
			    }
			}
			$response->setTplValue("AdsCnt", $adsCnt);
		}

    	$source = Tracking_Session::getInstance()->getSource();
    	$semKeyword = Tracking_Session::getInstance()->getKeyword();
        $isRealSearch  = (!empty($source) && ($semKeyword == $searchText)) ? 'NO' : 'YES';
        Tracking_Logger::getInstance()->search(array(
            'resultCount'       => $countall,
            'keyword'           => $searchText,
            "source"            => $source,
            'costTime'          => $searchCostTime,
            'isRealSearch'      => $isRealSearch,
            'isCached'          => 'NO',
            'SearchEngineType'  => 1,
        ));

		$pageString = $oSearch->getNewPageStr($page,$pageall,$type,Utilities::encode($searchText));

		$response->setTplValue("pageString", $pageString);


		$response->setTplValue("topShow", $topShow);

		$response->setTplValue("newsearchlist", $newsearchlist);
		$response->setTplValue("searchText", htmlspecialchars($searchText));
		$response->setTplValue("searchTextFormat", Utilities::encode($searchText));

		$response->setTplValue("searchTextRe", htmlspecialchars(Utilities::cutString($searchText,12)));

		$cityname = CityDao::getNowCityName($nowkjjcityid);  //get city name by city id
		$response->setTplValue('nowcityname',$cityname);
		$citylist = CityDao::getCityList();   //city list for city select
		$response->setTplValue('citylist',$citylist);


		$hottag = PageDao::getPage('hottag');
		$response->setTplValue('hottag',$hottag);

		$response->setTplValue('__ADHOST',__ADHOST);


//		$winduser = P_GetCookie("winduser");
		$url = empty($_GET['url'])?($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']):$_GET['url'];
		$response->setTplValue('url',$url);

//		if(empty($winduser)){
//			$response->setTplValue('islogon',0);
//		}else{
//			//echo $winduser;
//			$user = explode("\t",$winduser);
//			$userinfo = MemberDao::getuserinfo($user[0]);
//			//print_r($userinfo);
//			$response->setTplValue("userinfo",$userinfo[0]);
//			$response->setTplValue('islogon',1);
//		}

		$response->setTplValue("title", $metaTitle);

		$response->setTplName($tplname);

	}

	private function getKeyword($request) {
		$keyword = trim($request->getParameter("searchText"));
		if ($keyword == "") {
			throw new Exception("Keyword is empty.");
		}
		return $keyword;
	}

}
?>