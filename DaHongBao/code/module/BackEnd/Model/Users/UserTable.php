<?php
/**
 * smcninternal.Sys_User
 * @author Yaron Jiang
 * @version $id
 */

namespace BackEnd\Model\Users;


use Zend\Paginator\Adapter\DbSelect;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Select;

use Zend\Db\Sql\Update;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;
use BackEnd\Model\Users\User;

class UserTable extends TableGateway
{
    function __construct(Adapter $adapter, $features = null, ResultSetInterface $resultSetPrototype = null){
        parent::__construct('Sys_User' , $adapter, $features, $resultSetPrototype);
    }
    
    function getAllToPage(){
        $select = $this->getSql()->select();
        $adapter = new DbSelect($select, $this->getAdapter());
        return $adapter;
    }
    
    function getAll(){
        return $this->select();
    }
    
    function getOneForEmail($email){
        $rowset = $this->select(array('Mail' => $email));
        $row = $rowset->current();
        return $row;
    }
    
    function getOneForId($id){
        $rowset = $this->select(array('UserID' => $id));
        $row = $rowset->current();
        return $row;
    }
    
    function getUserForName($name){
        $select = $this->getSql()->select();
        $where = function (Where $where) use($name){
            $where->like('Name' , "$name%");
        };
        $select->where($where);
        $adapter = new DbSelect($select, $this->getAdapter());
        return $adapter;
    }
    
    function delete($id){
        return $this->delete(array("UserID" => $id));
    }
    
    function save(User $user){
        $user = (array)$user;
        if(empty($user['UserID'])){
            unset($user['UserID']);
            $this->insert($user);
        }else{
            $id = $user['UserID'];
            if($this->getOneForId($id)){
                unset($user['UserID']);
                return $this->update($user , array('UserID' => $id));
            }else{
                return false;
            }
        }
    }
}