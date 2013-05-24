<?php
namespace Custom\Navigation\Service;
use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Exception;

/**
* backendNavigation.php
*-------------------------
*
* BackEnd导航
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
* @version CVS: $Id: BackendNavigation.php,v 1.1 2013/04/15 10:56:30 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

class BackendNavigation extends AbstractNavigationFactory
{
    function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {

            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig(include(APPLICATION_MODULE_PATH . 'Config/page.config.php'));

            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        return $this->pages;
    }
    
    function getName(){
        return 'backend';
    }
    
    function injectComponents(array $pages, $routeMatch = null, $router = null)
    {
        foreach ($pages as &$page) {
            $hasMvc = isset($page['action']) || isset($page['controller']) || isset($page['route']);
            if ($hasMvc) {
                if (!isset($page['routeMatch']) && $routeMatch) {
                    $page['routeMatch'] = $routeMatch;
                }
                if (!isset($page['router'])) {
                    $page['router'] = $router;
                }
                
                if(!isset($page['route'])){
                    $page['route'] = 'backend';
                }
            }
    
            if (isset($page['pages'])) {
                $page['pages'] = $this->injectComponents($page['pages'], $routeMatch, $router);
            }
        }
        return $pages;
    }
}