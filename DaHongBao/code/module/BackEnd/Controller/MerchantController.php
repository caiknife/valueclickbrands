<?php
/**
* MerchantController.php
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
* @version CVS: $Id: MerchantController.php,v 1.6 2013/05/03 09:17:45 thomas_fu Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Controller;

use Custom\File\Uploader;

use Custom\Util\Utilities;
use BackEnd\Model\Merchant\Merchant;
use Zend\File\Transfer\Adapter\Http;
use Zend\Validator\File\Size;
use BackEnd\Model\Merchant\MerchantTable;
use Zend\Paginator\Paginator;
use Custom\Mvc\Controller\AbstractActionController;
use BackEnd\Form\MerchantForm;

class MerchantController extends AbstractActionController
{
    function indexAction()
    {
        $params = $this->params()->fromQuery();
        $params['SiteId'] = isset($params['SiteId']) ? $params['SiteId'] : 1;
        $params['IsActive'] = isset($params['IsActive']) ? $params['IsActive'] : 'YES';
        
        $paginator = $this->_getMerchantListPaginator($params);
        $re = array(
            'paginator' => $paginator,
            'merchants' => $this->_getMerchantList($paginator),
            'categories' => $this->_getCategoryies($params),
            'affilites' => $this->_getAffilites($params),
            'sites' => $this->sites,
            'query' => http_build_query($params)
        );
        unset($params['page']);
        
        $re = array_merge($params , $re);
        $re['uri'] = Utilities::encode($_SERVER['REQUEST_URI']);
        return $re;
    }
    function saveAction()
    {
        $params = $this->params()->fromQuery();
        $siteId = $params['SiteId'] = isset($params['SiteId']) ? $params['SiteId'] : 1;
        $re = $params;
        $re['gobackUrl'] = Utilities::decode($params['referer']);
        
        $categories = $this->_getCategoryies($params);
        $affilites = $this->_getAffilites($params);
        $shippings = $this->_getShippings();
        $payments = $this->_getPayments($siteId);
        
        $form = new MerchantForm();
        $request = $this->getRequest();
        
        $uploadOptions = $this->_getConfig('upload');
        $marchantLogoOptions = $uploadOptions['merchantLogo'];

        $re['referer'] = $this->params()->fromQuery('referer');
        $re['query'] = http_build_query($params);
        
        if($request->isPost()){
            $params = $this->params()->fromPost();
            $action = 'update';

            $re['referer'] = $this->params()->fromQuery('referer');
            $merchantTable = $this->_getMerchantTable();
            $merchant = new Merchant();
            
            $form->setInputFilter($merchant->getInputFilter());
            $form->setData($params);
            if($form->isValid()){
                $params = $form->getData();
                if(empty($params['MerchantID'])){
                    $params['MerchantID'] = $merchantTable->getNewMerchantId($params['SiteID']);
                    $action = 'insert';
                }
                
                $data = $params;
                $data['LogoFile'] = $this->_insertImg($params['MerchantID']);
                
                $merchant->exchangeArray($data);
                
                if('insert' == $action){
                    $merchantTable->insert($merchant);
                    
                    //插入MerchantAlias
                    $this->_insertMerchantAlias($merchant);
                }else{
                    $this->_updateCouponSearch($merchant);
                    $merchantTable->update($merchant);
                }
                
                $this->_saveMerchantCategories($params['MerchantID'] , $params['CategoryID']);
                
                if(! empty($params['PaymentID'])){
                    $this->_saveMerchantPayment($params['MerchantID'] , $params['PaymentID']);
                }else{
                    $this->_clearMerchantPayment($params['MerchantID']);
                }
                if(! empty($params['ShippingID'])){
                    $this->_saveMerchantShipping($params['MerchantID'] , $params['ShippingID']);
                }else{
                    $this->_clearMerchantShipping($params['MerchantID']);
                }
                
                if($action == 'update'){
                    $this->_message('更新成功');
                    return $this->redirect()->toUrl(Utilities::decode($this->params()->fromQuery('referer')));
                }else{
                    $this->_message('新增成功');
                    $form = new MerchantForm();
                    
                }
            }else{
                $this->_message('新增失败，请检查错误' , self::MSG_ERROR);
            }
            $params['SiteId'] = $params['SiteID'];
        }

        
        
        if($id = $request->getQuery('id')){
            $merchantTable = $this->_getMerchantTable();
            $row = $merchantTable->getInfoById($id);
            
            if(! $row){
                throw new \Exception('error id');
            }
            
            $form->setData($row);
            
            $form->get('CategoryID')->setValue($this->_getMarchantCategories($row['MerchantID']));
            $form->get('ShippingID')->setValue($this->_getShippingsByMer($row['MerchantID']));
            $form->get('PaymentID')->setValue($this->_getPaymentByMid($row['MerchantID']));
        }
        
        $form->get('SiteID')->setValue($params['SiteId']);
        $form->get('AffiliateID')->setSelectOptions($affilites , true);
        $form->get('CategoryID')->setCheckboxOptions($categories);
        $form->get('ShippingID')->setCheckboxOptions($shippings);
        $form->get('PaymentID')->setCheckboxOptions($payments);
        $re['form'] = $form;
        return $re;
    }
    function deleteAction()
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $id = $request->getPost('MerchantID');
        }else{
            $id = $request->getQuery('id');
        }
        if(! $id){
            throw new \Exception('incomplete MerchantID');
        }
        $params = $this->params()->fromQuery();
        $this->_removeMerchant($id);
        
        $this->_message('删除成功');
        return $this->redirect()->toUrl('/merchant/index?' . http_build_query($params));
    }
    
    /**
     * 上线
     */
    function activeAction()
    {
        return $this->_setActive(MerchantTable::MERCHANT_ACTIVE_YES);
    }
    
    /**
     * 下线
     */
    function inactiveAction()
    {
        return $this->_setActive(MerchantTable::MERCHANT_ACTIVE_NO);
    }
    
    /**
     * 设置merchantAlias
     */
    function mappingAction()
    {
        $re = $this->params()->fromQuery();
        $request = $this->getRequest();
        
        if($request->isPost()){
            $params['MerchantAliasName'] = $this->params()->fromPost('MerchantAliasName');
            $params['AffiliateID'] = $this->params()->fromPost('AffiliateID');
            $params['MerchantID'] = $this->params()->fromPost('MerchantID');
            $params['CreateDateTime'] = Utilities::getDateTime();
            
            $table = $this->_getTable('MerchantAliasTable');
            $table->insert($params);
            
            $table = $this->_getTable('UserDataFeedTable');
            $table->updateMerchant($params['MerchantID'] , $params['MerchantAliasName'] , $params['AffiliateID']);
            
            if(! empty($re['referer'])){
                return $this->redirect()->toUrl(Utilities::decode($re['referer']));
            }else{
                return $this->redirect()->toRoute('backend' , array(
                    'controller' => 'merchant',
                    'action' => 'index'
                ));
            }
        }
        
        if(! empty($re['merchantName'])){
            $table = $this->_getMerchantTable();
            $re['list'] = $table->getListByName($re['merchantName']);
        }
        $re['affiliates'] = $this->_getAffilites();
        
        return $re;
    }
    
    /* ----------------- 操作 --------------- */
    
    private function _updateCouponSearch($merchant)
    {
        $merchant = $merchant->toArray();
        $merchantTable = $this->_getMerchantTable();
        $oldMerchant = $merchantTable->getInfoById($merchant['MerchantID']);
        
        if($oldMerchant['MerchantAliasName'] != $merchant['MerchantAliasName']){
            $couponSeachTable = $this->_getTable('CouponSearchTable');
            $merchantAliasName = str_replace(';', ' ', $merchant['MerchantAliasName']);
            $couponSeachTable->upDateMerchantAlias($merchant['MerchantID'] , $merchantAliasName);
        }
        
        return true;
    }
    /**
     * 插入图片
     * @param string $name
     * @return string|NULL
     */
    private function _insertImg($name)
    {
        $files = $this->params()->fromFiles('LogoFile');
        $config = $this->_getConfig('upload');
        $config = $config['merchantLogo'];
        if(! empty($files['name'])){
            return $config['showPath'] . Uploader::upload($files , $name , $config['uploadPath'] , $config);
        }else{
            return $this->params()->fromPost('LogoFile');
        }
        
        return null;
    }
    
    private function _insertMerchantAlias(Merchant $merchant)
    {
        $table = $this->_getTable('MerchantAliasTable');
        return $table->save($merchant->MerchantID , $merchant->MerchantName , $merchant->AffiliateID);
    }
    
    private function _removeMerchant($id)
    {
        $table = $this->_getMerchantTable();
        $table->removeForId($id);
        $table = $this->_getMerchantCategoryTable();
        $table->clear($id);
        return true;
    }
    private function _setActive($state)
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $id = $request->getPost('MerchantID');
        }else{
            $id = $request->getQuery('MerchantID');
        }
        if(! $id){
            throw new \Exception('incomplete MerchantID');
        }
        $params = $this->params()->fromQuery();
        $table = $this->_getMerchantTable();
        $table->changeActive($id , $state);
        
        return $this->redirect()->toUrl('/merchant/index?' . http_build_query($params));
    }
    private function _getMerchantListPaginator($params)
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $merchantTable = $this->_getMerchantTable();
        $merchantTable->formatWhere($params);
        $paginator = new Paginator($merchantTable->getListToPaginator());
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(self::LIMIT);
        
        return $paginator;
    }
    private function _getMerchantList(Paginator $paginator)
    {
        $couponTable = $this->_getCouponTable();
        
        $rows = $merchantIDs = array();
        foreach($paginator->getCurrentItems() as $v){
            $rows[$v->MerchantID] = $v->getArrayCopy();
            $rows[$v->MerchantID]['CouponCount'] = $couponTable->getCouponCountByMer($v->MerchantID);
        }
        
        return $rows;
    }
    private function _saveMerchantCategories($merid, $cates)
    {
        //获取老的category
        $categoryies = $this->_getMarchantCategories($merid);
        foreach ($cates as $catid) {
            if (($catKey = array_search($catid, $categoryies)) !== false) {
                unset($categoryies[$catKey]);
            } else {
                $newCategory[] = $catid;
            }
        }
        $merchantcategroyTable = $this->_getMerchantCategoryTable();
        //商家新增分类
        if ($newCategory) {
            $merchantcategroyTable->save($merid , $newCategory);
        }
        //商家需要删除分类
        if ($categoryies) {
            $merchantcategroyTable->deleteMerCategory($merid , $categoryies);
        }
        return true;
    }
    private function _saveMerchantShipping($mid, $shippings)
    {
        $table = $this->_getMerchantShippingTable();
        return $table->save($mid , $shippings);
    }
    private function _saveMerchantPayment($mid, $payments)
    {
        $table = $this->_getMerchantPaymentTable();
        return $table->save($mid , $payments);
    }
    
    private function _clearMerchantShipping($mid){
        $table = $this->_getMerchantShippingTable();
        return $table->removeByMid($mid);
    }
    private function _clearMerchantPayment($mid){
        $table = $this->_getMerchantPaymentTable();
        return $table->removeByMid($mid);
    }
    
    private function _getCategoryies($params)
    {
        $params['IsActive'] = 'YES';
        $categroyTable = $this->_getCategoryTable();
        $categroyTable->formatWhere($params);
        $categroiesList = $categroyTable->getList();
        $re = array();
        foreach($categroiesList as $categroy){
            $re[$categroy['CategoryID']] = $categroy['CategoryName'];
        }
        
        return $re;
    }
    private function _getAffilites($params = array())
    {
        $params['IsActive'] = 'YES';
        $affliliateTable = $this->_getAffiliateTable();
        $affiliiates = $affliliateTable->formatWhere($params)->getList();
        $re = array();
        foreach($affiliiates as $v){
            $re[$v['ID']] = $v['Name'];
        }
        return $re;
    }
    private function _getMarchantCategories($mid)
    {
        $merchantCategoryTable = $this->_getMerchantCategoryTable();
        return $merchantCategoryTable->getCategoriesByMid($mid);
    }
    private function _getShippingsByMer($mid)
    {
        $table = $this->_getMerchantShippingTable();
        return $table->getShippingByMid($mid);
    }
    private function _getPaymentByMid($mid)
    {
        $table = $this->_getMerchantPaymentTable();
        return $table->getPaymentByMid($mid);
    }
    private function _getShippings()
    {
        $table = $this->_getShippingTable();
        
        $re = array();
        foreach($table->getList() as $v){
            $re[$v['ShippingID']] = $v['Name'];
        }
        
        return $re;
    }
    private function _getPayments($siteId)
    {
        $table = $this->_getPaymentTable();
        
        $re = array();
        foreach($table->getPaymentBySiteId($siteId) as $v){
            $re[$v['PaymentID']] = $v['Name'];
        }
        
        return $re;
    }
    
    /* --------------------- 获取表 ------------------------ */
    private function _getCategoryTable()
    {
        return $this->getServiceLocator()->get('CategoryTable');
    }
    private function _getMerchantTable()
    {
        return $this->getServiceLocator()->get('MerchantTable');
    }
    private function _getAffiliateTable()
    {
        return $this->getServiceLocator()->get('AffiliateTable');
    }
    private function _getCouponTable()
    {
        return $this->getServiceLocator()->get('CouponTable');
    }
    private function _getMerchantCategoryTable()
    {
        return $this->getServiceLocator()->get('MerchantCategoryTable');
    }
    private function _getMerchantPaymentTable()
    {
        return $this->getServiceLocator()->get('MerchantPaymentTable');
    }
    private function _getPaymentTable()
    {
        return $this->getServiceLocator()->get('PaymentTable');
    }
    private function _getMerchantShippingTable()
    {
        return $this->getServiceLocator()->get('MerchantShippingTable');
    }
    private function _getShippingTable()
    {
        return $this->getServiceLocator()->get('ShippingTable');
    }
}