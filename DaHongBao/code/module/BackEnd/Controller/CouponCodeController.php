<?php
/**
* CouponCodeController.php
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
* @version CVS: $Id: CouponCodeController.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Controller;

use BackEnd\Model\Coupon\CouponCode;

use Custom\Util\Utilities;

use Custom\Mvc\Controller\AbstractActionController;

class CouponCodeController extends AbstractActionController
{
    /**
     * Code列表
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
    function indexAction()
    {
        $params = $this->params()->fromQuery();
        if(empty($params['CouponID'])){
            throw new \Exception('incompele CouponID');
        }
        
        $re = $params;
        $re['list'] = $this->_getCodes($params['CouponID']);
        $re['uri'] = Utilities::encode($_SERVER['REQUEST_URI']);
        
        return $re;
    }
    
    /**
     * 设置Code数量
     * @throws \Exception
     */
    function setCountAction()
    {
        $uri = Utilities::decode($this->params()->fromQuery('uri'));
        $request = $this->getRequest();
        if($request->isPost()){
            $id = $this->params()->fromPost('CouponCodeID');
            $count = $this->params()->fromPost('CouponCodeTotalCnt');
            $addCount = $count - $this->params()->fromPost('OldCount');
            
            $couponId = $this->params()->fromPost('CouponID');
            $this->_setCount($id, $count);
            $this->_updateCodeCount($couponId , $addCount);
            return $this->redirect()->toUrl($uri);
        }
        
        throw new \Exception('no post');
    }
    
    /**
     * 新Code
     * @throws \Exception
     */
    function saveAction(){
        $uri = Utilities::decode($this->params()->fromQuery('uri'));
        $request = $this->getRequest();
        
        if($request->isPost()){
            $code = trim($this->params()->fromPost('CouponCode'));
            if(empty($code)){
                throw new \Exception('incomplete CouponCode : ' . $code);
            }
            $couponId = $this->params()->fromPost('CouponID');
            
            $count = $this->_saveCode($couponId, $code);
            if($count != 0){
                $this->_updateCodeCount($couponId, $count);
            }
            
            return $this->redirect()->toUrl($uri);
        }
        
        throw new \Exception('no post');
    }
    
    function deleteAction()
    {
        
    }
    
    /**
     * 获取Codes
     * @param int $couponId
     */
    private function _getCodes($couponId)
    {
        $couponCodeTable = $this->_getTable('CouponCodeTable');
        return $couponCodeTable->getCodeByCouponId($couponId);
    }
    
    /**
     * 更新数量
     * @param int $codeId
     * @param int $count
     */
    private function _setCount($codeId , $count)
    {
        $couponCodeTable = $this->_getTable('CouponCodeTable');
        return $couponCodeTable->setCouponCodeTotalCnt($codeId , $count);
    }
    
    /**
     * 保存Code
     * @param int $couponId
     * @param string $code
     */
    private function _saveCode($couponId , $code)
    {
        // 插入CouponCodeTable
        $codeTable = $this->_getTable('CouponCodeTable');
        $count = $codeTable->saveCouponCodes(CouponCode::csvToArray($code , $couponId));
    }
    
    /**
     * 更新扩展表数量
     * @param int $couponId
     * @param int $count
     */
    private function _updateCodeCount($couponId , $count){
        // 插入CouponExtra表
        $couponExtraTable = $this->_getTable('CouponExtraTable');
        $couponExtraTable->save($couponId , $count);
    }
}