<?php
/**
* AjaxController.php
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
* @version CVS: $Id: AjaxController.php,v 1.2 2013/04/17 06:45:05 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Controller;
use Custom\Mvc\Controller\AbstractActionController;

class AjaxController extends AbstractActionController
{
    /**
     * 获取商家
     */
    function getMerchantsAction(){
        $table = $this->_getTable('MerchantTable');
        $table->formatWhere($this->params()->fromQuery());
        
        $re = '';
        foreach($table->getList() as $k => $v){
            $row['MerchantID'] = $v['MerchantID'];
            $row['MerchantName'] = $v['MerchantName'];
            $row['AffiliateID'] = $v['AffiliateID'];
            $re[] = $row;
        }
        
        echo json_encode($re);
        exit;
    }
    
    /**
     * 获取商家Category
     * @return string|multitype:unknown
     */
    function getMerchantCateAction(){
        $table = $this->_getTable('MerchantCategoryTable');
        $mid = $this->params()->fromQuery('mid');
        
        $cateids = $table->getCategoriesByMid($mid);
        if(!$cateids){
            return '';
        }
        
        $table = $this->_getTable('CategoryTable');
        $re = array();
        
        foreach($cateids as $v){
            $row['CategoryID'] = $v;
            $cate = $table->getInfoById($v);
            
            if($cate){
                $row['CategoryName'] = $cate['CategoryName'];
            }else{
                continue;
            }
            
            $re[] = $row;
        }
        
        echo json_encode($re);
        exit;
    }
}