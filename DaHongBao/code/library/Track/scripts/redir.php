<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Outgoing
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: redir.php,v 1.2 2013/04/16 05:07:29 jjiang Exp $
 */

//require_once dirname(dirname(dirname(dirname(__FILE__)))). '/etc/define.php';
require_once dirname(__FILE__).'/init.php';

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
        'requesturi' => $request->getRequestUri(),
        'referer'    => $request->getHttpReferer(),
    );
    Tracking_Logger::getInstance()->Error($log);
}

header("Pragma: no-cache");
header("Cache: no-cache");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

try {
    $request = new Tracking_Request_Outgoing();
    $redirectorType = $request->getParam(Tracking_Uri::BUILD_TYPE, '');
    if (!preg_match('/^[a-z0-9_]+$/i', $redirectorType)) {
        logMessage("error redirector type: $redirectorType", $request);
    }

     /** verify the parameters with key for sponsor only */
    $_key = Mezi_Config::getInstance()->tracking->key;
    if (! empty($_key) && $redirectorType == 'affiliate') {
        Tracking_Uri::extract($request->getRequestUri(), $_key);
    } elseif ($redirectorType == 'cmusaffiliate') {
        $redirector = Tracking_Redirector::factory("affiliate");
    } else {
        $redirector = Tracking_Redirector::factory($redirectorType);
    }
    unset($_key);
    $redirector->dispatch();
} catch (Exception $exception){
    logMessage($exception->__toString(), $request);

    header('Location: '.$request->getParam(Tracking_Uri::DESTINED_URL, '/'), 301);
}