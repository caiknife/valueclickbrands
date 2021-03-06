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
 * @version    CVS: $Id: Simple.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */


/**
 * @see Mezi_Log_Formatter_Interface
 */
require_once 'Mezi/Log/Formatter/Interface.php';

/**
 * @see Mezi_Log_Exception
 */
require_once 'Mezi/Log/Exception.php';


/**
 * Mezi_Log_Formatter_Simple
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Formatter
 */
class Mezi_Log_Formatter_Simple implements Mezi_Log_Formatter_Interface
{
    /**
     * @var string
     */
    protected $_format;

    /**
     * Class constructor
     *
     * @param  NULL|string  $format  Format specifier for log messages
     * @throws Mezi_Log_Exception
     */
    public function __construct($format = NULL)
    {
        if ($format === NULL) {
            $format = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;
        }

        if (!is_string($format)) {
            throw new Mezi_Log_Exception('Format must be a string');
        }

        $this->_format = $format;
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
            if ((is_object($value) && !method_exists($value,'__toString')) || is_array($value)) {
                $value = gettype($value);
            }

            $output = str_replace("%$name%", $value, $output);
        }
        return $output;
    }
}