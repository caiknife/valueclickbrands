<?php

/**
 * Mezi_Log_Writer_ApacheNote
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Writer
 */
class Mezi_Log_Writer_ApacheNote extends Mezi_Log_Writer_Abstract
{
    /**
     * array of log events
     */
    public $events = array();

    /**
     * shutdown called?
     */
    public $shutdown = FALSE;

    /**
     * has apache note ?
     *
     * @var bool
     */
    protected $_apache = TRUE;

    /**
     * Holds the Apache note name to log to.
     * @var NULL|string
     */
    protected $_noteName = NULL;

    /**
     * Class Constructor
     *
     * @param  streamOrUrl     Stream or URL to open as a stream
     * @param  mode            Mode, only applicable if a URL is given
     */
    public function __construct($noteName)
    {
        if (!is_string($noteName)) {
            throw new Mezi_Log_Exception($noteName . ' is not a string');
        }
        $this->_noteName = $noteName;
        $this->_formatter = new Mezi_Log_Formatter_Simple();

        $this->_apache = function_exists('apache_note');
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    public function _write($event)
    {
        if ($this->_apache) {
            $line = $this->_formatter->format($event);

            $message = apache_note($this->_noteName);

            $message .= $line;
            apache_note($this->_noteName, $message);
        } else {
            echo "apache_note() not installed!\n";
        }
    }

    /**
     * Record shutdown
     *
     * @return void
     */
    public function shutdown()
    {
        $this->shutdown = TRUE;
    }
}