<?php
/**
 * @author Jacky <jjiang@corp.valueclick.com>
 * $Header: /CVSRPT/Tracking/TrackingSites/SmarterJP/track/scripts/async_csa.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 * $Id: async_csa.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))). '/etc/define.php';

function logMessage($message, Tracking_Request_Outgoing $request){
    $log = array(
            'remark' => $message,
            'requesturi' => $request->getRequestUri(),
            'referer'    => $request->getHttpReferer(),
    );
    Tracking_Logger::getInstance()->Error($log);
} 

try {
    $_logger = Tracking_Logger::getInstance();
    $logtype = isset($_REQUEST['a'])?$_REQUEST['a']:null;
    
    if ($logtype == "imp") {
        $v=$_REQUEST['v'];
        $arr=explode('|', $v);
        $currandstr = $arr[0];
        $ip = $arr[1];
        $returnCount = $arr[2];
        $keyword = urldecode($arr[3]);
        $showarea = $arr[4];
        
        $logSponsorImpression = array(
                'sponsorType' => 'GOOGLECSA',
                'keyword' => $keyword,
                'curRandStr' => $currandstr,
                'impressionCount' => $returnCount,
                'showarea' => $showarea
        );
      
        $_logger->sponsorImpression($logSponsorImpression);
        echo 'Log sponsorImpression successfully.';
    } else {
        
        // trackingseed pattern: base64_encode([click area]_[channel tag]_[keyword]_[currandstr]_[clientIP])
        $ct = "";
        $nc = "";
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
        
        $clickArea = $paramsArr[0];
        $channelTag = $paramsArr[1];
        $keyword = $paramsArr[2];
        $currandstr = isset($paramsArr[3]) ? $paramsArr[3] : null;
     
        if ($currandstr == false) {
            $currandstr = Tracking_Session::getInstance()->getRequestId();
            if ($currandstr == false) {
                $keyPrefix = $_SERVER['SERVER_ADDR'] . '-' . $_SERVER['REMOTE_ADDR'];
                $currandstr = Tracking_Session::getInstance()->generateUniqueId('userId' . $keyPrefix);
            }
        }
            
            // log down Outgoing
        $outgoingLog = array(
                'destUrl' => base64_encode(str_replace('{&&}', '||', $params) . "&nc=" . $nc),
                'keyword' => $keyword,
                'clickArea' => $clickArea,
                'advertiserhost' => $currandstr, // put randstr here to trace back
                'channelId' => '',
                'categoryId' => '',
                'channelTag' => $channelTag,
                'sponsorType' => 'GOOGLECSA',
                'advertiserHost' => '',
                'revenue' => 0,
                'outgoingType' => 0
        );
        
        //var_dump($outgoingLog);
       
        /*
         * sponsorOutgoing = "{sponsor_outgoing_log}|#{20100603}|# <sessionid>|#<visittime>|#<siteid>|#<sponsortype>|#<channelid>|#<revenue>|#<keyword>
         * |#<displayPosition>|#<advertiserHost>|#<channeltag>|#<desturl>|#<randstr>|#<currandstr> |#<outgoingtype>|#<clickarea>"
         */
        
        // log the click event
        $_logger->sponsorOutgoing($outgoingLog);
        
        echo 'log successfully.';
    }
        
} catch (Exception $exception) {
    echo "log failed.";
    $request = new Tracking_Request_Outgoing();
    logMessage($exception->__toString(), $request);
}

exit();
