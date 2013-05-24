<?php
/**
* UploaderController.php
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
* @version CVS: $Id: UploaderController.php,v 1.1 2013/04/15 10:57:07 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace BackEnd\Controller;

use Custom\Mvc\Controller\AbstractActionController;
use Custom\Editor\Uploader;
class UploaderController extends AbstractActionController
{
    function imageUpAction()
    {
        header("Content-Type: text/html; charset=utf-8");
        
        $config = $this->_getConfig('upload');
        // 上传图片框中的描述表单名称，
        $title = htmlspecialchars($_POST['pictitle'] , ENT_QUOTES);
        $path = $config['custom']['showPath'];
        
        // 上传配置
        $config = array(
            "savePath" => $config['custom']['uploadPath'],//($path == "1" ? "upload/" : "upload1/"),
            "maxSize" => $config['custom']['size'], // 单位KB
            "showPath" => $path,
            "allowFiles" => array(
                ".gif",
                ".png",
                ".jpg",
                ".jpeg",
                ".bmp"
            )
        );
        
        // 生成上传实例对象并完成上传
        $up = new Uploader("upfile" , $config);
        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         * "originalName" => "", //原始文件名
         * "name" => "", //新文件名
         * "url" => "", //返回的地址
         * "size" => "", //文件大小
         * "type" => "" , //文件类型
         * "state" => "" //上传状态，上传成功时必须返回"SUCCESS"
         * )
         */
        $info = $up->getFileInfo();
        
        /**
         * 向浏览器返回数据json数据
         * {
         * 'url' :'a.jpg', //保存后的文件路径
         * 'title' :'hello', //文件描述，对图片来说在前端会添加到title属性上
         * 'original' :'b.jpg', //原始文件名
         * 'state' :'SUCCESS' //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
         * }
         */
        echo "{'url':'" . $info["url"] . "','title':'" . $title . "','original':'" . $info["originalName"] . "','state':'" . $info["state"] . "'}";
        exit;
    }
    
    function imageManagerAction(){
        $config = $this->_getConfig('upload');
        header("Content-Type: text/html; charset=utf-8");
        
        //需要遍历的目录列表，最好使用缩略图地址，否则当网速慢时可能会造成严重的延时
        $path = $config['custom']['uploadPath'];
        
        $action = htmlspecialchars( $_POST[ "action" ] );
        if ( $action == "get" ) {
            $files = $this->_getfiles( $path );
            if ( !count($files) ) return;
            rsort($files,SORT_STRING);
            $str = "";
            foreach ( $files as $file ) {
                $file = str_replace(APPLICATION_PATH . '/public/', '', $file);
                $str .= $file . "ue_separate_ue";
            }
            echo $str;
        }
        exit;
    }
    
    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    private function _getfiles( $path , &$files = array() )
    {
        if ( !is_dir( $path ) ) return null;
        $handle = opendir( $path );
        while ( false !== ( $file = readdir( $handle ) ) ) {
            if ( $file != '.' && $file != '..' ) {
                $path2 = $path . '/' . $file;
                if ( is_dir( $path2 ) ) {
                    $this->_getfiles( $path2 , $files );
                } else {
                    if ( preg_match( "/\.(gif|jpeg|jpg|png|bmp)$/i" , $file ) ) {
                        $files[] = $path2;
                    }
                }
            }
        }
        return $files;
    }
}