<?php

abstract class Tracking_Revenue_Abstract extends Tracking_Request_Abstract {
    protected $_hostname = null;

    protected $_script_name = null;
    
    protected $_GA_property = null;
    
    protected $_GAParams = null;
    
    protected $_session = null;
    
    protected $_source = null;
    
    protected $_sourceAbbr = 'organic';
    
    protected $_tagParams = array();
    
    protected $_debug = false;
    
    protected $_landingReferral = null;
    
    protected $_sponsorType = 'GOOGLE';
    
    protected $_clickType = null;
    
    protected abstract function get_ad_click_revenue();
    
    protected $_curl_exception_info = null;
    
    protected $_requestUrl = null;
    
    public function __construct() {
        parent::__construct();
        
        if (preg_match('/^([a-z0-9]*)?(dev|demo|beta)[0-9]*\./i', $this->getServer('HTTP_HOST'))) {
            $this->_debug = true;
        } elseif ($this->getParam('track_traffic') === 'test') {
            $this->_debug = true;
        }
        
        $gaFile = TRACKING_ROOT_PATH.'/config/GA.ini';
        if (is_file($gaFile)) {
            $this->_GAParams = parse_ini_file($gaFile, true);
        } else {
            $message = $gaFile . ' file does not exist.';
            $this->logMessage($message);
            if ($this->_debug) {
                die($message);
            }
            exit();
        }
        
        $this->_session = Tracking_Session::getInstance();
        $this->_hostname = $this->getHostname();
        $this->_GA_property = $this->get_google_analytics_property();
        
        $this->_source = $this->_session->getSource();
        $this->_landingReferral = $this->_session->getReferer();
        
        if ($this->_debug) {
            $this->vd('source value is ' . $this->_source);
            $this->vd('landingreferral value is ' . $this->_landingReferral);
        }
    }
    
    public function set_sourceAbbr() {
        if (null != $this->_source) { // traffic with a source
            foreach ($this->_GAParams['traffic_partner'] as $key => $val) {
                if (false !== stripos($this->_source, $key)) {
                    $sourceAbbr = explode('#', $val);
                    $this->_sourceAbbr = $sourceAbbr[1];
                    break;
                }    
            }
            
            if ($this->_sourceAbbr == 'organic') {
                $this->_sourceAbbr = 'sem-' . $this->_source;
            }
        } else { // traffic without a source
            if ($this->_landingReferral != null) { // has a landing referer
                $urlInfo = parse_url($this->_landingReferral);
                $host = $urlInfo['host'];
                foreach ($this->_GAParams['SEO_traffic'] as $key => $val) {
                    if (stripos($host, $key) !== false) {
                        $this->_sourceAbbr = 'org-' . substr(1, -1);
                        break;
                    }
                }
                
                if ($this->_sourceAbbr == 'organic') {
                    $this->_sourceAbbr = 'ref-' . $host;
                }
            } else { // has no landing referer
                $this->_sourceAbbr = 'dir';
            }
        }
    }
    
    public function pre($x, $y=null, $color='#000000') {
        if ($this->_debug) {
            echo '<pre style="color:' . $color . '">';
            if ($y) {
                echo "<span style=\"color:#C00;font-weight:bold\">$y</span>";
            }
            print_r($x);
            echo '</pre>';
        }
    }
    
    public function vd($x, $y=null, $color='#000000') {
        if ($this->_debug) {
            echo '<pre style="color:' . $color . '">';
            if ($y) {
                echo "<span style=\"color:#C00;font-weight:bold\">$y</span>";
            }
            var_dump($x);
            echo '</pre>';
        }
    }
    
    public function logError($message=null, $requestUri=null, $referer=null) {
        $log = array(
            'remark' => $message,
            'requesturi' => ($requestUri == null) ? $this->getRequestUri() : $requestUri,
            'referer' => ($referer == null) ? $this->getHttpReferer() : $referer,
        );
        Tracking_Logger::getInstance()->Error($log);
        
        $this->pre($log, null, '#f00');
    }
    
    protected function _getParam($key, $default=null) {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }
        return $this->getParam($key, $default);
    }
    
    protected function _setParams($params=array()) {
        $keyPrefix = $this->getServer('SERVER_ADDR') . '-' . $this->getClientIp();
        $outgoingId = $this->_session->generateUniqueId('userId' . $keyPrefix);
        
        $siteId = $this->_session->getSiteId();
        /* if (isset($params['clickAdType'])) {
            $clickAdType = $params['clickAdType'];
            if ($clickAdType == 'afc' && substr($siteId, - 2) != '00') {
                $siteId .= '00';
            }
        } */
        $this->_tagParams = array(
            'clicktype' => $this->_clickType,
            'siteid'    => $siteId,
            'kw'        => $params['kw'],
            'country'   => $params['country'],
            'source'    => ($this->_source == null) ? '' : $this->_source,
            'ref'       => $this->_landingReferral,
            //'sessionid' => $this->_session->getSessionId(),
            //'userip' => $this->getClientIp(),
            //'channeltag' => $params['channelTag'],
            //'landingurl' => $this->_session->getLandingUri(),
            //'useragent' => $this->getUserAgent(),
        );
        $arr = array(
            'outgoingId'         => $outgoingId,
            'config_track_event' => true,
            'config_track_ecomm' => true,
        );
        
        $params = array_merge($this->_tagParams, $arr, $params);
        foreach ($params as $key => $val) {
            $this->_params[$key] = $val;
        }
    }
    
    protected function get_google_analytics_property() {
        return isset($this->_GAParams['GA_properties'][$this->_hostname]) ? $this->_GAParams['GA_properties'][$this->_hostname] : null;
    }
    
    protected function getHostname() {
        return preg_replace('/^([a-z0-9]*)?(dev|demo|beta|www)[0-9]*\./i', '', $this->getServer('HTTP_HOST'));
    }
    
    protected function _getClickType($sl) {
        $ct = null;
        $sl = strtolower($sl);
        if ($sl == 'google-csa') {
            $ct = 'google_csa';
        } elseif ($sl == 'google-afc') {
            $ct = 'google_afc';
        } elseif ($sl == 'yahoo-in' || $sl == 'yahoo-sl') {
            $ct = 'yahoo_sl';
        } elseif ($sl == 'merchant-sdc') {
            $ct = 'merchant_sdc';
        } elseif ($sl == 'merchant-cpc') {
            $ct = 'merchant_cpc';
        }
        return $ct;
    }
    
	/**
     * request url data
     *
     * @param integer $url            
     * @param integer $timeout            
     */
    protected function _requestData($url, $timeout=1) {
        $handle = curl_init();
        
        /* set URL and other appropriate options */
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);
        //curl_setopt($handle, CURLOPT_TIMEOUT_MS, $timeout);//more than equal to 5.23 PHP version available
        
        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        
        /* grab URL */
        $result = curl_exec($handle);
        
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        
        /* close cURL resource, and free up system resources */
        curl_close($handle);
      
        //if (empty($result)) {
            //throw new Exception(curl_error($handle), curl_errno($handle));
        //}
        if ($httpCode >= 400) {
            throw new Exception("response code - {$httpCode}");
        }
        
        return $result;
    }
    
	/**
     * The url for beta
     * http://semtag101.beta.wl.mezimedia.com:8899/getrpc/?siteid=12&kw=xx&source=campaign28_keyword_mobilephone&country=us&ref=&clicktype=yahoo_sl
     * The url for production
     * http://semtag101.la.mezimedia.com:8899/getrpc/?siteid=12&kw=xx&source=campaign28_keyword_mobilephone&country=us&ref=&clicktype=yahoo_sl
     *
     * @return mixed
     */
    protected function _requestChannelTag() {
        $this->pre($_SERVER);
        try {
            $this->_tagParams['clicktype'] = $this->_clickType;
            
            $requestHost = 'http://10.16.194.10:8890/getrpc/?';
            
            $requestUrl = $requestHost . http_build_query($this->_tagParams);
            if ($this->_debug == true) {
                $requestType = (stripos($this->_params['revPartner'], Tracking_Constant::SPONSOR_GOOGLE) !== false) ? 'G-STS' : 'Y-STS';
                $this->pre($requestType . " Url: " . $requestUrl, null, '#20C');
            }
            
            return $this->_requestData($requestUrl);
        } catch (Exception $exception) {
            $this->logError('request channel tag error: {' . $exception->getMessage() . '}', $requestUrl, $this->getRequestUri());
        }
    }

    /**
     *  $requestHost = 'http://semtag101.beta.wl.mezimedia.com:8890/setgtag/?';//dev
        $requestHost = 'http://10.16.194.10:8890/setgtag/?';//production
     * @return mixed
     * Note: old version in settag 
     */
    protected function _requestChannelTag_v10() {
        try {
           /*  if (stripos($this->_params['revPartner'], Tracking_Constant::SPONSOR_GOOGLE) !== false) {
                $requestHost = rtrim(TAG_SERVICE_GSTS_BASE_URL, '/') . '/setgtag/?';
            } else {
                $requestHost = rtrim(TAG_SERVICE_BASE_URL, '/') . '/settag/?';
            }  */

            $requestHost = rtrim(TAG_SERVICE_GSTS_BASE_URL, '/') . '/setgtag/?';

            $requestUrl = $requestHost . http_build_query($this->_tagParams);
            if ($this->_debug == true) {
                $requestType = (stripos($this->_params['revPartner'], Tracking_Constant::SPONSOR_GOOGLE) !== false) ? 'G-STS' : 'Y-STS';
                $this->pre($requestType . " Url: " . $requestUrl, null, '#20C');
            }
            
            return $this->_requestData($requestUrl);
        } catch (Exception $exception) {
            $this->logError('request channel tag error: {' . $exception->getMessage() . '}', $requestUrl, $this->getRequestUri());
        }
    }

    /**
     * request for channel tag to sem service
     *
     * @param string $keyword            
     * @param string $country            
     * @param string $channelTag            
     * @param string $sponsorType            
     */
    protected function _requestChannelTag2($keyword, $country, $channelTag, $sponsorType) {
        $session = Tracking_Session::getInstance();
        $query = array(
            'siteid'     => $session->getSiteId(),
            'kw'         => $keyword,
            'source'     => $session->getSource(),
            'ref'        => $session->getLandingReferer(),
            'country'    => $country,
            'landingurl' => $session->getLandingUri(),
            'userip'     => $this->_request->getClientIp(),
            'useragent'  => $this->_request->getUserAgent(),
            'sessionid'  => $session->getSessionId(),
            'channeltag' => $channelTag
        );
        
        if ($sponsorType == Tracking_Constant::SPONSOR_GOOGLE) {
            $requestHost = rtrim(TAG_SERVICE_GSTS_BASE_URL, '/') . '/setgtag/?';
        } else {
            $requestHost = rtrim(TAG_SERVICE_BASE_URL, '/') . '/settag/?';
        }
        
        $requestUrl = $requestHost . http_build_query($query);
        if ($this->_request->getCookie('debug') == 'yes') {
            $requestType = ($sponsorType == Tracking_Constant::SPONSOR_GOOGLE) ? 'G-STS' : 'Y-STS';
            echo $requestType . " Url: " . $requestUrl;
        }
        
        try {
            $rpc = $this->_requestData($requestUrl);
        } catch (Exception $exception) {
            $this->_logger->Error(array(
                'remark'     => "request channel tag error: {$exception->getMessage()}",
                'requestUri' => $requestUrl,
                'referer'    => $this->_request->getRequestUri()
            ));
        }
    }

    protected function getXmlData($xml, $type='string') {
        $pos = strpos($xml, 'xml');
        if ($pos) {
            if ($type == 'string') {
                $xmlCode = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
            } else {
                $xmlCode = simplexml_load_file($xml);
            }
            $arrayCode = $this->get_object_vars_final($xmlCode);
            return $arrayCode;
        } else {
            return '';
        }
    }

    protected function get_object_vars_final($obj) {
        if (is_object($obj)) {
            $obj = get_object_vars($obj);
        }
        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $obj[$key] = self::get_object_vars_final($value);
            }
        }
        return $obj;
    }
}