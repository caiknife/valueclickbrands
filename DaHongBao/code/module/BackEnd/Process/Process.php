<?php
/*
 * package_name : Process.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: Process.php,v 1.1 2013/04/15 10:57:08 rock Exp $
 */
namespace BackEnd\Process;

use BackEnd\Process\Exception\CouponProcessException;

class Process 
{
    /**
     * 得到后台配置
     * @var array
     */
    protected $config = array();
    
    /**
     * Affiliate 配置
     */
    protected $affiliateConfig = array(
        'CJ'    => 2,
        'CMUS'  => 9,
    );
    
    /**
     * 站点配置
     * @var $siteConfig
     */
     protected $siteConfig = array(
        'CN' => 1,
        'US' => 2
     );
    
    /**
     * 得到CMUS AffliateID
     */
    protected function getCmusAffiliateID() 
    {
        return $this->affiliateConfig['CMUS']; 
    }
    
    /**
     * 是否是CMUS AffiliateID
     * @param int $affiliateID
     * @return true|false
     */
    protected function isCmusAffiliate($affiliateID) 
    {
        $affiliateReserveConfig = array_flip($this->affiliateConfig);
        if ($affiliateReserveConfig[$affiliateID] == 'CMUS') {
            return true;
        }
        return false;
    }
    
    /**
     * 得到US站点ID
     */
    public function getUsSiteID()
    {
        return $this->siteConfig['US'];
    }
    
    /**
     * 判断是否是美国站点
     * @param unknown_type $siteID
     * @return boolean
     */
    public function isUsSiteID($siteID)
    {
        $siteConfigReserverConfig = array_flip($this->siteConfig);
        if ($siteConfigReserverConfig[$siteID] == 'US') {
            return true;
        }
        return false;
    } 
    
    
    /**
     * 得到后台配置
     * @param string $name
     * @return string $value
     */
    protected function getBackEndOptions($name) {
        if (empty($this->config)) {
            $configPath = dirname(__DIR__);
            $file = $configPath . '/Config/dps.config.php';
            if (!file_exists($file)) {
                throw new Exception\DownLoadFeedException('can not found the file' . $file);
            }
            $this->config = \Zend\Config\Factory::fromFile($file);
        }
        return $this->config[$name];
    }
    
    /**
     * 设置serviceManger
     * @param object $sm manger
     */
    public function setServiceManager($sm)
    {
        if (!$sm instanceof \Zend\ServiceManager\ServiceLocatorInterface) {
            throw new \Exception('need init service manager ');
        }
        $this->serviceManager = $sm;
    }
    /**
     * 根据文件名获取文件时间
     * @param string $fileName
     * @return string 文件时间
     */
    protected function getFeedFileDateTime($fileName) 
    {
        return substr(str_replace('.', '', $fileName), -12);
    }
    
    /**
     * 得到serviceManager
     */
    public function getServiceManager() 
    {
        return $this->serviceManager;
    }
    
    /**
     * 创建日志
     * @id int 文件后缀名称
     * @type string 日志类型
     * return string 文件路径
     */
    public function getStdoutFile($id, $merType, $typeName) 
    {
        if (empty($id) || empty($typeName)) {
            throw new CouponProcessException('id or typeName can not empty');
        }
        $logPath = $this->getBackEndOptions($typeName);
        if (!file_exists($logPath)) {
            mkdir($logPath, 0777, true);
        }
        $logFile = $id . '_'. $merType . '.log';
        return $logPath . $logFile;
    }
}
