<?php

require_once dirname(__FILE__) . '/../../etc/const.inc.php';

/* redirector type */
$_GET[Tracking_Uri::BUILD_TYPE] = 'sponsor';

/* channel id */
if (isset($_GET['chid'])){
    $_GET[Tracking_Uri::CHANNEL_ID] = $_GET['chid'];
    unset($_GET['chid']);
}

/* channel tag */
if (isset($_GET['sltag'])){
    $_GET[Tracking_Uri::CHANNEL_TAG] = base64_decode($_GET['sltag']);
    unset($_GET['sltag']);
}

/* destined url */
if (isset($_GET['url'])){
    $_GET[Tracking_Uri::DESTINED_URL] = base64_decode($_GET['url']);
    unset($_GET['url']);
}

/* display position */
if (isset($_GET['pos'])){
    $_GET[Tracking_Uri::DISPLAY_POSITION] = (integer) base64_decode($_GET['pos']);
    unset($_GET['pos']);
}

/* keyword */
if (isset($_GET['slkwd'])){
    $_GET[Tracking_Uri::KEYWORD] = $_GET['slkwd'];
    unset($_GET['slkwd']);
}


$_GET[Tracking_Uri::SPONSOR_TYPE] = Tracking_Constant::SPONSOR_GOOGLE;
if (isset($_GET['st'])){
    switch ((integer) $_GET['st']) {
        case 2:
            $_GET[Tracking_Uri::SPONSOR_TYPE] = Tracking_Constant::SPONSOR_GOOGLE;
            break;

        case 4:
            $_GET[Tracking_Uri::SPONSOR_TYPE] = Tracking_Constant::SPONSOR_BAIDU;
            break;
    }
}

$query = "http://{$_SERVER['SERVER_NAME']}" . Tracking_Uri::build($_GET);
Tracking_Response::getInstance()->setRedirect($query, 301)->sendResponse();