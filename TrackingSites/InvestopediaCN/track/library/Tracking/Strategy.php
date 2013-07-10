<?php

/**
 * Tracking Strategy with ip and useragent
 *
 * @category   Tracking
 * @package    Tracking_Strategy
 */
class Tracking_Strategy
{

    /**
     * black ip list
     * @var array
     */
    private $_listIp = array();

    /**
     * robot agent list
     * @var array
     */
    private $_listUserAgent = array();

    /**
     * Tracking Strategy
     * filter ip and useragent based balck list
     *
     * @return Tracking_Strategy
     */
    public function __construct()
    {
        $trackingConfig   =   Mezi_Config::getInstance()->tracking;

        $this->_listIp        = $this->_loadList($trackingConfig->root . $trackingConfig->strategy->ip->denied);
        $this->_listUserAgent = $this->_loadList($trackingConfig->root . $trackingConfig->strategy->useragent->denied);
    }

    /**
     * is private ip?
     *
     * Private IP 2006-7-25
     * 10.0.0.0 - 10.255.255.255
     * 172.16.0.0 - 172.31.255.255
     * 192.168.0.0 - 192.168.255.255
     *
     * @param string $ipAddr
     * @return boolean
     */
    public function isPrivateIP($ipAddr)
    {
        $long = ip2long($ipAddr);
        if (($long >= ip2long('192.168.0.0')
          && $long <= ip2long('192.168.255.255'))
         || ($long >= ip2long('10.0.0.0')
          && $long <= ip2long('10.255.255.255'))
         || ($long >= ip2long('172.16.0.0')
          && $long <= ip2long('172.31.255.255'))
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * is fraud ip in list?
     *
     * @param string $ipAddr
     * @return boolean
     */
    public function isFraudIP($ipAddr)
    {
        foreach ($this->_listIp as $search) {
            if ($ipAddr === $search) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * is robot in list?
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isRobot($userAgent)
    {
        foreach ($this->_listUserAgent as $search) {
            if (stripos($userAgent, $search) !== FALSE) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * empty useragent?
     *
     * @param string $userAgent
     * @return boolean
     */
    public function emptyUserAgent($userAgent)
    {
        return empty($userAgent);
    }

    /**
     * load list from file
     * strip comment by #
     *
     * @param string $filename
     * @return array
     */
    private function _loadList($filename)
    {
        $result = array();

        if (is_readable($filename) && ($data = file($filename, TRUE))) {
            foreach ($data as $line) {
                $line = trim($line);
                if ($line == '' || $line{0} == '#') {continue;}
                $result[] = $line;
            }
        }

        return $result;
    }
}
