<?php

	require_once(__INCLUDE_ROOT."lib/classes/class.WordSplitter.php");
	
	class SearchDao
	{//{{{
		
		var $ClassName = "Search";
		var $SearchExpr = "";
		var $CM2Count = 0;
		var $SearchList = array();
		var $SE_FIELD_WEIGHT = array("Title" => "40","Descript" => "16");
		var $KeywordsSql = "";
		var $SearchListCount = 0;
		var $SearchType = "";
		 
		function __construct($sExpr,$type){
			$this->SearchExpr = $sExpr;
			$this->SearchType = $type;
		}
		
		function search_(){
			if ( $this->SearchExpr != " " && !empty($this->SearchExpr) ){
				//$this->prepare_search();
				$this->cleanKeywords();
				$this->doSearch();
			}
			return $this->SearchList;
		}
		
		function searchForSTX($p, $perPage){
			if ( $this->SearchExpr != " " && !empty($this->SearchExpr) ){
				//$this->prepare_search();
				$this->cleanKeywords();
				$this->doSearchForSTX($p, $perPage);
			}
			return $this->SearchList;
		}
		
		function cleanKeywords() {
		
			$oSplitter = new WordSplitter($this->SearchExpr);
			$this->KeywordsSql = DBQuery::filter($oSplitter->execute("+(",")"));
			return $this->KeywordsSql;
		}
		
		function doSearch(){

			require_once "Cache/Lite.php";

			$cityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];
			$cacheid = $this->KeywordsSql.$cityid.$this->SearchType;

			$cachedir = __FILE_FULLPATH."cache/search/{$cityid}/".substr(md5($cacheid), 0, 2)."/";
			$cache = new Cache_Lite(array('cacheDir' => $cachedir,'lifeTime' => 60*60,'pearErrorMode' => CACHE_LITE_ERROR_DIE));

			if ($cache->get($cacheid)) {
				$r = $cache->get($cacheid);
				$result = unserialize($r);//use cache
			} else { 
				$sql = "SELECT p.CityId,p.NewsId,p.Type,(50*(MATCH(p.Title) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+5*(MATCH(p.Descript) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+50*(MATCH(p.Tag) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+30*(MATCH(p.Category) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+p.Rank+0) AS Relevance FROM ".__DB_SE.".News_SE p WHERE MATCH(p.Title,p.Descript,p.Tag,p.Category) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "' IN BOOLEAN MODE) ";
				if($this->SearchType == 99){
					$sql .= "AND (p.Type= 99 OR p.Type= 114)";
				}
				else if ($this->SearchType){
					$sql .= "AND p.Type=".$this->SearchType." ";
				}
				$sql .= "ORDER BY Relevance DESC limit 50000";
				$result = DBQuery::instance(__SE)->executeQuery($sql);
				
				$array1 = $array2 = array();
				foreach($result as $key=>$value){
					if($cityid == $value['CityId'] || $value['CityId'] == 0){
						$array1[] = $value;
					}else{
						$array2[] = $value;
					}
				}
				$result = array_merge($array1,$array2);
				//如果有值，缓存此结果
                if ($result) { 
				    if(!file_exists($cachedir)) mkdir($cachedir, 0777, true);
                    $cache->save(serialize($result),$cacheid);
				}
			}
			$this->SearchList = $result;
		}

		function doSearchCoupon(){
			$sql = "SELECT count(*) count,p.Type FROM dahongbao_se.News_SE p ";
			$sql .= "WHERE MATCH(p.Title,p.Descript,p.Tag,p.Category) AGAINST('" .DBQuery::filter($this->KeywordsSql). "' IN BOOLEAN MODE) Group BY p.Type DESC";
			$re = DBQuery::instance(__SE)->executeQuery($sql);
			$result = array();
			foreach($re as $key=>$value){
				$k = $value['Type'];
				$result[$k] = $value['count'];
			}
			return $result;
		}


		function getCouponDetailList($idarray,$page=1,$perPage=10){
			$idstring ="";
			foreach($idarray as $key=>$value){
				$idstring .= $value['NewsId'].",";
			}
			$idstring = substr($idstring,0,-1);
			$start = ($page-1) * $perPage;
			$sql = "SELECT m.NameURL,c.ImageDownload,c.Coupon_,GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.Descript,c.Detail,p.hits,p.digest,p.replies,c.AddDate,c.ExpireDate,m.Name name,m.name1 name1,m.Merchant_ Merchant_ FROM Coupon c ";
			$sql .= "LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) INNER JOIN pw_threads p ON (p.dhbid = c.Coupon_) LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) WHERE c.Coupon_ IN (".$idstring.") AND c.IsActive=1 GROUP BY c.Coupon_ ORDER BY FIND_IN_SET(c.Coupon_,'".$idstring."') LIMIT $start,$perPage";
			//echo $sql;
			$re = DBQuery::instance()->executeQuery($sql);


			$newre = array();
			foreach($re as $key=>$value){
				$k = $value['Coupon_'];
				$newre[$k] = $value;
			}
			return $newre;
		}

		function getInfoDetailList($idarray,$page=1,$perPage=10){
			$idstring ="";
			foreach($idarray as $key=>$value){
				$idstring .= $value['NewsId'].",";
			}
			$idstring = substr($idstring,0,-1);
			$start = ($page-1) * $perPage;
			$sql = "SELECT p.fid,p.tid,p.subject,p.author,p.authorid,TRIM(SUBSTRING(pt.content,1,400)) AS content,p.hits,p.postdate ";
			$sql .= "FROM pw_threads p INNER JOIN pw_tmsgs pt ON (pt.tid = p.tid) WHERE p.tid IN (".$idstring.") ORDER BY FIND_IN_SET(p.tid,'".$idstring."') LIMIT $start,$perPage";
			$result = DBQuery::instance()->executeQuery($sql);
		
			$newre = array();
			foreach($result as $key=>$value){
				$k = $value['tid'];
				$newre[$k] = $value;
			}
			return $newre;

			//exit();
		}

		function getSmarterDetailList($idarray, $child = 13, $page=1, $perPage=10){
			$idstring ="";
			$start = ($page-1) * $perPage;
			$end = ($page-1) * $perPage + $perPage;
			if($start < count($idarray)) {
				$end = min($end, count($idarray));
				$idarray = array_slice($idarray, $start, $end - $start);
			} else {
				return array();
			}
			foreach($idarray as $key=>$value) {
				$chid = substr($value['NewsId'], 0, 2);
				$prodid = substr($value['NewsId'], 2);
//				if($chid != 13) continue; //TODO: support other channel
				$productIDs[] = $prodid;
			}
			$idstring = implode(",", $productIDs);
			
//			$productTable = "C13Product";
			$productTable = CommonDao::channel($chid, "ProductTable");
			$addField = ($chid == 13)?',r_LowestURL OfferURL' : "";
			$sql = "SELECT B.ProductID,B.Name,B.MfName,B.Description,B.HasImage,B.r_AvgRating,B.CategoryName, " .
					" B.r_LowestPrice Price,B.r_LowestPriceMerName,B.r_LowestPriceMerID MerchantID," .
					" B.r_HighestPrice FullPrice,B.r_MerchantCount, B.Brief, " .
					" B.r_LowestPriceOfferID,B.r_LowestBidPosition,".
					" B.r_ChangePrice, UNIX_TIMESTAMP(B.r_ChangeTime) as r_ChangeTime, UNIX_TIMESTAMP(B.r_AddDate) as r_AddDate, " .
					" B.r_VideoCount".$addField.
					" FROM  $productTable B ".
					" WHERE B.ProductID IN ( " . $idstring . ")";
			$rs = DBQuery::instance()->executeQuery($sql);
			$rs2 = array();
			foreach($rs as $index => $product) {
//						$product['CategoryID'] = $catid;
				if ($chid == 14) { //酒店
					$product['ImageURL'] = PathManager::getProductImage($chid, $product['ProductID'], "small", 
						$product['HasImage']);
					$product['DetailURL'] = PathManager::getHotelProductUrl($chid, $product['ProductID']);
				}
				else {
					$product['ImageURL'] = PathManager::getProductImage($chid, $product['ProductID'], "small", $product['HasImage']);
					$product['DetailURL'] = PathManager::getProdUrl($chid, $product['ProductID']);
				}
				$product['OfferURL'] = PathManager::getOfferUrl($chid, $product['ProductID'], $product['MerchantID'], $product['OfferURL']);
				$product['LoginDate'] = getDateTime('Y-m-d', $product['r_AddDate']);
				$product['Brief'] = $product['Brief'] && $product['Brief'] != "YES" ? $product['Brief']
					 : Utilities::cutString($product['Description'], 150);
				$key = $child.$product['ProductID'];
				$rs2[$key] = $product;
			}
			return $rs2;

			//exit();
		}

		function getNewPageStr($nowPage,$maxPage,$type,$se) {
	  	  
	  	  $str = "<div class=\"page\"><ul><li>当前第".$nowPage."页,共".$maxPage."页</li>";
		  if($nowPage > 1) {
		  	  $url = "/se-".$se."-".($nowPage-1)."-".$type."/";
		  	  $str .= "<li><a href='$url'>上一页</a></li>";  
		  }
		  if($maxPage <= 5) {
		  	  for($i=1; $i<=$maxPage; $i++) {
				  $url = "/se-".$se."-".$i."-".$type."/";
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  if($nowPage == $maxPage && $maxPage!="1") {
			  	$str .= "";
			  }
		  }elseif($nowPage > 4 && $nowPage < ($maxPage - 2)) {
		  	  $starti = $nowPage -2;
			  $url = "/se-".$se."-1-".$type."/";
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
			  $str .= "<li>...</li>";
			  for($i=$starti; $i<=$starti+4 && $i<=$maxPage; $i++) {
				  $url = "/se-".$se."-".$i."-".$type."/";
				  if($nowPage == $i) {
					  $str .= "<li>$i</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  $str .= "<li>...</li>";
			  
		  } elseif($nowPage <= 4) {
		  	  for($i=1; $i<=5; $i++) {
				  $url = "/se-".$se."-".$i."-".$type."/";
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  $str .= "<li>...</li>";
		  } else {
		  	   $url = "/se-".$se."-1-".$type."/";
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
				if($nowPage >= ($maxPage - 2)){
					$str .= "<li>...</li>";
				}else{
			  		
				}
			  $starti = $maxPage -4;
		  	  for($i=$starti; $i<=$maxPage; $i++) {
				  $url = "/se-".$se."-".$i."-".$type."/";
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  if($nowPage != $maxPage) {
				if($nowPage >= ($maxPage - 2)){

				}else{
			  		$str .= "<li>...</li>";
				}
			  }
		  }
		  
		   if($maxPage>6 &&($nowPage < $maxPage-5) ) {
		  	  $url = "/se-".$se."-".$maxPage."-".$type."/";
		  	  $str .= "<li><a href='$url'>".$maxPage."</a></li>";  
		  }
		  
		  if($nowPage != $maxPage) {
			  $url = "/se-".$se."-".($nowPage+1)."-".$type."/";
		  	  $str .= "<li><a href='$url'>下一页</a></li>";  
		  }
		  $str .= "</ul></div>";
	  	  return $str;
	  }
		
		function doSearchForSTX($page=1,$perPage=10){
			$limitStart = ($page -1) * $perPage;
			$sql = "SELECT m.isActive as MerIsActive,m.NameURL,m.Name,m.Merchant_,t.StartDate,t.ExpireDate,t.Descript as DescriptOri,t.Detail as DetailOri,t.Coupon_,p.CityId,p.NewsId,p.Type, ";
			$sql .="(50*(MATCH(p.Title) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+5*(MATCH(p.Descript) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+50*(MATCH(p.Tag) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+30*(MATCH(p.Category) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+p.Rank+0) AS Relevance ";
			$sql .= "FROM ".__DB_SE.".News_SE p ";
			$sql .= "INNER JOIN ".__DB_FRONT.".Coupon t ON (t.Coupon_ = p.NewsId) INNER JOIN ".__DB_FRONT.".Merchant m ON (m.Merchant_ = t.Merchant_) INNER JOIN ".__DB_FRONT.".CoupCat ca ON (t.Coupon_ = ca.Coupon_) INNER JOIN ".__DB_FRONT.".Category c ON (c.Category_ = ca.Category_)";
			$sql .= "WHERE MATCH(p.Title,p.Descript,p.Tag,p.Category) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "' IN BOOLEAN MODE)  AND Type=1 ";
			$sql .= "ORDER BY Relevance DESC ";
			$sql .= "limit $limitStart,10";
			//echo $sql;
			$this->SearchList = DBQuery::instance(__SE)->executeQuery($sql);
		}
		function doSearchCountForSTX($page=1,$perPage=10){
			$sql = "SELECT count(*) FROM ".__DB_SE.".News_SE p ";
			$sql .= "WHERE MATCH(p.Title,p.Descript,p.Tag,p.Category) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "' IN BOOLEAN MODE) AND Type=1";
			$this->SearchListCount = DBQuery::instance(__SE)->getOne($sql);
			return $this->SearchListCount;
		}


		function writeKeyLog(){
//			$sql = "INSERT INTO KeyLog(Keywords,UpdateDate) VALUES('$this->SearchExpr',NOW())";
//			return DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
		}
		
		function getTourDetailList($idarray, $page=1, $perPage=10) {
			$idstring = "";
			$start = ($page-1) * $perPage;
			$end = $start + $perPage;
			if($start < count($idarray)) {
				$end = min($end, count($idarray));
				$idarray = array_slice($idarray, $start, $end - $start);
			} else {
				return array();
			}
			foreach($idarray as $key=>$value) {
				$idstring .= $value['NewsId'].",";
			}
			$idstring = trim ($idstring, ",");
		
			$tourDao = new TourDao();
			$limit = "$start, $perPage";
			$where .= " AND t.TourID IN (".$idstring.")";
			$tourList = $tourDao -> getTourList(false, $where, $limit);
			$rs2 = array();
			foreach($tourList as $index => $tourInfo) {
				$rs2[$tourInfo['TourID']] = $tourInfo;
			}
			return $rs2;
		}

	}//}}}


?>
