<?php
/**
 * 
 * lt : logType 
 * ctrk : client tracking
 *
 * @author jjiang
 *
 */
class Tracking_Event
{

    private $_account;

    private $_sessionID;

    private $_requestID;

    private $_preRequestID;

    private $_requestURI;

    private $_requestURL;

    private $_referrer;
    
    private static $_logPV = true;

    private static $_instance = null;
    
    /**
     * Tracking Session
     *
     * @var Tracking_Session
     */
    protected $_session;

    private $_logType = array(
            '_trackPV' => 1,
            '_trackEvent' => 1,
            '_trackTrans' => 1,
            '_trackItem' => 1
    );

    public function __construct ()
    {    
        $this->_account       = isset($_REQUEST['acc']) ? $_REQUEST['acc'] : '';
        $this->_sessionID     = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : '';
        $this->_requestID     = isset($_REQUEST['rid']) ? $_REQUEST['rid'] : '';
        $this->_preRequestID  = isset($_REQUEST['prid']) ? $_REQUEST['prid'] : '';
        $this->_requestURI    = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : '';
        $this->_requestURL    = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
        $this->_referrer      = isset($_REQUEST['refer']) ? $_REQUEST['refer'] : '';
        
        if (empty($this->_sessionID)) {
            $this->_sessionID = Tracking_Session::getInstance()->getSessionId();
        }
        if (empty($this->_requestID)) {
            $this->_requestID = Tracking_Session::getInstance()->getRequestId();
        }
        if (empty($this->_preRequestID)) {
            $this->_preRequestID = Tracking_Session::getInstance()->getPreRequestId();
        }
    }

    public static function getInstance ()
    {
        if (NULL === self::$_instance) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    public function getRequestUri ()
    {
        return $this->_requestURI;
    }

    public function getReferrer ()
    {
        return $this->_referrer;
    }

    public function getRequestUrl ()
    {
        return $this->_requestURL;
    }
    
    public function isLanding ()
    {
        if (isset($_COOKIE['mmc_s'])) {
            return false;
        }
        return true;
    }

    public static function logPV ()
    {
        $rs = null;
        if (self::$_instance == null) {
            $rs = true;
        } else {
            $rs = self::$_logPV;
        }
        self::setLogPv(true);
        return $rs;
    }

    public static function setLogPv ($param = true)
    {
        self::$_logPV = $param;
    }
    
    /* if ('PAGEVISIT' == $method) {
        if(Tracking_Event::logPV()){
            $log->$method($params);
        }
    }else{
        $log->$method($params);
    } */

    public function clientTrack ()
    {
        if (isset($_REQUEST['pv'])) {
             $this->_trackPV();
        }
        if (isset($_REQUEST['LL']) && $_REQUEST['LL'] != null) {
            $ax = explode('{}', $_REQUEST['LL']);
            foreach ($ax as $v) {
                $ay = explode('|*', $v);
                $lt = $ay[0];
                if (isset($this->_logType[$lt])) {
                    $this->{$lt}($ay);
                }
            }
        }
    }

    public function fdecode ($s)
    {
        $a = str_split($s, 2);
        $s = '%' . implode('%', $a);
        return urldecode($s);
    }

    private function _trackPV ()
    {    
        $browserLanguage  = isset($_REQUEST['bl']) && ctype_print($_REQUEST['bl']) ? $_REQUEST['bl'] : '';
        $characterSet     = isset($_REQUEST['cs']) && ctype_print($_REQUEST['cs']) ? $_REQUEST['cs'] : '';
        $timezoneOffset   = isset($_REQUEST['tz']) && ctype_print($_REQUEST['tz']) ? (integer) $_REQUEST['tz'] : 0;
        $screenResolution = isset($_REQUEST['sr']) && ctype_print($_REQUEST['sr']) ? $_REQUEST['sr'] : '';
        $screenColors     = isset($_REQUEST['sc']) && ctype_print($_REQUEST['sc']) ? (integer) $_REQUEST['sc'] : 0;
        $javaEnabled      = isset($_REQUEST['je']) && ctype_print($_REQUEST['je']) ? (integer) $_REQUEST['je'] : 0;
        $cookieEnabled    = isset($_REQUEST['ce']) && ctype_print($_REQUEST['ce']) ? (integer) $_REQUEST['ce'] : 0;
        $jsEnabled        = isset($_REQUEST['js']) && ctype_print($_REQUEST['js']) ? (integer) $_REQUEST['js'] : 1;
        $flashVersion     = isset($_REQUEST['fl']) && ctype_print($_REQUEST['fl']) ? $_REQUEST['fl'] : '';
        $requestTime      = isset($_REQUEST['vt']) && ctype_print($_REQUEST['vt']) ? $_REQUEST['vt'] : '';
        
        try {
            $clientPageVisitLog = array(
                    'sessionId'         => $this->_sessionID,
                    'clientTime'        => $requestTime,
                    'loadTime'          => 0,
                    'cookieEnabled'     => $cookieEnabled,
                    'javaEnabled'       => $javaEnabled,
                    'jsEnabled'         => $jsEnabled,
                    'screenResolution'  => $screenResolution,
                    'timezone'          => $timezoneOffset,
                    'languageSetting'   => $browserLanguage,
                    'flashVersion'      => $flashVersion,
                    'randStr'           => $this->_preRequestID,
                    'curRandStr'        => $this->_requestID,
            );
            Tracking_Logger::getInstance()->clientPageVisit($clientPageVisitLog);
        } catch (Exception $exception){
            Tracking_Logger::getInstance()->error(array(
                    'remark'     => $exception->__toString(),
                    'requestUri' => $this->_requestURI,
                    'referer'    => $this->_referrer
            ));
        }
    }

    private function _trackEvent ($fields)
    {
        try{
            $eventLog = array(
                    'sessionId'		=> $this->_sessionID,
                    'category'		=> $fields[1],
                    'action'		=> $fields[2],
                    'label'			=> $fields[3],
                    'value'			=> $fields[4],
                    'randStr'       => $this->_preRequestID,
                    'curRandStr'    => $this->_requestID,
            );
            Tracking_Logger::getInstance()->event($eventLog);
        } catch (Exception $exception) {
            Tracking_Logger::getInstance()->error(array(
                    'remark'     => $exception->__toString(),
                    'requestUri' => $this->_requestURI,
                    'referer'    => $this->_referrer
            ));
        }
    }
}