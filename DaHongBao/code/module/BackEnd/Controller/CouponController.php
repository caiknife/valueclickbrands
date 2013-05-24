<?php

/**
* CouponController.php
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
* @version CVS: $Id: CouponController.php,v 1.17 2013/05/03 10:24:13 thomas_fu Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Controller;
use Custom\File\Uploader;

use Zend\Validator\File\Size;

use Zend\File\Transfer\Adapter\Http;

use BackEnd\Form\CouponForm;

use BackEnd\Model\Coupon\CouponCode;
use BackEnd\Model\Coupon\Coupon;
use Custom\Util\Utilities;
use Custom\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
class CouponController extends AbstractActionController
{
    private $_message;
    private $couponTypes = array(
        'COUPON',
        'DISCOUNT',
        'ACTIVITY',
        'OTHER'
    );
    private $requiredUserDataFeed = array(
        'MerchantID',
        'MerchantFeedName',
        'CouponName',
        'CouponType',
        'CouponStartDate'
    ); // 必须的UserDateFeed字段
    
    function indexAction(){
        return $this->redirect()->toRoute(null , array('controller' => 'coupon' ,'action' => 'couponList'));
    }
    /**
     * 待审核Coupon
     *
     * 
     */
    function userDataFeedAction()
    {
        $params = $this->params()->fromQuery();
        $affiliates = $this->_getAffiliates();
        
        $params['AffiliateID'] = isset($params['AffiliateID']) ? $params['AffiliateID'] : '';
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        
        $re = array(
            'affiliates' => $affiliates,
            'couponTypes' => $this->couponTypes,
            'list' => $this->_getUserDataFeed($params),
            'uri' => Utilities::encode($_SERVER['REQUEST_URI'])
        );
        unset($params['page']);
        
        $re['query'] = http_build_query($params);
        $re['uri'] = Utilities::encode($_SERVER['REQUEST_URI']);
        
        return array_merge($params , $re);
    }
    
    /**
     * Coupon列表
     */
    function couponListAction()
    {
        $re = array();
        $params = $this->params()->fromQuery();
        $params['SiteID'] = empty($params['SiteID']) ? 1 : $params['SiteID'];
        $params['IsActive'] = empty($params['IsActive'])? 'YES' : $params['IsActive'];
        
        $re['categories'] = $this->_getCategories($params['SiteID']);
        $re['affiliates'] = $this->_getAffiliates();
        $re['couponTypes'] = $this->couponTypes;
        $re['sites'] = $this->sites;
        $re['uri'] = Utilities::encode($_SERVER['REQUEST_URI']);

        unset($params['page']);
        $re['query'] = http_build_query($params);
        
        $re['list'] = $this->_getCoupons();
        $re = array_merge($params , $re);
        
        return $re;
    }
    
    /**
     * 编辑新建Coupon
     */
    function saveAction()
    {
        $request = $this->getRequest();
        $params = $this->params()->fromQuery();
        $form = new CouponForm();
        
        
        if($request->isPost()){
            $coupon = new Coupon();
            $form->setData($this->params()->fromPost());
            $form->setInputFilter($coupon->getInputFilter());
            if($form->isValid() && $this->_validation($this->params()->fromPost())){
                $data = $form->getData();
                if(empty($data['CouponID'])){
                    $action = 'insert';
                    $data['CouponID'] = $this->_addCoupon($data);
                    
                    if(!empty($data['UserDataFeedId'])){
                        $userDataFeedTable = $this->_getUserDataFeedTable();
                        // 删除UserDataFeed
                        $userDataFeedTable->statusOnline($data['UserDataFeedId']);
                    }
                }else{
                    $action = 'update';
                    $this->_updateCoupon($data);
                }

                //插入图片
                $data['CouponImageUrl'] = $this->_insertImg($data['CouponID']);
                //更新表
                if($data['CouponImageUrl']){
                    $this->_updateCouponImage($data['CouponID'] , $data['CouponImageUrl'] );
                }
                
                
                $referer = $this->params()->fromQuery('referer');
                
                //新生成Form
                if('insert' == $action && empty($data['UserDataFeedId'])){
                    $form = new CouponForm();
                    $form->get('MerchantID')->setValue($data['MerchantID']);
                    $form->get('CategoryID')->setCheckboxOptions($this->_getMerchantCate($data['MerchantID']));
                    $form->get('CategoryID')->setValue($data['CategoryID']);
                    $form->get('AffiliateID')->setSelectOptions($this->_getAffiliates());
                    $form->get('AffiliateID')->setValue($data['AffiliateID']);
                    $form->get('SiteID')->setValue($data['SiteID']);
                    $re['MerchantName'] = $this->params()->fromPost('MerchantName');
                    $re['form'] = $form;
                    $re['referer'] = $referer;
                    
                    $this->_message('添加成功');
                    return $re;
                }

                $this->_message('编辑成功');
                if(!empty($referer)){
                    $url = Utilities::decode($referer);
                    return $this->redirect()->toUrl($url);
                }else{
                    return $this->redirect()->toRoute(null , array('controller' => 'coupon' , 'action' 
                        => 'couponList'));
                }
                
            }else{
                $params['message'] = $this->_message;
            }
        }
        

        $params['SiteID'] = isset($params['SiteID']) ? $params['SiteID'] : 1;
        $form->get('SiteID')->setValue($params['SiteID']);
        //来自UserDataFeed
        if($userDataFeedId = $this->params()->fromQuery('userDataFeedId')){
            $userDataFeedTable = $this->_getUserDataFeedTable();
            $row = $userDataFeedTable->getInfoByID($userDataFeedId);
            // 检查数据完整性
            //$this->_checkUserDataFeed($row);
            // 数据格式化
            $row = $this->_formatUserDataFeedToCoupon($row);
            $row['UserDataFeedId'] = $userDataFeedId;
            
            // 如果CategoryName存在,CouponCategory以CategoryName为准
            if(! empty($row['CategoryName'])){
                $categoryTable = $this->_getCategoryTable();
                $categories[] = $categoryTable->getIdByName($row['CategoryName']);
            }
            
            // 如果CategoryID存在,CouponCategory以CategoryID为准
            if(! empty($row['CategoryID'])){
                $categories = $row['CategoryID'];
            }
            // 如果不存在，以商家类别为准
            if(empty($categories)){
                $merchantCategoryTable = $this->_getTable('MerchantCategoryTable');
                $categories = $merchantCategoryTable->getCategoriesByMid($row['MerchantID']);
            }
            $row['CategoryID'] = $categories;
            $form->setData($row);
            $params['SiteID'] = $row['SiteID'];
            
            //获取商家名
            if($row['MerchantID']){
                $mid = $row['MerchantID'];
                $params['MerchantName'] = $this->_getMerchantName($row['MerchantID']);
            }
            
        }elseif($couponId = $this->params()->fromQuery('CouponID')){//来自CouponList
        
            $coupon = $this->_getCoupon($couponId);
            $params['SiteID'] = $coupon['SiteID'];
            $form->setData($coupon);
            //获取商家名
            if($coupon['MerchantID']){
                $mid = $coupon['MerchantID'];
                $params['MerchantName'] = $this->_getMerchantName($coupon['MerchantID']);
            }
        }
        
        $params['SiteID'] = $this->params()->fromQuery('SiteID' , $params['SiteID']);
        $form->get('SiteID')->setValue($params['SiteID']);
        
        $categories = $this->_getCategories($params['SiteID']);
        $affiliates = $this->_getAffiliates();
        
        if(isset($mid)){
            $form->get('CategoryID')->setCheckboxOptions($this->_getMerchantCate($mid));
        }else{
            $form->get('CategoryID')->setCheckboxOptions($categories);
        }
        $form->get('AffiliateID')->setSelectOptions($affiliates);
        
        unset($params['SiteID']);
        $re = $params;
        $re['query'] = http_build_query($params);
        $re['form'] = $form;
        return $re;
    }
    
    
    /**
     * 审核通过
     */
    function userDataFeedToCouponAction()
    {
        $request = $this->getRequest();
        $params = $request->getQuery();
        $referer = Utilities::decode($this->params()->fromQuery('referer' , '/'));
        
        if($request->isPost()){
            $id = $request->getPost('ID');
        }else{
            $id[] = $request->getQuery('ID');
        }
        $userDataFeedTable = $this->_getUserDataFeedTable();
        
        foreach($id as $v){
            $row = $userDataFeedTable->getInfoByID($v);
            // 检查数据完整性
            if(!$row = $this->_checkUserDataFeed($row)){
                return $this->redirect()->toUrl($referer);
            }
            
            // 数据格式化
            $this->_addCoupon($this->_formatUserDataFeedToCoupon($row));
            
            // 更改UserDataFeed状态
            $userDataFeedTable->statusOnline($v);
        }
        
        unset($params['ID']);
        $this->_message('审核通过');
        return $this->redirect()->toUrl($referer);
    }
    
    /**
     * 删除临时表
     * @throws \Exception
     */
    function userDataFeedDeleteAction(){
        $referer = Utilities::decode($this->params()->fromQuery('referer' , '/'));
        $id = $this->params()->fromQuery('ID');
        if(!$id){
            throw new \Exception('缺失参数：ID');
        }
        
        $table = $this->_getTable('UserDataFeedTable');
        $table->statusDelete($id);
        $this->_message('删除成功');
        
        return $this->redirect()->toUrl($referer);
    }
    
    /**
     * 下线
     */
    function inActiveAction()
    {
        $this->_message('下线成功');
        return $this->_changeActive('NO');
    }
    
    /**
     * 上线
     */
    function activeAction()
    {
        $this->_message('上线成功');
        return $this->_changeActive('YES');
    }
    
    /**
     * 删除
     */
    function deleteAction(){
        if(!$id = $this->params()->fromQuery('CouponID')){
            throw new \Exception('缺少的参数：CouponID');
        }
        $this->_message('删除成功');
        return $this->_deleteCoupon($id);
    }
    /* ------------------ 操作 ------------------- */
    
    /**
     * 提交验证
     * @param array $data
     * @return boolean
     */
    private function _validation($data){
        $result = true;
        $this->_message = array();
        if(!empty($data['CouponEndDate']) && $data['CouponStartDate'] >= $data['CouponEndDate']){
            $this->_message['CouponStartDate'] = '开始时间晚于结束时间';
            $result = false;
        }
        
        return $result;
    }
    /**
     * 删除
     * @param int $id
     */
    private function _deleteCoupon($id){
        $couponTable = $this->_getTable('CouponTable');
        $couponCateTable = $this->_getTable('CouponCategoryTable');
        $coupon = $couponTable->getCouponById($id);
        $couponId = $coupon['CouponID'];
        $couponCates = $couponCateTable->getListByCouponId($coupon['CouponID']);
        
        
        //修改MerchantCategory表
        $merchantCateTable = $this->_getTable('MerchantCategoryTable');
        if('COUPON' == $coupon['CouponType']){
            foreach($couponCates as $v){
                $result = $merchantCateTable->getInfoByMidAndCid($coupon['MerchantID'] , $v['CategoryID']);
                if($result['r_OnlineCouponCount'] > 0){
                    $merchantCateTable->replace($coupon['MerchantID'] , $v['CategoryID'] 
                        , $result['r_OnlineCouponCount'] - 1 , $result['r_OnlineDiscountCount']);
                }
            }
        }else{
            foreach($couponCates as $v){
                $result = $merchantCateTable->getInfoByMidAndCid($coupon['MerchantID'] , $v['CategoryID']);
                if($result['r_OnlineDiscountCount'] > 0){
                    $merchantCateTable->replace($coupon['MerchantID'] , $v['CategoryID']
                        , $result['r_OnlineCouponCount'] , $result['r_OnlineDiscountCount'] - 1);
                }
            }
        }
        
        //删除CouponSearch表
        $couponSearchTable = $this->_getTable('CouponSearchTable');
        $couponSearchTable->deleteByCid($couponId);
        
        //删除CouponExtra表
        $couponExtraTable = $this->_getTable('CouponExtraTable');
        $couponExtraTable->deleteByCid($couponId);
        
        //删除CouponCode表
        $couponCodeTable = $this->_getTable('CouponCodeTable');
        $couponCodeTable->deleteByCid($couponId);
        
        //删除CouponCategory表
        $couponCategoryTable = $this->_getTable('CouponCategoryTable');
        $couponCategoryTable->clearByCouponId($couponId);
        
        //删除Coupon表
        $couponTable = $this->_getTable('CouponTable');
        $couponTable->deleteByCid($couponId);
        
        return $this->redirect()->toUrl(Utilities::decode($this->params()->fromQuery('referer' , '/')));
    }
    /**
     * 获取商家名
     * @param int $id
     * @return string
     */
    private function _getMerchantName($id){
        $table = $this->_getTable('MerchantTable');
        $merchant = $table->getInfoById($id);
        return $merchant['MerchantName'];
    }
    /**
     * 插入图片
     * @param string $name
     * @return string|NULL
     */
    private function _insertImg($name)
    {
        $config = $this->_getConfig('upload');
        $config = $config['conpun'];
        $files = $this->params()->fromFiles('CouponImageUrl');
        if(! empty($files['name'])){
            return $config['showPath'] . Uploader::upload($files , $name , $config['uploadPath'] , $config);
        }else{
            return $this->params()->fromPost('CouponImageUrl');
        }
        
        return null;
    }
    
    /**
     * 获取单条Coupon
     * @param int $id
     * @throws \Exception
     * @return array
     */
    private function _getCoupon($id)
    {
        $id = (int)$id;
        if(empty($id)){
            throw new \Exception('incomplete id');
        }
        
        $couponTable = $this->_getCouponTable();
        
        $coupon = $couponTable->getCouponById($id);
        //判断是否不限时
        if('3333-03-03 00:00:00' == $coupon['CouponEndDate']){
            $coupon['CouponEndDate'] = '';
        }
        $coupon['CategoryID'] = $this->_getCouponCategories($id);
        
        return $coupon;
    }
    /**
     * 变更上线状态
     * @param string $state
     * @throws \Exception
     * @return boolean
     */
    private function _changeActive($state)
    {
        $request = $this->getRequest();
        if($request->isPost()){
            $id = $this->params()->fromPost('CouponID');
        
        }else{
            $id[] = $this->params()->fromQuery('CouponID');
        }
        
        if(empty($id[0])){
            throw new \Exception('incomplete CouponID');
        }
        $couponTable = $this->_getCouponTable();
        $couponTable->changeActive($id , $state);
        
        return $this->redirect()->toUrl(Utilities::decode($this->params()->fromQuery('uri')));
    }
    /**
     * 获取Coupon分页
     * @return \Zend\Paginator\Paginator
     */
    private function _getCoupons()
    {
        $params = $this->params()->fromQuery();
        $page = empty($params['page'])? 1 : $params['page'];
        $params['SiteID'] = isset($params['SiteID']) ? $params['SiteID'] : 1;
        
        $couponTable = $this->_getCouponTable();
        $couponTable->formatWhere($params);
        
        $paginator = new Paginator($couponTable->getCouponsForPaginator());
        $paginator->setItemCountPerPage(self::LIMIT);
        $paginator->setCurrentPageNumber($page);
        
        return $paginator;
    }
    
    /**
     * 新增一条Coupon
     *
     * @param array $data            
     */
    private function _addCoupon($row)
    {
        if(isset($row['errorMsg'])){
            throw new \Exception(implode(',' , $row['errorMsg']));
        }
        // 检查Coupon表里是否存在
        // 生成MD5格式的SKU
        if(empty($row['SKU']) || ! $couponID = $this->_checkSKU($row['SKU'])){
            // 不存在，插入Coupon表，返回ID
            $couponTable = $this->_getCouponTable();
            $coupon = new Coupon();
            $coupon->exchangeArray($row);
            $couponID = $couponTable->insert($coupon->toArray());
        }
        $row['CouponID'] = $couponID;
        
        // 如果CategoryName存在,CouponCategory以CategoryName为准
        if(! empty($row['CategoryName'])){
            $categoryTable = $this->_getCategoryTable();
            $categories[] = $categoryTable->getIdByName($row['CategoryName']);
        }
        
        // 如果CategoryID存在,CouponCategory以CategoryID为准
        if(! empty($row['CategoryID'])){
            $categories = $row['CategoryID'];
        }
        // 如果不存在，以商家类别为准
        if(empty($categories)){
            $merchantCategoryTable = $this->_getTable('MerchantCategoryTable');
            $categories = $merchantCategoryTable->getCategoriesByMid($row['MerchantID']);
        }
        // 插入CouponCategory表
        $couponCategoryTable = $this->_getCouponCategoryTable();
        foreach($categories as $v){
            $couponCategoryTable->insertCategory($couponID , $v);
        }
        
        // 插入CouponCode表
        // 插入CouponExtra表
        $couponExtraTable = $this->_getCouponExtraTable();
        if(! empty($row['CouponCode'])){
            $couponCodeTable = $this->_getCouponCodeTable();
            $couponCodes = CouponCode::csvToArray($row['CouponCode'] , $couponID);
            if(count($couponCodes) > 0){
                $addCount = $couponCodeTable->saveCouponCodes($couponCodes);
                $couponExtraTable->save($couponID , $addCount);
            }
        }else{
            $couponExtraTable->save($couponID , 0);
        }
        
        // 插入CouponOperateDetail表
        
        $couponOperateDetailTable = $this->_getCouponOperateDetailTable();
        $couponOperateDetailTable->insert($couponID , $_SESSION['user']['Name'] , 'INSERT');
        // 插入CouponSearch表
        
        $couponSearchTable = $this->_getCouponSearchTable();
        if(! $couponSearchTable->hasInfoByCouponId($couponID)){
            $merchantTable = $this->_getMerchantTable();
            $merchant = $merchantTable->getNameByMerID($row['MerchantID']);
            $row['MerchantName'] = $merchant['MerchantName'];
            $row['MerchantNameEN'] = $merchant['MerchantNameEN'];
            $row['MerchantAliasName'] = $merchant['MerchantAliasName'];
            $couponSearchTable->insert($row);
        }
        
        // 插入MerchantCategory表
        $couponCnt = $discountCnt = 0;
        if('COUPON' == $row['CouponType']){
            $couponCnt ++;
        }elseif('DISCOUNT' == $row['CouponType']){
            $discountCnt ++;
        }
        
        $merchantCategoryTable = $this->_getTable('MerchantCategoryTable');
        foreach($categories as $v){
            $result = $merchantCategoryTable->getInfoByMidAndCid($row['MerchantID'] , $v);
            
            $merchantCategoryTable->replace($row['MerchantID'] , $v , $result['r_OnlineCouponCount'] + $couponCnt 
                , $result['r_OnlineDiscountCount'] + $discountCnt);
        }
        
        return $couponID;
    }
    
    /**
     * 更新图片
     * @param int $couponId
     * @param string $imageFile
     */
    private function _updateCouponImage($couponId , $imageFile){
        $couponTable = $this->_getCouponTable();
        return $couponTable->updateImage($couponId , $imageFile);
    }
    /**
     * 更新Coupon
     * @param array $row
     */
    private function _updateCoupon($row){
        //获取更新之前数据
        $couponTable = $this->_getCouponTable();
        $oldData = $couponTable->getCouponById($row['CouponID']);
        
        //更新Coupon表
        $coupon = new Coupon();
        $coupon->exchangeArray($row);
        $couponTable = $this->_getCouponTable();
        $couponTable->save($coupon->toArray());
        
        //更新CouponCategory表
        $couponCategoryTable = $this->_getCouponCategoryTable();
        $oldCatList = $this->_getCouponCategories($row['CouponID']);
        $couponCategoryTable->save($row['CouponID'] , $row['CategoryID']);
        
        //更新CouponSearch表
        $couponSearchTable = $this->_getCouponSearchTable();
        $merchantTable = $this->_getMerchantTable();
        $merchantName = $merchantTable->getNameByMerID($row['MerchantID']);
        $row['MerchantName'] = $merchantName['MerchantName'];
        $row['MerchantNameEN'] = $merchantName['MerchantNameEN'];
        $row['MerchantAliasName'] = $merchantName['MerchantAliasName'];
        $couponSearchTable->save($row);
        
        //插入记录
        $couponOperateDetailTable = $this->_getCouponOperateDetailTable();
        $couponOperateDetailTable->insert($row['CouponID'] , $_SESSION['user']['Name'] , 'UPDATE');
        
        //by thomas 判断是否修改Category
        $hasModifyCategory = false;
        if (array_diff($oldCatList, $row['CategoryID'])) {
            $hasModifyCategory = true;
        }
        //by thomas 检查商家是否有变化  且 检查couponType是否有变化
        if ($oldData['MerchantID'] != $row['MerchantID'] 
            || $oldData['CouponType'] != $row['CouponType'] 
            || $hasModifyCategory === true) {
            $merchantCategoryTable = $this->_getTable('MerchantCategoryTable');
            //原有的MerchantcategoryID数量减一
            foreach ($oldCatList as $catid) {
                $couponCntInfo = $merchantCategoryTable->getInfoByMidAndCid(
                    $oldData['MerchantID'],
                    $catid
                );
                if ($oldData['CouponType'] == 'COUPON') {
                    $couponCountCnt = $couponCntInfo['r_OnlineCouponCount'] - 1;
                    $discountCnt = $couponCntInfo['r_OnlineDiscountCount'];
                } else {
                    $couponCountCnt = $couponCntInfo['r_OnlineCouponCount'];
                    $discountCnt = $couponCntInfo['r_OnlineDiscountCount'] - 1;
                }
                $merchantCategoryTable->replace(
                    $oldData['MerchantID'],
                    $catid,
                    $couponCountCnt, 
                    $discountCnt
                );
            }
            
            //更新新的merchantCategory
            foreach ($row['CategoryID'] as $catid) {
                $couponCntInfo = $merchantCategoryTable->getInfoByMidAndCid($row['MerchantID'] , $catid);
                if ($row['CouponType'] == 'COUPON') {
                    $couponCountCnt = $couponCntInfo['r_OnlineCouponCount'] + 1;
                    $discountCnt = $couponCntInfo['r_OnlineDiscountCount'];
                } else {
                    $couponCountCnt = $couponCntInfo['r_OnlineCouponCount'];
                    $discountCnt = $couponCntInfo['r_OnlineDiscountCount'] + 1;
                }
                $merchantCategoryTable->replace(
                    $row['MerchantID'], 
                    $catid, 
                    $couponCountCnt,
                    $discountCnt
                );
            }
        }
        return true;
    }
    
    /**
     * 检查UserDataFeed数据完整性
     *
     * @param array $data            
     * @return array
     */
    private function _checkUserDataFeed(array $data)
    {
        $status = true;
        //检查参数
        foreach($this->requiredUserDataFeed as $v){
            if(empty($data[$v])){
                $status = false;
                $this->_message('缺失参数：' . $v , self::MSG_ERROR);
            }
        }
        
        //检查Code
        if(! empty($data['CouponCode'])){
            $codes = CouponCode::csvToArray($data['CouponCode']);
            if($codes < 1){
                $status = false;
                $this->_message('错误的CouponCode' , self::MSG_ERROR);
            }
        }
        
        if(!empty($data['CouponStartDate']) && !empty($data['CouponEndDate'])){
            if($data['CouponStartDate'] > $data['CouponEndDate']){
                $status = false;
                $this->_message('开始时间晚于结束时间' , self::MSG_ERROR);
            }
        }
        if($status){
            return $data;
        }else{
            return false;
        }
    }
    /**
     * 格式转换
     *
     * @param array $row            
     */
    private function _formatUserDataFeedToCoupon($data)
    {
        $table = $this->_getAffiliateTable();
        $coupon = array();
        $coupon['MerchantID'] = $data['MerchantID'];
        $coupon['AffiliateID'] = $data['AffiliateID'];
        $coupon['CouponName'] = $data['CouponName'];
        $coupon['CouponDescription'] = $data['CouponDescription'];
        $coupon['CouponRestriction'] = $data['CouponRestriction'];
        $coupon['CouponUrl'] = $data['CouponUrl'];
        $coupon['IsAffiliateUrl'] = $data['IsAffiliateUrl'] == 'YES' ? 'YES' : 'NO';
        $coupon['CouponImageUrl'] = $data['CouponImageUrl'];
        $coupon['CouponStartDate'] = !empty($data['CouponStartDate']) ? $data['CouponStartDate'] 
        : Utilities::getDateTime('Y-m-d');
        $coupon['CouponEndDate'] = !empty($data['CouponEndDate']) ? $data['CouponEndDate'] : '';
        $coupon['CouponType'] = $data['CouponType'];
        $coupon['CouponAmount'] = $data['CouponAmount'];
        $coupon['CouponReduction'] = $data['CouponReduction'];
        $coupon['CouponDiscount'] = $data['CouponDiscount'];
        $coupon['CouponBrandName'] = $data['CouponBrandName'];
        $coupon['SKU'] = $data['SKU'];
        $coupon['IsFree'] = '';
        $coupon['IsFromCmus'] = $data['IsFromCmus'];
        $coupon['IsActive'] = 'YES';
        $coupon['IsPromote'] = 'NO';
        $coupon['SiteID'] = $table->getSiteIDById($data['AffiliateID']);
        $coupon['InsertDateTime'] = Utilities::getDateTime();
        $coupon['CouponCode'] = trim($data['CouponCode']);
        return $coupon;
    }
    
    /**
     * 检查COUPON是否存在
     *
     * @param array $row            
     * @return int boolean
     */
    private function _checkSKU($SKU)
    {
        $couponTable = $this->_getCouponTable();
        
        $row = $couponTable->getInfoBySKU($SKU);
        if(! empty($row['CouponID'])){
            return $row['CouponID'];
        }
        
        return false;
    }
    /**
     * 获取分类列表
     *
     * @param int $siteId            
     * @return array
     */
    private function _getCategories($siteId)
    {
        $re = array();
        
        $table = $this->_getCategoryTable();
        $categories = $table->getCategoryListBySiteId($siteId);
        
        foreach($categories as $v){
            $re[$v['CategoryID']] = $v['CategoryName'];
        }
        
        return $re;
    }
    
    /**
     * 获取Affiliate
     *
     * @return array
     */
    private function _getAffiliates()
    {
        $re = array();
        
        $table = $this->_getAffiliateTable();
        $affiliates = $table->getList();
        $re[0] = '无';
        foreach($affiliates as $v){
            $re[$v['ID']] = $v['Name'];
        }
        
        return $re;
    }
    
    /**
     * 根据名字查找商家
     *
     * @param string $name            
     * @return array
     */
    private function _getMerchants($name)
    {
        $re = array();
        
        $table = $this->_getMerchantTable();
        $merchants = $table->getListByName($name);
        
        foreach($merchants as $v){
            $re[$v['MerchantID']] = $v;
        }
        
        return $re;
    }
    
    /**
     * 查找未审核表
     *
     * @param array $params            
     */
    private function _getUserDataFeed($params)
    {
        $table = $this->_getUserDataFeedTable();
        $table->formatWhere($params);
        
        $re = new Paginator($table->getListToPaginator());
        $re->setDefaultItemCountPerPage(self::LIMIT);
        $re->setCurrentPageNumber($params['page']);
        
        return $re;
    }
    
    /**
     * 获取CouponCategoris
     *
     * @param int $couponId            
     * @return array
     */
    private function _getCouponCategories($couponId)
    {
        $re = array();
        $table = $this->_getCouponCategoryTable();
        $categories = $table->getListByCouponId($couponId);
        
        foreach($categories as $v){
            $re[] = $v['CategoryID'];
        }
        
        return $re;
    }
    
    /**
     * 根据CouponID获取Code
     *
     * @param int $couponId            
     */
    private function _getCouponCodes($couponId)
    {
        $table = $this->_getCouponCodes($couponId);
        return $table->getCodeByCouponId($couponId);
    }
    
    /**
     * 获取商家Category
     * @return string|multitype:unknown
     */
    private function _getMerchantCate($mid){
        $table = $this->_getTable('MerchantCategoryTable');
    
        $cateids = $table->getCategoriesByMid($mid);
        if(!$cateids){
            return '';
        }
    
        $table = $this->_getTable('CategoryTable');
        $re = array();
    
        foreach($cateids as $v){
            $cate = $table->getInfoById($v);
    
            if($cate){
                $re[$v] = $cate['CategoryName'];
            }else{
                continue;
            }
        }
    
        return $re;
    }
    
    /* ----------------- 获取表 ------------------ */
    private function _getCouponTable()
    {
        return $this->getServiceLocator()->get('CouponTable');
    }
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
    private function _getUserDataFeedTable()
    {
        return $this->getServiceLocator()->get('UserDataFeedTable');
    }
    private function _getCouponCategoryTable()
    {
        return $this->getServiceLocator()->get('CouponCategoryTable');
    }
    private function _getCouponCodeTable()
    {
        return $this->getServiceLocator()->get('CouponCodeTable');
    }
    private function _getCouponExtraTable()
    {
        return $this->getServiceLocator()->get('CouponExtraTable');
    }
    private function _getCouponOperateDetailTable()
    {
        return $this->getServiceLocator()->get('CouponOperateDetailTable');
    }
    private function _getCouponSearchTable()
    {
        return $this->getServiceLocator()->get('CouponSearchTable');
    }
}