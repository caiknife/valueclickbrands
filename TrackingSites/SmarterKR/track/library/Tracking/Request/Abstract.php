<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Request
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Abstract.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
 */

/**
 * parse the request and provide the parameters of url
 *
 * @category   Tracking
 * @package    Tracking_Request
 */
abstract class Tracking_Request_Abstract extends Mezi_Request_Http
{
    /**
     * get Client IP
     *
     * @return string
     */
    public function getClientIp()
    {
        if (isset($_SERVER['HTTP_AKAMAI_CLIENT_IP'])) {
            return $_SERVER['HTTP_AKAMAI_CLIENT_IP'];
        } elseif (isset($_SERVER["HTTP_RLNCLIENTIPADDR"])) {
            return $_SERVER['HTTP_RLNCLIENTIPADDR'];
        } else {
            return parent::getClientIp();
        }
    }

    /**
     * Retrieve a tracking parameter
     *
     * @param string $param
     * @param mixed $default Default value to use if key not found
     * @return mixed
     */
    public function getParam($param, $default = NULL)
    {
        if (($result = parent::getParam($param)) === NULL) {
            return $default;
        }

        if (in_array($param, Tracking_Uri::$encodings)) {
            $result = Tracking_Uri::decode($result);
        } elseif (in_array($param, Tracking_Uri::$obfuscatings)) {
            $result = Tracking_Uri::unobfuscate($result);
        }

        return $result;
    }
}