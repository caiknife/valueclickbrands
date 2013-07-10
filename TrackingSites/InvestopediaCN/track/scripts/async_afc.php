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

/**
 * {desturl:desturl,keyword:keyword,channelId:chid,categoryId:cateid,
              destSite:destSite,channelTag:channelTag,googleAdClient:googleAdClient,
              curRandStr:curRandStr,pathname:pathname,trafficPartner:trafficPartner,revenuePartner:revenuePartner,
              countryCode:countryCode,stateCode:stateCode,cityCode:cityCode});

 */
try {
    $_logger = Tracking_Logger::getInstance();
    $logtype = isset($_REQUEST['a']) ? $_REQUEST['a'] : null;
    
    if ($logtype == "imp") {
        $v=$_REQUEST['v'];
        $arr=explode('|', $v);
    
        $currandstr=$arr[0];
        $ip=$arr[1];
        $returnCount=$arr[2];
        $keyword=urldecode($arr[3]);
        $channelTag = isset($_REQUEST['channelTag']) ? $_REQUEST['channelTag'] : '';
        $showarea = '';
        if (isset($_REQUEST['pos'])) {
            $pos = $_REQUEST['pos'];
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
    
        $logSponsorImpression = array(
                'sponsorType' => 'GOOGLEAFC' ,
                'keyword' => $keyword,
                'curRandStr'=>$currandstr,
                'impressionCount' => $returnCount,
                'showarea' => $showarea,
                'channelTag'=>$channelTag
        );
    
        $_logger->sponsorImpression($logSponsorImpression);
        echo 'Log sponsorImpression successfully.';
    } else {
        $destUrl = $_REQUEST['desturl'];
        $keyword = $_REQUEST['keyword'];
        $displayPosition = 1;
        if (! empty($_REQUEST['displayPosition'])) {
            $displayPosition = $_REQUEST['displayPosition'];
        }
        $channelId = $_REQUEST['channelId'];
        $categoryId = $_REQUEST['categoryId'];
        $destSite = $_REQUEST['destSite'];
        if (! empty($_REQUEST['channelTag'])) {
            $channelTag = $_REQUEST['channelTag'];
        }
        $googleAdClient = $_REQUEST['googleAdClient'];
        $sponsorType = "GOOGLE";
        $outgoingType = (int) Tracking_Session::getInstance()->getTrafficType();
        
        // isFraud start
        if (Tracking_Session::getInstance()->isNormalTraffic()) {
            $curClick = md5("$displayPosition|$keyword|$sponsorType");
            $preClick = Tracking_Session::getInstance()->getSponsorClick();
            if (stripos($preClick, $curClick) !== FALSE) {
                $outgoingType = - 4;
            } else {
                Tracking_Session::getInstance()->setSponsorClick("$curClick|$preClick")->update();
            }
        }
        // log down Outgoing
        $sponsorTypes = array(
                'GOOGLEAFC-TEXT',
                'GOOGLEAFC-IMAGE',
                'GOOGLEAFC-FLASH',
                'GOOGLEAFC-HTML'
        );
        if (! in_array($destSite, $sponsorTypes)) {
            $destSite = 'GOOGLEAFC';
        }

        $transactionId = Tracking_Revenue_Ingest::get_transactionId();
        $outgoingLog = array(
            'destUrl'           => $destUrl.'||'.$transactionId,
            'keyword'           => $keyword,
            'clickArea'         => 1,
            'displayPosition'   => $displayPosition,
            'channelId'         => $channelId,
            'categoryId'        => $categoryId,
            'channelTag'        => $googleAdClient . '|' . $channelTag,
            'sponsorType'       => $destSite,
            'advertiserHost'    => '',
            'revenue'           => 0,
            'outgoingType'      => $outgoingType
        );
       
		//log the click event
		$_logger->sponsorOutgoing($outgoingLog);
		echo 'log successfully.';
		exit;
    
    /**
     * GARI
     * revenue ingest project
     */
        $channelTag = $_REQUEST['channelTag'];
        $currandstr = $_REQUEST['curRandStr'];
        $pathname = $_REQUEST['pathname'];
        $revPartner = $_REQUEST['revenuePartner'];
        $revPartnerType = $_REQUEST['revenuePartnerType'];
        $country = $_REQUEST['countryCode'];
        $state = $_REQUEST['stateCode'];
        $city = $_REQUEST['cityCode'];
        
        if ($currandstr == false) {
            $currandstr = Tracking_Session::getInstance()->getRequestId();
            if ($currandstr == false) {
                $keyPrefix = $_SERVER['SERVER_ADDR'] . '-' . $_SERVER['REMOTE_ADDR'];
                $currandstr = Tracking_Session::getInstance()->generateUniqueId('userId' . $keyPrefix);
            }
        }
        
        $ga_params = array(
                'kw' => $keyword,
                'channelTag' => $channelTag,
                'clickArea' => $displayPosition,
                'curRandStr' => $currandstr,
                'pathname' => $pathname,
                'revPartnerType' => $revPartnerType,
                'revPartner' => $revPartner,
                'country' => $country,
                'merchantId' => '',
                'affiliateId' => '',
                'couponId' => '',
                'clickAdType'=>'afc'
        );
        
        Tracking_Revenue_Ingest::getInstance($ga_params)->GALogger();
    }
} catch (Exception $exception){
	echo "log failed.";
	$request = new Tracking_Request_Outgoing();
    logMessage($exception->__toString(), $request);
}

exit;
