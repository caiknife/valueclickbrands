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
 * @version    CVS: $Id: Message.php,v 1.1 2013/04/15 10:56:34 rock Exp $
 */

/**
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Filter
 * @version    $Id: Message.php,v 1.1 2013/04/15 10:56:34 rock Exp $
 */
class Mezi_Log_Filter_Message implements Mezi_Log_Filter_Interface
{
    /**
     * @var string
     */
    protected $_regexp;

    /**
     * Filter out any log messages not matching $regexp.
     *
     * @param  string  $regexp     Regular expression to test the log message
     * @throws Mezi_Log_Exception
     */
    public function __construct($regexp)
    {
        if (@preg_match($regexp, '') === FALSE) {
            throw new Mezi_Log_Exception("Invalid regular expression '$regexp'");
        }
        $this->_regexp = $regexp;
    }

    /**
     * Returns TRUE to accept the message, FALSE to block it.
     *
     * @param  array    $event    event data
     * @return boolean            accepted?
     */
    public function accept($event)
    {
        return preg_match($this->_regexp, $event['message']) > 0;
    }
}