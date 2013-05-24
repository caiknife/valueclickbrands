<?php
/**
* RoleController.php
*-------------------------
*
* 角色控制器
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
* @version CVS: $Id: RoleController.php,v 1.2 2013/04/20 09:50:44 thomas_fu Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Controller;

use Custom\Db\Adapter\SmcninternalAdapter;

use BackEnd\Model\Users\MyAcl;

use BackEnd\Model\Users\Role;

use BackEnd\Form\RoleForm;

use Zend\Paginator\Paginator;

use Custom\Mvc\Controller\AbstractActionController;

use Custom\Util\Utilities;

class RoleController extends AbstractActionController
{
    function indexAction(){
        $page = $this->params()->fromQuery('page' , 1);
        $table = $this->_getRoleTable();
        $list = $table->getAll();
        return array('list' => $list);
    }
    
    function saveAction(){
        $request = $this->getRequest();
        $form = new RoleForm();
        
        if($request->isPost()){
            $table = $this->_getRoleTable();
            $params = $request->getPost();
            $role = new Role();
            $role->RoleID = $params->RoleID;
            $role->Name = $params->Name;
            $role->ParentName = $params->ParentName;
            $role->AddTime = Utilities::getDateTime('Y-m-d h:i:s');
            
            $table->save($role);
            
            $acl = $this->_getAcl();
            $acl->acl->addRole($role);
            $acl->saveAcl();
            
            return $this->redirect()->toRoute('backend' , array('controller' => 'role' , 'action' => 'index'));
        }
        
        $table = $this->_getRoleTable();
        $roles = $table->getAll();
        $form->setParents($roles);
        return array('form' => $form);
    }
    
    function deleteAction(){
        $name = $this->params()->fromQuery('name');
        if($name){
            $table = $this->_getRoleTable();
            $table->deleteForName($name);
            
            $acl = $this->_getAcl();
            $acl->acl->removeRole($name);
        }
        
        return $this->redirect()->toRoute('backend' , array('controllter' => 'role' , 'action' => 'index'));
    }
    
    private function _getRoleTable(){
        return $this->getServiceLocator()->get('RoleTable');
    }
    
    private function _getCache(){
        return $this->getServiceLocator()->get('cache');
    }
    
    private function _getAcl(){
        return $this->getServiceLocator()->get('acl');
    }
}