<?php
/**
 * Mezimedia Tracking Session
 *
 * define session based on Cookie
 *
 * @category   Tracking
 * @package    Tracking_Session
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Session.php,v 1.2 2013/04/16 05:12:42 jjiang Exp $
 */

/**
 * tracking session based on cookie
 *
 * @category   Tracking
 * @package    Tracking_Seesion
 */
class Tracking_Session
{
    /**
     * user id cookie name, stand-alone
     */
    const COOKIE_USER_ID    = 'mm_uid';

    /**
     * session id cookie name, stand-alone
     */
    const COOKIE_SESSION_ID = 'mm_sid';

    /**
     * cookie data
     */
    const COOKIE_DATA       = 'mm_trk';

    /**
     * keyword, just used for SEM
     */
    const COOKIE_KEYWORD    = 'mm_key';

    /**
     * sponsor click info
     */
    const COOKIE_SPONSOR_CLICKS = 'mm_spn';

    /**
     * offer click info
     */
    const COOKIE_OFFER_CLICKS   = 'mm_off';

    /**
     * keyword, just used for SEM
     */
    const COOKIE_PREFIX     = 'mm_';

	/**
	* Cookie for TraType SMCN/JP product traffic only
	*/
	const COOKIE_TRA_TYPE			= "mm_TraType";

    /**
     * cookie array index
     */
    const COOKIE_DATA_TRAFFIC_TYPE      = 0;
    const COOKIE_DATA_SOURCE            = 1;
    const COOKIE_DATA_SOURCE_GROUP      = 2;
    const COOKIE_DATA_UI_TYPE           = 3;
    const COOKIE_DATA_REQUEST_ID        = 4;
    const COOKIE_DATA_LANDING_REFERER   = 5;
    const COOKIE_DATA_LANDING_URI       = 6;
    const COOKIE_DATA_SPONSOR_CLICKS    = 7;

    const MAX_URI_LENGTH                = 512;
    const MAX_REFERER_LENGTH            = 512;

    const MAX_SPONSOR_CLICKS            = 1;

    /**
     * static cookie settings
     *
     * @var string
     */
    protected static $_cookieDomain;
    protected static $_cookiePath;
    protected static $_cookieLife;

    /**
     * stand-alone cookie vars
     * sid, uid, keyword
     */
    protected $_cookie = array();

    /**
     * tracking cookie data
     * visitOrder, trafficType, source,sourceGroup, ui, requestId
     */
    protected $_data = array();

    /**
     * previous request id
     *
     * @var string
     */
    protected $_preRequestId = '';

    /**
     * need update requestId?
     * redir/async would not update requestId
     *
     * @var boolean
     */
    protected $_updateRequestId = TRUE;

    /**
     * page visit order
     *
     * @var int
     */
    protected $_visitOrder;

    /**
     * page visit time
     *
     * @var string
     */
    protected $_visitTime;

    /**
     * is landing(first visit)?
     *
     * @var boolean
     */
    protected $_isLanding = TRUE;

    /**
     * site id
     *
     * @var string
     */
    protected $_siteId;

    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Tracking_Session
     */
    protected static $_instance = NULL;

    /**
     * overloading
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->_cookie[$name]) ? $this->_cookie[$name] : NULL;
    }

    /**
     * overloding
     *
     * @param string $name
     * @parma mixed $value
     */
    public function __set($name, $value)
    {
        $this->_cookie[$name] = $value;
    }

    /**
     * overloding isset()
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_cookie[$name]);
    }

    /**
     * overloding unset()
     *
     * @param string $name
     * @return Tracking_Session
     */
    public function __unset($name)
    {
        unset($this->_cookie[$name]);
        return $this;
    }

    /**
     * serialize cookie
     *
     * keep array index and pad default value
     *
     * @param array $data
     * @return string
     */
    protected function _serialize($data)
    {
        if (count($data) == 0) {
            return '';
        }
        $values = array();
        $keys = array();
        
        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = urlencode($value);
        }
        return implode('|', $values) . '{}' . implode('|', $keys);
    }

    /**
     * unserialize cookie
     *
     * @param string
     * @return array
     */
    protected function _unserialize ($str)
    {
        if (count($str) == 0) {
            return '';
        }
        $arr = array();
    
        if (stripos($str, '{}') === false) {//parse old cookie
            $a = explode('|', $str);
            foreach ($a as $key => $val) {
                if ($val !== NULL) {
                    $arr[$key] = $val;
                }
            }
        } else {
            $rs = explode('{}', $str);
            $keys = explode('|', $rs[1]);
            $values = explode('|', $rs[0]);
    
            foreach ($keys as $key => $val) {
                $arr[$val] = $values[$key];
            }
        }
        return $arr;
    }

    /**
     * get value from array
     * return default value if not exists key
     *
     * @param array $array
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    protected function _getValueFrom($array, $name, $default = NULL)
    {
        return isset($array[$name]) ? $array[$name] : $default;
    }

    /**
     * import data from cookie
     *
     * @return Tracking_Session
     */
    protected function _importCookie()
    {
        foreach ($_COOKIE as $name => $data) {
            if (0 === strpos($name, self::COOKIE_PREFIX)) {
                if ($name == self::COOKIE_DATA) {
                    $this->_data = $this->_unserialize($data);
                } else {
                    $this->_cookie[$name] = $data;
                }
            }
        }

        return $this;
    }

    /**
     * class constructor
     *
     * @return Tracking_Session
     */
    public function __construct($siteId = NULL)
    {
        $this->setSiteId($siteId);

        /* important! make sure that we win the patent litigation  */
        list($microsecond, $second) = explode(' ', microtime());
        $this->_visitTime = date('Y-m-d H:i:s', $second);
        $this->_visitOrder = (integer) (1000 * $microsecond);

        $this->_importCookie();

        $sessionId = $this->getSessionId();
        $this->_isLanding = empty($sessionId);

        $this->_preRequestId = $this->getRequestId();
    }

    /**
     * Singleton instance
     *
     * @return Tracking_Session
     */
    public static function getInstance()
    {
        if (NULL === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * set default setting, use it before getInstance()
     * code:
     *     Tracking_Session::setDefaultSetting(0, '/', '.domain.com');
     *     $session = Tracking_Session::getInstance();
     *
     * @param int $life
     * @param string $path
     * @param string $domain
     */
    public static function setDefaultSetting($life = NULL, $path = NULL, $domain = NULL)
    {
        self::$_cookieLife   = $life;
        self::$_cookiePath   = $path;
        self::$_cookieDomain = $domain;
    }

    /**
     * get value from cookie
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getCookie($name, $default = NULL)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }

    /**
     * get cookie domain
     *
     * @return string
     */
    public function getDomain()
    {
        return self::$_cookieDomain;
    }

    /**
     * get keyword from session
     *
     * @return NULL|string
     */
    public function getKeyword()
    {
        return $this->_getValueFrom($this->_cookie, self::COOKIE_KEYWORD);
    }

    /**
     * set search keyword
     *
     * @param string $keyword
     * @return Tracking_Session
     */
    public function setKeyword($keyword)
    {
        $this->_cookie[self::COOKIE_KEYWORD] = $keyword;

        return $this;
    }

    /**
     * get session keyword clicks
     *
     * @return string
     */
    public function getSponsorClick()
    {
        return $this->_getValueFrom($this->_cookie, self::COOKIE_SPONSOR_CLICKS, '');
    }

    /**
     * save current click infomation
     *
     * @param string $clickInfo
     * @return Tracking_Session
     */
    public function setSponsorClick($clickInfo = null)
    {
        $this->_cookie[self::COOKIE_SPONSOR_CLICKS] = $clickInfo;

        return $this;
    }

    /**
     * get total sponsor clicks
     *
     * @return integer
     */
    public function getSponsorClicks()
    {
        return (integer) $this->_getValueFrom($this->_data, self::COOKIE_DATA_SPONSOR_CLICKS);
    }

    /**
     * save session total sponsor clicks
     *
     * @param integer $click
     * @return Tracking_Session
     */
    public function setSponsorClicks($click = 0)
    {
        $this->_data[self::COOKIE_DATA_SPONSOR_CLICKS] = $click;

        return $this;
    }

    /**
     * get session keyword clicks
     *
     * @return string
     */
    public function getOfferClick()
    {
        return $this->_getValueFrom($this->_cookie, self::COOKIE_OFFER_CLICKS, '');
    }

    /**
     * save current click infomation
     *
     * @param string $clickInfo
     * @return Tracking_Session
     */
    public function setOfferClick($clickInfo = null)
    {
        $this->_cookie[self::COOKIE_OFFER_CLICKS] = $clickInfo;

        return $this;
    }

    /**
     * get previous request id
     *
     * @return NULL|string
     */
    public function getPreRequestId()
    {
        return $this->_preRequestId;
    }

    /**
     * set previous request id
     *
     *
     * @param string $requestId
     * @return NULL|string
     */
    public function setPreRequestId($requestId)
    {
        return $this->_preRequestId = $requestId;
    }

    /**
     * get request id come from cookie
     *
     * @return NULL|string
     */
    public function getRequestId()
    {
        return $this->_getValueFrom($this->_data, self::COOKIE_DATA_REQUEST_ID);
    }

    /**
     * set request id
     *
     * @param string $requestId
     * @return Tracking_Session
     */
    public function setRequestId($requestId)
    {
        $this->_data[self::COOKIE_DATA_REQUEST_ID] = $requestId;

        return $this;
    }

    /**
     * get session id
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->_getValueFrom($this->_cookie, self::COOKIE_SESSION_ID);
    }

    /**
     * set sessionId, stand-alone
     *
     * @param string $sessionId
     * @return Tracking_Session
     */
    public function setSessionId($sessionId)
    {
        $this->_cookie[self::COOKIE_SESSION_ID] = $sessionId;

        return $this;
    }

    /**
     * get site Id
     *
     * @return int
     */
    public function getSiteId()
    {
        return $this->_siteId;
    }

    /**
     * set site id
     *
     * @param integer $siteId
     * @return Tracking_Session
     */
    public function setSiteId($siteId)
    {
        $this->_siteId = (integer)$siteId;

        return $this;
    }

    /**
     * @see self::getLandingReferer()
     *
     * @deprecated
     * @return string
     */
    public function getReferer()
    {
        return $this->getLandingReferer();
    }

    /**
     * @see self::getUiType()
     *
     * @deprecated
     * @return integer
     */
    public function getTestKey()
    {
        return $this->getUiType();
    }

    /**
     * get Source from session
     *
     * @return NULL|string
     */
    public function getSource()
    {
        return $this->_getValueFrom($this->_data, self::COOKIE_DATA_SOURCE);
    }

    /**
     * set source to session
     *
     * @param string $source
     * @return Tracking_Session
     */
    public function setSource($source)
    {
        $this->_data[self::COOKIE_DATA_SOURCE] = $source;

        return $this;
    }

    /**
     * get Source Group from session
     *
     * @return NULL|string
     */
    public function getSourceGroup()
    {
        return $this->_getValueFrom($this->_data, self::COOKIE_DATA_SOURCE_GROUP);
    }

    /**
     * set source group
     *
     * @param string $sourceGroup
     * @return Tracking_Session
     */
    public function setSourceGroup($sourceGroup)
    {
        $this->_data[self::COOKIE_DATA_SOURCE_GROUP] = $sourceGroup;

        return $this;
    }

    /**
     * get http referer from session
     *
     * @return string
     */
    public function getLandingReferer()
    {
        return (string)gzinflate(base64_decode($this->_getValueFrom($this->_data, self::COOKIE_DATA_LANDING_REFERER)));
    }

    /**
     * set http referer for session
     *
     * @return Tracking_Session
     */
    public function setLandingReferer($referer = null)
    {
        $this->_data[self::COOKIE_DATA_LANDING_REFERER] = base64_encode(gzdeflate(substr($referer, 0, self::MAX_REFERER_LENGTH)));

        return $this;
    }

    /**
     * get landing request uri from session
     *
     * @return string
     */
    public function getLandingUri()
    {
        return (string)gzinflate(base64_decode($this->_getValueFrom($this->_data, self::COOKIE_DATA_LANDING_URI)));
    }

    /**
     * set landing request uri for session
     *
     * @return Tracking_Session
     */
    public function setLandingUri($uri = '')
    {
        $this->_data[self::COOKIE_DATA_LANDING_URI] = base64_encode(gzdeflate(substr($uri, 0, self::MAX_URI_LENGTH)));

        return $this;
    }

    /**
     * get test key for UI test
     *
     * @return integer
     */
    public function getUiType()
    {
        return $this->_getValueFrom($this->_data, self::COOKIE_DATA_UI_TYPE);
    }

    /**
     * set user interface
     *
     * @param string $key
     * @return Tracking_Session
     */
    public function setUiType($key)
    {
        $this->_data[self::COOKIE_DATA_UI_TYPE] = $key;

        return $this;
    }

    /**
     * get traffic type
     *
     * @return NULL|integer
     */
    public function getTrafficType()
    {
        return $this->_getValueFrom($this->_data, self::COOKIE_DATA_TRAFFIC_TYPE);
    }

	/*
	 *get and set product traffic type for front end web page rendering logic.
	 *
	 */
	public function getTraType()
    {
        return $this->_getValueFrom($this->_cookie, self::COOKIE_TRA_TYPE);
    }

    public function setTraType($type)
    {
        $this->_cookie[self::COOKIE_TRA_TYPE] = $type;
        return $this;
    }
	

    /**
     * set traffic type
     *
     * @param integer $trafficType
     * @return Tracking_Session
     */
    public function setTrafficType($type)
    {
        $this->_data[self::COOKIE_DATA_TRAFFIC_TYPE] = (integer)$type;

        return $this;
    }

    /**
     * generate unique id
     *
     * @param string $prefix
     * @return string
     */
    public function generateUniqueId($prefix = '_session_')
    {
        return md5(uniqid($prefix . mt_rand(), TRUE));
    }

    /**
     * get User Id from session
     *
     * @return NULL|integer
     */
    public function getUserId()
    {
        return $this->_getValueFrom($this->_cookie, self::COOKIE_USER_ID);
    }

    /**
     * set user id, stand-alone
     *
     * @param integer $userId
     * @return Tracking_Session
     */
    public function setUserId($userId)
    {
        $this->_cookie[self::COOKIE_USER_ID] = $userId;

        return $this;
    }

    /**
     * get visit order
     *
     * @return integer
     */
    public function getVisitOrder()
    {
        return $this->_visitOrder;
    }

    /**
     * get visit time
     *
     * @return string
     */
    public function getVisitTime()
    {
        return $this->_visitTime;
    }

    /**
     * set wether update coookie request id for redir.php process
     *
     * @param bool $flag
     * @return Tracking_Session
     */
    public function setUpdateRequestId($flag = TRUE)
    {
        $this->_updateRequestId = $flag;
        return $this;
    }

    public function setIsLanding($isLanding)
    {
        $this->_isLanding = (bool) $isLanding;
    }

    /**
     * is first incoming ?
     *
     * @return boolean
     */
    public function isLanding()
    {
        return (bool)$this->_isLanding;
    }

    /**
     * is normal traffic ?
     *
     * @return boolean
     */
    public function isNormalTraffic()
    {
        return ($this->getTrafficType() >= 0);
    }

    /**
     * is total sponsor clicks limit?
     *
     * @return bool
     */
    public function isLimitTraffic()
    {
        return  ($this->getSourceGroup()=='sogou') &&
                ((stripos($this->getLandingReferer(), '&pid=') !== false) ||
                 (stripos($this->getLandingReferer(), '?pid=') !== false));
    }

    /**
     * is total sponsor clicks limit?
     *
     * @return bool
     */
    public function isSponsorClickLimit()
    {
        return  ($this->getSponsorClicks()>self::MAX_SPONSOR_CLICKS) && $this->isLimitTraffic();
    }

    /**
     * save session to cookie, update cookie
     *
     * @return Tracking_Session
     */
    public function update()
    {
        $success = TRUE;
        $domain = self::$_cookieDomain;
        $path   = self::$_cookiePath;

        $data = $this->_cookie;
        foreach ($data as $name => $value) {
            if (0 === strpos($name, self::COOKIE_PREFIX)) {
                if (is_array($value)) {
                    $value = $this->_serialize($value);
                }
                $life = ($name == self::COOKIE_USER_ID)
                      ? 5*3600*24*365
                      : self::$_cookieLife;
                if ($life) {
                    $life += time();
                }
                $success = setcookie($name, $value, $life, $path, $domain,false,true) && $success;
            }
        }

        $name = self::COOKIE_DATA;
        $life = (self::$_cookieLife) ? time() + self::$_cookieLife : 0;
        $data = $this->_data;
        if (is_array($data)) {
            if (!$this->_updateRequestId) {
                //no update request id, use pre request id
                $data[self::COOKIE_DATA_REQUEST_ID] = $this->_preRequestId;
            }
            $value = $this->_serialize($data);
        }
        $success = setrawcookie($name, $value, $life, $path, $domain,false,true) && $success;

        return $success;
    }

    /**
     * get Session data as Array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_cookie + $this->_data;
    }
}