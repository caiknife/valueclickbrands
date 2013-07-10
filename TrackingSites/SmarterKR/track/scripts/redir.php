<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Outgoing
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: redir.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))). '/init.php';

/**
 * log message
 *
 * @param string $message
 * @param Tracking_Request_Outgoing $request
 * @return void
 */
function logMessage($message, Tracking_Request_Outgoing $request){
    $log = array(
        'remark'        => $message,
        'requesturi'    => $request->getRequestUri(),
        'referer'       => $request->getHttpReferer(),
    );

    Tracking_Logger::getInstance()->Error($log);
}

try {
    $request = new Tracking_Request_Outgoing();
    $redirectorType = $request->getParam(Tracking_Uri::BUILD_TYPE, '');
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
    $redirector->dispatch();
} catch (Exception $exception){
    logMessage($exception->__toString(), $request);

    header('Location: '.$request->getParam(Tracking_Uri::DESTINED_URL, '/'), 301);
}
