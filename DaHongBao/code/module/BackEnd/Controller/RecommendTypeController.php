<?php
/**
* RecommendTypeController.php
*-------------------------
*
* 
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
* @version CVS: $Id: RecommendTypeController.php,v 1.2 2013/05/20 02:44:46 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Controller;
use BackEnd\Model\Recommend\RecommendType;

use Custom\Util\Utilities;

use BackEnd\Form\RecommendTypeForm;

use Custom\Mvc\Controller\AbstractActionController;
class RecommendTypeController extends AbstractActionController
{
    function indexAction()
    {
        $re['referer'] = Utilities::encode($_SERVER['REQUEST_URI']);
        $re['SiteID'] = $this->params()->fromQuery('SiteID' , 1);
        $re['sites'] = $this->sites;
        $table = $this->_getTable('RecommendTypeTable');
        $re['list']    = $table->getListBySite($re['SiteID']);

        return $re;
    }
    function saveAction()
    {
        $request = $this->getRequest();
        $form = new RecommendTypeForm();
        
        if($request->isPost()){
            $recommendType = new RecommendType();
            $form->setData($this->params()->fromPost());
            $form->setInputFilter($recommendType->getInputFilter());
            
            if($form->isValid()){
                $recommendType->exchangeArray($form->getData());
                $table = $this->_getTable('RecommendTypeTable');
                $table->save($recommendType->toArray());
                return $this->redirect()->toUrl(Utilities::decode($this->params()->fromQuery('referer')));
            }
        }
        
        if($id = $this->params()->fromQuery('RecommendTypeID')){
            $table = $this->_getTable('RecommendTypeTable');
            $data = $table->getInfoById($id);
            $form->setData($data);
        }
        $re['form'] = $form;
        $re['referer'] = $this->params()->fromQuery('referer');
        
        return $re;
    }
    function deleteAction()
    {
    }
}