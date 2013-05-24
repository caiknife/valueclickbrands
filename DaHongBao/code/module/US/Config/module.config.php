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
 * @Version  : CVS: $Id: module.config.php,v 1.2 2013/04/18 13:27:48 rizhang Exp $
 */
return array(
    'controllers' => array(
        'invokables' => array( 
            'US\Controller\Index'    => 'US\Controller\IndexController',
            'US\Controller\Merchant' => 'US\Controller\MerchantController',
            'US\Controller\Category' => 'US\Controller\CategoryController',
            'US\Controller\Deals'    => 'US\Controller\DealsController',
            'US\Controller\Search'   => 'US\Controller\SearchController',
            'US\Controller\Article'  => 'US\Controller\ArticleController',
            'US\Controller\Ajax'     => 'US\Controller\AjaxController',
            'US\Controller\Passport' => 'US\Controller\PassportController',
            'US\Controller\Tracking'    => 'US\Controller\TrackingController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'US\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'merchant' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/all-merchant/',
                    'defaults' => array(
                        'controller' => 'US\Controller\Merchant',
                        'action'     => 'index',
                    ),
                ),
            ),
            'mertype' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/all-merchant-[:type]/',
                    'constraints' => array(
                        'type' => '[\~A-Z]'
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Merchant',
                        'action'     => 'key',
                    ),
                ),
            ),
            'merdetail' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/merchant-[:mid]/[page-:page/]',
                    'constraints' => array(
                        'id'   => '[0-9]+',
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'page' => 1,
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Merchant',
                        'action'     => 'detail',
                    ),
                ),
            ),
            'category' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/category/[page-:page/]',
                    'constraints' => array(
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'page' => 1,
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Category',
                        'action'     => 'index',
                    ),
                ),
            ),
            'catelist' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/category-[:cid]/[page-:page/]',
                    'constraints' => array(
                        'cid'   => '[0-9]+',
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'page' => 1,
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Category',
                        'action'     => 'category',
                    ),
                ),
            ),
            'catemerchant' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/category-[:cid]-merchant-[:mid]/[page-:page/]',
                    'constraints' => array(
                        'merid' => '[0-9]+',
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'page' => 1,
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Category',
                        'action'     => 'merchant',
                    ),
                ),
            ),
            'deals' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/deals/[page-:page/]',
                    'constraints' => array(
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Deals',
                        'action'     => 'index',
                    ),
                ),
            ),
            'search' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/search',
                    'defaults' => array(
                        'controller' => 'US\Controller\Search',
                        'action'     => 'index',
                    ),
                ),
            ),
            'secoupon' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/s-coupon-[:q]/[page-:page/]',
                    'constraints' => array(
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Search',
                        'action'     => 'coupon',
                    ),
                ),
            ),
            'sedeals' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/s-deals-[:q]/[page-:page/]',
                    'constraints' => array(
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Search',
                        'action'     => 'deals',
                    ),
                ),
            ),
            'artcate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/help[-:cid]/',
                    'constraints' => array(
                        'cid' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Article',
                        'action'     => 'category',
                    ),
                ),
            ),
            'artdetail' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/article-[:aid]/',
                    'constraints' => array(
                        'aid' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'US\Controller\Article',
                        'action'     => 'detail',
                    ),
                ),
            ),
            'ajax' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/ajax',
                    'defaults' => array(
                        'controller' => 'US\Controller\Ajax',
                        'action'     => 'index',
                    ),
                ),
            ),
            'passport' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/passport',
                    'defaults' => array(
                        'controller' => 'US\Controller\Passport',
                        'action'     => 'index',
                    ),
                ),
            ),
            'redir' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/redir.php',
                    'defaults' => array(
                        'controller' => 'US\Controller\Tracking',
                        'action'     => 'index',
                    ),
                ),
            ),
            'asyc' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/async_tracker.php',
                    'defaults' => array(
                        'controller' => 'US\Controller\Tracking',
                        'action'     => 'async',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'doctype'             => 'HTML5',
        'not_found_template'  => 'error/404',
        'exception_template'  => 'error/index',
        'template_path_stack' => array(__DIR__ . '/../View'),
    ),
);
?>