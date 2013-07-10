<?php
/**
 * Mezimedia Tracking Uri
 *
 * @category   Tracking
 * @package    Tracking_Uri
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Client.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
 */

/**
 * An adapter class for Tracking_Client based on the curl extension.
 * Curl requires libcurl. See for full requirements the PHP manual: http://php.net/curl
 *
 * @category   Tracking
 * @package    Tracking_Client
 */
class Tracking_Client
{
    const RESPONSE_OK           = 0;
    const RESPONSE_TIMEOUT      = -1;
    const RESPONSE_EXCEPTION    = -100;

    /**
     * Configuration array, set using the constructor or using ::setConfig()
     *
     * @var array
     */
    protected $_config = array(
        'method'            => 'GET',
        'referer'           => null,
        'useragent'         => null,
        'timeout'           => 0,
        'username'          => null,
        'password'          => null,
    );

    /**
     * The adapter used to preform the actual connection to the server
     */
    protected $_curl        = null;

    /**
     * Request URI
     *
     * @var string
     */
    protected $_uri;

    /**
     * Contructor method. Will create a new HTTP client. Accepts the target
     * URL and optionally configuration array.
     *
     * @param string $uri
     * @param array $config Configuration key-value pairs.
     */
    public function __construct($uri = null, $config = null)
    {
        if (!extension_loaded('curl')) {
            throw new Tracking_Exception('cURL extension has to be loaded to use this Zend_Http_Client adapter.');
        }
        $this->_curl = curl_init($this->_uri = $uri);

        $this->setConfig($config);
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        if (!is_null($this->_curl)) {
            curl_close($this->_curl);
        }
    }

    /**
     * Set configuration parameters for this HTTP client
     *
     * @param array $config
     * @return Zend_Http_Client
     * @throws Zend_Http_Client_Exception
     */
    public function setConfig($config = array())
    {
        if (empty($config)) { return $this; }

        if (!is_array($config)) {
            throw new Tracking_Exception('Expected array parameter, given ' . gettype($config));
        }

        foreach ($config as $key => $value) {
            $this->_config[strtolower($key)] = $value;
        }

        return $this;
    }

    /**
     * Send the HTTP request and return an HTTP response object
     *
     * @param string | array
     * @return string
     * @throws Tracking_Exception
     */
    public function request($data = null)
    {
        curl_setopt_array($this->_curl, array(
            CURLOPT_TIMEOUT         => (integer) $this->_config['timeout'],
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_FAILONERROR     => true,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 10,
        ));

        if (!is_null($this->_config['referer'])) {
        	curl_setopt($this->_curl, CURLOPT_REFERER, $this->_config['referer']);
        }

        if (!is_null($this->_config['useragent'])) {
            curl_setopt($this->_curl, CURLOPT_USERAGENT, $this->_config['useragent']);
        }

        if (!is_null($this->_config['username']) && !is_null($this->_config['password'])) {
            curl_setopt($this->_curl, CURLOPT_USERPWD, "{$this->_config['username']}:{$this->_config['password']}");
        }

        switch (strtoupper($this->_config['method'])) {
            case 'POST':
                curl_setopt($this->_curl, CURLOPT_POST, true);
                curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $data);
                break;

            case 'GET':
            default:
                curl_setopt($this->_curl, CURLOPT_HTTPGET, true);

                break;
        }

        if (($response = curl_exec($this->_curl)) === false) {
            if (curl_errno($this->_curl) == CURLE_OPERATION_TIMEOUTED) {
                $errorCode  = self::RESPONSE_TIMEOUT;
            } else {
                $errorCode  = self::RESPONSE_EXCEPTION;
            }

        	throw new Tracking_Exception(curl_error($this->_curl), $errorCode);
        }

        return $response;
    }
}