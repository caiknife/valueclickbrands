<?php
/**
 * Mezi Framework
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Formatter
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Message.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */


/**
 * @see Mezi_Log_Formatter_Interface
 */
require_once 'Mezi/Log/Formatter/Interface.php';


/**
 * Mezi_Log_Formatter_Message
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Formatter
 */
class Mezi_Log_Formatter_Message implements Mezi_Log_Formatter_Interface
{
    /**
     * @var string
     */
    protected $_format;

    protected $_messageFormat;

    /**
     * Class constructor
     *
     * @param  NULL|string  $format  Format specifier for log messages
     * @throws Mezi_Log_Exception
     */
    public function __construct($format = NULL, $messageFormat = NULL)
    {
        if ($format === NULL) {
            $format = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;
        }

        if (! is_string($format)) {
            throw new Mezi_Log_Exception('Format must be a string');
        }

        $this->_format = $format;
        $this->_messageFormat = $messageFormat;
    }

    /**
     * Formats data into a single line to be written by the writer.
     *
     * @param  array    $event    event data
     * @return string             formatted line to write to the log
     */
    public function format($event)
    {
        $output = $this->_format;
        foreach ($event as $name => $value) {
            if ('message' == $name) {
                $value = $this->formatMessage($event['priorityName'], $value);
            }
            $output = str_replace("%$name%", $value, $output);
        }
        return $output;
    }

    /**
     * format message
     *
     * @param string $priorityName
     * @param array $params
     * @return string
     */
    public function formatMessage($priorityName, $params)
    {
        if (is_string($params)) {
            return $params;
        }

        $output = isset($this->_messageFormat[$priorityName]) ? $this->_messageFormat[$priorityName] : '';

        foreach ($params as $name => $value) {
            $output = str_ireplace("<$name>", $value, $output);
        }
        return $output;
    }
}