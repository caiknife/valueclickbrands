<?php
/**
 * tracking boostrap file
 *
 * @category   Tracking
 * @package    Tracking_Incoming
 * @author     Ken <ken_zhang@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: incoming.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
 */

require_once dirname(__FILE__).'/init.php';

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
