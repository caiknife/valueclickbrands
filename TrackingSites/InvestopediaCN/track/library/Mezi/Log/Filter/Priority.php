<?php

/**
 * Mezi_Log_Filter_Priority
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Filter
 */
class Mezi_Log_Filter_Priority implements Mezi_Log_Filter_Interface
{
    /**
     * @var integer
     */
    protected $_priority;

    /**
     * @var string
     */
    protected $_operator;

    /**
     * Filter logging by $priority.  By default, it will accept any log
     * event whose priority value is less than or equal to $priority.
     *
     * @param  integer  $priority  Priority
     * @param  string   $operator  Comparison operator
     * @throws Mezi_Log_Exception
     */
    public function __construct($priority, $operator = '<=')
    {
        if (! is_integer($priority)) {
            throw new Mezi_Log_Exception('Priority must be an integer');
        }

        $this->_priority = $priority;
        $this->_operator = $operator;
    }

    /**
     * Returns TRUE to accept the message, FALSE to block it.
     *
     * @param  array    $event    event data
     * @return boolean            accepted?
     */
    public function accept($event)
    {
        return version_compare($event['priority'], $this->_priority, $this->_operator);
    }
}