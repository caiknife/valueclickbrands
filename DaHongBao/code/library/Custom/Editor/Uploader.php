<?php
/**
* Uploader.php
*-------------------------
*
* UEditor编辑器通用上传类
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
* @version CVS: $Id: Uploader.php,v 1.1 2013/04/15 10:56:30 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace Custom\Editor;

class Uploader
{
    
    private $fileField; // 文件域名
    private $file; // 文件上传对象
    private $config; // 配置信息
    private $oriName; // 原始文件名
    private $fileName; // 新文件名
    private $fullName; // 完整文件名,即从当前配置目录开始的URL
    private $fileSize; // 文件大小
    private $fileType; // 文件类型
    private $stateInfo; // 上传状态信息,
    private $dir; //生成的文件夹
    private $stateMap = array( // 上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS", // 上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "POST" => "文件大小超出 post_max_size 限制",
        "SIZE" => "文件大小超出网站限制",
        "TYPE" => "不允许的文件类型",
        "DIR" => "目录创建失败",
        "IO" => "输入输出错误",
        "UNKNOWN" => "未知错误",
        "MOVE" => "文件保存时出错"
    );
    
    /**
     * 构造函数
     * 
     * @param string $fileField
     *            表单名称
     * @param array $config
     *            配置项
     * @param bool $base64
     *            是否解析base64编码，可省略。若开启，则$fileField代表的是base64编码的字符串表单名
     */
    public function __construct($fileField, $config, $base64 = false)
    {
        $this->fileField = $fileField;
        $this->config = $config;
        $this->stateInfo = $this->stateMap[0];
        $this->upFile($base64);
    }
    
    /**
     * 上传文件的主处理方法
     * 
     * @param
     *            $base64
     * @return mixed
     */
    private function upFile($base64)
    {
        // 处理base64上传
        if("base64" == $base64){
            $content = $_POST[$this->fileField];
            $this->base64ToImage($content);
            return;
        }
        
        // 处理普通上传
        $file = $this->file = $_FILES[$this->fileField];
        if(! $file){
            $this->stateInfo = $this->getStateInfo('POST');
            return;
        }
        if($this->file['error']){
            $this->stateInfo = $this->getStateInfo($file['error']);
            return;
        }
        if(! is_uploaded_file($file['tmp_name'])){
            $this->stateInfo = $this->getStateInfo("UNKNOWN");
            return;
        }
        
        $this->oriName = $file['name'];
        $this->fileSize = $file['size'];
        $this->fileType = $this->getFileExt();
        
        if(! $this->checkSize()){
            $this->stateInfo = $this->getStateInfo("SIZE");
            return;
        }
        if(! $this->checkType()){
            $this->stateInfo = $this->getStateInfo("TYPE");
            return;
        }
        $this->fullName = $this->getFolder() . '/' . $this->getName();
        if($this->stateInfo == $this->stateMap[0]){
            if(! move_uploaded_file($file["tmp_name"] , $this->fullName)){
                $this->stateInfo = $this->getStateInfo("MOVE");
            }
        }
    }
    
    /**
     * 处理base64编码的图片上传
     * 
     * @param
     *            $base64Data
     * @return mixed
     */
    private function base64ToImage($base64Data)
    {
        $img = base64_decode($base64Data);
        $this->fileName = time() . rand(1 , 10000) . ".png";
        $this->fullName = $this->getFolder() . '/' . $this->fileName;
        if(! file_put_contents($this->fullName , $img)){
            $this->stateInfo = $this->getStateInfo("IO");
            return;
        }
        $this->oriName = "";
        $this->fileSize = strlen($img);
        $this->fileType = ".png";
    }
    
    /**
     * 获取当前上传成功文件的各项信息
     * 
     * @return array
     */
    public function getFileInfo()
    {
        return array(
            "originalName" => $this->oriName,
            "name" => $this->fileName,
            "url" => $this->config['showPath'] . $this->dir . '/' . $this->fileName,
            "size" => $this->fileSize,
            "type" => $this->fileType,
            "state" => $this->stateInfo
        );
    }
    
    /**
     * 上传错误检查
     * 
     * @param
     *            $errCode
     * @return string
     */
    private function getStateInfo($errCode)
    {
        return ! $this->stateMap[$errCode] ? $this->stateMap["UNKNOWN"] : $this->stateMap[$errCode];
    }
    
    /**
     * 重命名文件
     * 
     * @return string
     */
    private function getName()
    {
        return $this->fileName = time() . rand(1 , 10000) . $this->getFileExt();
    }
    
    /**
     * 文件类型检测
     * 
     * @return bool
     */
    private function checkType()
    {
        return in_array($this->getFileExt() , $this->config["allowFiles"]);
    }
    
    /**
     * 文件大小检测
     * 
     * @return bool
     */
    private function checkSize()
    {
        return $this->fileSize <= ($this->config["maxSize"] * 1024);
    }
    
    /**
     * 获取文件扩展名
     * 
     * @return string
     */
    private function getFileExt()
    {
        return strtolower(strrchr($this->file["name"] , '.'));
    }
    
    /**
     * 按照日期自动创建存储文件夹
     * 
     * @return string
     */
    private function getFolder()
    {
        $pathStr = $this->config["savePath"];
        if(strrchr($pathStr , "/") != "/"){
            $pathStr .= "/";
        }
        
        $this->dir = date("Ym");
        $pathStr .= $this->dir;
        if(! file_exists($pathStr)){
            if(! mkdir($pathStr , 0777 , true)){
                return false;
            }
        }
        
        return $pathStr;
    }
}