<?php

/**
 * Mezi_Log_Writer_Mock
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Writer
 */
class Mezi_Log_Writer_Mock extends Mezi_Log_Writer_Abstract
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
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    public function _write($event)
    {
        $this->events[] = $event;
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