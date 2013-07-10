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
 * @version    CVS: $Id: Interface.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */


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