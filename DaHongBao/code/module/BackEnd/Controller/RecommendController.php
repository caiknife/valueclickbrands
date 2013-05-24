<?php
/**
* RecommendController.php
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
* @version CVS: $Id: RecommendController.php,v 1.10 2013/05/20 02:44:46 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Controller;
use Custom\Util\PathManager;

use Zend\Config\Writer\PhpArray;

use Zend\Config\Config;

use Custom\Util\Utilities;
use BackEnd\Model\Recommend\Recommend;
use BackEnd\Form\RecommendForm;
use Custom\Mvc\Controller\AbstractActionController;
use Custom\File\Uploader;
class RecommendController extends AbstractActionController
{
    protected $recommendTypes;
    function indexAction()
    {
    }
    function couponListAction()
    {
        $re = $this->params()->fromQuery();
        $re['SiteID'] = $this->params()->fromQuery('SiteID' , 1);
        $re['sites'] = $this->sites;
        $re['recommendTypes'] = $this->_getRecommendTypes('COUPON' , $re['SiteID']);
        $re['uri'] = Utilities::encode($_SERVER['REQUEST_URI']);
        $coupons = array();
        if(!empty($re['RecommendTypeID'])){
            $list = $this->_getList();
            foreach($list as $v){
                if('COUPON' != $v['ContentType']){
                    continue;
                }
                $coupon = $this->_getCoupon($v['ID']);
                $merchant = $this->_getMerchant($coupon['MerchantID']);
                $coupons[$v['RecommendID']] = $coupon;
                $coupons[$v['RecommendID']]['RecommendImage'] = $v['RecommendImage'];
                $coupons[$v['RecommendID']]['RecommendOrder'] = $v['RecommendOrder'];
                $coupons[$v['RecommendID']]['MerchantName'] = $merchant['MerchantName'];
            }
            $re['list'] = $coupons;
        }
        return $re;
    }
    function merchantListAction()
    {
        $re = $this->params()->fromQuery();
        $re['SiteID'] = $this->params()->fromQuery('SiteID' , 1);
        $re['sites'] = $this->sites;
        $re['recommendTypes'] = $this->_getRecommendTypes('MERCHANT' , $re['SiteID']);
        $re['uri'] = Utilities::encode($_SERVER['REQUEST_URI']);
        
        if(!empty($re['RecommendTypeID'])){
            $list = $this->_getList();
            $merchants = array();
            foreach($list as $v){
                if('MERCHANT' != $v['ContentType']){
                    continue;
                }
                $merchant = $this->_getMerchant($v['ID']);
                $merchants[$v['RecommendID']] = $merchant;
                $merchants[$v['RecommendID']]['RecommendImage'] = $v['RecommendImage'];
                $merchants[$v['RecommendID']]['RecommendOrder'] = $v['RecommendOrder'];
            }
            $re['list'] = $merchants;
        }
        return $re;
    }
    function articleListAction()
    {
        $re = $this->params()->fromQuery();
        $re['SiteID'] = $this->params()->fromQuery('SiteID' , 1);
        $re['sites'] = $this->sites;
        $re['recommendTypes'] = $this->_getRecommendTypes('ARTICLE' , $re['SiteID']);
        $re['uri'] = Utilities::encode($_SERVER['REQUEST_URI']);
        
        if(!empty($re['RecommendTypeID'])){
            $list = $this->_getList();
            $articles = array();
            foreach($list as $v){
                if('ARTICLE' != $v['ContentType']){
                    continue;
                }
                $article = $this->_getArticle($v['ID']);
                $articles[$v['RecommendID']] = $article;
                $articles[$v['RecommendID']]['RecommendImage'] = $v['RecommendImage'];
                $articles[$v['RecommendID']]['RecommendOrder'] = $v['RecommendOrder'];
            }
            $re['list'] = $articles;
        }
        return $re;
    }
    function saveAction()
    {
        $re = $this->params()->fromQuery();
        $request = $this->getRequest();
        $form = new RecommendForm();
        $table = $this->_getTable('RecommendTable');
        
        if($request->isPost()){
            $recommend = new Recommend();
            $form->setData($this->params()->fromPost());
            $form->setInputFilter($recommend->getInputFilter());
            
            if($form->isValid()){
                $recommend->exchangeArray($form->getData());
                $data = $recommend->toArray();
                
                $name = time() . rand(0,99);
                $data['RecommendImage'] = $this->_insertImg($name);
                
                if(!$data['RecommendImage']){
                    $data['RecommendImage'] = $this->params()->fromPost('RecommendDefaultImage');
                }
                
                $recommendTypeID = $data['RecommendTypeID'];
                foreach($recommendTypeID as $v){
                    $data['RecommendTypeID'] = $v;
                    $recommendID[] = $table->save($data);
                }
                
                return $this->redirect()->toUrl(Utilities::decode($this->params()->fromQuery('referer' , '/')));
            }
        }

        if(empty($re['ID']) || empty($re['ContentType'])){
            throw new \Exception('参数不完整');
        }
        
        $table->formatWhere(array('ID' => $re['ID'] , 'ContentType' => $re['ContentType']));
        $list = $table->getList();
        $recommendType = $this->_getRecommendType();
        foreach($list as $v){
            if(isset($recommendType[$v['RecommendTypeID']])){
                unset($recommendType[$v['RecommendTypeID']]);
            }
        }
        if(empty($recommendType)){
            return $this->redirect()->toUrl(Utilities::decode($this->params()->fromQuery('referer' , '/')));
        }
        $form->get('RecommendTypeID')->setCheckboxOptions($recommendType);
        $form->setData($this->params()->fromQuery());
        
        $re['form'] = $form;
        $re['content'] = $this->_getContent($this->params()->fromQuery('ContentType') 
            , $this->params()->fromQuery('ID'));
        return $re;
    }
    function setOrderAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            foreach($this->params()->fromPost() as $k => $v){
                $k = explode('_', $k);
                if($k[0] == 'recommendid' && isset($k[1])){
                    $this->_updateOrder($k[1] , $v);
                }
            }
            $this->_createFileCache($this->params()->fromQuery('RecommendTypeID'));
            return $this->redirect()->toUrl(Utilities::decode($this->params()->fromQuery('referer' , '/')));
        }else{
            throw new \Exception('非法的请求');
        }
    }
    
    function createFileAction(){
        $this->_createFileCache();
        return $this->redirect()->toRoute(null , array('controller' => 'recommend'));
    }
    
    
    function deleteAction()
    {
        $id = $this->params()->fromQuery('RecommendID');
        if(!$id){
            throw new \Exception('错误的参数 RecommendID');
        }
        
        $table = $this->_getTable('RecommendTable');
        $table->removeById($id);
        
        return $this->redirect()->toUrl(Utilities::decode($this->params()->fromQuery('referer' , '/')));
    }
    
    /*
     *
     * ------------------------------------------------------------------------------------------------------
     */
    
    /**
     * 获取类别
     * @param int $id
     * @return NULL|array
     */
    private function _getRecommendTypeById($id = null){
        if(!$this->recommendTypes){
            $table = $this->_getTable('RecommendTypeTable');
            foreach($table->getList() as $v){
                $this->recommendTypes[$v['RecommendTypeID']] = $v;
            }
        }
        
        if(null == $id){
            return $this->recommendTypes;
        }
        
        if(isset($this->recommendTypes[$id])){
            return $this->recommendTypes[$id];
        }else{
            return null;
        }
    }
    
    private function _updateOrder($id , $order){
        $table = $this->_getTable('RecommendTable');
        return $table->updateOrder($id , $order);
    }

    /**
     * 生成文件
     */
    private function _createFileCache($typeId = null){
        $cache = $this->_getConfig('cache');
        $cachePath = $cache['recommend'];
        $write = new PhpArray();
        
        if(!is_dir($cachePath)){
            if(!mkdir($cachePath , 0777 , true)){
                throw new \Exception($cachePath . '没有写入权限');
            }
        }
        
        $table = $this->_getTable('RecommendTable');
        
        if(null != $typeId){
            $type = $this->_getRecommendTypeById($typeId);
            $typeName = $type['RecommendTypeEnName'];
            $table->formatWhere(array('RecommendTypeID' => $typeId));
            $table->order();
            $list = $table->getList();
            
            $re = array();
            foreach($list as $v){
                $row = $this->_getContent($v['ContentType'] , $v['ID']);
                if(!empty($v['RecommendImage'])){
                    if('COUPON' == $v['ContentType']){
                        $row['CouponImageUrl'] = $v['RecommendImage'];
                    }elseif('MERCHANT' == $v['ContentType']){
                        $row['LogoFile'] = $v['RecommendImage'];
                    }
                }
                
                $row['RecommendImage'] = $v['RecommendImage'];
                $row['RecommendOrder'] = $v['RecommendOrder'];
                $re[] = $row;
            }
            if(empty($re)){
                $this->_message('没有文件生成，请检查', self::MSG_ERROR);
                return false;
            }
            
            $config = new Config($re , true);
            $write->toFile($cachePath . $typeName . '.php', $config);
        }else{
            $table->order();
            $list = $table->getList();
            
            $re = array();
            foreach($list as $v){
                $row = $this->_getContent($v['ContentType'] , $v['ID']);
                if(!empty($v['RecommendImage'])){
                    if('COUPON' == $v['ContentType']){
                        $row['CouponImageUrl'] = $v['RecommendImage'];
                    }elseif('MERCHANT' == $v['ContentType']){
                        $row['LogoFile'] = $v['RecommendImage'];
                    }
                }
                
                $row['RecommendImage'] = $v['RecommendImage'];
                $row['RecommendOrder'] = $v['RecommendOrder'];
                $re[$v['RecommendTypeID']][] = $row;
            }
            
            if(empty($re)){
                $this->_message('没有文件生成，请检查', self::MSG_ERROR);
                return false;
            }
            
            $recommendTypeTbale = $this->_getTable('RecommendTypeTable');
            foreach($recommendTypeTbale->getList() as $v){
                $recommendTypes[$v['RecommendTypeID']] = $v['RecommendTypeEnName'];
            }
            
            foreach($re as $k => $v){
                $config = new Config($v , true);
                $write->toFile($cachePath . $recommendTypes[$k] . '.php', $config);
            }
        }
        $this->_message('生成成功', self::MSG_SUCCESS);
    }
    
    /**
     * 获取列表
     * @param string $type
     */
    private function _getList()
    {
        $table = $this->_getTable('RecommendTable');
        $params['RecommendTypeID'] = $this->params()->fromQuery('RecommendTypeID');
        if(empty($params['RecommendTypeID'])){
            throw new \Exception('RecommendTypeID 参数丢失');
        }
        $table->formatWhere($params);
        $table->order();
        
        return $table->getList();
    }
    /**
     * 获取位置
     * @param string $type
     */
    private function _getRecommendTypes($type , $siteId)
    {
        $table = $this->_getTable('RecommendTypeTable');
        return $table->getListByContentType($type , $siteId);
    }
    
    /**
     * 上传图片
     * @param string $name
     * @return string|NULL
     */
    private function _insertImg($name)
    {
        $config = $this->_getConfig('upload');
        $config = $config['recommend'];
        $files = $this->params()->fromFiles('RecommendImage');
        if(! empty($files['name'])){
            return $config['showPath'] . Uploader::upload($files , $name , $config['uploadPath'] , $config);
        }
        
        return null;
    }
    /**
     * 获取位置
     * 
     * @return array
     */
    private function _getRecommendType()
    {
        $recommendTypeTable = $this->_getTable('RecommendTypeTable');
        $recommendTypes = $recommendTypeTable->getListByContentType($this->params()->fromQuery('ContentType') 
            , $this->params()->fromQuery('SiteID' , 1));
        $re = array();
        foreach($recommendTypes as $v){
            $re[$v['RecommendTypeID']] = $v['RecommendTypeName'];
        }
        return $re;
    }
    /**
     * 获取内容
     */
    private function _getContent($type , $id)
    {
        switch($type){
            case 'MERCHANT':
                return $this->_getMerchant($id);
            case 'COUPON':
                return $this->_getCoupon($id);
            case 'ARTICLE':
                return $this->_getArticle($id);
        }
    }
    private function _getMerchant($id)
    {
        $table = $this->_getTable('MerchantTable');
        $re = $table->getInfoById($id);
        $re['MerchantDetailUrl'] = PathManager::getDhbMerchantDetailUrl($re['MerchantID']);
        if(!empty($re['AffiliateID'])){
            $affiliate = $this->_getAffiliate($re['AffiliateID']);
            $re['Name'] = $affiliate['Name'];
            $re['UrlVarible'] = $affiliate['UrlVarible'];
        }else{
            $re['Name'] = '';
            $re['UrlVarible'] = '';
        }
        
        unset($re['DescriptionCN']);
        unset($re['DescriptionEn']);
        unset($re['MainSales']);
        return $re;
    }
    private function _getCoupon($id)
    {
        $table = $this->_getTable('CouponTable');
        $re = $table->getCouponById($id);
        $merchant = $this->_getMerchant($re['MerchantID']);
        $re['MerchantName'] = $merchant['MerchantName'];
        $re['LogoFile'] = $merchant['LogoFile'];
        $re['Name'] = $merchant['Name'];
        $re['UrlVarible'] = $merchant['UrlVarible'];
        $re['MerchantDetailUrl'] = $merchant['MerchantDetailUrl'];
        $re['AffiliateUrl'] = $merchant['AffiliateUrl'];
        if('COUPON' == $re['CouponType']){
            $re['CouponDetailUrl'] = PathManager::getDhbCouponDetailUrl($re['CouponID']);
        }else{
            $re['DealsDetailUrl'] = PathManager::getDhbDealsDetailurl($re['CouponID']);
        }
        return $re;
    }
    private function _getArticle($id)
    {
        $table = $this->_getTable('ArticleTable');
        $re = $table->getInfoById($id);
        $re['Url'] = PathManager::getDhbArticleDetailUrl($re['ArticleID']);
        unset($re['Detail']);
        unset($re['Summary']);
        return $re;
    }
    
    private function _getAffiliate($id){
        $table = $this->_getTable('AffiliateTable');
        return $table->getInfoById($id);
    }
}