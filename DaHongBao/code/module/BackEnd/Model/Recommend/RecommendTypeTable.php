<?php
/**
* RecommendTypeTable.php
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
* @version CVS: $Id: RecommendTypeTable.php,v 1.3 2013/05/20 02:44:46 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Model\Recommend;
use Custom\Db\TableGateway\TableGateway;
class RecommendTypeTable extends TableGateway
{
    protected $table = 'RecommendType';
    
    /**
     * 根据ID获取内容
     * @param int $id
     * @return array
     */
    function getInfoById($id)
    {
        return $this->getInfo(array('RecommendTypeID' => (int)$id));
    }
    
    /**
     * 根据ContentType获取内容
     * @param string $contentType
     * @return array
     */
    function getListByContentType($contentType, $siteId = 1)
    {
        return $this->getList(array('ContentType' => $contentType , 'SiteID' => $siteId));
    }

    /**
     * 根据SiteID
     * @param int $siteId
     * @return array
     */
    function getListBySite($siteId = 1)
    {
        return $this->getList(array('SIteID' => (int) $siteId));
    }

    /**
     * 保存
     * @param array $data
     * @return boolean|number
     */
    function save(array $data)
    {
        if(!empty($data['RecommendTypeID'])){
            $id = $data['RecommendTypeID'];
            unset($data['RecommendTypeID']);
            return $this->update($data , array('RecommendTypeID' => $id));
        }else{
            $this->insert($data);
            return $this->lastInsertValue;
        }
    }
}