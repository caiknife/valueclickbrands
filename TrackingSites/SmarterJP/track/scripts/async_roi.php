<?php
// For other call back, such as banner/small clients

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/etc/define.php';

function logMessage ($message, Tracking_Request_Outgoing $request)
{
    $log = array(
            'remark' => $message,
            'requesturi' => $request->getRequestUri(),
            'referer' => $request->getHttpReferer()
    );
    Tracking_Logger::getInstance()->Error($log);
}

try {
    $_logger = Tracking_Logger::getInstance();
    $logtype = isset($_REQUEST['a']) ? $_REQUEST['a'] : null;
    
    $sponsorType = $_REQUEST['sponsorType'];
    
    // trackingseed pattern: base64_encode([click area]_[channel tag]_[keyword]_[currandstr]_[clientIP])
    // new pattern tag: [click area]_[channel tag]_[keyword]_[currandstr]_[page path]_[rev partner]_[rev partner type]_[country code])_[state code]_[city code]
    $ct = "";
    $nc = "";
    if (isset($_REQUEST['ct'])) {
        $ct = trim($_REQUEST['ct']);
    }
    if (isset($_REQUEST['nc'])) {
        $nc = trim($_REQUEST['nc']);
    }
    
    $params = Mezi_Utility::decrypt($ct);
    
    $paramsArr = explode('{&&}', $params);
    
    if (count($paramsArr) < 3) {
        // invalid request
        $request = new Tracking_Request_Outgoing();
        logMessage($ct, $request);
        exit();
    }
    
    $clickArea = $paramsArr[0];
    $channelTag = $paramsArr[1];
    $keyword = $paramsArr[2];
    $currandstr = isset($paramsArr[3]) ? $paramsArr[3] : null;
    if (count($paramsArr) > 3) {
        $pathname = $paramsArr[4];
        $revPartner = $paramsArr[5];
        $revPartnerType = $paramsArr[6];
        $country = $paramsArr[7];
        $state = $paramsArr[8];
        $city = $paramsArr[9];
    }
    if ($currandstr == false) {
        $currandstr = Tracking_Session::getInstance()->getRequestId();
        if ($currandstr == false) {
            $keyPrefix = $_SERVER['SERVER_ADDR'] . '-' . $_SERVER['REMOTE_ADDR'];
            $currandstr = Tracking_Session::getInstance()->generateUniqueId('userId' . $keyPrefix);
        }
    }
    $transactionId = Tracking_Revenue_Ingest::get_transactionId();
    
    // log down Outgoing
    $outgoingLog = array(
            'destUrl' => base64_encode(str_replace('{&&}', '||', $params) . "&nc=" . $nc) . '||' . $transactionId,
            'keyword' => $keyword,
            'clickArea' => $clickArea,
            'advertiserhost' => $currandstr, // put randstr here to trace back
            'channelId' => '',
            'categoryId' => '',
            'channelTag' => $channelTag,
            'sponsorType' => $sponsorType,
            'advertiserHost' => '',
            'revenue' => 0,
            'outgoingType' => 0
    );
    
    // var_dump($outgoingLog);
    
    /*
     * sponsorOutgoing = "{sponsor_outgoing_log}|#{20100603}|# <sessionid>|#<visittime>|#<siteid>|#<sponsortype>|#<channelid>|#<revenue>|#<keyword>
     * |#<displayPosition>|#<advertiserHost>|#<channeltag>|#<desturl>|#<randstr>|#<currandstr> |#<outgoingtype>|#<clickarea>"
     */
    
    // log the click event
    $_logger->sponsorOutgoing($outgoingLog);
    
    echo 'log successfully.';
    
    /**
     * revenue ingest project
     */
    // if needn't to track ecommerce,add config_track_ecomm variable in the following array,the value is set to false.
    // if needn't to track event,add config_track_event variable in the following array,the value is set to false.
    $ga_params = array(
            // 'config_track_ecomm' => false,
            'kw' => $keyword,
            'channelTag' => $channelTag,
            'clickArea' => $clickArea, // passed by FE code, TODO, confirm whether it's position field, sponsor link rank may not work here.
            'curRandStr' => $currandstr,
            'pathname' => $pathname, // location.pathname
            'revPartnerType' => $revPartnerType,
            'revPartner' => $revPartner,
            'country' => $country,
            'merchantId' => '',
            'affiliateId' => '',
            'couponId' => ''
    );
    Tracking_Revenue_Ingest::getInstance($ga_params)->GALogger();
} catch (Exception $exception) {
    echo "log failed.";
    $request = new Tracking_Request_Outgoing();
    logMessage($exception->__toString(), $request);
}

exit();
