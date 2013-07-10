<?php

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
        return isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) ? $_SERVER["HTTP_RLNCLIENTIPADDR"] : parent::getClientIp();
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