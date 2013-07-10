<?php

/**
 * Tracking_Response represents an HTTP 1.0 / 1.1 response message. It
 * includes easy access to all the response's different elemts, as well as some
 * convenience methods for parsing and validating HTTP responses.
 *
 * @category   Tracking
 * @package    Tracking_Response
 * @subpackage Response
 */
class Tracking_Response extends Mezi_Response_Http
{
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Tracking_Response
     */
    protected static $_instance = NULL;

    /**
     * Singleton instance
     *
     * @return Tracking_Response
     */
    public static function getInstance()
    {
        if (NULL === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}