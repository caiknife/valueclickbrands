<?php

/**
* CommonControlAbstractFactory.php
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
* @author Yaron Jiang<yjiang@corp.valueclick.com>
* @copyright (C) 2004-2013 Mezimedia.com
* @license http://www.mezimedia.com PHP License 5.3
* @version CVS: $Id: CommonController.php,v 1.1 2013/04/15 10:56:30 rock Exp $
* @link http://www.dahongbao.com
* @deprecated File deprecated in Release 3.0.0
*/
namespace Custom\AbstractFactory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommonController implements AbstractFactoryInterface
{
    function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName){
        if(class_exists('BackEnd\Controller\\'.ucfirst($requestedName) . 'Controller')){
            return true;
        }
        return false;
    }
    
    function createServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName){
        $className = 'BackEnd\Controller\\'.ucfirst($requestedName) . 'Controller';
        return new $className;
    }
}