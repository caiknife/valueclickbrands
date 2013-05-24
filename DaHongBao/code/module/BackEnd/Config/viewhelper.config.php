<?php

use Zend\Session\Container;

use Zend\View\HelperPluginManager;

/**
 * viewhelper.config.php
 * -------------------------
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
 * @author Yaron Jiang<yjiang@corp.valueclick.com>
 * @copyright (C) 2004-2013 Mezimedia.com
 * @license http://www.mezimedia.com PHP License 5.3
 * @version CVS: $Id: viewhelper.config.php,v 1.3 2013/04/25 03:44:37 yjiang Exp $
 * @link http://www.dahongbao.com
 * @deprecated File deprecated in Release 3.0.0
 */
return array(
    'invokables' => array(
        'formText' => '\Custom\Form\View\Helper\FormText',
        'formPassword' => '\Custom\Form\View\Helper\FormPassword',
        'formSubmit' => '\Custom\Form\View\Helper\FormSubmit',
        'formButton' => '\Custom\Form\View\Helper\FormButton',
        'formSelect' => '\Custom\Form\View\Helper\FormSelect',
        'formTextarea' => '\Custom\Form\View\Helper\FormTextarea',
        'formFile'=> '\Custom\Form\View\Helper\FormFile',
        'formEmail' => '\Custom\Form\View\Helper\FormEmail',
        'formCheckbox' => '\Custom\Form\View\Helper\FormCheckbox',
        'formNumber' => '\Custom\Form\View\Helper\FormNumber',
        'formDate' => '\Custom\Form\View\Helper\FormDate',
        'formMultiSelect' => '\Custom\Form\View\Helper\FormMultiSelect',
        'formMultiCheckbox' => '\Custom\Form\View\Helper\FormMultiCheckbox',
    ),
    
    'factories' => array(
        'mynavigation' => function(Zend\View\HelperPluginManager $pm){
            $sm = $pm->getServiceLocator();
            $myacl = $sm->get('acl');
            $session = new Container('user');
            $navigation = $pm->get('\Custom\View\Helper\Navigation');
            $navigation->setAcl($myacl->acl)
                ->setRole($session->Role);
            
            return $navigation;
        },
        
        'isAllowed' => function(Zend\View\HelperPluginManager $pm){
            $sm = $pm->getServiceLocator();
            $myacl = $sm->get('acl');
            $isAllowed = $pm->get('\Custom\View\Helper\IsAllowed');
            $isAllowed->setAcl($myacl->acl);
            return $isAllowed;
        }
    ),
);