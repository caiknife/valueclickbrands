<?php
/**
 * tracking boostrap file
 *
 * @category   Tracking
 * @package    Tracking_Incoming
 * @version    CVS: $Id: async.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))). '/etc/define.php';

/**
 * log message
 *
 * @param string $message
 * @param Tracking_Request_Outgoing $request
 * @return void
 */
function logMessage($message, Tracking_Request_Outgoing $request){
    $logger = Tracking_Logger::getInstance();
    $log = array(
        'remark' => $message,
        'requesturi' => $request->getRequestUri(),
        'referer'    => $request->getHttpReferer(),
    );
    $logger->Error($log);
}

try {
    $request = new Tracking_Request_Outgoing();

    $asyncType = $request->getParam(Tracking_Uri::ASYNC_TYPE, '');
    if (!preg_match('/^[a-z0-9_]+$/i', $asyncType)) {
        throw new Tracking_Async_Exception("error asunc type: $asyncType");
    }

    $redirector = Tracking_Async::factory($asyncType);
    $redirector->dispatch();
} catch (Exception $exception){
    logMessage($exception->__toString(), $request);

    header('Location: '.$request->getParam(Tracking_Uri::DESTINED_URL, '/'), 301);
}