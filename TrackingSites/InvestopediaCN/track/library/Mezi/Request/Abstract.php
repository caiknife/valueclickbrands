<?php

/**
 * Base class for Mezi request
 *
 * @category   Mezi
 * @package    Mezi_Request
 */
abstract class Mezi_Request_Abstract {

     /**
     * Instance parameters
     * @var array
     */
    protected $_params = array();

    /**
     * Get an action parameter
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed
     */
    public function getParam($key, $default = NULL)
    {
        $key = (string) $key;
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        return $default;
    }

    /**
     * Set an action parameter
     *
     * A $value of NULL will unset the $key if it exists
     *
     * @param string $key
     * @param mixed $value
     * @return Mezi_Request_Abstract
     */
    public function setParam($key, $value)
    {
        $key = (string) $key;

        if ((NULL === $value) && isset($this->_params[$key])) {
            unset($this->_params[$key]);
        } elseif (NULL !== $value) {
            $this->_params[$key] = $value;
        }

        return $this;
    }

    /**
     * Get all action parameters
     *
     * @return array
     */
     public function getParams()
     {
         return $this->_params;
     }

    /**
     * Set action parameters en masse; does not overwrite
     *
     * Null values will unset the associated key.
     *
     * @param array $params
     * @return Mezi_Request_Abstract
     */
    public function setParams($params)
    {
        foreach ($params as $key => $value) {
            $this->setParam($key, $value);
        }

        return $this;
    }
}