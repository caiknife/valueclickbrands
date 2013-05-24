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
namespace FrontEnd\Controller;

use Custom\Mvc\Controller\FrontEndController;
use Zend\View\Model\ViewModel;
use Custom\Util\Utilities;
use Custom\Util\PathManager;
use CommModel\Article\ArticleCategory;

class ArticleController extends FrontEndController 
{
    protected $categoryList = array(
            '22', // 新手帮助
            '30', // 意见反馈
            '31', // 公司信息
            '32', // 使用说明
            '33', // 订阅分类
    );

    protected $helpCenterName = '帮助中心';

    /*
     * Article list page
     * 左侧展示文章分类及文章（文章要设置排序）， 右侧默认展示‘常见问题’这篇文章
     */
    public function categoryAction()
    {
        $aid = "7"; // 帮助中心默认显示文章:如何领取优惠券
        $articleInfo = $this->getActicleInfo($aid);

        $articleCategoryTable = $this->_getTable('ArticleCategory');
        $articleCategoryInfo = $articleCategoryTable->getCategoryArticleInfo(self::SITEID, $this->categoryList);

        $assign = array(
            'aid' => $aid,
            'helpCenterName'=> $this->helpCenterName,
            'helpCenterUrl'=> PathManager::getDhbArticleCateUrl(),
            'articleInfo'  => $articleInfo,
            'articleCategoryInfo' => $articleCategoryInfo,
        );
        return new ViewModel($assign);
    }

    /*
     * Article detail page
     * 左侧展示文章分类及文章（文章要设置排序）
     */
    public function detailAction()
    {
        preg_match('/^(\/article-[0-9]+)\/$/', $_SERVER["REQUEST_URI"], $matches);
        if (empty($matches[1])) {
            throw new \Exception("article id error");
        }
        $aid = str_replace("/article-", "", $matches[1]);
        $articleInfo = $this->getActicleInfo($aid);

        $articleCategoryTable = $this->_getTable('ArticleCategory');
        $articleCategoryInfo = $articleCategoryTable->getCategoryArticleInfo(self::SITEID, $this->categoryList);

        $assign = array(
            'aid' => $aid,
            'helpCenterName' => $this->helpCenterName,
            'helpCenterUrl'=> PathManager::getDhbArticleCateUrl(),
            'articleInfo'  => $articleInfo,
            'articleCategoryInfo' => $articleCategoryInfo,
        );
        return new ViewModel($assign);
    }

    public function getActicleInfo($aid) {
        //获取文章信息
        $articleTable = $this->_getTable('Article');
        $articleInfo  = $articleTable->getArticleInfoByID(self::SITEID, $aid);
        if (empty($articleInfo)) {
            throw new \Exception("artcile id {$aid} error");
        }
        $articleInfo['CategoryName'] = $articleInfo['CnName'];
        unset($articleInfo['CnName']);
        return $articleInfo;
    }
}
?>