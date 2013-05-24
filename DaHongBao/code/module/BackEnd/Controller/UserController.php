<?php
/**
* UserController.php
*-------------------------
*
* User manager
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
* @author Yaron Jiang <yjiang@corp.valueclick.com>
* @copyright (C) 2004-2013 Mezimedia.com
* @license http://www.mezimedia.com PHP License 5.0
* @version CVS: $Id
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Controller;

use Zend\Paginator\Paginator;

use BackEnd\Model\Users\RoleTable;

use BackEnd\Model\Users\Role;

use Custom\Mvc\Controller\AbstractActionController;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use BackEnd\Model\Users\UserTable;
use BackEnd\Model\Users\User;
use BackEnd\Form\UserForm;
use Zend\Form\Annotation\AnnotationBuilder;
use Custom\Util\Utilities;

class UserController extends AbstractActionController
{
    function indexAction(){
        $page = $this->params()->fromQuery('page' , 1);
        $table = $this->_getUserTable();
        $username = $this->params()->fromQuery('username' , '');
        
        if($username){
            $re = $table->getUserForName($username);
        }else{
            $re = $table->getAllToPage();
        }
        
        $paginaction = new Paginator($re);
        $paginaction->setCurrentPageNumber($page);
        $paginaction->setItemCountPerPage(self::LIMIT);
        return array('paginaction' => $paginaction);
    }
    
    function saveAction(){
        $requery = $this->getRequest();
        $form = new UserForm();
        if($requery->isPost()){
            $params = $requery->getPost();
            $user = new User();
            $user->UserID = $params->UserID;
            $user->Name = $params->Name;
            $user->Mail = $params->Mail;
            $user->LastChangeDate = $user->AddDate = Utilities::getDateTime('Y-m-d h:i:s');
            $user->Remark = $params->Remark;
            $user->DahongbaoRole = $params->DahongbaoRole;
            $user->Password = '';
            $table = $this->_getUserTable();
            $table->save($user);
            return $this->redirect()->toRoute('backend' , array('controller' => 'user' , 'action' => 'index'));
        }
        $table = $this->_getRoleTable();
        $roles = $table->getAll();
        
        if($userId = $requery->getQuery('id')){
            $table = $this->_getUserTable();
            $re = $table->getOneForId($userId);
            $form->bind($re);
            $form->setRole($roles , $re->DahongbaoRole);
            $form->get('UserID')->setAttribute('value', $userId);
            return array('user' => $re , 'roles' => $roles , 'form' => $form , 'userId' => $userId);
        }
        
        $form->setRole($roles);
        return array('user' => new User() , 'form' => $form);
    }
    
    function deleteAction(){
        $requery = $this->getRequest();
        if($userId = $requery->getQuery('id')){
            $table = $this->_getUserTable();
            $table->delete($userId);
            $this->redirect()->toUrl('/backend/user/index');
            
        }
        throw new \Exception('没有ID参数');
    }
    
    private function _getUserTable(){
        return $this->getServiceLocator()->get('UserTable');
    }
    
    
    private function _getRoleTable(){
        return $this->getServiceLocator()->get('RoleTable');
    }
    
}
