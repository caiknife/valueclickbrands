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

	/** for content banner 1000 click test **/
	
	if(strtolower($redirectorType)=='affiliate'){
		//www.creditcards.com
		$destUrl = $request->getParam(Tracking_Uri::DESTINED_URL, '');	
		if(strpos(strtolower($destUrl),'www.creditcards.com') >1 ){
			Tracking_Session::getInstance()->setMemcacheNode("tracking_clickcnt_AEcreditcards",1);
			Tracking_Session::getInstance()->setMemcacheNode('tracking_clickcnt_'.trim(Tracking_Session::getInstance()->getSourceGroup()),1);
		}
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
