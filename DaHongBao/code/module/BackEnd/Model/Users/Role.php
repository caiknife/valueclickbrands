<?php

/**
 * 角色
 * 
 * 
 * @author Yaron jiang
 * @version $id
 */
namespace BackEnd\Model\Users;

use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory;

class Role implements RoleInterface
{
    public $Name;
    public $CnName;
    public $ParentName;
    public $AddTime;
    public $State;
    public $RoleID;
    protected $inputFilter;
    function __construct($name = '', $parentName = '') {
        $this->Name = $name;
        $this->ParentName = $parentName;
        $this->State = '1';
    }
    function getRoleId() {
        return $this->Name;
    }
    function exchangeArray(Array $data) {
        $this->Name = isset ( $data ['Name'] ) ? $data ['Name'] : '';
        $this->CnName = isset ( $data ['CnName'] ) ? $data ['CnName'] : '';
        $this->ParentName = isset ( $data ['ParentName'] ) ? $data ['ParentName'] : '';
        $this->AddTime = isset ( $data ['addTime'] ) ? $data ['addTime'] : '';
        $this->State = isset ( $data ['State'] ) ? $data ['State'] : '';
        $this->RoleID = isset ( $data ['RoleID'] ) ? $data ['RoleID'] : '';
    }
    function toArray() {
        return get_object_vars ( $this );
    }
    function __toString() {
        return $this->Name;
    }
}