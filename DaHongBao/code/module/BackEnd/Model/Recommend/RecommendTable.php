<?php
/**
* RecommendTable.php
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
* @version CVS: $Id: RecommendTable.php,v 1.3 2013/05/20 02:44:46 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Model\Recommend;

use Zend\Db\Sql\Select;

use Zend\Db\Sql\Where;

use Custom\Util\Utilities;

use Custom\Db\TableGateway\TableGateway;

class RecommendTable extends TableGateway
{
    protected $table = 'Recommend';
    
    /**
     * 保存
     * @param array $data
     * @return number|boolean
     */
    function save(array $data)
    {
        if(empty($data['RecommendID'])){
            if(!$this->select(array('RecommendTypeID' => $data['RecommendTypeID'] , 'ID' => $data['ID']))){
                return false;
            }
            $data['Editor'] = $_SESSION['user']['Name'];
            $data['InsertDateTime'] = Utilities::getDateTime();
            $this->insert($data);
            return $this->lastInsertValue;
        }else{
            $id = $data['RecommendID'];
            unset($data['RecommendID']);
            return $this->update($data , array('RecommendID' => $id));
        }
    }
    
    
    /**
     * 更新图片
     * @param string $imageUrl
     * @param int $id
     * @return boolean
     */
    function updateImage($imageUrl , $id){
        return $this->update(array('RecommendImage' => $imageUrl) , array('RecommendID' => $id));
    }
    
    /**
     * 更新排序
     * @param int $id
     * @param int $order
     * @return boolean
     */
    function updateOrder($id , $order){
        return $this->update(array('RecommendOrder' => (int)$order) , array('RecommendID' => (int)$id));
    }
    function formatWhere($data){
        $where = new Where();
        $this->_getSelect();
        if(!empty($data['RecommendTypeID'])){
            $where->equalTo('RecommendTypeID', $data['RecommendTypeID']);
        }
        if(!empty($data['ID'])){
            $where->equalTo('ID' , $data['ID']);
        }
        if(!empty($data['ContentType'])){
            $where->equalTo('ContentType' , $data['ContentType']);
        }
        
        $this->select->where($where);
        return $this;
    }
    /**
     * 排序
     * @param array $data
     */
    function order($data = array()){
        $this->_getSelect();
        $this->select->order(array('RecommendOrder' => 'DESC'));
    }
    
    /**
     * 删除
     * @param int $id
     * @return number
     */
    function removeById($id){
        return $this->delete(array('RecommendID' => (int)$id));
    }
}