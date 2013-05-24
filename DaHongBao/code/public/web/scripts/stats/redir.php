<?
require_once dirname(__FILE__) . '/../../etc/const.inc.php';

/* redirector type */
$redirectorType = isset($_GET['rdtp']) ? (integer) trim($_GET['rdtp']) : 0;
switch ($redirectorType) {
    case 1:
        $_GET[Tracking_Uri::BUILD_TYPE] = 'normal';
        break;

    case 3:
        $_GET[Tracking_Uri::BUILD_TYPE] = 'sl';
        break;

    case 4:
        $_GET[Tracking_Uri::BUILD_TYPE] = 'banner';
        break;

    case 5:
        $_GET[Tracking_Uri::BUILD_TYPE] = isset($_GET['type']) ? $_GET['type'] : 'special';
        break;

    case 6:
        $_GET[Tracking_Uri::BUILD_TYPE] = 'blog';
        break;

    case 7:
        $_GET[Tracking_Uri::BUILD_TYPE] = 'offer';
        break;

    case 2:
    default:
        $_GET[Tracking_Uri::BUILD_TYPE] = 'coupon';
        break;
}
unset($_GET['rdtp']);

/* merchant id */
if (isset($_GET['m'])){
    $_GET[Tracking_Uri::MERCHANT_ID] = $_GET['m'];
    unset($_GET['m']);
}

/* category id */
if (isset($_GET['c'])){
    $_GET[Tracking_Uri::CATEGORY_ID] = $_GET['c'];
    unset($_GET['c']);
}

/* coupon id */
if (isset($_GET['p'])){
    $_GET[Tracking_Uri::COUPON_ID] = base64_decode($_GET['p']);
    unset($_GET['p']);
}

/* destined url */
if (isset($_GET['url'])){
    $_GET[Tracking_Uri::DESTINED_URL] = base64_decode($_GET['url']);
    unset($_GET['url']);
}

/* display position */
if (isset($_GET['pos'])){
    $_GET[Tracking_Uri::DISPLAY_POSITION] = (integer) $_GET['pos'];
    unset($_GET['pos']);
}

$query = "http://{$_SERVER['SERVER_NAME']}" . Tracking_Uri::build($_GET);
Tracking_Response::getInstance()->setRedirect($query, 301)->sendResponse();