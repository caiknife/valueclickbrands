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
            'FrontEnd\Controller\Index'    => 'FrontEnd\Controller\IndexController',
            'FrontEnd\Controller\Coupon'   => 'FrontEnd\Controller\CouponController',
            'FrontEnd\Controller\Category' => 'FrontEnd\Controller\CategoryController',
            'FrontEnd\Controller\Merchant' => 'FrontEnd\Controller\MerchantController',
            'FrontEnd\Controller\Deals'    => 'FrontEnd\Controller\DealsController',
            'FrontEnd\Controller\Search'   => 'FrontEnd\Controller\SearchController',
            'FrontEnd\Controller\Article'  => 'FrontEnd\Controller\ArticleController',
            'FrontEnd\Controller\Ajax'     => 'FrontEnd\Controller\AjaxController',
            'FrontEnd\Controller\Passport' => 'FrontEnd\Controller\PassportController',
            'FrontEnd\Controller\Tracking'    => 'FrontEnd\Controller\TrackingController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'coupon' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/quan-all/[page-:page][-][sortby-:sortby][/]',
                    'constraints' => array(
                        'page' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Coupon',
                        'action'     => 'index',
                    ),
                ),
            ),
            'couponcate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/category-[:cid]/[page-:page][-][sortby-:sortby][/]',
                    'constraints' => array(
                        'cid'  => '[0-9]+',
                        'page' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Category',
                        'action'     => 'category',
                    ),
                ),
            ),
            'coupondetail' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/quan-[:id]/',
                    'constraints' => array(
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Coupon',
                        'action'     => 'detail',
                    ),
                ),
            ),
            'merchant' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/merchant-[:mid]/',
                    'constraints' => array(
                        'mid' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Merchant',
                        'action'     => 'detail',
                    ),
                ),
            ),
            'cuxiao' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/cuxiao/[page-:page][-][sortby-:sortby][/]',
                    'constraints' => array(
                        'page' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Deals',
                        'action'     => 'index',
                    ),
                ),
            ),
            'cxdetail' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/cuxiao-[:cid]/',
                    'constraints' => array(
                        'cid' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Deals',
                        'action'     => 'detail',
                    ),
                ),
            ),
            'search' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/search',
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Search',
                        'action'     => 'index',
                    ),
                ),
            ),
            'secp' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/s-quan-[:q]/[page-:page/]',
                    'constraints' => array(
                        'page' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Search',
                        'action'     => 'coupon',
                    ),
                ),
            ),
            'secx' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/s-cuxiao-[:q]/[page-:page][-][sortby-:sortby][/]',
                    'constraints' => array(
                        'page' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Search',
                        'action'     => 'deals',
                    ),
                ),
            ),
            'artcate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/help/',
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Article',
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
                        'controller' => 'FrontEnd\Controller\Article',
                        'action'     => 'detail',
                    ),
                ),
            ),
            'ajax' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/ajax',
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Ajax',
                        'action'     => 'index',
                    ),
                ),
            ),
            'passport' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/passport',
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Passport',
                        'action'     => 'index',
                    ),
                ),
            ),
            'redir' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/redir.php',
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Tracking',
                        'action'     => 'index',
                    ),
                ),
            ),
            'asyc' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/async_tracker.php',
                    'defaults' => array(
                        'controller' => 'FrontEnd\Controller\Tracking',
                        'action'     => 'async',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'doctype'            => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_path_stack'=> array(__DIR__ . '/../View'),
    ),
);
?>