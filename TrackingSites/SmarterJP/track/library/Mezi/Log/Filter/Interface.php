<?php
/**
 * Mezi Framework
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Filter
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Interface.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */


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