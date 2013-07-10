<?php
/**
 * Mezi Framework
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Writer
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Mock.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
 */


/**
 * @see Mezi_Log_Writer_Abstract
 */
require_once 'Mezi/Log/Writer/Abstract.php';


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