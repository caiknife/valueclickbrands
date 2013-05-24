<?php
/**
* page.config.php
*-------------------------
*
* 页面导航配置
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
* @version CVS: $Id: page.config.php,v 1.3 2013/05/20 02:44:46 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

return array (
    array (
        'label' => '用户管理',
        'controller' => 'user',
        'resource' => 'user_index' 
    ),
    array(
        'label' => '权限控制',
        'controller' => 'acl',
        'resource' => 'acl_index',
        'pages' => array(
            array (
                'label' => '角色管理',
                'controller' => 'role',
                'resource' => 'role_index' 
            ),
            array (
                'label' => '资源管理',
                'controller' => 'resource',
                'resource' => 'resource_index' 
            ) 
        )
    ),
    array(
        'label' => '文章分类管理',
        'controller' => 'ArticleCategory',
        'resource' => 'articlecategory_index',
        'pages' => array(
            array(
                'label' => '新增分类',
                'controller' => 'ArticleCategory',
                'action' => 'save',
                'visible' => false
            ),
        )
    ),
    array(
        'label' => '文章管理',
        'controller' => 'article',
        'resource' => 'article_index'
    ),
    array(
        'label' => '商家管理',
        'controller' => 'merchant',
        'resource' => 'merchant_index',
        'pages' => array(
            array(
                'label' => '聪明点商家导入',
                'controller' => 'SmcnMerFeed',
                'action' => 'index',
                'resource' => 'smcnmerfeed_index'
            ),
            array(
                'label' => '商家别名映射',
                'controller' => 'merchant',
                'action' => 'mapping',
                'resource' => 'merchant_mapping'
            ),
        ),
    ),
    array(
        'label' => '分类管理',
        'controller' => 'category',
        'resource' => 'category_index'
    ),
    
    array(
        'label' => 'Coupon管理',
        'controller' => 'coupon',
        'resource' => 'coupon_index',
        'pages' => array(
            array(
                'label' => '审核Coupon',
                'controller' => 'coupon',
                'action' => 'userDataFeed',
                'resource' => 'coupon_userDataFeed'
            ),
            array(
                'label' => '线上Coupon管理',
                'controller' => 'coupon',
                'action' => 'couponList',
                'resource' => 'coupon_couponList'
            )
        )
    ),
    array(
            'label' => '联盟feed下载导入管理',
            'controller' => 'FeedConfig',
            'resource' => 'feedconfig_index'
    ),
    array(
            'label' => '商家feed导入管理',
            'controller' => 'FeedConfig',
            'action'  => 'merchantFeedConfig',
            'resource' => 'feedconfig_merchantFeedConfig'
    ),
    
    array(
        'label' => '推荐管理',
        'controller' => 'Recommend',
        'resource' => 'recommend_index',
        'pages' => array(
            array(
                'label' => '推荐位置管理',
                'controller' => 'RecommendType',
                'resource' => 'recommendtype_index',
            ),
            array(
                'label' => 'Coupon推荐',
                'controller' => 'Recommend',
                'action' => 'couponList',
                'resource' => 'recommend_couponList'
            ),
            array(
                'label' => '商家推荐',
                'controller' => 'Recommend',
                'action' => 'merchantList',
                'resource' => 'recommend_merchantList'
            ),
            array(
                'label' => '文章推荐',
                'controller' => 'Recommend',
                'action' => 'articleList',
                'resource' => 'recommend_articleList'
            ),
        )
    ),
);