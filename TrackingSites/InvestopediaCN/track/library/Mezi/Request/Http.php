<?php

/**
 * It additionally allows access to values contained in the superglobals
 * as public members, and manages the current Base URL and Request URI.
 * Superglobal values cannot be set on a request object, instead use
 * the setParam/getParam methods to set or retrieve user parameters.
 *
 * @category   Mezi
 * @package    Mezi_Request
 */
class Mezi_Request_Http extends Mezi_Request_Abstract
{

    /**
     * REQUEST_URI
     *
     * @var string;
     */
    protected $_requestUri = null;

    /**
     * Constructor
     *
     * If a $uri is passed, the object will attempt to populate itself using
     * that information.
     *
     * @param string $uri
     * @return void
     * @throws Mezi_Request_Exception when invalid URI passed
     */
    public function __construct($uri = NULL)
    {
        if (NULL !== $uri) {
            if ($this->_validUri($uri)) {
                $this->setRequestUri($uri);
            } else {
                throw new Mezi_Request_Exception('Invalid URI provided to constructor');
            }
        } else {
            $this->setRequestUri();
        }
    }

    /**
     * Set the REQUEST_URI on which the instance operates
     *
     * If no request URI is passed, uses the value in $_SERVER['REQUEST_URI'],
     * $_SERVER['HTTP_X_REWRITE_URL'], or $_SERVER['ORIG_PATH_INFO'] + $_SERVER['QUERY_STRING'].
     *
     * @param string $requestUri
     * @return Mezi_Request_Http
     */
    public function setRequestUri($requestUri = NULL)
    {
        if ($requestUri === NULL) {
            if (isset($GLOBALS['track_requestUri'])) {
                $requestUri = $GLOBALS['track_requestUri'];
            } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // check this first so IIS will catch
                $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
            } elseif (isset($_SERVER['REQUEST_URI'])) {
                $requestUri = $_SERVER['REQUEST_URI'];
            } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0, PHP as CGI
                $requestUri = $_SERVER['ORIG_PATH_INFO'];
                if (! empty($_SERVER['QUERY_STRING'])) {
                    $requestUri .= '?' . $_SERVER['QUERY_STRING'];
                }
            } else {
                return $this;
            }
        } elseif (! is_string($requestUri)) {
            return $this;
        } else {
            /* Set GET items, if available */
            $_GET = array();
            if ((bool) $uriComponents = parse_url($requestUri)) {
                $requestUri = $uriComponents['path'];
                if (isset($uriComponents['query'])) {
                    /* Get key => value pairs and set $_GET */
                    parse_str($uriComponents['query'], $_GET);
                    $requestUri .= '?' . $uriComponents['query'];
                }
            }
        }
        $this->_requestUri = $requestUri;
        return $this;
    }

    /**
     * Returns the REQUEST_URI taking into account
     * platform differences between Apache and IIS
     *
     * @return string
     */
    public function getRequestUri ()
    {
        if (isset($GLOBALS['track_requestUri'])) {
            $this->_requestUri = $GLOBALS['track_requestUri'];
        } else {
            if (empty($this->_requestUri)) {
                $this->setRequestUri();
            }
        }
        return $this->_requestUri;
    }

    /**
     * Access values contained in the superglobals as public members
     * Order of precedence: 1. GET, 2. POST, 3. COOKIE, 4. SERVER, 5. ENV
     *
     * @see http://msdn.microsoft.com/en-us/library/system.web.httprequest.item.aspx
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        switch (TRUE) {
            case isset($this->_params[$key]):
                return $this->_params[$key];
            case isset($_GET[$key]):
                return $_GET[$key];
            case isset($_POST[$key]):
                return $_POST[$key];
            case isset($_COOKIE[$key]):
                return $_COOKIE[$key];
            case ($key == 'REQUEST_URI'):
                return $this->getRequestUri();
            case isset($_SERVER[$key]):
                return $_SERVER[$key];
            case isset($_ENV[$key]):
                return $_ENV[$key];
            default:
                return NULL;
        }
    }

    /**
     * Set values
     *
     * In order to follow {@link __get()}, which operates on a number of
     * superglobals, setting values through overloading is not allowed and will
     * raise an exception. Use setParam() instead.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     * @throws Mezi_Request_Exception
     */
    public function __set($key, $value)
    {
        throw new Mezi_Request_Exception('Setting values in superglobals not allowed; please use setParam()');
    }

    /**
     * Check to see if a property is set
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        switch (TRUE) {
            case isset($this->_params[$key]):
                return TRUE;
            case isset($_GET[$key]):
                return TRUE;
            case isset($_POST[$key]):
                return TRUE;
            case isset($_COOKIE[$key]):
                return TRUE;
            case isset($_SERVER[$key]):
                return TRUE;
            case isset($_ENV[$key]):
                return TRUE;
            default:
                return FALSE;
        }
    }

    /**
     * object to string
     * @return string   return original URL.
     */
    public function __toString ()
    {
        if (isset($GLOBALS['track_requestUri'])) {
            return $GLOBALS['track_requestUri'];
        }
        return $this->_requestUri;
    }

    /**
     * object to string
     * @return string   return original URL.
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * Alias to __get
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->__get($key);
    }

    /**
     * Alias to __set()
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        return $this->__set($key, $value);
    }

    /**
     * Alias to __isset()
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return $this->__isset($key);
    }

/**
     * Validate the current URI from the instance variables. Returns TRUE if and only if all
     * parts pass validation.
     *
     * @param string $uri
     * @return boolean
     */
    private function _validUri($uri)
    {
        return (boolean)parse_url($uri);
    }

    /**
     * Retrieve a member of the $_GET superglobal
     *
     * If no $key is passed, returns the entire $_GET array.
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns NULL if key does not exist
     */
    public function getQuery($key = NULL, $default = NULL)
    {
        if (NULL === $key) {
            return $_GET;
        }

        return (isset($_GET[$key])) ? $_GET[$key] : $default;
    }

    /**
     * Retrieve a member of the $_POST superglobal
     *
     * If no $key is passed, returns the entire $_POST array.
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns NULL if key does not exist
     */
    public function getPost($key = NULL, $default = NULL)
    {
        if (NULL === $key) {
            return $_POST;
        }

        return (isset($_POST[$key])) ? $_POST[$key] : $default;
    }

    /**
     * Retrieve a member of the $_COOKIE superglobal
     *
     * If no $key is passed, returns the entire $_COOKIE array.
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns NULL if key does not exist
     */
    public function getCookie($key = NULL, $default = NULL)
    {
        if (NULL === $key) {
            return $_COOKIE;
        }

        return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
    }

    /**
     * Retrieve a member of the $_SERVER superglobal
     *
     * If no $key is passed, returns the entire $_SERVER array.
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns NULL if key does not exist
     */
    public function getServer($key = NULL, $default = NULL)
    {
        if (NULL === $key) {
            return $_SERVER;
        }

        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }

    /**
     * Retrieve a member of the $_ENV superglobal
     *
     * If no $key is passed, returns the entire $_ENV array.
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed Returns NULL if key does not exist
     */
    public function getEnv($key = NULL, $default = NULL)
    {
        if (NULL === $key) {
            return $_ENV;
        }

        return (isset($_ENV[$key])) ? $_ENV[$key] : $default;
    }

    /**
     * Retrieve a parameter
     *
     * Retrieves a parameter from the instance. Priority is in the order of
     * userland parameters (see {@link setParam()}), $_GET, $_POST. If a
     * parameter matching the $key is not found, NULL is returned.
     *
     * @param mixed $key
     * @param mixed $default Default value to use if key not found
     * @return mixed
     */
    public function getParam($key, $default = NULL)
    {
        if ((boolean)$result = parent::getParam($key)) {
            return $result;
        }

        if (isset($_GET[$key])) {
            return $_GET[$key];
        } elseif (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    /**
     * Retrieve an array of parameters
     *
     * Retrieves a merged array of parameters, with precedence of userland
     * params (see {@link setParam()}), $_GET, $POST (i.e., values in the
     * userland params will take precedence over all others).
     *
     * @return array
     */
    public function getParams()
    {
        $result = parent::getParams();
        if (isset($_GET) && is_array($_GET)) {
            $result += $_GET;
        }
        if (isset($_POST) && is_array($_POST)) {
            $result += $_POST;
        }
        return $result;
    }

    /**
     * Return the method by which the request was made
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->getServer('REQUEST_METHOD');
    }

    /**
     * Was the request made by POST?
     *
     * @return boolean
     */
    public function isPost()
    {
        if ('POST' == $this->getMethod()) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Was the request made by GET?
     *
     * @return boolean
     */
    public function isGet()
    {
        if ('GET' == $this->getMethod()) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Was the request made by PUT?
     *
     * @return boolean
     */
    public function isPut()
    {
        if ('PUT' == $this->getMethod()) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Was the request made by DELETE?
     *
     * @return boolean
     */
    public function isDelete()
    {
        if ('DELETE' == $this->getMethod()) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Was the request made by HEAD?
     *
     * @return boolean
     */
    public function isHead()
    {
        if ('HEAD' == $this->getMethod()) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Was the request made by OPTIONS?
     *
     * @return boolean
     */
    public function isOptions()
    {
        if ('OPTIONS' == $this->getMethod()) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Return the raw body of the request, if present
     *
     * @return string|FALSE Raw body, or FALSE if not present
     */
    public function getRawBody()
    {
        $body = file_get_contents('php://input');

        if (strlen(trim($body)) > 0) {
            return $body;
        }

        return FALSE;
    }

    /**
     * Return the value of the given HTTP header. Pass the header name as the
     * plain, HTTP-specified header name. Ex.: Ask for 'Accept' to get the
     * Accept header, 'Accept-Encoding' to get the Accept-Encoding header.
     *
     * @param string $header HTTP header name
     * @return string|FALSE HTTP header value, or FALSE if not found
     * @throws Mezi_Request_Exception
     */
    public function getHeader($header)
    {
        if (empty($header)) {
            throw new Mezi_Request_Exception('An HTTP header name is required');
        }

        /* Try to get it from the $_SERVER array first */
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (!empty($_SERVER[$temp])) {
            return $_SERVER[$temp];
        }

        /* This seems to be the only way to get the Authorization header on Apache */
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (!empty($headers[$header])) {
                return $headers[$header];
            }
        }

        return FALSE;
    }

    /**
     * Is the request a Javascript XMLHttpRequest?
     *
     * Should work with Prototype/Script.aculo.us, possibly others.
     *
     * @return boolean
     */
    public function isXmlHttpRequest()
    {
        return ($this->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
    }

    /**
     * Is this a Flash request?
     *
     * @return bool
     */
    public function isFlashRequest()
    {
        return ($this->getHeader('USER_AGENT') == 'Shockwave Flash');
    }

    /**
     * get Client IP
     *
     * @return string
     */
    public function getClientIp()
    {
        if (isset($_SERVER["HTTP_RLNCLIENTIPADDR"])) {
            $clientIp = $_SERVER["HTTP_RLNCLIENTIPADDR"];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $clientIp = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        } else {
            $clientIp = '';
        }

        return $clientIp;
    }

    /**
     * get HTTP user agent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '';
    }

    /**
     * get HTTP referer
     *
     * @return string
     */
    public function getHttpReferer()
    {
        if (isset($GLOBALS['track_referrer'])) {
            return $GLOBALS['track_referrer'];
        }
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }
}