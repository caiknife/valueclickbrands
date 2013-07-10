<?php

/**
 * Mezi_Log_Formatter_Interface
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Formatter
 */
interface Mezi_Log_Formatter_Interface
{
    /**
     * Formats data into a single line to be written by the writer.
     *
     * @param  array    $event    event data
     * @return string             formatted line to write to the log
     */
    public function format($event);
}