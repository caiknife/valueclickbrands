<?php
/**
 * @author Jacky <jjiang@corp.valueclick.com>
 * $Header: /CVSRPT/Tracking/TrackingSites/SmarterJP/track/scripts/async_afc.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 * $Id: async_afc.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
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

/**
 * {desturl:desturl,keyword:keyword,channelId:chid,categoryId:cateid,
              destSite:destSite,channelTag:channelTag,googleAdClient:googleAdClient,
              curRandStr:curRandStr,pathname:pathname,trafficPartner:trafficPartner,revenuePartner:revenuePartner,
              countryCode:countryCode,stateCode:stateCode,cityCode:cityCode});

 */
try {
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

        $_logger      = Tracking_Logger::getInstance();
        $outgoingLog = array(
            'destUrl'           => $destUrl,
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

} catch (Exception $exception){
	echo "log failed.";
	$request = new Tracking_Request_Outgoing();
    logMessage($exception->__toString(), $request);
}

exit;
