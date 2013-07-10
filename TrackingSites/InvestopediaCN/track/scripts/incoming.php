<?php

require_once dirname(__FILE__).'/init.php';


//echo '<strong style="font-size:33px">incoming.php is run</strong>';

/* fecth tracking configuration */
$_configTracking = Mezi_Config::getInstance()->tracking;

/* set default setting for session    */
Tracking_Session::setDefaultSetting(
    $_configTracking->session->life,
    $_configTracking->session->path,
    $_configTracking->session->domain
);

/* tracking incoming main script */
try {
    $_incoming = new Tracking_Incoming();
    $_incoming->run();
} catch(Exception $exception) {
    if (isset($_GET['debug'])) {
        echo $exception->getMessage();
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
