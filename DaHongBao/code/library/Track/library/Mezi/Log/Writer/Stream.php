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
 * @version    CVS: $Id: Stream.php,v 1.1 2013/04/15 10:56:35 rock Exp $
 */

/**
 * Mezi_Log_Writer_Stream
 *
 * @category   Mezi
 * @package    Mezi_Log
 * @subpackage Writer
 */
class Mezi_Log_Writer_Stream extends Mezi_Log_Writer_Abstract
{
    /**
     * Holds the PHP stream to log to.
     * @var NULL|stream
     */
    protected $_stream = NULL;

    /**
     * Class Constructor
     *
     * @param  streamOrUrl     Stream or URL to open as a stream
     * @param  mode            Mode, only applicable if a URL is given
     */
    public function __construct($streamOrUrl, $mode = 'a')
    {
        if (is_resource($streamOrUrl)) {
            if (get_resource_type($streamOrUrl) != 'stream') {
                throw new Mezi_Log_Exception('Resource is not a stream');
            }

            if ($mode != 'a') {
                throw new Mezi_Log_Exception('Mode cannot be changed on existing streams');
            }

            $this->_stream = $streamOrUrl;
        } else {
            if (! $this->_stream = @fopen($streamOrUrl, $mode, FALSE)) {
                $msg = "\"$streamOrUrl\" cannot be opened with mode \"$mode\"";
                throw new Mezi_Log_Exception($msg);
            }
        }

        $this->_formatter = new Mezi_Log_Formatter_Simple();
    }

    /**
     * Close the stream resource.
     *
     * @return void
     */
    public function shutdown()
    {
        if (is_resource($this->_stream)) {
            fclose($this->_stream);
        }
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        $line = $this->_formatter->format($event);

        if (FALSE === @fwrite($this->_stream, $line)) {
            throw new Mezi_Log_Exception("Unable to write to stream");
        }
    }
}