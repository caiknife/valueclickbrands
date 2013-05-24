<?php
/**
* ArticleController.php
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
* @version CVS: $Id: ArticleController.php,v 1.3 2013/04/18 10:19:33 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Controller;

use Custom\Util\Utilities;

use CommModel\Article\Article;

use Zend\Paginator\Paginator;

use Custom\Mvc\Controller\AbstractActionController;
use BackEnd\Form\ArticleForm;
use Zend\Session\Container;

class ArticleController extends AbstractActionController
{
    function indexAction(){
        $page = $this->params()->fromQuery('page' , 1);
        
        $re = $this->params()->fromQuery();
        unset($re['page']);
        
        $where = array();
        foreach($re as $k => $v){
            if($v !== ''){
                $where[$k] = $v;
            }
        }

        $siteid = $this->params()->fromQuery('siteid' , key($this->sites));
        $re['siteId'] = $where['SiteID'] = $siteid;
        
        $articleTable = $this->_getArticleTable();
        $articleTable->order();
        $articles = new Paginator($articleTable->getResultToPage($where));
        $articles->setItemCountPerPage($page);
        $articles->setItemCountPerPage(self::LIMIT);
        
        $re['articles'] = $articles;
        
        $articleCategoryTable = $this->_getArticleCategoryTable();
        $userTable = $this->_getUserTabel();
        
        $categories = $articleCategoryTable->getAll($siteid);
        $re['categories'] = $categories;
        $re['categoriesResult'] = $this->_handleCates($articleCategoryTable->getResult($siteid));
        $re['sites'] = $this->sites;
        $re['authors'] = $userTable->getAll();
        $re['query'] = http_build_query($where);
        $re['uri'] = Utilities::encode($_SERVER['REQUEST_URI']);
        $re['State'] = isset($re['State'])? $re['State'] : 1;
        return $re;
    }
    
    function saveAction(){
        $articleForm = new ArticleForm();
        $request = $this->getRequest();

        $siteId = $this->params()->fromQuery('siteid');

        if(!$siteId){
            throw new \Exception('incomplete siteid');
        }
        
        if($request->isPost()){
            $params = $request->getPost();
            $article = new Article();
            
            $articleForm->setData($params);
            $articleForm->setInputFilter($article->getInputFilter());
            
            if($articleForm->isValid()){
                $article->exchangeArray($articleForm->getData());
                $user = $this->_getUserSession();
                
                $article->AuthorID = $user->UserID;
                $articleTable = $this->_getArticleTable();
                $articleTable->save($article);

                return $this->redirect()->toUrl('/Article/index?siteid=1');
            }
        }
        
        $articleCategoryTable = $this->_getArticleCategoryTable();
        $siteTable = $this->_getSiteTable();
        $articleForm->get('SiteID')->setValue($siteId);
        if($id = $this->params()->fromQuery('id')){
            $articleTable = $this->_getArticleTable();
            $article = $articleTable->getOneForId($id);
            $articleForm->setCategoryIDOptions($articleCategoryTable->getAll($siteId));
            $articleForm->bind($article);
            
        }else{
            $articleForm->setCategoryIDOptions($articleCategoryTable->getAll($siteId));
        }
        
        return array('articleForm' => $articleForm);
    }
    
    function deleteAction(){
        return $this->_changeState(3);
    }
    
    function recoverAction(){
        return $this->_changeState(2);
    }
    
    function publishAction(){
        return $this->_changeState(1);
    }
    
    private function _changeState($state){
        $request = $this->getRequest();
        $articleTable = $this->_getArticleTable();
        if($request->isPost()){
            if(!$id = $this->params()->fromPost('id')){
                return $this->redirect()->toRoute('backend' , array('controller' => 'Article' , 'action' => 'index'));
            }
        }
        
        if(!isset($id)){
            if(!$id = $this->params()->fromQuery('id')){
                return $this->redirect()->toRoute('backend' , array('controller' => 'Article' , 'action' => 'index'));
            }
        }
        $articleTable->changeState($id , $state);
        
        return $this->redirect()->toRoute('backend' , array('controller' => 'Article' , 'action' => 'index'));
    }
    
    private function _getArticleCategoryTable(){
        return $this->getServiceLocator()->get('ArticleCategoryTable');
    }
    
    private function _getSiteTable(){
        return $this->getServiceLocator()->get('SiteTable');
    }
    
    private function _getArticleTable(){
        return $this->getServiceLocator()->get('ArticleTable');
    }
    
    private function _getUserTabel(){
        return $this->getServiceLocator()->get('UserTable');
    }
    
    private function _getUserSession(){
        return  new Container('user');
    }
    
    private function _handleCates($cates){
        $re = array();
        foreach($cates as $v){
            $re[$v->CategoryID] = $v;
        }
        
        return $re;
    }
}