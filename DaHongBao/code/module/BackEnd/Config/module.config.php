<?php

/*
 * package_name : module.config.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 *
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: module.config.php,v 1.6 2013/05/08 02:35:54 yjiang Exp $
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'index' => 'BackEnd\Controller\IndexController',
            'role' => 'BackEnd\Controller\RoleController',
            'login' => 'BackEnd\Controller\LoginController',
            'acl' => 'BackEnd\Controller\AclController',
            'user' => 'BackEnd\Controller\UserController',
            'BackEnd\Controller\MerchantFeed' => 'BackEnd\Controller\MerchantFeedController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'BackEnd\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                
            ),
            'backend' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/[:controller][/:action]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'BackEnd\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'merchantfeed' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/merchantfeed',
                    'defaults' => array(
                        'controller' => 'BackEnd\Controller\merchantFeed',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
            'doctype'                  => 'HTML5',
            'not_found_template'       => 'error/404',
            'exception_template'       => 'error/index',
            'template_map' => array(
                //'layout/layout'           => __DIR__ . '/../View/error/layout.phtml',
                //'layout/breadcrumb'           => __DIR__ . '/../View/layout/breadcrumb.phtml',
                //'layout/footer'           => __DIR__ . '/../View/layout/footer.phtml',
                //'layout/header'           => __DIR__ . '/../View/layout/header.phtml',
                //'layout/leftSideBar'      => __DIR__ . '/../View/layout/leftSideBar.phtml',
                //'layout/demo'             => __DIR__ . '/../View/layout/demo.phtml',
                //'frontend/index/index'    => __DIR__ . '/../View/backend/index/index.phtml',
            ),
        'template_path_stack' => array(__DIR__ . '/../View'),
    ),
    
    'translator' => array(
        'locale' => 'zh_CN',
    ),
    
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'backend',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionStorage',
        'validators' => array(
            array(
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ),
        ),
    ),
);
