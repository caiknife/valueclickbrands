<?php
/**
* SmcnMerFeedController.php
*-------------------------
*
* SMCN的商家手动导入到大红包
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
* @version CVS: $Id: SmcnMerFeedController.php,v 1.1 2013/05/20 02:44:46 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Controller;

use Custom\Mvc\Controller\AbstractActionController;
use Custom\Util\CURL;

class SmcnMerFeedController extends AbstractActionController
{
    const SMCNLOGOURL = 'http://images.smarter.com.cn/other_images/merchant_images/';
    
    function indexAction()
    {
        if($type = $this->params()->fromQuery('type')){
            if(!$value = $this->params()->fromQuery('value')){
                $this->_message('搜索内容不能为空' , self::MSG_ERROR);
                return $this->redirect()->toRoute(null , array('controller' => 'SmcnMerFeed'));
            }else{
                $table = $this->_getTable('SmcnMerchantTable');
                $merchantTable = $this->_getTable('MerchantTable');
                
                if('id' == $type){
                    $re = $table->getListByID($value);
                    
                }elseif('name' == $type){
                    $re = $table->getListByName($value);
                }
                
                $result['list'] = array();
                foreach($re as $v){
                    if(!$merchantTable->getInfoById($v['MerchantID'])){
                        $result['list'][] = $v;
                    }
                }
                return $result;
            }
        }
        
        return array();
    }
    
    function insertAction()
    {
        if(!$id = $this->params()->fromQuery('id')){
            $this->_message('ID不能为空' , self::MSG_ERROR);
            return $this->redirect()->toRoute(null , array('controller' => 'SmcnMerFeed'));
        }
        
        $table = $this->_getTable('SmcnMerchantTable');
        $merchant = $table->getInfoByID($id);
        
        if(!$merchant){
            $this->_message('商家不存在' , self::MSG_ERROR);
            return $this->redirect()->toRoute(null , array('controller' => 'SmcnMerFeed'));
        }
        
        if($merchant['LogoFile']){
            $merchant['LogoFile'] = $this->_copyLogo($merchant['MerchantID'], $merchant['LogoFile']);
        }
        
        $table = $this->_getTable('MerchantTable');
        try{
            $table->insert($merchant);
            
            $this->_message('添加成功' , self::MSG_SUCCESS);
        }catch(\Exception $e){
            $this->_message($e->getMessage() , self::MSG_ERROR);
        }
        return $this->redirect()->toRoute(null , array('controller' => 'SmcnMerFeed'));
        
    }
    
    private function _copyLogo($merchantId , $logo)
    {
        $curl = new CURL();
        $uploadConfig = $this->_getConfig('upload');
        $postfix = pathinfo($logo , PATHINFO_EXTENSION);
        
        $url = self::SMCNLOGOURL . $logo;
        $filepath = $uploadConfig['merchantLogo']['uploadPath'] . $merchantId . '.' . $postfix;
        
        if($curl->download($url, $filepath)){
            return $uploadConfig['merchantLogo']['showPath'] . $merchantId . '.' . $postfix;
        }else{
            return '';
        }
    }
}