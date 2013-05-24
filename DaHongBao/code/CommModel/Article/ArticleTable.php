<?php
/**
* ArticleTable.php
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
* @version CVS: $Id: ArticleTable.php,v 1.2 2013/04/18 10:19:33 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace CommModel\Article;
use Zend\Db\ResultSet\ResultSet;

use Zend\Db\Sql\Expression;

use Zend\Db\Sql\Select;

use Custom\Util\Utilities;
use \Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Paginator\Adapter\DbSelect;
use Custom\Util\PathManager;

class ArticleTable extends TableGateway
{
    protected $table = 'Article';
    
    
    function getCountGroupByCate($siteId){
        $select = $this->getSql()->select();
        $select->reset(Select::COLUMNS);
        $select->reset(Select::LIMIT);
        $select->reset(Select::OFFSET);
        $select->columns(array('c' => new Expression('COUNT(1)') , 'CategoryID'));
        $select->group('CategoryID');
        $select->where(array('SiteID' => $siteId));
        
        $this->resultSetPrototype = new ResultSet();
        $result = $this->selectWith($select);
        foreach($result->toArray() as $v){
            $re[$v['CategoryID']] = $v['c'];
        }
        return $re;
    }
    
    function save(Article $data)
    {
        $data = $data->toArray();
        
        if(empty($data['ArticleID'])){
            unset($data['ArticleID']);
            $data['CreatDateTime'] = Utilities::getDateTime();
            $this->insert($data);
        }else{
            $id = (int)$data['ArticleID'];
            if($this->getOneForId($id)){
                unset($data['ArticleID']);
                unset($data['CreatDateTime']);
                return $this->update($data , array(
                    'ArticleID' => $id
                ));
            }else{
                return false;
            }
        }
    }
    function changeState($id, $state)
    {
        $where = $this->getSql()->select()->where;
        
        if(is_array($id)){
            $where->in('ArticleID' , $id);
        }elseif(is_string($id)){
            $where->equalTo('ArticleID' , $id);
        }
        
        return $this->update(array(
            'State' => $state
        ) , $where);
    }
    function getOneForId($id)
    {
        $id = (int)$id;
        $result = $this->select(array(
            'ArticleID' => $id
        ));
        return $result->current();
    }
    /**
     * 根据ID返回结果
     * @param int $id
     * @return array
     */
    function getInfoById($id)
    {
        return $this->getInfo(array('ArticleID' => (int)$id));
    }
    function getResultToPage($options)
    {
        $select = $this->_getSelect();
        
        $where = $this->_where($options);
        $select->where($where);
        $result = new DbSelect($select , $this->getAdapter());
        
        return $result;
    }
    private function _where(array $data)
    {
        $where = $this->getSql()->select()->where;
        
        if(isset($data['Title'])){
            $where->like('Title' , '%' . $data['Title'] . '%');
        }
        
        if(isset($data['AuthorID'])){
            $where->equalTo('AuthorID' , $data['AuthorID']);
        }
        
        if(isset($data['CategoryID'])){
            $where->equalTo('CategoryID' , $data['CategoryID']);
        }
        
        if(isset($data['startTime'])){
            $where->greaterThanOrEqualTo('CreatDateTime' , $data['startTime']);
        }
        
        if(isset($data['endTime'])){
            $where->lessThanOrEqualTo('CreatDateTime' , $data['endTime']);
        }
        
        if(isset($data['SiteID'])){
            $where->equalTo('SiteID' , $data['SiteID']);
        }
        
        $data['State'] = isset($data['State']) ? $data['State'] : 1;
        
        $where->equalTo('State' , $data['State']);
        
        return $where;
    }
    
    /**
     * 排序
     * @param array $data
     */
    function order(array $data = array()){
        $this->_getSelect();
        $data['Order'] = 'DESC';
        $data['CreatDateTime'] = 'DESC';
        $this->select->order($data);
        return $this;
    }

    /*
     * 大红包海外 - 站点帮助
     */
    public function getSiteHelpList($siteid = 2) {
        return array(
            '0' => array('Name' => '海淘攻略',       'Class' => 'haitaohelp', 'Url' => PathManager::getArticleDetailUrl("6")),
            '1' => array('Name' => '优惠券使用攻略', 'Class' => 'couponhelp' , 'Url' => PathManager::getArticleCateUrl("27")),
        );
    }

    /*
     * 大红包海外 - 帮助中心8条， 取海淘知识、商家购物攻略的文章 cateid = 26, 27
     * 按照Article Order降序、CategroyID升序、 ArticleID 降序
     */
    public function getHelpCenterList($limit = 8) {
        $aid = (int)$aid;
        $where = array(
            'State'      => 1,
            'CategoryID' => $catid,
        );
        $select = $this->getSql()->select();
        $select->where("State = 1 AND CategoryID IN ('26', '27')");
        $select->columns(array('ArticleID', 'Title'));
        $select->order('Order DESC, CategoryID ASC, ArticleID DESC');
        $select->limit($limit);
        $resultSet = $this->selectWith($select);
        $resultArray = $resultSet->toArray();
        $helpCenterList = array();
        if ($resultArray) {
            $helpCenterList['helpCenterName'] = '帮助中心';
            $helpCenterList['helpCenterUrl']  = PathManager::getArticleCateUrl();
            foreach ($resultArray as $result) {
                $helpCenterList['ArticleList'][] = array(
                    'ArticleID' => $result['ArticleID'],
                    'ArticleTitle' => $result['Title'],
                    'ArticleDetailUrl' => PathManager::getArticleDetailUrl($result['ArticleID']),
                );
            }
        }
        //echo "<pre>";print_r($helpCenterList);exit;
        return $helpCenterList;
    }

    /*
     * 获取文章的正文
     */
    public function getArticleInfoByID($siteid, $aid)
    {
        $aid = (int)$aid;
        $where = array(
            'Article.SiteID' => $siteid,
            'State'          => 1,
            'ArticleID'      => $aid,
        );
        $select = $this->getSql()->select();
        $select->join('ArticleCategory', 'ArticleCategory.CategoryID = Article.CategoryID', array('CnName'), 'left');
        $select->where($where);
        $resultSet = $this->selectWith($select);
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        return current($resultSet->toArray());
    }

    /*
     * 获取leafCategory下的文章
     */
    /*
    public function getArticleInfoByCID($siteid, $cid = array())
    {
        $aid = (int)$aid;
        $where = array(
            'Article.SiteID'    => $siteid,
            'State'      => 1,
            '' => $cid,
        );
        $select = $this->getSql()->select();
        $select->where($where);
        if ($columns) {
            $select->columns($columns);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }
    */
}