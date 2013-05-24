<?php
/*
* package_name : DealsController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: DealsController.php,v 1.1 2013/04/15 10:57:19 rock Exp $
*/
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;
use CommModel\Merchant\Merchant;
use CommModel\Article\Article;
use CommModel\Ads\Google;
use CommModel\Coupon\Coupon;
use CommModel\Category\Category;
use Custom\Util\Utilities;
use Custom\Util\TrackingFE;
use Custom\Util\PathManager;
use Zend\Paginator\Paginator;

class DealsController extends UsController 
{
    const PAGE = 25;//这里分页25条
    /*
     * Deals list page
     */
    public function indexAction()
    {
        //获取merid
        preg_match('/^(\/deals)\/+(.*)$/', $_SERVER["REQUEST_URI"], $matches);
        if (isset($matches[2])) {
            $page = str_replace(array('/', 'page-'), "", $matches[2]);
        }

        $page = $page ? $page : 1;
        $offset = ($page-1) * self::PAGE;

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $articleTable  = $this->_getTable('Article');
        $categoryTable = $this->_getTable('Category');
        $favoriteTable = $this->_getTable('Favorite');
        $merchantTable = $this->_getTable('Merchant');

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        // 帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        //促销列表
        $adapter = $couponTable->getDealsList(self::SITEID);
        $paginator = new Paginator($adapter);
        $paginator->setCurrentPageNumber($page)
                  ->setPageRange(self::RANGE)
                  ->setItemCountPerPage(self::PAGE);
        $dealsList = $paginator->getCurrentItems()->toArray();
        foreach ($dealsList as $key => $deals) {
            $offerUrl = TrackingFE::registerOfferLink($deals);
            $dealsList[$key]['OfferUrl'] = $offerUrl;
            $dealsList[$key]['CouponName'] = Utilities::cutString(strip_tags($dealsList[$key]['CouponDescription']), 200);
        }
        //echo '<pre>';print_r($dealsList);exit;

        $assign = array(
            'categoryList'   => $categoryList,
            'helpCenterList' => $helpCenterList,
            'googleAdsList'  => Google::getGoogleAds(),
            'page'           => $page,
            'paginator'      => $paginator,
            'dealsList'      => $dealsList,
        );
        return new ViewModel($assign);
    }
}
?>