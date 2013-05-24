<?php
/**
* ArticleCategoryTable.php
*-------------------------
*
* 
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
* @author Yaron Jiang <yjiang@corp.valueclick.com.cn>
* @copyright (C) 2004-2013 Mezimedia.com
* @license http://www.mezimedia.com PHP License 5.0
* @version CVS: $Id: ArticleCategoryTable.php,v 1.2 2013/04/15 13:41:10 rizhang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace CommModel\Article;


use Custom\Util\Utilities;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSetInterface;
use Custom\Categories\Category\ArticleCategory as Cate;
use Custom\Categories\Categories;
use Custom\Util\PathManager;

class ArticleCategoryTable extends TableGateway
{
    protected $table = 'ArticleCategory';
    
    function getAll($siteId = 1){
        $re = $this->select(array('SiteID' => $siteId));
        $list = $re->toArray();
        $cates = array();
        foreach($list as $v){
            $cates[$v['CategoryID']] = new Cate($v);
        }
        foreach($list as $v){
            if($v['ParentID'] > 0){
                $cates[$v['ParentID']]->addCate($cates[$v['CategoryID']]);
            }
        }
        $re = new Categories();
        foreach($list as $v){
            if($v['ParentID']  < 1){
                $re->addCate($cates[$v['CategoryID']]);
            }
        }
        return $re;
    }
    
    function getResult($siteId){
        return $this->select(array('SiteID' => $siteId));
    }
    
    function getOneForId($id){
        $id = (int)$id;
        $result = $this->select(array('CategoryID' => $id));
        return $result->current();
    }
    
    function save(ArticleCategory $data){
        $data = $data->getArrayCopy();
        
        if($data['CategoryID']){
            if($this->getOneForId($data['CategoryID'])){
                $id = $data['CategoryID'];
                unset($data['CategoryID']);
                
                $data['CreatDateTime'] = Utilities::getDateTime();
                return $this->update($data , array('CategoryID' => $id));
            }
        }else{
                $data['CreatDateTime'] = Utilities::getDateTime();
                return $this->insert($data);
        }
    }
    
    function remove($id){
        $this->delete(array('CategoryID' => $id));
        $this->delete(array('ParentID' => $id));
    }

    public function getParcentInfo($pid)
    {
        $where = array(
            'CategoryID' => $pid,
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->columns(array('Name', 'ParcentID'));
        $resultSet = $this->selectWith($select);
        $result = current($resultSet->toArray());
    }

    public function getCategoryArticleInfo($siteid, $catid, $groupby = true)
    {
        $select = $this->getSql()->select();
        $where = array(
            'ArticleCategory.isActive' => 1,
            'Article.SiteID' => $siteid,
            'Article.State'  => 1,
        );
        if (is_array($catid)) {
            $select->where($where);
            $sqlIn = "'".implode("','", $catid)."'";
            $select->where("ArticleCategory.CategoryID IN (".$sqlIn.")");
        } else {
            $where['ArticleCategory.CategoryID'] = $catid;
            $select->where($where);
        }
        $select->columns(array('CategoryID', 'CnName'));
        $select->join('Article', 'ArticleCategory.CategoryID = Article.CategoryID', array('ArticleID', 'Title'));
        $select->order('Article.Order DESC, Article.CategoryID ASC, Article.ArticleID DESC');
        //cho str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $resultArray = $resultSet->toArray();
        $articleList = array();
        if ($groupby && $resultArray) {
            foreach ($resultArray as $result) {
                $CategoryID   = $result['CategoryID'];
                $CategotyName = $result['CnName'];
                $ArticleID    = $result['ArticleID'];
                $ArticleTitle = $result['Title'];
                if ($siteid == '1') {
                    $ArticleDetailUrl = PathManager::getDhbArticleDetailUrl($ArticleID);
                } elseif ($siteid == '2') {
                    $ArticleDetailUrl = PathManager::getArticleDetailUrl($ArticleID);
                }
                if (!$articleList[$CategoryID]) {
                    $articleList[$CategoryID]['CategoryID']   = $CategoryID;
                    $articleList[$CategoryID]['CategoryName'] = $CategotyName;
                }
                $articleList[$CategoryID]['ArticleList'][] = array(
                    'ArticleID'        => $ArticleID,
                    'ArticleTitle'     => $ArticleTitle,
                    'ArticleDetailUrl' => $ArticleDetailUrl,
                );
            }
        } elseif ($resultArray) {
            foreach ($resultArray as $result) {
                $CategoryID   = $result['CategoryID'];
                $CategotyName = $result['CnName'];
                $ArticleID    = $result['ArticleID'];
                $ArticleTitle = $result['Title'];
                if ($siteid == '1') {
                    $ArticleDetailUrl = PathManager::getDhbArticleDetailUrl($ArticleID);
                } elseif ($siteid == '2') {
                    $ArticleDetailUrl = PathManager::getArticleDetailUrl($ArticleID);
                }
                $articleList[] = array(
                    'CategoryID'       => $CategoryID,
                    'CategotyName'     => $CategotyName,
                    'ArticleID'        => $ArticleID,
                    'ArticleTitle'     => $ArticleTitle,
                    'ArticleDetailUrl' => $ArticleDetailUrl,
                );
            }
        }
        //echo '<pre>';print_r($articleList);exit;
        return $articleList;
    }
}