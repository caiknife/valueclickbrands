<?php
/*
* package_name : Category.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: Category.php,v 1.5 2013/04/22 08:37:18 rizhang Exp $
*/
namespace CommModel\Category;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;
use Custom\Util\PathManager;

class Category extends TableGateway
{
    protected $table = "Category";

    public function fetchAll($where = array(), $columns = array(), $order = null, $limit = null, $offset = null)
    {
        $select = $this->getSql()->select();
        $select->where($where);
        if ($columns) {
            $select->columns($columns);
        }
        if ($order !== null) {
            $select->order($order);
        } else {
            $select->order('CategoryID DESC');
        }
        if ($limit !== null || $offset !== null) {
            $select->limit($limit);
            $select->offset($offset);
        }
        $resultSet = $this->selectWith($select);
        return $resultSet->toArray();
    }

    /*
     * 导航 全部优惠券分类
     */
    public function getCategoryList($siteid)
    {
        $where = array(
            'SiteID' => $siteid,
            'IsActive' => 'YES',
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->where("Sequence > 0");
        $select->columns(array('CategoryID', 'CategoryName'));
        $select->order('Sequence DESC');
        $resultSet = $this->selectWith($select);
        $resultArray = $resultSet->toArray();
        foreach ($resultArray as $key => $result) {
            if ($siteid == '1') {
                $result['CategoryUrl'] = PathManager::getDhbCouponCateUrl($result['CategoryID']);
            } elseif ($siteid == '2') {
                $result['CategoryUrl'] = PathManager::getCateListUrl($result['CategoryID']);
            }
            $categoryList[$result['CategoryID']] = $result;
        }
        return $categoryList;
    }

    /*
     * 大红包顶部导航 9个推荐category
     */
    public function getDhbTopCategoryList($siteid, $limit = 9)
    {
        $where = array(
            'SiteID' => $siteid,
            'IsActive' => 'YES',
        );
        $select = $this->getSql()->select();
        $select->where($where);
        $select->where("Sequence > 0");
        $select->columns(array('CategoryID', 'CategoryName'));
        $select->order('Sequence DESC');
        $select->limit($limit);
        $resultSet = $this->selectWith($select);
        $topCategoryList = $resultSet->toArray();
        foreach ($topCategoryList as $key => $topCategory) {
            $topCategoryList[$key]['CategoryUrl'] = PathManager::getCateListUrl($topCategory['CategoryID']);
        }
        return $topCategoryList;
    }

    /*
     * 获取CategoryName
     */
    public function getCategoryNameByID($catid)
    {
        $select = $this->getSql()->select();
        $select->where("CategoryID = {$catid}");
        $select->columns(array('CategoryName'));
        $resultSet = $this->selectWith($select);
        $resultArr = current($resultSet->toArray());
        return $resultArr['CategoryName'];
    }

    /*
     * 是不是CategoryName
     */
    public function isCategoryName($siteid, $name)
    {
        $select = $this->getSql()->select();
        $subWhereForName = clone $select->where;
        $select->where(
            array(
            'SiteID' => $siteid,
            'IsActive' => 'YES',
            )
        );
        $subWhereForName->equalTo('CategoryName', $name);
        $subWhereForName->or;
        $subWhereForName->equalTo('CategoryEnName', $name);
        $select->where->addPredicate($subWhereForName);
        $select->columns(array('CategoryID'));
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $resultArr = current($resultSet->toArray());
        return $resultArr['CategoryID'];
    }
}
?>