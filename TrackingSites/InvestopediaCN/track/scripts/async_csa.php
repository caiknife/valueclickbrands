<?php
require_once dirname(__FILE__) . '/incoming.php';

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
        
        $currandstr=$arr[0];
        $ip=$arr[1];
        $returnCount=$arr[2];
        $keyword=urldecode($arr[3]);
        
        $showarea = '';
        if (isset($_REQUEST['pos'])) {
            $pos = $_REQUEST['pos'];
            if (is_array($_REQUEST['pos'])) {
                /**
                 * $_REQUEST['pos'] = array('mid-1'=>'3', 'mid-2'=>3);
                 * 
                 * $showarea should be like - mid-1:3|mid-2:3
                 */
                $showarea = array();
                foreach ($_REQUEST['pos'] as $k => $v) {
                    $showarea[] = $k . ':' . $v;
                }
                $showarea = implode('|', $showarea);
            } else {
                $showarea = str_replace(array(
                    '{',
                    '}',
                    '"',
                    ','
                ), array(
                    '',
                    '',
                    '',
                    '|'
                ), $pos);
            }
        }
        
        $logSponsorImpression = array(
            'sponsorType'     => 'GOOGLECSA' ,
            'keyword'         => $keyword,
            'curRandStr'      => $currandstr,
            'impressionCount' => $returnCount,
            'showarea'        => $showarea
        );
      
        $_logger->sponsorImpression($logSponsorImpression);
        echo 'Log sponsorImpression successfully.';
    } else {
        // new tracking code for InvestoPedia CSA click track
        $ct = $kw = $ch = $ca = ""; //clicktype, keyword, channeltag, clickarea
        if (isset($_REQUEST['ct'])) {
            $ct = $_REQUEST['ct'];
        }
        if (isset($_REQUEST['kw'])) {
            $kw = $_REQUEST['kw'];
        }
        if (isset($_REQUEST['ch'])) {
            $ch = $_REQUEST['ch'];
        }
        if (isset($_REQUEST['ca'])) {
            $ca = $_REQUEST['ca'];
        }
        
        $currandstr = Tracking_Session::getInstance()->getRequestId();
        if ($currandstr == false) {
            $keyPrefix = $_SERVER['SERVER_ADDR'] . '-' . $_SERVER['REMOTE_ADDR'];
            $currandstr = Tracking_Session::getInstance()->generateUniqueId('userId'.$keyPrefix);
        }
        
        $outgoingLog = array(
            'destUrl'        => $ct,
            'keyword'        => $kw,
            'clickArea'      => $ca,
            'advertiserhost' => $currandstr, // put randstr here to trace back
            'channelId'      => '',
            'categoryId'     => '',
            'channelTag'     => $ch,
            'sponsorType'    => 'GOOGLECSA',
            'revenue'        => 0,
            'outgoingType'   => 0,
        );
        $_logger->sponsorOutgoing($outgoingLog);
        echo 'log successfully.';
        exit();
        
        /**
        // trackingseed pattern: base64_encode([click area]_[channel tag]_[keyword]_[currandstr])
        //new pattern tag: [click area]_[channel tag]_[keyword]_[currandstr]_[page path]_[rev partner]_[rev partner type]_[country code])_[state code]_[city code]
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
            //invalid request
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
        //$transactionId = Tracking_Revenue_Ingest::get_transactionId();
        $outgoingLog = array(
                //'destUrl' => base64_encode(str_replace('{&&}', '||', $params) . "&nc=" . $nc) . '||' . $transactionId, //GARI
                'destUrl' => base64_encode(str_replace('{&&}', '||', $params) . "&nc=" . $nc),
                'keyword' => $keyword,
                'clickArea' => $clickArea,
                'advertiserhost' => $currandstr, // put randstr here to trace back
                'channelId' => '',
                'categoryId' => '',
                'channelTag' => $channelTag,
                'sponsorType' => 'GOOGLECSA',
                'revenue' => 0,
                'outgoingType' => 0
        );
        // log the click event
        $_logger->sponsorOutgoing($outgoingLog);
        echo 'log successfully.';
        exit;
        */
        
        /**
         * GARI
         * revenue ingest project
         */
        $ga_params = array(
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
    }
} catch (Exception $exception) {
    echo "log failed.";
    $request = new Tracking_Request_Outgoing();
    logMessage($exception->__toString(), $request);
}

exit();
