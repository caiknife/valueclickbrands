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
    $log = array(
        'remark' => $message,
        'requestUri' => $request->getRequestUri(),
        'referer'    => $request->getHttpReferer(),
    );
    Tracking_Logger::getInstance()->Error($log);
}

try {
    $request = new Tracking_Request_Outgoing();
    $redirectorType = $request->getParam(Tracking_Uri::ASYNC_TYPE_GA, '');
    if (!preg_match('/^[a-z0-9_]+$/i', $redirectorType)) {
        logMessage("error redirector type: $redirectorType", $request);
    }

    /** verify the parameters with key for sponsor only */
    $_key = Mezi_Config::getInstance()->tracking->key;
    if (!empty($_key) && strtolower($redirectorType)=='affiliate') {
        Tracking_Uri::extract($request->getRequestUri(), $_key);
    }
    unset($_key);

    $redirector = Tracking_Redirector::factory($redirectorType);
    $redirector->dispatch(false, false);
} catch (Exception $exception){
    logMessage($exception->__toString(), $request);

    header('Location: '.$request->getParam(Tracking_Uri::DESTINED_URL, '/'), 301);
}
