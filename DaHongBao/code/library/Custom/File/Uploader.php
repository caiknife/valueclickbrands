<?php
/**
* Uploader.php
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
* @version CVS: $Id: Uploader.php,v 1.2 2013/04/19 03:15:43 yjiang Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/

namespace Custom\File;

use Zend\File\Transfer\Adapter\Http;

class Uploader
{
    /**
     * 上传
     * @param array $files POST返回的FILE
     * @param string $newName 新文件名
     * @param string $path 目标路径
     * @param array $options 设置 , validators为验证数组
     * @throws \Exception
     * @return string 新文件名
     */
    static function upload($files , $newName , $path , array $options = array()){
        $filename = $newName . strrchr($files['name'] , '.');
        $adapter = new Http();
        
        if(isset($options['validators'])){
            $adapter->setValidators(
                $options['validators'] , $files['name']
            );
        }
        
        $adapter->addFilter('rename' , array(
            'target' => $filename
        ));
        
        if($adapter->isValid()){
            if(!is_dir($path)){
                if(!mkdir($path , 0777 , true)){
                    throw new \Exception('没有生成文件夹的权限：' . $path);
                }
            }
            $adapter->setDestination($path);
            $adapter->receive($files['name']);
            return $filename;
        }else{
            throw new \Exception(implode(',' , $adapter->getMessages()));
        }
    }
}