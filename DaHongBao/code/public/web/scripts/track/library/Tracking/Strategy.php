<?php
/**
 * Mezimedia Tracking Service
 *
 * @category   Tracking
 * @package    Tracking_Service
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2009 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Strategy.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 */

class Tracking_Strategy
{
    /**
     * ip strategy, white or black
     *
     * @var array
     */
    private $_ipStrategy = array();

    /**
     * user agent strategy, white or black
     *
     * @var array
     */
    private $_userAgentStrategy = array();

    /**
     * contructor
     *
     * @param array $option
     */
    public function __construct ($option = null)
    {
        if (!empty($option['ip'])) {
            $this->_loadIpStrategy($option['ip']);
        }

        if (!empty($option['useragent'])) {
            $this->_loadUserAgentStrategy($option['useragent']);
        }
    }

    /**
     * is good user agent?
     *
     * @param string $userAgent
     * @return bool
     */
    public function isGoodUserAgent($userAgent)
    {
        return !empty($this->_userAgentStrategy['white']) && $this->_isMatch($userAgent, $this->_userAgentStrategy['white']);
    }

    /**
     * is bad user agent?
     *
     * @param string $userAgent
     * @return bool
     */
    public function isBadUserAgent($userAgent)
    {
        return !empty($this->_userAgentStrategy['black']) && $this->_isMatch($userAgent, $this->_userAgentStrategy['black']);
    }

    /**
     * is empty useragent?
     *
     * @param string $userAgent
     * @return bool
     */
    public function isEmptyUserAgent($userAgent)
    {
        return empty($userAgent);
    }

    /**
     * is good ip?
     *
     * @param string $userAgent
     * @return bool
     */
    public function isGoodIp($ip)
    {
        return !empty($this->_ipStrategy['white']) && $this->_isMatch($ip, $this->_ipStrategy['white']);
    }

    /**
     * is bad ip?
     *
     * @param string $userAgent
     * @return bool
     */
    public function isBadIp($ip)
    {
        return !empty($this->_ipStrategy['black']) && $this->_isMatch($ip, $this->_ipStrategy['black']);
    }

    /**
     * is private ip?
     *
     * @param string $ip
     * @return bool
     */
    public function isPrivateIP($ip)
    {
        return  (($ip >= "192.168.0.0") && ($ip <= "192.168.255.255")) ||
                (($ip >= "10.0.0.0")    && ($ip <= "10.255.255.255")) ||
                (($ip >= "172.16.0.0")  && ($ip <= "172.31.255.255")) ;
    }

    /**
     * load ip strategy from file
     *
     * @param string $filename
     * @return array
     */
    private function _loadIpStrategy($filename)
    {
        if ((bool) $strategy = $this->_loadStrategy($filename)) {
            $this->_ipStrategy = $this->_parseStrategy($strategy);
        }
    }

    /**
     * load user agent strategy from file
     *
     * @param string $filename
     * @return array
     */
    private function _loadUserAgentStrategy($filename)
    {
        if ((bool) $strategy = $this->_loadStrategy($filename)) {
            $this->_userAgentStrategy = $this->_parseStrategy($strategy);
        }
    }

    /**
     * match exactly the rules
     *
     * @param string $element
     * @param array $rules
     * @return bool
     */
    private function _isExactMatch($element, $rules)
    {
        if (empty($rules)) { return false; }

        return in_array($element, $rules);
    }

    /**
     * match broadly the rules
     *
     * @param string $element
     * @param array $rules
     * @return bool
     */
    private function _isBroadMatch($element, $rules)
    {
        if (empty($rules)) { return false; }

        foreach ($rules as $rule) {
            if (stripos($element, $rule) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * match the rules, whether exactly or broadly
     *
     * @param string $element
     * @param array $rules
     * @return bool
     */
    private function _isMatch($element, $rules)
    {
        $result = false;

        if (!empty($rules['exact'])){
            $result = $this->_isExactMatch($element, $rules['exact']);
        }

        if (!$result && !empty($rules['broad'])) {
            $result = $this->_isBroadMatch($element, $rules['broad']);
        }

        return $result;
    }

    /**
     * is a white rule?
     *
     * @param string $rule
     * @return bool
     */
    private function _isWhiteRule($rule)
    {
        return (!empty($rule) && $rule[0] == '+');
    }

    /**
     * is a black rule ?
     *
     * @param string $rule
     * @return bool
     */
    private function _isBlackRule($rule)
    {
        return (!empty($rule) && $rule[0] == '-');
    }

    /**
     * is an exact rule?
     *
     * @param string $rule
     * @return bool
     */
    private function _isExactRule($rule)
    {
        return (!empty($rule) && $rule[0] == '=');
    }

    /**
     * is a broad rule?
     *
     * @param string $rule
     * @return bool
     */
    private function _isBroadRule($rule)
    {
        return (!empty($rule) && $rule[0] == '~');
    }

    /**
     * is a rule of strategy?
     *
     * @param string $line
     */
    private function _isRule($rule)
    {
        return (!empty($rule) && ($rule[0] != '#' || $rule[0] != ';'));
    }

    /**
     * remove the first flag of rule
     *
     * @param string $rule
     * @return string
     */
    private function _cleanFlag($rule)
    {
        return substr($rule, 1);
    }

    /**
     * parse rules from strategy
     *
     * @param array $strategy
     * @return array
     */
    private function _parseStrategy($strategy)
    {
        $whiteRules = array_map(array($this, '_cleanFlag'), array_filter($strategy, array($this, '_isWhiteRule')));
        $whiteRules = array(
            'exact' => array_map(array($this, '_cleanFlag'), array_filter($whiteRules, array($this, '_isExactRule'))),
            'broad' => array_map(array($this, '_cleanFlag'), array_filter($whiteRules, array($this, '_isBroadRule'))),
        );

        $blackRules = array_map(array($this, '_cleanFlag'), array_filter($strategy, array($this, '_isBlackRule')));
        $blackRules = array(
            'exact' => array_map(array($this, '_cleanFlag'), array_filter($blackRules, array($this, '_isExactRule'))),
            'broad' => array_map(array($this, '_cleanFlag'), array_filter($blackRules, array($this, '_isBroadRule'))),
        );

        return array('white' => $whiteRules, 'black' => $blackRules);
    }

    /**
     * load strategy from file
     *
     * @param string $filename
     * @return array
     */
    private function _loadStrategy($filename)
    {
        if (empty($filename)) { return array(); }

        if (!is_readable($filename)) {
            throw new Tracking_Service_Exception("strategy file: {$filename} is NOT exist OR NOT readable!");
        }

        if (is_readable($filename) && ($data = file($filename, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES))) {
            return array_filter(array_map('trim', $data), array($this, '_isRule'));
        } else {
            return array();
        }
    }
}