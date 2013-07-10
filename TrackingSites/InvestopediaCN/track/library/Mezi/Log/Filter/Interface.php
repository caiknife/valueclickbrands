<?php

/**
 * Mezi_Log_Filter_Interface
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Filter
 */
interface Mezi_Log_Filter_Interface
{
    /**
     * Returns TRUE to accept the message, FALSE to block it.
     *
     * @param  array    $event    event data
     * @return boolean            accepted?
     */
    public function accept($event);
}