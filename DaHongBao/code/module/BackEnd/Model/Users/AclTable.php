<?php
namespace BackEnd\Model\Users;

use Zend\Db\Sql\Predicate\Operator;

use Zend\Db\Sql\Where;

use Zend\Db\Sql\Select;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSetInterface;

class AclTable extends TableGateway
{
    
    function __construct(Adapter $adapter, $features = null, ResultSetInterface $resultSetPrototype = null){
        parent::__construct('Sys_Acl' , $adapter, $features, $resultSetPrototype);
    }
    
    function getAll($offset = null , $limit = null){
        $select = $this->getSql()->select();
        if($limit){
            $select->limit($limit);
            $select->offset($offset);
        }
        return $this->selectWith($select);
    }
    
    function save(Acl $acl){
        $acl = $acl->getArrayCopy();
        if(isset($acl['AclID'])){
            unset($acl['AclID']);
        }
        
        $this->insert($acl);
    }
    
    function remove($role , $resource){
        $where = new Where();
        $where->addPredicate(new Operator('RoleName' , '=' ,$role));
        if(is_array($resource)){
            $where->in('ResourceName' , $resource);
        }else{
            $where->addPredicate(new Operator('ResourceName' , '=' , $resource));
        }
        $this->delete($where);
    }
}