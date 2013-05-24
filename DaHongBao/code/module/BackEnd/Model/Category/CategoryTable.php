<?php
/*
 * package_name : CategoryTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CategoryTable.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Model\Category;

use Custom\Util\Utilities;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Custom\Categories\Category\Category as Cate;
use Custom\Categories\Categories;

class CategoryTable extends TableGateway 
{
    const ACTIVE_YES = 'YES';
    const ACTIVE_NO = 'NO';
    
    protected $table = 'Category';
    /**
     * 站点分配
     * @var array 
     */
    protected $siteMapping = array(
        'CN' => 1,
        'US' => 2,
    );
    
    /**
     * 获取海外的category 列表
     * 
     * @return array
     */
    public function getUsCategoryList()
    {
        $where['SiteID'] = $this->siteMapping['US'];
        return $this->getList($where);
    }
    
    /**
     * 根据Name返回ID
     * @param string $name
     * @return Ambigous <>
     */
    public function getIdByName($name){
        $result = $this->getInfo(array('CategoryName' => $name) , array('CategoryID'));
        return $result['CategoryID'];
    }
    
    /**
     * 根据站点获取分类列表
     * 
     * @param int $siteId            
     * @return array
     */
    public function getCategoryListBySiteId($siteId)
    {
        return $this->getList(array(
            'SiteID' => (int)$siteId
        ));
    }
    
    /**
     * 返回树
     * 
     * @return \BackEnd\Model\Category\Categories
     */
    public function getListToTree()
    {
        $re = $this->select();
        $list = $re->toArray();
        $cates = array();
        foreach($list as $v){
            $cates[$v['CategoryID']] = new Cate($v);
        }
        foreach($list as $v){
            if($v['ParentID'] > 0){
                $cates[$v['ParentID']]->addCate($cates[$v['CategoryID']]);
            }
        }
        $re = new Categories();
        foreach($list as $v){
            if($v['ParentID'] < 1){
                $re->addCate($cates[$v['CategoryID']]);
            }
        }
        return $re;
    }
    
    /**
     * 存储
     * @param array $data
     * @return boolean|Ambigous <number, \Custom\Db\TableGateway\false, boolean>
     */
    function save(array $data)
    {
        if(! empty($data['CategoryID'])){
            return $this->update($data , array(
                'CategoryID' => $data['CategoryID']
            ));
        }else{
            $data['InsertDateTime'] = Utilities::getDateTime();
            return parent::insert($data);
        }
    }
    
    /**
     * 根据ID获取数据
     * @param int $cid
     * @return Ambigous <multitype:, mixed>
     */
    function getInfoById($cid)
    {
        return parent::getInfo(array(
            'CategoryID' => $cid
        ));
    }
    
    /**
     * 改变激活状态
     * @param int $id
     * @param string $state
     * @return boolean
     */
    function changeActive($id, $state)
    {
        $where = $this->_getSelect()->where;
        
        if(is_array($id)){
            $where->in('CategoryID' , $id);
        }else{
            $where->equalTo('CategoryID' , $id);
        }
        return parent::update(array(
            'IsActive' => $state
        ) , $where);
    }
    
    /**
     * 
     * @see \Custom\Db\TableGateway\TableGateway::formatWhere()
     */
    function formatWhere(array $data)
    {
        $where = $this->getSql()->select()->where;
        
        $data['IsActive'] = isset($data['IsActive']) ? $data['IsActive'] : self::ACTIVE_YES;
        $where->equalTo('IsActive' , $data['IsActive']);
        
        if(isset($data['SiteId'])){
            $where->equalTo('SiteID' , $data['SiteId']);
        }
        
        if(isset($data['ParentCategoryID'])){
            $where->equalTo('ParentCategoryID' , $data['ParentCategoryID']);
        }
        
        $this->_getSelect();
        $this->select->where($where);
        $this->select->order(array('Sequence' => 'DESC' , 'CategoryID' => 'DESC'));
        
        return $this;
    }
}