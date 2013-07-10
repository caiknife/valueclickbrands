<?php

/**
 * Mezi_Log_Writer_Abstract
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Writer
 */
abstract class Mezi_Log_Writer_Abstract
{
    /**
     * @var array of Mezi_Log_Filter_Interface
     */
    protected $_filters = array();

    /**
     * Formats the log message before writing.
     * @var Mezi_Log_Formatter_Interface
     */
    protected $_formatter;

    /**
     * Add a filter specific to this writer.
     *
     * @param  Mezi_Log_Filter_Interface  $filter
     * @return void
     */
    public function addFilter($filter)
    {
        if (is_integer($filter)) {
            $filter = new Mezi_Log_Filter_Priority($filter);
        }

        $this->_filters[] = $filter;
    }

    /**
     * Log a message to this writer.
     *
     * @param  array     $event  log data event
     * @return void
     */
    public function write($event)
    {
        foreach ($this->_filters as $filter) {
            if (! $filter->accept($event)) {
                return;
            }
        }

        // exception occurs on error
        $this->_write($event);
    }

    /**
     * Set a new formatter for this writer
     *
     * @param  Mezi_Log_Formatter_Interface $formatter
     * @return void
     */
    public function setFormatter($formatter) {
        $this->_formatter = $formatter;
    }

    /**
     * Perform shutdown activites such as closing open resources
     *
     * @return void
     */
    public function shutdown() {}

    /**
     * Write a message to the log.
     *
     * @param  array  $event  log data event
     * @return void
     */
    abstract protected function _write($event);
}