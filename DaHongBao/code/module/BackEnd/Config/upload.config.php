<?php

/**
* upload.config.php
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
* @version CVS: $Id: upload.config.php,v 1.4 2013/04/20 05:21:52 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

use Zend\Validator\File\Size;
return array(
    //商家LOGO
    'merchantLogo' => array(
        'uploadPath' => APPLICATION_PATH . '/public/img/merchant/logo/',
        'showPath' => '/img/merchant/logo/',
        'validators' => array(
             new Size(array(
                 'max' => '256000' //单位Bit
             ))
         ),
     ),
    //文章图片
     'custom' => array(
         'uploadPath' => APPLICATION_PATH . '/public/img/article/',
         'showPath' => 'http://images.dahongbao.com/img/article/',
         'size' => '512' //单位KB
     ),
     //conpun图片
     'conpun' => array(
         'uploadPath' => APPLICATION_PATH . '/public/img/coupon/',
         'showPath' => '/img/coupon/',
         'validators' => array(
             new Size(array(
                 'max' => '256000'
             ))
         ),
     ),
    
    //推荐图片
    'recommend' => array(
         'uploadPath' => APPLICATION_PATH . '/public/img/other/recommend/',
         'showPath' => '/img/other/recommend/',
         'validators' => array(
             new Size(array(
                 'max' => '256000'
             ))
         ),
     ),
    
);