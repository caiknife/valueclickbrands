<?php
/**
 * @author Jacky <jjiang@corp.valueclick.com>
 * $Header: /CVSRPT/Tracking/TrackingSites/SmarterKR/track/scripts/async_csa_gari.php,v 1.2 2013/07/02 06:13:48 zcai Exp $
 * $Id: async_csa_gari.php,v 1.2 2013/07/02 06:13:48 zcai Exp $
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))). '/init.php';

function logMessage($message, Tracking_Request_Outgoing $request){
    $log = array(
        'remark'     => $message,
        'requesturi' => $request->getRequestUri(),
        'referer'    => $request->getHttpReferer(),
    );
    Tracking_Logger::getInstance()->Error($log);
} 

try {
    $_logger = Tracking_Logger::getInstance();
    $logtype = isset($_REQUEST['a']) ? $_REQUEST['a'] : null;
    
    if ($logtype == "imp") {
        $v = $_REQUEST['v'];
        $arr = explode('|', $v);
        
        $currandstr  = $arr[0];
        $ip          = $arr[1];
        $returnCount = $arr[2];
        $keyword     = urldecode($arr[3]);
        $pos         = $_REQUEST['pos'];
        $showarea    = str_replace(array(
            '{',
            '}',
            '"',
            ',',
        ), array(
            '',
            '',
            '',
            '|',
        ), $pos);
        
        $logSponsorImpression = array(
            'sponsorType'     => 'GOOGLECSA' ,
            'keyword'         => $keyword,
            'curRandStr'      => $currandstr,
            'impressionCount' => $returnCount,
            'showarea'        => $showarea,
        );
      
        $_logger->sponsorImpression($logSponsorImpression);
        echo 'Log sponsorImpression successfully.';
    } else {
        // trackingseed pattern: base64_encode([click area]_[channel tag]_[keyword]_[currandstr]_[clientIP])
        // new pattern tag: base64_encode([click area]_[channel tag]_[keyword]_[currandstr]_[page path]_[rev partner]_[rev partner type]_[country code])_[state code]_[city code])
        $ct = $nc = "";
        if (isset($_REQUEST['ct'])) {
            $ct = trim($_REQUEST['ct']);
        }
        if (isset($_REQUEST['nc'])) {
            $nc = trim($_REQUEST['nc']);
        }
        
        $key = date("Ymd") . 'IHG_G_CSA';
        $params = Mezi_Utility::decrypt($ct, $key);
        
        $paramsArr = explode('{&&}', $params);
        
        if (count($paramsArr) < 3) {
            //invalid request
            $request = new Tracking_Request_Outgoing();
            logMessage($ct, $request);
            exit();
        }
        
        // $clickArea  = $paramsArr[0];
        $clickArea = isset($_REQUEST['pos']) ? $_REQUEST['pos'] : $paramsArr[0];
        if (is_numeric($clickArea)) {
            $clickArea = 'mid-' . $clickArea;
        }
        
        $channelTag = $paramsArr[1];
        $keyword    = $paramsArr[2];
        $currandstr = isset($paramsArr[3]) ? $paramsArr[3] : null;
        if (count($paramsArr) > 3) {
            $pathname       = $paramsArr[4];
            $revPartner     = $paramsArr[5];
            $revPartnerType = $paramsArr[6];
            $country        = $paramsArr[7];
            $state          = $paramsArr[8];
            $city           = $paramsArr[9];
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
            'destUrl'        => base64_encode(str_replace('{&&}', '||', $params) . "&nc=" . $nc) . '||' . $transactionId,
            'keyword'        => $keyword,
            'clickArea'      => $clickArea,
            'advertiserhost' => $currandstr, // put randstr here to trace back
            'channelId'      => '',
            'categoryId'     => '',
            'channelTag'     => $channelTag,
            'sponsorType'    => 'GOOGLECSA',
            'advertiserHost' => '',
            'revenue'        => 0,
            'outgoingType'   => 0,
        );
        
        /*
         * sponsorOutgoing = "{sponsor_outgoing_log}|#{20100603}|# <sessionid>|#<visittime>|#<siteid>|#<sponsortype>|#<channelid>|#<revenue>|#<keyword>
         * |#<displayPosition>|#<advertiserHost>|#<channeltag>|#<desturl>|#<randstr>|#<currandstr> |#<outgoingtype>|#<clickarea>"
         */
        
        // log the click event
        $_logger->sponsorOutgoing($outgoingLog);        
        echo 'log successfully.';
        
        //=================================================================================================
        /**
         * revenue ingest project
         */
        // if needn't to track ecommerce,add config_track_ecomm variable in the following array,the value is set to false.
        // if needn't to track event,add config_track_event variable in the following array,the value is set to false.
        $ga_params = array(
            // 'config_track_ecomm' => false,
            'kw'             => $keyword,
            'channelTag'     => $channelTag,
            'clickArea'      => $clickArea, // passed by FE code, TODO, confirm whether it's position field, sponsor link rank may not work here.
            'curRandStr'     => $currandstr,
            'pathname'       => $pathname, // location.pathname
            'revPartnerType' => $revPartnerType,
            'revPartner'     => $revPartner,
            'country'        => $country,
            'merchantId'     => '',
            'affiliateId'    => '',
            'couponId'       => '',
        );
        Tracking_Revenue_Ingest::getInstance($ga_params)->GALogger();
        echo 'GA tracking successfully.';
    }
        
} catch (Exception $exception) {
    echo "log failed.";
    $request = new Tracking_Request_Outgoing();
    logMessage($exception->__toString(), $request);
}

exit();
