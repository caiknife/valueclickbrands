<?php

require_once dirname(__FILE__) . '/incoming.php';

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
        logMessage("error async type: $asyncType", $request);
    }

    $redirector = Tracking_Async::factory($asyncType);
    $redirector->dispatch();
} catch (Exception $exception){
    logMessage($exception->__toString(), $request);

    header('Location: '.$request->getParam(Tracking_Uri::DESTINED_URL, '/'), 301);
}