<?php
/**
 * Tracking Incoming
 *
 * @category   Tracking
 * @package    Tracking_Logger
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Logger.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
 */


/**
 * Tracking_Logger
 *
 * @category   Tracking
 * @package    Tracking_Logger
 */
class Tracking_Logger
{
    /**
     * apache access log title
     */
    const APACHE_ACCCESS_TITLE = 'trackinginfo';

    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Tracking_Logger
     */
    protected static $_instance = NULL;

    /**
     * log even data stack
     *
     * @var Mezi_Log_Writer_Mock
     */
    protected $_mock = NULL;

    /**
     * logger
     *
     * @var Mezi_Log
     */
    protected $_logger = NULL;

    /**
     * apache note log format
     *
     * @var string
     */
    protected $_logFormat = NULL;   //"%message%{&&}";

    /**
     * custom tracking message format list
     *
     * @var array
     */
    protected $_msgFormats = array();

    /**
     * display debug info?
     *
     * @var bool
     */
    protected $_debug = FALSE;

    protected $_config = array();

    /* Compatible old DB Tracking */
    protected $_db = NULL;

    /**
     * search engine type
     *
     * @var array
     */
    private $_searchEngineType = array(
        Tracking_Constant::SERVICE_SMARTER          => 1,
        Tracking_Constant::SERVICE_SHOPZILLA        => 2,
        Tracking_Constant::SERVICE_SHOPPING         => 2,
        Tracking_Constant::SERVICE_PRICERUNNER      => 3,
        Tracking_Constant::SERVICE_YAHOOSHOPPING    => 4,
        Tracking_Constant::SERVICE_COUPONMOUNTAIN   => 7,
    );

    /**
     * constructor
     *
     * @return Tracking_Logger
     */
    public function __construct()
    {
        $this->_startTime = microtime(TRUE);

        $writer = new Mezi_Log_Writer_Mock;
        $this->_logger = new Mezi_Log($writer);

        $configTracking =   Mezi_Config::getInstance()->tracking;
        $this->loadConfig($configTracking->root . $configTracking->logger);

        $this->_mock   = $writer;
    }

    /**
     * destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $this->_mock = NULL;
        $this->_logger = NULL;

        if ($this->_debug == 'track') {
            // @codeCoverageIgnoreStart
            $_endTime = microtime(TRUE);
            $rawlog = apache_note('trackinginfo');
            echo "\n<pre>\n",
                 __METHOD__, "()\n",
                 " time:", $_endTime - $this->_startTime, "\n",
                 " apache_note('trackinginfo'):\n", $rawlog, "\n",
                 " length=", strlen($rawlog);
            echo " logger config:\n";
            var_dump($this->_config);
            echo " include files:\n";
            var_dump(get_included_files());
            echo "\n</pre>\n";
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Singleton instance
     *
     * @return Tracking_Logger
     */
    public static function getInstance()
    {
        if (NULL === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * register shutdown callback
     *
     * @return Tracking_Logger
     */
    public function registerShutdown()
    {
        register_shutdown_function(array($this, 'shutdown'));
        return $this;
    }

    /**
     * shutdown callback, save all log to storge
     *
     * @return void
     */
    public function shutdown()
    {
        try {
            $connectStatus = connection_status();
            $responseCode  = Tracking_Response::getInstance()->getHttpResponseCode();

            if ($this->_debug == 'track') {
                echo "\n<pre>\n";
                echo __METHOD__, "()\n";
                echo " PID: ", getmypid(), "\n";
                echo " Apache Note: ", function_exists('apache_note') ? "Yes" : "No";
                echo " Current work dir: ", getcwd(), "\n";
                echo " Connection Status: {$connectStatus}\n",
                     " Http Status: {$responseCode}\n";
                echo " logger config file: {Mezi_Config::getInstance()->tracking->root}{Mezi_Config::getInstance()->tracking->logger}\n";
                var_dump($this->_config);
                echo "\n</pre>\n";
            }

            $data = $this->_mock->events;

            //save log here
            $log = new Mezi_Log();

            foreach ($this->_logList as $priorityName => $priority) {
                    $log->addPriority($priorityName, $priority);
            }

            $writer = new Mezi_Log_Writer_ApacheNote('trackinginfo');
            $writer->setFormatter(new Tracking_Log_Formatter_Message($this->_logFormat, $this->_msgFormats));
            $writer->addFilter(new Mezi_Log_Filter_Priority((int)min($this->_logList), ">="));
            $writer->addFilter(new Mezi_Log_Filter_Priority((int)max($this->_logList), "<="));
            $log->addWriter($writer);

            $session = Tracking_Session::getInstance();
            $this->_defaultParams = array(
                'sessionId'  => $session->getSessionId(),
                'visitTime'  => $session->getVisitTime(),
                'siteId'     => $session->getSiteId(),
                'valid'      => $session->getTrafficType(),
                'randStr'    => $session->getPreRequestId(),
                'curRandStr' => $session->getRequestId(),
            );
            //unset($session);

            foreach ($data as $events) {
                $method = $events['priorityName'];
                $params = $events['message'];

                /** @todo replace the ugly patch with Mezi_Log_Filter **/
                if ((in_array($method, array('SPONSORIMPRESSION', 'OFFERIMPRESSION', 'AFFILIATEIMPRESSION', 'MODULEIMPRESSION'))) && (!Tracking_Session::getInstance()->isNormalTraffic())) {
                    continue;
                }
                if ('SPONSORIMPRESSION' == $method) {
                    $p = array_change_key_case($params);
                    if (isset($p['impressioncount']) && 0 == $p['impressioncount']) {
                        continue;
                    }
                }

                if (is_array($params)) {
                    /** update Incoming_Log and Page_Visit_Log HttpStatus */
                    $params = array_merge($this->_defaultParams, $params);
                    if ('PAGEVISIT' == $method) {
                        /** update page response code and php connect status */
                        $params['httpStatus']    = $responseCode;
                        $params['connectStatus'] = $connectStatus;
                    } elseif (('SEARCH' == $method) || ('AFFILIATETRANSFER' == $method)) {
                        $p = array_change_key_case($params);
                        $params['resulttype']        = ((int)$p['iscached']) | ((int)$p['isrealsearch']) | ((int)$p['searchenginetype']) | ((int)$p['resulttype']);
                        $params['iscached']          = isset($p['iscached']) && ((int)$p['iscached'] & Tracking_Constant::IS_CACHED)              ? 'YES': 'NO';
						
						$params['isrealsearch']      = isset($p['isrealsearch']) && ((int)$p['isrealsearch'] & Tracking_Constant::IS_REAL_SEARCH) ? 'YES': 'NO';

						$isRealSearch = isset($p['isrealsearch'])? $p['isrealsearch']: 'NO';
						if($isRealSearch =="0"){
							$isRealSearch = 'NO';
						}else if($isRealSearch =="1"){
							$isRealSearch = 'YES';
						}
						
                        $params['isrealsearch']      = $isRealSearch;

                        $params['searchenginetype']  = isset($this->_searchEngineType[(int)$p['searchenginetype']]) ? $this->_searchEngineType[(int)$p['searchenginetype']] : Tracking_Constant::SERVICE_UNKNOWN;
                    } elseif ('INCOMING' == $method) {
                        $params['httpStatus']    = $responseCode;
                    }
                }
                $log->$method($params);
            }
        } catch (Exception $exception) {
            if ($this->_debug) {
                echo "\n<pre>Exception:\n", $exception, "\n</pre>\n";
            }
        }
    }

    /**
     * magic method call
     *
     * @param string $method
     * @param array $params
     */
    public function __call($method, $params)
    {
        $params = array_shift($params);
        $this->_logger->$method($params);
    }

    /**
     * set debug option, display debug info ?
     *
     * @param bool $debug
     * @return Tracking_Logger
     */
    public function setDebug($debug = FALSE)
    {
        $this->_debug = $debug;

        return $this;
    }

    /**
     * set log format
     * %message%
     *
     * @param string|array $format
     * @return Tracking_Logger
     */
    public function setLogFormat($format)
    {
        if (is_string($format)) {
            $this->_logFormat = $format;
        } else {
            $logFormat    = isset($format['logFormat']) ? $format['logFormat'] : '%message%';
            $logSeparator = isset($format['logSeparator']) ? $format['logSeparator'] : PHP_EOL;

            $this->_logFormat = $logFormat . $logSeparator;
        }

        return $this;
    }

    /**
     * set message formats
     *
     * @param array $formatList
     * @return Tracking_Logger
     */
    public function setMessageFormats($formatList = array())
    {
        $msgFormats = (array)$formatList;

        $this->_msgFormats = array_change_key_case($msgFormats, CASE_UPPER);

        return $this;
    }

    /**
     * add prioritys
     *
     * @param array $list
     * @return unknown
     */
    public function addPrioritys($list)
    {
        $this->_logList = (array)$list;
        if (is_array($list)) {
            foreach ($list as $priorityName => $priority) {
                $this->_logger->addPriority($priorityName, $priority);
            }
        }

        return $this;
    }

    /**
     * set options
     *
     * @param array $options
     * @return Tracking_Logger
     */
    public function setOptions($options)
    {
        foreach ($options as $key => $value) {
            $this->_config[$key] = $value;
        }
        return $this;
    }

    /**
     * load config from ini file
     *
     * @param string $configFile
     * @return Tracking_Logger
     */
    public function loadConfig($configFile)
    {
        $this->_config = parse_ini_file($configFile, TRUE);

        $this->addPrioritys($this->getConfig('logList'))
             ->setLogFormat($this->getConfig('logFormat'))
             ->setMessageFormats($this->getConfig('messageFormat'));
        return $this;
    }

    /**
     * get config value
     *
     * @param string $name
     * @return mixed
     */
    public function getConfig($name)
    {
        return isset($this->_config[$name]) ? $this->_config[$name] : NULL;
    }

}