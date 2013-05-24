<?php
/*
* package_name : ArticleController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: ArticleController.php,v 1.1 2013/04/15 10:57:19 rock Exp $
*/
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;
use CommModel\Coupon\Coupon;
use CommModel\Ads\Google;
use CommModel\Article\Article;
use CommModel\Category\Category;
use Custom\Util\Utilities;
use Custom\Util\PathManager;

class ArticleController extends UsController 
{
    protected $helpCenterName = '帮助中心';

    /*
     * Search coupon page
     */
    public function categoryAction()
    {
        preg_match('/^(\/help-[0-9]+)\/$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            $catid = array('26', '27'); // 帮助中心默认显示分类里的文章:catid = 26
            $isHelpCenter = true;
        } else {
            $catid = str_replace("/help-", "", $matches[1]);
            $isHelpCenter = false;
        }

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $categoryTable = $this->_getTable('Category');
        $articleTable  = $this->_getTable('Article');
        $articleCategoryTable = $this->_getTable('ArticleCategory');

        // 右侧，获取分类下的所有文章
        $categoryArticleInfo = $articleCategoryTable->getCategoryArticleInfo(self::SITEID, $catid, false);
        if (empty($categoryArticleInfo)) {
            throw new \Exception('catid：$catid no info');
        }

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        //特价促销
        $specialDealsList = $couponTable->getSpecialDealsList();

        $assign = array(
            'categoryList'     => $categoryList,
            'siteHelpList'     => $siteHelpList,
            'helpCenterList'   => $helpCenterList,
            'specialDealsList' => $specialDealsList,
            'specialDealsMore' => PathManager::getDealsListUrl(),
            'googleAdsList'    => Google::getGoogleAds(),
            'helpCenterName'   => $this->helpCenterName,
            'helpCenterUrl'    => PathManager::getArticleCateUrl(),
            'categoryName'     => $isHelpCenter ? $this->helpCenterName : $categoryArticleInfo[0]['CategotyName'],
            'isHelpCenter'     => $isHelpCenter,
            'categoryArticleInfo' => $categoryArticleInfo,
        );
        return new ViewModel($assign);
    }

    /*
     * Search deals page
     */
    public function detailAction()
    {
        // 获取URL中的参数，验证
        preg_match('/^(\/article-[0-9]+)\/$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("文章id格式错误");
        }
        $aid = str_replace("/article-", "", $matches[1]);

        // 初始化数据库
        $couponTable   = $this->_getTable('Coupon');
        $categoryTable = $this->_getTable('Category');
        $articleTable  = $this->_getTable('Article');

        //获取文章信息
        $articleInfo  = $articleTable->getArticleInfoByID(self::SITEID, $aid);
        if (empty($articleInfo)) {
            throw new \Exception("artcile id {$aid} error");
        }
        $articleInfo['CategoryName'] = $articleInfo['CnName'];
        $articleInfo['ArticleCateUrl'] = PathManager::getArticleCateUrl($articleInfo['CategoryID']);
        unset($articleInfo['CnName']);

        // 全部优惠券分类
        $categoryList = $categoryTable->getCategoryList(self::SITEID);

        //站点帮助
        $siteHelpList = $articleTable->getSiteHelpList();

        //帮助中心
        $helpCenterList = $articleTable->getHelpCenterList();

        //特价促销
        $specialDealsList = $couponTable->getSpecialDealsList();

        $assign = array(
            'categoryList'     => $categoryList,
            'siteHelpList'     => $siteHelpList,
            'helpCenterList'   => $helpCenterList,
            'specialDealsList' => $specialDealsList,
            'specialDealsMore' => PathManager::getDealsListUrl(),
            'googleAdsList'    => Google::getGoogleAds(),
            'helpCenterName'   => $this->helpCenterName,
            'helpCenterUrl'    => PathManager::getArticleCateUrl(),
            'articleInfo'      => $articleInfo,
        );
        return new ViewModel($assign);
    }
}
?>