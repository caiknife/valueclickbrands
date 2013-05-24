<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Db
 */

namespace Custom\Db\TableGateway;

use Zend\Db\Sql\Select;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\ResultSetInterface;
#use Zend\Db\Sql\Sql;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\TableGateway\Feature;
use Custom\Db\TableGateway\Feature\MasterSlaveFeature;
use Zend\Db\Exception;
use Zend\Db\Sql\Expression;

use Custom\Db\Sql\Sql;

/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage TableGateway
 */
abstract class TableGateway extends AbstractTableGateway
{
    protected $select;
    /**
     * Constructor
     *
     * @param string $table
     * @param Adapter $adapter
     * @param Feature\AbstractFeature|Feature\FeatureSet|Feature\AbstractFeature[] $features
     * @param ResultSetInterface $resultSetPrototype
     * @param Sql $sql
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(Adapter $adapter, Adapter $slaveAdapter = null, $features = null, ResultSetInterface $resultSetPrototype = null, Sql $sql = null)
    {
        // adapter
        $this->adapter = $adapter;
        //add slaveAdapter to feature
        if ($slaveAdapter !== null) {
            $slaveFeature = new MasterSlaveFeature($slaveAdapter);
            $features[] = $slaveFeature;
        }
        // process features
        if ($features !== null) {
            if ($features instanceof Feature\AbstractFeature) {
                $features = array($features);
            }
            if (is_array($features)) {
                $this->featureSet = new Feature\FeatureSet($features);
            } elseif ($features instanceof Feature\FeatureSet) {
                $this->featureSet = $features;
            } else {
                throw new Exception\InvalidArgumentException(
                    'TableGateway expects $feature to be an instance of an AbstractFeature or a FeatureSet, or an array of AbstractFeatures'
                );
            }
        } else {
            $this->featureSet = new Feature\FeatureSet();
        }

        // result prototype
        $this->resultSetPrototype = ($resultSetPrototype) ?: new ResultSet;

        // Sql object (factory for select, insert, update, delete)
        $this->sql = ($sql) ?: new Sql($this->adapter, $this->table);
        

        // check sql object bound to same table
        if ($this->sql->getTable() != $this->table) {
            throw new Exception\InvalidArgumentException('The table inside the provided Sql object must match the table of this TableGateway');
        }

        $this->initialize();
    }
    
    /**
      * 获取数据列表
      * @param array $where 查询条件
      * @param array $column 查询字段
      * @param int $limiter 限制条数
      * @param int $offset 从什么位置开始
      * @param string $order 排序
      * @param string $group 分组
      * @return Array
      */
    public function getList($where = array(), $columns = array(), $limit = null, $offset = null, $order = null, $group = null)
    {
        $this->_getSelect();
        if (!empty($where)) {
            $this->select->where($where);
        }
        if (!empty($order)) {
            $this->select->order($order);
        }
        if (!empty($group)) {
            $this->select->group($group);
        }
        if (!empty($limit)) {
            $this->select->limit($limit);
        }
        if (!empty($offset)) {
            $this->select->offset($offset);
        }
        if (!empty($columns)) {
            $this->select->columns($columns);
        }
//         var_dump($this->select->getSqlString());
        $list = $this->selectWith($this->select)->toArray();
        
        //reset
        if (!empty($where)) {
            $this->select->reset(Select::WHERE);
        }
        if (!empty($order)) { 
            $this->select->reset(Select::ORDER);
        }
        if (!empty($group)) {
            $this->select->reset(Select::GROUP);
        }
        if (!empty($limit)) {
            $this->select->reset(Select::LIMIT);
        }
        
        if (!empty($offset)) {
            $this->select->reset(Select::OFFSET);
        }
        
        if (!empty($columns)) {
            $this->select->columns(array(SELECT::SQL_STAR));
        }
        return $list;
    }
    
    /**
     * 获取单条数据
     * @param array $where
     * @param array $column
     * @return array
     */
    public function getInfo($where = array(), $columns = array()) 
    {
        if (empty($where)) {
            return array();
        }
        $list = $this->getList($where, $columns);
        if (empty($list)) {
            return array();
        } else {
            return current($list);
        }
    }
    
    /**
     * 获取列表条数数量
     * @param array $where
     * @return int 条数
     */
    public function getListCount($where = array()) 
    {
        $this->_getSelect();
        if (!empty($where)) {
            $this->select->where($where);
        }
        $this->select->columns(array('ListCount' => New Expression('count(*)')));
        $listCount = $this->selectWith($this->select)->current()->ListCount;
//         var_dump('selectcount' . $this->select->getSqlString());
        //reset
        if (!empty($where)) {
            $this->select->reset(Select::WHERE);
        }
        //reset
        $this->select->columns(array(SELECT::SQL_STAR));
        
        return $listCount;
    }
    
    /**
     * 插入数据
     * @param array $data 插入的值
     * @return int|false
     */
    public function insert($data) 
    {
        if (empty($data)) {
            return false;
        }
        parent::insert($data);
    }
    
    /**
     * 更新的数据
     * @param array $data
     * @param array 
     */
    public function update($data, $where = array()) 
    {
        if (empty($data)) {
            return false;
        }
        parent::update($data, $where);
    }
    
    /**
     * 格式化查询条件 在每个子类实现
     * @param array $where
     * @return array
     */
    public function formatWhere($data) {
        $where = $this->getSql()->select()->where;
        
        $this->_getSelect();
        $this->select->where($where);
        return $this;
    }
    
    protected function _getSelect(){
        if(!isset($this->select)){
            $this->select = $this->getSql()->select();
        }
        return $this->select;
    }
}
