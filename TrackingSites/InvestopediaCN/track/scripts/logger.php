<?php
require_once dirname(__FILE__) . '/init.php';

/* fecth tracking configuration */
$_configTracking = Mezi_Config::getInstance()->tracking;

/* set default setting for session    */
Tracking_Session::setDefaultSetting(
    $_configTracking->session->life,
    $_configTracking->session->path,
    $_configTracking->session->domain
);

$track_request = new Tracking_Request_Outgoing();
$track_requestUri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : $_SERVER['REQUEST_URI'];
$track_referrer = isset($_REQUEST['refer']) ? $_REQUEST['refer'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
$track_request->setRequestUri($track_requestUri);

/* tracking incoming main script */
try {
    $_incoming = new Tracking_Incoming();
    $_incoming->run();
} catch(Exception $exception) {
    if (isset($_GET['debug'])) {
        echo $exception->getMessage();
    }
}

if (isset($_REQUEST['lt'])) {
    $lt = $_REQUEST['lt'];
    switch ($lt) {
        case 'ctrk':
            if (isset($_REQUEST['tk']) && Tracking_Event::getInstance()->fdecode($_REQUEST['tk']) == $_REQUEST['cvt']) {
                Tracking_Event::getInstance()->clientTrack();
            }
            break;
        default:
            break;
    }
}

try {
    /* normalize the request uri */
    $_incoming->normalize();
} catch(Exception $exception) {
    if (isset($_GET['debug'])) {
        echo $exception->getMessage();
    }
}

unset($_incoming, $_configTracking);