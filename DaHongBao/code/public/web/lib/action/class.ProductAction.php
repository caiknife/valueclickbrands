<?php
/*
 * Created on 2009-5-7
 * class.ProductListAction.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.ProductAction.php,v 1.1 2013/04/15 10:57:53 rock Exp $
 * @link       http://www.smarter.com/
 */

class ProductAction extends BaseAction {

	/**
	 * 入力check
	 */
	protected function check($request, $response) {

	}

	/**
	 * 分支
	 */
	protected function service($request, $response) {
		$this->doDetailAction($request, $response);
	}

	protected function doDetailAction($request, $response) {
		$productDao = new ProductDao();
		$chid = $request->getParameter("chid");
		$prodid = $request->getParameter("prodid");
		$detailInfo = $productDao->getDetailInfo($chid, $prodid);
        // prod does not exist, then redirect to 404
		if (empty($detailInfo)) {
		    throw new Exception("Product not exists! ");
		}
		$catid = $detailInfo['CategoryID'];
		//common data
		$categories = CategoryDao::getCategoryByChannel($chid);
		$categories = CategoryDao::formatCategoryNav($chid, $categories, $catid);
//		$page['CurrentCategoryName'] = CategoryDao::get($chid, $catid, 'CategoryName');
//		$page['CurrentCategoryURL'] = PathManager::getCategoryURL($chid, $catid);
		$categoryChild = CategoryDao::getCategoryChild($chid);
		$categoryBreadcrumbs = CategoryDao::getCateBreadcrumbsByCateID($chid, $catid);
		if ($categoryBreadcrumbs) {
			$currentCategory = $categoryBreadcrumbs[0];
			$cateBreadcrumbsReverse = array_reverse($categoryBreadcrumbs);
			$response->setTplValue("breadCrums", $cateBreadcrumbsReverse);
		}
		//get children category
		foreach ($categories as $tmpKey => $tmpCategory) {
			if ($child = $categoryChild[$tmpCategory["CategoryID"]]) {
				$categories[$tmpKey]["child"] = $child;
				if ($categories[$tmpKey]["CategoryID"] == $cateBreadcrumbsReverse[0]["categoryID"]) {
					$categories[$tmpKey]["IsSelected"] = "YES";
				}
			}
		}
		if ($detailInfo["Spec"]) {
			$spec = unserialize($detailInfo["Spec"]);
			$response->setTplValue("attributes", $spec);
		}
		$pageDao = new PageDao();
		$page['HotCoupon'] = $pageDao->getHotCoupon();
		$page['HotBBS'] = $pageDao->getHotBBS();
		
    //add google ads by thomas.fu 07/14/09
//    $params = array();
////    $params["channelTag"] = "Search";
//    $adsWords = new AdWordsDao($detailInfo["ProductName"], 8);
//    $adsResult = $adsWords -> dispatch($params);
    $splitCountArr = array(3, 5);
    $baiduParams = array('splitCountArr' => array(-3, -5));
    $adsParams = array('splitCountArr' => $splitCountArr, 'keyword' => $detailInfo["ProductName"], "IsHighlight" => true);
    $adsResult = AdWordsDao::getAdsScript($adsParams, $baiduParams);
    $response->setTplValue("adsResult", $adsResult);
		
    $response->setTplValue("page", $page);
    $response->setTplValue("currentCategory", $currentCategory);
		$response->setTplValue("chid", $chid);
		$response->setTplValue("catid", $catid);
		$response->setTplValue("categories", $categories);
		$response->setTplValue("detailInfo", $detailInfo);
		$response->setTplName("prod/Detail");
	}
	
}
?>