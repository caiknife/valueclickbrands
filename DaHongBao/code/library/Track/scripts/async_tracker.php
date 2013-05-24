<?php

require_once dirname(__FILE__).'/init.php';

if (!isset($_GET['ri']) || !ctype_xdigit($requestId = $_GET['ri'])) { return; }
if (!isset($_GET['rt']) || !$requestTime = (float) $_GET['rt']) { return; }

$browserLanguage  = isset($_GET['bl'])? $_GET['bl'] : '';
$characterSet     = isset($_GET['cs'])? $_GET['cs'] : '';
$timezoneOffset   = isset($_GET['tz'])? (integer) $_GET['tz'] : 0;
$screenResolution = isset($_GET['sr'])? $_GET['sr'] : '';
$screenColors     = isset($_GET['sc'])? (integer) $_GET['sc'] : 0;
$javaEnabled      = isset($_GET['je'])? (integer) $_GET['je'] : 0;
$cookieEnabled    = isset($_GET['ce'])? (integer) $_GET['ce'] : 0;
$jsEnabled        = isset($_GET['js'])? (integer) $_GET['js'] : 1;
$flashVersion     = isset($_GET['fl'])? $_GET['fl'] : '';
$sessionId        = isset($_GET['si'])? $_GET['si'] : '';

try {
    $clientPageVisitLog = array(
        'sessionId'         => $sessionId,
        'clientTime'        => $requestTime,
        'loadTime'          => 0,
        'cookieEnabled'     => $cookieEnabled,
        'javaEnabled'       => $javaEnabled,
        'jsEnabled'         => $jsEnabled,
        'screenResolution'  => $screenResolution,
        'timezone'          => $timezoneOffset,
        'languageSetting'   => $browserLanguage,
        'flashVersion'      => $flashVersion,
        'curRandStr'        => $requestId,
    );
    Tracking_Logger::getInstance()->clientPageVisit($clientPageVisitLog);
} catch (Exception $exception){
    /*$request = new Tracking_Request_Outgoing();
    Tracking_Logger::getInstance()->error(array(
        'remark'        => $exception->__toString(),
        'requestUri'    => $request->getRequestUri(),
        'referer'       => $request->getHttpReferer(),
    ));*/
}