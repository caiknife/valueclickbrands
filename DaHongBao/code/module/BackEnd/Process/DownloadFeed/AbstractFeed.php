<?php
/*
 * package_name : AbstractFeed.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: AbstractFeed.php,v 1.1 2013/04/15 10:57:08 rock Exp $
 */
namespace BackEnd\Process\DownloadFeed;

use Zend\Http\Client;
use Custom\Util\Utilities;
use BackEnd\Process\Exception;
use BackEnd\Process\Process;

abstract class AbstractFeed extends Process
{
    /**
     * @var object
     */
    protected $client;
    
    /**
     * set curl options
     * @var array
     */
    protected $options = array(
        'timeout'         => 120,
        'adapter'         => 'Zend\Http\Client\Adapter\Curl',
    );
     
    /**
     * dps global config 
     * @var array
     */
    protected $config;
     
    /**
     * get title pattern from desc
     * @var array
     */
    protected $titlePatternArr = array(
        '/\\$[\d\,\.]+\s?off\s?\\$\d+/i',
        '/[\d\,\.]+\%\s?off\s?\\$\d+/i',
        '/[\d\,\.]+\%\soff\ssitewide/i',
        '/[\d\,\.]+\%\soff\syour\sentire\sorder/i',
        '/save\s\\$[\d\,\.]+\on\syour\sentire\sorder/i',
        '/[\d\,\.]+\%\soff\sorder[s]?\sof\s\\$[\d\,\.]+/i',
        '/save\s\\$[\d\,\.]+\son\sorder[s]?\sof\s\\$[\d\,\.]+/i',
        '/free\sshipping\son\sorder[s]?\sover\s\\$[\d\,\.]+/i',
        '/free\sshipping\son\sorder[s]?\sof\s\\$[\d\,\.]+/i',
        '/free\sshipping\swith\sany\sorder[s]?\sof\s\\$[\d\,\.]+/i',
        '/\\$[\d\,\.]+\soff\s[a-z\s0-9]{1,20}\s\\$[\d\,\.]+/i',
        '/[\d\,\.]+\%\soff\s[a-z\s0-9]{1,20}\s\\$[\d\,\.]+/i',
        '/[\d\,\.]+\%\soff\s\+\sfree\sshipping/i',
        '/\\$[\d\,\.]+\soff\s\+\sfree\sshipping/i',
        '/[\d\,\.]+\%\soff\splus\sfree\sshipping/i',
        '/\s*\\$[\d\,\.]+\soff\splus\sfree\sshipping/i',
        '/[\d\,\.]+\%\soff\sselect/i',
        '/up\sto\s[\d\,\.]+\%\soff/i',
        '/save\sup\sto\s\\$[\d\,\.]+/i',
        '/get\sup\sto\s\\$[\d\,\.]+/i',
        '/save\s[\d\,\.]+\%\soff/i',
        '/save\san\sextra\s[\d\,\.]+\%\soff/i',
        '/save\san\sextra\s\\$[\d\,\.]+/',
        '/save\s[\d\,\.]+\%/i',
        '/take\s[\d\,\.]+\%\soff/i',
        '/get\s[\d\,\.]+\%\soff/i',
        '/save\s\\$[\d\,\.]+\soff/i',
        '/save\s\\$[\d\,\.]+\s/i',
        '/take\s\\$[\d\,\.]+\soff/i',
        '/get\s\\$[\d\,\.]+\soff/i',
        '/[\d\,\.]+\%\soff/i',
        '/\\$[\d\,\.]+\%\soff/i',
        '/Buy more save more/i',
        '/free shipping/i'
    );
      
    /**
     * 存放之前feed md5 记录的文件名
     * @var string
     */
    protected $oldFeedMd5Name = 'Md5.php';
    
    /**
     * 是否开启DEBUG
     * @var boolean
     */
    protected $isDebug = false;
    
    /**
     * 下载商家FEED数量
     */
    protected $debugCount = 500;

    /**
     * 统计运行信息
     * @param array
     */
    protected $statInfo = array(
        'SuccessCnt'        => 0,
        'ExistRows'         => 0,
        'ParseErrorCnt'     => 0,
        'TotalProdCnt'      => 0,
        'ExpireCnt'         => 0,
        'FileName'          => 0,
    );
    
    /**
     * @param  array|null  $options
     */
    public function __construct($options = null) 
    {
        $this->client = new Client();
        if ($options !== null) {
            $this->options = array_merge($this->options, $options);
        }
        $this->client->setOptions($this->options);
    }
    
    /**
     * 打印输出日志
     * @return string
     */
    public function statInfo()
    {
        return $this->statInfo;
    }
    
    /**
     * 从描述信息中得到标题
     * @param string $desc
     * @return string|false title
     */
    public function getTitleByDesc($desc) 
    {
        $title = '';
        if (empty($desc)) {
            return $title;
        }
        $title = '';
        foreach ($this->titlePatternArr as $pattern) {
            if (preg_match($pattern, $desc, $matchArr)) {
                if (strstr($matchArr[0], "off select")) {
                        $title = str_ireplace("select", "select items", $matchArr[0]);
                } else {
                    $title = $matchArr[0];
                }
                break;
            }
        }
        return $title;
    }
    
    /**
     * Feed 文件名
     * @return string
     */
    public function getFeedFileName() 
    {
        $fileName = 'Feed.csv.' . date('Ymd.Hi');
        $this->statInfo['FileName'] = $fileName;
        return $fileName;
    }
    
    /**
     * 得到之前更新的FEED MD5 文件内容
     * @return string
     */
    public function getMd5File() 
    {
        $file = $this->getFeedFilePath() . $this->oldFeedMd5Name;
        if (!file_exists(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        return Utilities::getPhpArrayCache($file);
    }
    
    /**
     * 存储新的 FEED md5 记录
     */
    public function setMd5File($md5Arr = array()) 
    {
        if (empty($md5Arr)) {
            return false;
        }
        $file = $this->getFeedFilePath() . $this->oldFeedMd5Name;
        Utilities::setPhpArrayCache($file, $md5Arr);
    }
    
    /**
     * 存储feed 目录
     * @return string 
     */
    public function getFeedFilePath() 
    {
        $feedPath = $this->getBackEndOptions('affiliateFeedPath') . $this->feedFolderName . '/';
        if (!file_exists($feedPath)) {
            mkdir($feedPath, 0777, true);
        }
        return $feedPath;
    }
    
    /**
     * 格式化原始数据
     * @param string $data
     */
    protected function formatData($data, $type = 'string') 
    {
        if (empty($data)) {
            return '';
        }
        $data = trim($data);
        if ($type == 'int') {
            return (int) $data;
        }
        return $data;
    }
    
    /**
     * 根据URi 下载feed 文件
     * @param string $uri
     * @return false|string  放回xml内容
     */
    public function getUriContent($uri) 
    {
        $startTime = time();
        while (true) {
            Utilities::println('downloading feed '. $uri);
            $this->client->setUri($uri);
            $response = $this->client->send();
            if ($response->isOk() === true) {
                break;
            }
            //超过5分钟下载失败
            if (time() - $startTime > 5 * 60) {
                throw new Exception\DownLoadFeedException('feed file Failed, Url = ' . $uri);
            }
        }
        
        return $response->getBody();;
    }
    
    /**
     * 设置debug状态，方便脚本调试
     * @param boolean $debug
     */
    public function setDebug($debug) 
    {
        $this->isDebug = !empty($debug) ? true : false;
    }
}
?>