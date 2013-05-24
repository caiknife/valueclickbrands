<?php
/**
 * Sys_Role Table
 * 
 * @author Yaron Jiang
 * @version $id
 */

namespace BackEnd\Model\Users;

use Zend\Paginator\Adapter\DbSelect;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSetInterface;

class RoleTable extends TableGateway
{
    
    function __construct(Adapter $adapter, $features = null, ResultSetInterface $resultSetPrototype = null){
        parent::__construct('Sys_Role' , $adapter, $features, $resultSetPrototype);
    }
    
    function getAll(){
        return $this->select();
    }
    
    function getOneForId($id){
        $id = (int)$id;
        $rowset = $this->select(array('RoleID' => $id));
        $row = $rowset->current();
        if(!$row){
            throw new \Exception('No find this Role.');
        }
        return $row;
    }
    
    function save(Role $role){
        $role = (array)$role;
        if(empty($role['RoleID'])){
            unset($role['RoleID']);
            return $this->insert($role);
        }else{
            if($this->getOneForId($role['RoleID'])){
                $id = $role['RoleID'];
                unset($role['RoleID']);
                
                return $this->update($role , array('RoleID' => $id));
            }else{
                throw new \Exception('No find this Role');
            }
        }
    }
    
    function deleteForName($name){
        $id = (int)$id;
        $this->delete(array('Name' => $name));
    }
}