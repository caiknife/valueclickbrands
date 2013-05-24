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
 * @version    CVS: $Id: class.ProductListAction.php,v 1.1 2013/04/15 10:57:53 rock Exp $
 * @link       http://www.smarter.com/
 */

class ProductListAction extends BaseAction {

	/**
	 * 入力check
	 */
	protected function check($request, $response) {

	}

	/**
	 * 分支
	 */
	protected function service($request, $response) {
		$this->doListAction($request, $response);
	}

	protected function doListAction($request, $response) {
		$prodListDao = new ProductListDao();
		$params["sortby"] = $request->getParameter("sortby");
		$params["pn"] = $request->getParameter("pn");
		$chid = $request->getParameter("chid");
		$catid = $request->getParameter("catid");
		if(empty($catid)) $catid = 1;
		$countProds = $prodListDao->countProductList($params, $chid, $catid);
		$listProds = $prodListDao->fetchProductList($params, $chid, $catid);
		$pagination = $prodListDao->getPageStr($params, $chid, $catid, $countProds, 10);
		//common data
		$categories = CategoryDao::getCategoryByChannel($chid);
		$categories = CategoryDao::formatCategoryNav($chid, $categories, $catid);
		$categoryChild = CategoryDao::getCategoryChild($chid);
		$categoryBreadcrumbs = CategoryDao::getCateBreadcrumbsByCateID($chid, $catid);
//		$page['CurrentCategoryName'] = CategoryDao::get($chid, $catid, 'CategoryName');
//		$page['CurrentCategoryURL'] = PathManager::getCategoryURL($chid, $catid);
		$currentCategory = array_shift($categoryBreadcrumbs);
		if ($categoryBreadcrumbs) {
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
		$pageDao = new PageDao();
		$page['HotCoupon'] = $pageDao->getHotCoupon();
		$page['HotBBS'] = $pageDao->getHotBBS();
//		var_dump($countProds);
//		var_dump($listProds);
		$response->setTplValue("page", $page);
		$response->setTplValue("currentCategory", $currentCategory);
		$response->setTplValue("chid", $chid);
		$response->setTplValue("catid", $catid);
		$response->setTplValue("categories", $categories);
		$response->setTplValue("countProds", $countProds);
		$response->setTplValue("listProds", $listProds);
		$response->setTplValue("pagination", $pagination);
		$response->setTplName("prodlist/ProdList");
	}
	
}
?>