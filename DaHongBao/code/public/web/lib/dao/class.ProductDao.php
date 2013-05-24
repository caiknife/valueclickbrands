<?php
/*
 * Created on 2009-5-26
 * class.ProductDao.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.ProductDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class ProductDao {
	public function getDetailInfo($chid, $prodid) {
		$tblProduct = CommonDao::channel($chid, "Product");
		$tblProductCategory = CommonDao::channel($chid, "ProductCategory");
		$tblCategory = CommonDao::channel($chid, "Category");
	
		$sql = "SELECT a.ProductID,a.Name ProductName,a.Description,a.HasImage" .
				" ,a.r_LowestPrice Price,a.r_HighestPrice FullPrice,a.r_LowestURL OfferURL" .
				" ,a.r_LowestPriceMerID MerchantID,a.r_LowestPriceMerName MerchantName" .
				" ,c.CategoryID,c.CategoryName, UNIX_TIMESTAMP(a.r_AddDate) as r_AddDate" .
				" ,a.Spec FROM $tblProduct a" .
				" INNER JOIN $tblProductCategory b on b.ProductID=a.ProductID" .
				" INNER JOIN $tblCategory c on c.CategoryID=b.CategoryID and c.IsLeaf='YES'" .
				" WHERE a.ProductID={$prodid}";
		$row = DBQuery::instance()->getRow($sql);
		if($row == null) return null;
		$row['OfferURL'] = PathManager::getOfferUrl($chid, $row['ProductID'], $row['MerchantID'], $row['OfferURL']);
		$row['DetailURL'] = PathManager::getProdUrl($chid, $row['ProductID']);
		$row['ImageURL'] = PathManager::getProductImage($chid, $row['ProductID'], "big", $row['HasImage']);
		$row['LoginDate'] = getDateTime('Y-m-d', $row['r_AddDate']);
		$row['Brief'] = Utilities::cutString($row['Description'], 120);
		return $row;
	}
}
?>