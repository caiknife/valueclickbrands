<?php
/*
 * Created on 2009-5-7
 * class.ProductListDao.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.ProductListDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class ProductListDao {
	const GROUP_PRODLIST="ProdList";
	const PRODUCTID_LENGTH = 8;
	const CACHE_TIME = 3600;
	public $useCache = true;

	/**
	 * 取得产品数量
	 */
	public function countProductList($params, $chid=null, $catid=null) {
		$this->createProductIDs($chid, $catid, $params);
		$cacheFile = self::getProdCacheFile($params, $chid, $catid);
		return (int) ((filesize($cacheFile)+1) / 9);
	}


	/**
	 * 查询,返回符合条件的所有Product ids
	 */
	public function createProductIDs($chid, $catid, $params) {
		if($this->checkProdListCached($params, $chid, $catid)) {
			return true;
		}
		
		$productTable = CommonDao::channel($chid, "ProductTable");
		$catProdTable = CommonDao::channel($chid, "CatProdTable");
		$mbpTable = CommonDao::channel($chid, "MerBidProdTable");
		$sql = "SELECT LPAD(A.ProductID, " . self::PRODUCTID_LENGTH  . ", ' ') AS ProductID" .
				" FROM $productTable A" .
				" INNER JOIN $catProdTable B ON B.ProductID=A.ProductID";
		if($params['merid'] != NULL) {
			$sql .= " INNER JOIN $mbpTable C ON C.ProductID=A.ProductID";
		}

		//查询条件
		$sql .= " WHERE 1=1";
		if($params['merid'] != NULL) {
			$sql .= " AND C.MerchantID='{$params['merid']}'";
		}
		if($catid != NULL) {
			$sql .= " AND B.CategoryID='$catid'";
		}
		if(!empty($params["minp"])) {
			$sql .= " AND A.r_LowestPrice>='{$params['minp']}'";
		}
		if(!empty($params["maxp"])) {
			$sql .= " AND A.r_LowestPrice<'{$params['maxp']}'";
		}
		if(!empty($params["se"])) {
			$sql .= " AND A.r_SearchKeyWord LIKE '%".DBQuery::filter($params["se"])."%'";
		}
		if(!empty($params['atr_0']) && $params['atr_0'] != "more") {
			$sql .= " AND A.MfName = '" . DBQuery::filter($params['atr_0']) . "'";
		}
		for($loop = 1; $loop <= 8; $loop++) {
			$key = "atr_$loop";
			if(!empty($params[$key]) && $params[$key]!="more") {
				$sql .= " AND A.ValIDUnitID".$loop."='{$params[$key]}'";
			}
		}
		
		//add by rollenc 071022
		$spec = $params['spec'];
		$specDataL = getDateTime('Y-m-d H:i:s', time() - 3600*24*14);
		if($spec == 'new') {
			$sql .= sprintf(" AND A.r_AddDate > '%s'", $specDataL);
		}
		else if($spec == 'pricedown') {
			$sql .= sprintf(" AND A.r_ChangeTime > '%s' AND A.r_ChangePrice < 0", $specDataL);
		}
		else if ($spec == 'hasvideo') {
			$sql .= " AND A.r_VideoCount > 0";
		}
		//end
		
		//add by cooc 061108
		if($params['city'] != NULL) {
			if($productCity = CommonDao::channel($chid, "ProductCity")) {
				$where = "(SELECT DISTINCT ProductID FROM $productCity";
				$where .= " WHERE City = '".DBQuery::filter($params['city'])."' or City = '全国')";
				$sql .= " AND A.ProductID IN ".$where;
			}
		}//end
		
		//排序
		switch ($params["sortby"]) {
//		case 'Popularity':
//			$sql .= " ORDER BY A.r_ProductOutgoingRank DESC";
//			break;
		case 'Rating':
			$sql .= " ORDER BY A.r_AvgRating DESC";
			break;
		case 'PriceL':
			$sql .= " ORDER BY A.r_LowestPrice ASC";
			break;
		case 'PriceH':
			$sql .= " ORDER BY A.r_LowestPrice DESC";
			break;
		default:
			$sql .= " ORDER BY A.r_ProductOutgoingRank DESC, A.r_MerchantCount DESC";
		}
		$sql .= " LIMIT 50100";
		
		//echo "{$sql}<BR>\n";
		
		$rs = DBQuery::instance()->executeQuery($sql);
		$productIDs = Utilities::convertSimpleArray($rs, "ProductID");
		$this->saveProdListResult($params, $productIDs, $chid, $catid);
		return true;
	}

	/**
	 * 取得产品列表数据
	 */
	public function fetchProductList($params, $chid, $catid, $pageNo=1) {
		$count = 10;
		if($params["pn"] < 1) $pageNo = 1;
		else $pageNo = $params["pn"];
		$offset = ($pageNo - 1) * $count;		
		$this->createProductIDs($chid, $catid, $params);
		$productTable = CommonDao::channel($chid, "ProductTable");
		$productIDs = self::getSomeProductIDs($params, $chid, $catid, $offset, $count);
		if($productIDs == '') {
			return array();
		}
		$addField = ',r_LowestURL OfferURL';
		$sql = "SELECT B.ProductID,B.Name,B.MfName,B.Description,B.HasImage,B.r_AvgRating,B.CategoryName, " .
				" B.r_LowestPrice Price,B.r_LowestPriceMerName MerchantName,B.r_LowestPriceMerID MerchantID," .
				" B.r_HighestPrice FullPrice,B.r_MerchantCount," .
				" B.r_LowestPriceOfferID,B.r_LowestBidPosition,".
				" B.r_ChangePrice, UNIX_TIMESTAMP(B.r_ChangeTime) as r_ChangeTime, UNIX_TIMESTAMP(B.r_AddDate) as r_AddDate, " .
				" B.r_VideoCount".$addField.
				" FROM  $productTable B ".
				" WHERE B.ProductID IN ( " . $productIDs . ")";
		$rs = DBQuery::instance()->executeQuery($sql);
		$rs2 = array();
		$arrProductIDs = explode(',', $productIDs);
		foreach($arrProductIDs as $index => $pid) {
			foreach($rs as $index => $product) {
				if($product['ProductID'] == $pid) {
					$product['CategoryID'] = $catid;
					$product['DetailURL'] = PathManager::getProdUrl($chid, $product['ProductID']);
					$product['ImageURL'] = PathManager::getProductImage($chid, $product['ProductID'], "small", $product['HasImage']);
					$product['OfferURL'] = PathManager::getOfferUrl($chid, $product['ProductID'], $product['MerchantID'], $product['OfferURL']);
					$product['LoginDate'] = getDateTime('Y-m-d', $product['r_AddDate']);
					$product['Brief'] = Utilities::cutString($product['Description'], 150);
					$rs2[] = $product;
					unset($rs[$index]);
				}
			}
		}
		return $rs2;
	}

	public function & getSomeProductIDs($params, $chid, $catid, $start=0, $length=10) {
		$cacheFile = self::getProdCacheFile($params, $chid, $catid);
		$fp = fopen($cacheFile, 'r');
		if($fp == false) {
			return '';
		}
		fseek($fp,(self::PRODUCTID_LENGTH+1)*$start);
		$result = fread($fp, (self::PRODUCTID_LENGTH+1)*$length - 1);
		fclose($fp);
		return $result;
	}
	
	public function getProdCacheFile($params, $chid, $catid) {
		$prodListCacheDir = __SE_CACHE_DIR . "ProdList" . "/{$chid}/{$catid}/";
		if(! file_exists($prodListCacheDir)) {
			mkdir($prodListCacheDir, 0777, true);
		}
		
		unset($params['bn'], $params['vt'], $params['more']);
		ksort($params);
		return $prodListCacheDir . self::getCacheHash($params) . '.txt';
	}
	
	public function saveProdListResult($params, & $data, $chid, $catid = 0) {
		if($this->useCache == false) {
			return false;
		}
		$cacheFile = self::getProdCacheFile($params, $chid, $catid);
		file_put_contents($cacheFile, implode(',', $data));
		return true;
	}
	
	public function getCacheHash($params) {
		return md5(implode('_',$params));
	}
	public function checkProdListCached($params, $chid, $catid) {
		if($this->useCache == false) {
			return false;
		}
		$cacheFile = self::getProdCacheFile($params, $chid, $catid);
		if(!file_exists($cacheFile) || (filemtime($cacheFile) < time() - self::CACHE_TIME)) {
			return false;
		}
		return true;
	}

	/**
	 * 计算分页
	 */
	public function getPageStr($params, $chid, $catid, $count, $pageSize=10) {
		$totalCount = $count;
		if($params['pn'] > 0) {
			$pageno = $params['pn'];
		} else {
			$pageno = 1;
			$params['pn'] = null;
		}
		$totalPage = intval($count / $pageSize);
		if($count % $pageSize > 0) $totalPage++;
		$middle = min($totalPage-1, $pageno);
		$min = $middle - 3;
		$max = $middle + 3;
		if($min < 2) {
			$max += 2 - $min;
			$min = 2;
		}
		if($max > $totalPage) {
			$min = max(min($min, $min-($max-$totalPage)), 2);
			$max = $totalPage;
		}
		$pageStr = "\n<div id=\"pagination\"><ul>\n";
		$pageStr .= "<li>当前第{$pageno}页,共{$totalPage}页</li>\n";
		//page: previos
		if($pageno > 1) {
			$params['pn'] = $pageno - 1;
			$url = PathManager::getCategoryUrl($chid, $catid, $params);
			$pageStr .= "<li class=\"previous\"><a href=\"{$url}\">上一页</a></li>\n";
		}
		//page: 1
		if($pageno == 1) $pageStr .= "<li class=\"active\">1</li>\n";
		else {
			$params['pn'] = 1;
			$url = PathManager::getCategoryUrl($chid, $catid, $params);
			$pageStr .= "<li><a href=\"{$url}\">1</a></li>\n";
		}
		//page: ...
		if($min > 2) $pageStr .= "<li>...</li>\n";
		//page: loop
		for($i=$min; $i<=$max; $i++) {
			if($pageno == $i) $pageStr .= "<li class=\"active\">".$i."</li>\n";
			else {
				$params['pn'] = $i;
				$url = PathManager::getCategoryUrl($chid, $catid, $params);
				$pageStr .= "<li><a  href=\"{$url}\">".$i."</a></li>\n";
			}
		}
		//page: ...
		if($max+1 < $totalPage) $pageStr .= "<li>...</li>\n";
		//page: last
		if($max < $totalPage) {
			if($pageno == $totalPage) $pageStr .= "<li class=\"active\">".$totalPage."</li>\n";
			else {
				$params['pn'] = $totalPage;
				$url = PathManager::getCategoryUrl($chid, $catid, $params);
				$pageStr .= "<li><a  href=\"{$url}\">".$totalPage."</a></li>\n";
			}
		}
		//page: next
		if($pageno < $totalPage) {
			$params['pn'] = $pageno + 1;
			$url = PathManager::getCategoryUrl($chid, $catid, $params);
			$pageStr .= "<li class=\"next\"><a href=\"{$url}\">下一页</a></li>\n";
		}
		$pageStr .= "</ul></div>\n";
		return $pageStr;
	}

	
}
?>