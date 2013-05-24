<?php
/**
* ResourceController.php
*-------------------------
*
* 资源管理
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
* @version CVS: $Id: ResourceController.php,v 1.2 2013/04/20 09:50:44 thomas_fu Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace BackEnd\Controller;

use BackEnd\Model\Users\Resource;

use BackEnd\Form\ResourceForm;

use Zend\Paginator\Paginator;

use Custom\Mvc\Controller\AbstractActionController;

use Custom\Util\Utilities;

class ResourceController extends AbstractActionController
{
    function indexAction(){
        $page = $this->params()->fromQuery('page' ,1);
        $table = $this->_getResourceTable();
        $paginaction = new Paginator($table->getPage());
        $paginaction->setCurrentPageNumber($page);
        $paginaction->setItemCountPerPage(self::LIMIT);
        
        return array('paginaction' => $paginaction);
    }
    
    function saveAction(){
        $request = $this->getRequest();
        $form = new ResourceForm();
        $resource = new Resource();
        $table = $this->_getResourceTable();
        
        if($request->isPost()){
            $params = $request->getPost();
            $form->bind(new Resource());
            $form->setData($params);
            if($form->isValid()){
                $params = $form->getData();
                $resource = new Resource();
                $resource->ResourceID = $params->ResourceID;
                $resource->ControllerName = $params->ControllerName;
                $resource->ActionName = $params->ActionName;
                $table->save($resource);
                
                return $this->redirect()->toRoute('backend' , array('controller' => 'resource' , 'action' => 'index'));
            }
        }
        
        $id = $this->params()->fromQuery('id');
        $id = (int)$id;
        
        if(!$id){
            throw new \Exception('参数错误,没有ID');
        }
        
        $result = $table->getOneForId($id);
        if(!$result){
            throw new \Exception("ID:$id 没有数据");
        }
        
        $form->bind($result);
        
        return array('form' => $form);
    }
    
    function deleteAction(){
        $name = $this->params()->fromQuery('name');
        
        if(!$name){
            throw new \Exception('参数错误,没有Name');
        }
        
        $table = $this->_getResourceTable();
        $acl = $this->_getAcl();
        
        $table->removeForName($name);
        $acl->acl->removeResource($name);
        
        return $this->redirect()->toRoute('backend' , array('controller' => 'resource' , 'action' => 'index'));
    }
    
    function updateResourceAction(){
        $acl = $this->_getAcl();
        $table = $this->_getResourceTable();
    
        $controllerPath = APPLICATION_MODULE_PATH . 'Controller/';
        $files = scandir($controllerPath);
        $re = array();
        $date = Utilities::getDateTime('Y-m-d h:i:s');
        
        foreach($files as $file){
            if(is_file($controllerPath.$file)){
                $classname = basename($file , 'Controller.php');
                if(in_array($classname , array('Login'))){
                    continue;
                }
                $content = file_get_contents($controllerPath.$file);
                preg_match_all('/function (\w+)Action/', $content , $match);
                foreach($match[1] as $v){
                    $classname = strtolower($classname);
                    $re[] = $classname . '_' . $v;
                    $resource = new Resource($classname . '_' . $v , $classname , $v);
                    $resource->AddTime = $date;
                    if(!$acl->acl->hasResource($resource)){
                        $acl->acl->addResource($resource);
                    }
                    $table->save($resource);
                }
            }
        }
        $acl->saveAcl();
        return array('re' => $re);
    }
    
    private function _getResourceTable(){
        return $this->getServiceLocator()->get('ResourceTable');
    }
    
    private function _getAcl(){
        return $this->getServiceLocator()->get('acl');
    }
    
    private function _getCache(){
        return $this->getServiceLocator()->get('cache');
    }
}