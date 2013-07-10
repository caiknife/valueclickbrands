<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))). '/etc/define.php';

if (!isset($_GET['ri']) || !ctype_xdigit($requestId = $_GET['ri'])) { return; }
if (!isset($_GET['rt']) || !$requestTime = (float) $_GET['rt']) { return; }

$browserLanguage  = isset($_GET['bl']) && ctype_print($_GET['bl']) ? $_GET['bl'] : '';
$characterSet     = isset($_GET['cs']) && ctype_print($_GET['cs']) ? $_GET['cs'] : '';
$timezoneOffset   = isset($_GET['tz']) && ctype_print($_GET['tz']) ? (integer) $_GET['tz'] : 0;
$screenResolution = isset($_GET['sr']) && ctype_print($_GET['sr']) ? $_GET['sr'] : '';
$screenColors     = isset($_GET['sc']) && ctype_print($_GET['sc']) ? (integer) $_GET['sc'] : 0;
$javaEnabled      = isset($_GET['je']) && ctype_print($_GET['je']) ? (integer) $_GET['je'] : 0;
$cookieEnabled    = isset($_GET['ce']) && ctype_print($_GET['ce']) ? (integer) $_GET['ce'] : 0;
$jsEnabled        = isset($_GET['js']) && ctype_print($_GET['js']) ? (integer) $_GET['js'] : 1;
$flashVersion     = isset($_GET['fl']) && ctype_print($_GET['fl']) ? $_GET['fl'] : '';
$sessionId        = isset($_GET['si']) && ctype_print($_GET['si']) ? $_GET['si'] : '';
$deliveredcontent1=isset($_GET['Tracking_DC1'])?$_GET['Tracking_DC1']:'';
$deliveredcontent2=isset($_GET['Tracking_DC2'])?$_GET['Tracking_DC2']:'';
$deliveredcontent3=isset($_GET['Tracking_DC3'])?$_GET['Tracking_DC3']:'';
$UIName=isset($_GET['Tracking_UIName'])?$_GET['Tracking_UIName']:'';

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
    	'deliveredcontent1' =>$deliveredcontent1,
    	'deliveredcontent2' =>$deliveredcontent2,
    	'deliveredcontent3' =>$deliveredcontent3,
    	'uiname'            =>$UIName
    );
    Tracking_Logger::getInstance()->clientPageVisit($clientPageVisitLog);
} catch (Exception $exception){
    $request = new Tracking_Request_Outgoing();
    Tracking_Logger::getInstance()->error(array(
        'remark'        => $exception->__toString(),
        'requestUri'    => $request->getRequestUri(),
        'referer'       => $request->getHttpReferer(),
    ));
}