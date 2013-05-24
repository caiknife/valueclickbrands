<?php
/**
 * set error info to log file, if debug opened dump error info to browse
 *
 * @category   Tracking
 * @package    Tracking_FraudFilter
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: FraudFilter.php,v 1.1 2013/04/15 10:56:35 rock Exp $
 */

/**
 * Tracking_FraudFilter
 *
 * @category   Tracking
 * @package    Tracking_FraudFilter
 */
class Tracking_FraudFilter
{
    const TRAFFIC_UNKNOWN_USERAGENT = 1;
    const TRAFFIC_CLICK_TOO_MANY    = 2;

    protected $_ip;
    protected $_useragnet;
    protected $_source;
    protected $_key;
    protected $_keyword;

    protected $_status;
    protected $_trafficType = NULL;
    protected $_test = array();

    protected $_maxLife = 0;
    protected $_maxClicks = 0;
    protected $_limitRange      = 10;

    /**
     * status
     *
     * @var Memcache
     */
    protected $_memcache = NULL;
    protected $_ipWhiteList = array();
    protected $_userAgents = array();
    protected $_controlSources = array();
    protected $_keywordsLimit   = array();

    /**
     * Constructor
     *
     * @param string $ip
     * @param string $useragent
     * @param string $source
     */
    public function __construct($ip, $useragent, $source, $keyword='')
    {
        $this->_ip          = $ip;
        $this->_useragnet   = $useragent;
        $this->_source      = $source = empty($source) ? 'www' : strtolower($source);
        $this->_key         = $ip . '@' . $source;
        $this->_keyword   = $keyword;

        $config = Mezi_Config::getInstance()->fraudFilter;

        $this->_memcache    = $memcache = new Memcache();
        $memcache->connect($config->memcache->host, $config->memcache->port);

        /** load Resource from config */
        $configTracking     = Mezi_Config::getInstance()->tracking;
        $this->_ipWhiteList = $this->_loadCachedList($configTracking->root . $configTracking->strategy->ip->permit);
        $this->_userAgents = $this->_loadCachedList($configTracking->root . $configTracking->strategy->useragent->permit);
        $this->_controlSources = $config->source;
        $this->_keywordsLimit   = $this->_loadCsvFile($config->keywordlimit, "\t");
        $this->_limitRange      = isset($config->limitrange) ? (integer) $config->limitrange : 10 ;

        $this->_maxLife = isset($this->_controlSources->{$source}->hours)
                        ? $this->_controlSources->{$source}->hours * 3600
                        : 0;

        $this->_maxClicks = isset($this->_controlSources->{$source}->clicks)
                        ? $this->_controlSources->{$source}->clicks
                        : 0;
    }

    private function _loadCachedList($filename)
    {
        $memcache = $this->_memcache;
        if (!$resource = $memcache->get($filename)) {
            $list = file($filename);

            $resource = array();
            foreach ($list as $text) {
                $text = trim($text);
                /* skip empty line or comment */
                if (empty($text) || ';' == $text{1}) continue;
                $resource[] = trim($text);
            }
            $memcache->set($filename, $resource, null, 3600);
        }
        return $resource;
    }

    private function _loadCsvFile($filename, $delimiter)
    {
        $result = array();

        if (is_readable($filename) && $handle = fopen($filename, 'r')){
            while (($values = fgetcsv($handle, 1024, $delimiter)) !== FALSE) {
                /* skip empty line or comment */
                if (empty($values) || ';' == $values[0][0]) continue;

                $result[strtolower("{$values[0]}_{$values[1]}")] = array('ssl'=>3600*$values[2], 'limit'=>(integer)$values[3]);
            }
        }

        return $result;
    }

    public function getType()
    {
        return $this->_trafficType;
    }

    public function getCount()
    {
        $this->_status = $status = $this->_memcache->get($this->_key);
        if (is_array($status)) {
            return $status['clicks'];
        }else {
            return 0;
        }
    }

    public function setCount($count)
    {
        $status = $this->_memcache->get($this->_key);
        if (!$status) {
            $status = array('time' => time(), 'clicks' => $count);
        } else {
            $status['clicks'] = $count;
        }
        $expire = $status['time'] + $this->_maxLife;
        $this->_memcache->set($this->_key, $status, null, $expire);
        $this->_status = $status;

        return $this;
    }

    public function incCount()
    {
        $status = $this->_memcache->get($this->_key);

        if (!$status) {
            $status = array('time' => time(), 'clicks' => 1);
        } else {
            ++$status['clicks'];
        }
        $expire = $status['time'] + $this->_maxLife;
        $this->_memcache->set($this->_key, $status, null, $expire);
        $this->_status = $status;
    }

    public function incSourceKeywordCount()
    {
        $sourceKeyword  =   strtolower("{$this->_source}_{$this->_keyword}");
        if (isset($this->_keywordsLimit[$sourceKeyword])) {
            if (FALSE === $this->_memcache->get($sourceKeyword)) {  // NOT be cached
                $this->_memcache->set($sourceKeyword, 1, 0, $this->_keywordsLimit[$sourceKeyword]['ssl']);
            } else {
                $this->_memcache->increment($sourceKeyword, 1);
            }
        }

        return $this;
    }

    /**
     * is Valid Traffic or not ?
     *
     * @return bool
     */
    public function isValid()
    {
        $result = FALSE;

        if ($this->isControlSource()) {
            if (!$this->validUserAgent()) {
                $result = FALSE;
            } else if ($this->validIP()) {  //passby if ip in white list
                $result = TRUE;
            } else if ($this->validClick()) {
                $result = TRUE;
            }
        } else {
            $result = TRUE;
        }

        return $result && $this->validKeyword();
    }

    /**
     * ip in white list
     *
     * @return bool
     */
    public function validIP()
    {
        /* LA stupid requirement, detect null IP -_- */
        if (empty($this->_ip)) {
            $this->_test[] = __METHOD__. "():TRUE (empty ip)";
            return FALSE;
        }
        foreach ($this->_ipWhiteList as $cidr) {
            if ($this->_netmatch(trim($cidr), $this->_ip)) {
                $this->_test[] = __METHOD__. "():TRUE (allow ip $cidr)";
                return TRUE;
            }
        }
        $this->_test[] = __METHOD__. '():FALSE (limit IP)';
        return FALSE;
    }

    /**
     * user agent in white list
     *
     * @return bool
     */
    public function validUserAgent()
    {
        if (empty($this->_useragnet)) return FALSE;

        foreach ($this->_userAgents as $allow) {
            if (preg_match(trim($allow), $this->_useragnet)) {
                $this->_test[] = __METHOD__. "():TRUE (allow UA $allow)";
                return TRUE;
            }
        }

        $this->_trafficType = self::TRAFFIC_UNKNOWN_USERAGENT;
        $this->_test[] = __METHOD__. '():FALSE (limit UA)';
        return FALSE;
    }

    /**
     * is source need control?
     *
     * @return bool
     */
    public function isControlSource()
    {
        if (isset($this->_controlSources->{$this->_source})) {
            $this->_test[] = __METHOD__. '():TRUE (limit Source)';
            return TRUE;
        } else {
            $this->_test[] = __METHOD__. '():FALSE';
            return FALSE;
        }
    }

    /**
     * click has too many
     *
     * @return bool
     */
    public function validClick()
    {
        //check data in memcache
        $this->_status = $status = $this->_memcache->get($this->_key);

        if (!empty($status)) {
            $time = $status['time'];
            if (time() > $time + $this->_maxLife) {
                $this->_test[] = __METHOD__. '():TRUE (time > maxLife)';
                return TRUE;
            } else if ($status['clicks'] < $this->_maxClicks) {
                $this->_test[] = __METHOD__. '():TRUE (Clicks < maxClicks)';
                return TRUE;
            } else {
                $this->_trafficType = self::TRAFFIC_CLICK_TOO_MANY;
                $this->_test[] = __METHOD__. '():FALSE (Click too many in lifetime)';
                return FALSE;
            }
        }
        $this->_test[] = __METHOD__. '():TRUE (No Cache)';
        return TRUE;
    }


    /**
     * keyword has too many
     *
     * @return bool
     */
    public function validKeyword()
    {
        $sourceKeyword  =   strtolower("{$this->_source}_{$this->_keyword}");
        if (!isset($this->_keywordsLimit[$sourceKeyword])) {
            return TRUE;
        }

        if ($this->_keywordsLimit[$sourceKeyword]['limit']<=0) {
            return FALSE;
        }

        // check data in memcache
        $clicks = $this->_memcache->get($sourceKeyword);
        if (FALSE === $clicks) {  // NOT be cached
            return TRUE;
        }

        $range  = mt_rand(100-$this->_limitRange, 100+$this->_limitRange) / 100;
        return  ++$clicks <= $this->_keywordsLimit[$sourceKeyword]['limit'] * $range;
    }

    /**
     * ip match with CIDR
     *
     * @param string $cidr
     * @param ip $ip
     * @return bool
     */
    protected function _netmatch($cidr, $ip)
    {
        list ($net, $mask) = explode('/', $cidr);
        return ( ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($net);
    }
}