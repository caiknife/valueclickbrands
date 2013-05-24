<?php

class Tracking extends MysqlDB {

    /**
     * Singleton instance
     *
     * @var Tracking
     */
    protected static $_instance = NULL;

    /**
     * constructor
     *
     * @return Tracking
     */
    public function __construct() {
        parent::__construct();
        if (__DEBUG_ == 1) {
            parent::setDebug(__DEBUG_);
        }
    }

    /**
     * Singleton instance
     *
     * @return Tracking_Logger
     */
    public static function getInstance()
    {
        if (NULL === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function addIncomingLog($para){
        $visitTime      = getTimeStr();
        $clientIp       = isset($para['clientIp'])      ? addslashes($para['clientIp'])     : '';
        $referer        = isset($para['referer'])       ? addslashes($para['referer'])      : '';
        $userAgent      = isset($para['userAgent'])     ? addslashes($para['userAgent'])    : '';
        $requestUri     = isset($para['requestUri'])    ? addslashes($para['requestUri'])   : '';
        $valid          = isset($para['valid'])         ? (integer) $para['valid']          : 0 ;
        $source         = isset($para['source'])        ? addslashes($para['source'])       : '';
        $sourceGroup    = isset($para['sourceGroup'])   ? addslashes($para['sourceGroup'])  : '';
        $channelId      = isset($para['channelId'])     ? (integer) $para['channelId']      : 0 ;
        $tplType        = isset($para['tplType'])       ? (integer) $para['tplType']        : 0 ;

        $sql = "INSERT INTO `Incoming_Log`
                (VisitTime, IP, HttpReferer, HttpUserAgent, RequestUri, TrafficType, Source, SourceGroup, ChannelID, tplType)
                VALUES
                ('{$visitTime}', '{$clientIp}', '{$referer}', '{$userAgent}', '{$requestUri}', '{$valid}', '{$source}', '{$sourceGroup}', '{$channelId}', '{$tplType}')";
        parent::doQuery($sql);

        return parent::getLastInsertID();
    }

    public function addPageVisitLog($para){
        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $referer        = isset($para['referer'])       ? addslashes($para['referer'])      : '';
        $requestUri     = isset($para['requestUri'])    ? addslashes($para['requestUri'])   : '';
        $curRequestId   = isset($para['curRequestId'])  ? addslashes($para['curRequestId']) : '';
        $visitOrder     = isset($para['visitOrder'])    ? (integer) $para['visitOrder']     : 0 ;
        $channelId      = isset($para['channelId'])     ? (integer) $para['channelId']      : 0 ;

        $sql = "INSERT INTO `Page_Visit_Log`
                (VisitTime, SessionID, RequestUri, HttpReferer, PageVisitOrder, CurRandStr, ChannelID)
                VALUES
                ('{$visitTime}', '{$sessionId}', '{$requestUri}', '{$referer}', '{$visitOrder}', '{$curRequestId}', '{$channelId}')";
        return parent::doQuery($sql);
    }

    public function addOutgoingLog($para){
        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $valid          = isset($para['valid'])? $para['valid'] : 0;

        $sql = "INSERT INTO `Outgoing_Log`
                (VisitTime, SessionID, OutgoingType)
                VALUES
                ('{$visitTime}', '{$sessionId}', '{$valid}')";
        parent::doQuery($sql);
        return parent::getLastInsertID();
    }

    public function setOutgoingLog($para){
        $session        = getSessionId();
        $curRequestId   = addslashes(getCurRandStr());
        $preRequestId   = addslashes(getPreRandStr());
        $outid          = isset($para['outid'])? $para['outid'] : 0;
        $merchant       = isset($para['m'])? $para['m'] : 0;
        $coupon         = isset($para['p'])? $para['p'] : 0;
        $category       = isset($para['c'])? $para['c'] : 0;
        $url            = isset($para['url'])? addslashes($para['url']) : '';
        $redirect       = isset($para['rdtp'])? $para['rdtp'] : '';
        $chid           = isset($para['chid'])? $para['chid'] : 0;
        $keyword        = isset($para['clkKwd'])? addslashes($para['clkKwd']) : '';

        $sql = "UPDATE `Outgoing_Log`
                SET SessionID       = '{$session}',
                    MerchantID      = '{$merchant}',
                    CategoryID      = '{$category}',
                    CouponID        = '{$coupon}',
                    DestinationUrl  = '{$url}',
                    RedirectType    = '{$redirect}',
                    CurRandStr      = '{$curRequestId}',
                    PreRandStr      = '{$preRequestId}',
                    ClkKwd          = '{$keyword}',
                    ChannelID       = '{$chid}'
                WHERE ID = '{$outid}';";
        return parent::doQuery($sql);
    }

    public function addSearchLog($para){
        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $curRequestId   = addslashes(getCurRandStr());

        $keyword        = isset($para['keyword'])? addslashes($para['keyword']) : '';
        $isRealSearch   = statSource() && (statKeyword() == $keyword) ? 'NO' : 'YES';
        $costTime       = isset($para['costTime'])? $para['costTime'] : '';
        $resultCount    = isset($para['resultCount'])? (integer) $para['resultCount'] : 0;
        $channelId      = __T_CHANNEL;

         $sql = "INSERT INTO `Search_Log`
                (VisitTime, SessionID, Keyword, IsRealSearch, CostTime, ResultCount, ChannelID, CurRandStr)
                VALUES
                ('{$visitTime}', '{$sessionId}', '{$keyword}', '{$isRealSearch}', '{$costTime}', '{$resultCount}', '{$channelId}', '{$curRequestId}')";
         return parent::doQuery($sql);
    }

    public function addSponsorTransferLog($para){
        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $curRequestId   = addslashes(getCurRandStr());
        $preRequestId   = addslashes(getPreRandStr());

        $keyword        = isset($para['keyword'])   ? addslashes($para['keyword'])      : '';
        $costTime       = isset($para['costTime'])  ? $para['costTime']                 : 0 ;
        $channelTag     = isset($para['channelTag'])? addslashes($para['channelTag'])   : '';
        $channelId      = __T_CHANNEL;
        $sponsorType    = __SPONSOR_TYPE;

        $sql = "INSERT INTO `Sponsor_Transfer_Log`
                (VisitTime, SessionID, Keyword, CostTime, SponsorType, CurRandStr, PreRandStr, ChannelTag, ChannelID)
                VALUES
                ('{$visitTime}', '{$sessionId}', '{$keyword}', '{$costTime}', '{$sponsorType}', '{$curRequestId}', '{$preRequestId}', '{$channelTag}', '{$channelId}');";
        return parent::doQuery($sql);
    }

    public function addSponsorImpressionLog($para){
        $nTrafficType   = (integer) (isset($_COOKIE['TRACKING_TRAFFIC_TYPE']) ? $_COOKIE['TRACKING_TRAFFIC_TYPE'] : $nTrafficType);

        if ($nTrafficType<0 || !isset($para['sponsorCount']) || ($sponsorCount = (integer)$para['sponsorCount'])<=0) {
            return;
        }

        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $curRequestId   = addslashes(getCurRandStr());
        $preRequestId   = addslashes(getPreRandStr());

        $keyword        = isset($para['keyword'])       ? addslashes($para['keyword'])      : '';
        $channelTag     = isset($para['channelTag'])    ? addslashes($para['channelTag'])   : '';
        $channelId      = __T_CHANNEL;
        $sponsorType    = __SPONSOR_TYPE;
        $sql = "INSERT INTO `Sponsor_Impression_Log`
                (VisitTime, SessionID, Keyword, BidPosition, SponsorType, CurRandStr, PreRandStr, ChannelTag, ChannelID)
                VALUES
                ('{$visitTime}', '{$sessionId}', '{$keyword}', '{$sponsorCount}', '{$sponsorType}', '{$curRequestId}', '{$preRequestId}', '{$channelTag}', '{$channelId}');";
        parent::doQuery($sql);
    }

    public function addSponsorOutgoingLog($para){
        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $curRequestId   = addslashes(getCurRandStr());
        $preRequestId   = addslashes(getPreRandStr());
        $keyword        = isset($para['keyword'])       ? addslashes($para['keyword'])      : '';
        $bidPosition    = isset($para['bidPosition'])   ? (integer) $para['bidPosition']    : 0 ;
        $sponsorType    = isset($para['sponsorType'])   ? (integer) $para['sponsorType']    : 0 ;
        $channelId      = __T_CHANNEL;
        $channelTag     = isset($para['channelTag'])    ? addslashes($para['channelTag'])   : '';
        $destinedUrl    = isset($para['destinedUrl'])   ? addslashes($para['destinedUrl'])  : '';

        $sql = "INSERT INTO `Sponsor_Outgoing_Log`
                (VisitTime, SessionID, Keyword, BidPosition, SponsorType, CurRandStr, PreRandStr, ChannelTag, ChannelID, DestUrl)
                VALUES
                ('{$visitTime}', '{$sessionId}', '{$keyword}', '{$bidPosition}', '{$sponsorType}', '{$curRequestId}', '{$preRequestId}', '{$channelTag}', '{$channelId}', '{$destinedUrl}');";
        return parent::doQuery($sql);
    }

    public function addErrorPageLog($para){
        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $referer        = isset($para['referer'])? addslashes($para['referer']) : '';
        $requestUri     = isset($para['requestURI'])? addslashes($para['requestURI']) : '';
        $channelId      = __T_CHANNEL;
        $sql = "INSERT INTO `Error_Page_Log`
                (VisitTime, SessionID, HttpReferer, RequestUri, ChannelID)
                VALUES
                ('{$visitTime}', '{$sessionId}', '{$referer}', '{$requestUri}', '{$channelId}')";
        return parent::doQuery($sql);
    }

    public function addBidCPCLog($para){
        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $curRequestId   = addslashes(getCurRandStr());
        $preRequestId   = addslashes(getPreRandStr());

        $merchant       = isset($para['m'])? $para['m'] : 0;
        $coupon         = isset($para['p'])? $para['p'] : 0;
        $category       = isset($para['c'])? $para['c'] : 0;
        $cpccost        = isset($para['cpc'])? $para['cpc'] : '';
        $oldamount      = isset($para['oldamount'])? $para['oldamount'] : '';
        $newamount      = isset($para['newamount'])? $para['newamount'] : '';
        $chid           = isset($para['chid'])? $para['chid'] : 0;

        $sql = "INSERT INTO ". __TRACK_DB_BASE .".`Bid_CPC_Log`
                (VisitTime, SessionID, MerchantID, CategoryID, CouponID, CPC, OldBalance, NewBalance, CurRandStr, PreRandStr, ChannelID)
                VALUES
                ('{$visitTime}', '{$sessionId}', '{$merchant}', '{$coupon}', '{$category}', '{$cpccost}', '{$oldamount}', '{$newamount}', '{$curRequestId}', '{$preRequestId}', '{$chid}');";
        return parent::doQuery($sql);
    }

    public function addBannerClickLog($para){
        $visitTime      = getTimeStr();
        $sessionId      = getSessionId();
        $curRequestId   = addslashes(getCurRandStr());
        $preRequestId   = addslashes(getPreRandStr());
        $clkurl         = isset($para['url'])? addslashes($para['url']) : '';
        $clkpos         = isset($para['pos'])? $para['pos'] : 0;
        $chid           = isset($para['chid'])? $para['chid'] : 0;
        $sql = "INSERT INTO `Banner_Click_Log`
                (SessionID, VisitTime, ClickUrl, ClickPos, ChannelID, CurRandStr, PreRandStr)
                VALUES
                ('{$sessionId}', '{$visitTime}', '{$clkurl}', '{$clkpos}', '{$chid}', '{$curRequestId}', '{$preRequestId}');";
        return parent::doQuery($sql);
    }

    public function getMerAffiliateTag($affId, $url){
        $affTag = "";
        if ($affId > 0) {
            $sql = "SELECT UrlVarible FROM " .__DB_BASE. ".Affiliate WHERE Affiliate_ = {$affId}";
            parent::doQuery($sql);
            $rw  = parent::doFetch();
            $affTag = is_object($rw)? $rw->UrlVarible : "";
        }else{
            global $gAffHostIDs, $gAffTags;
            foreach ($gAffHostIDs as $host=>$id){
                if (strpos($url, $host) !== false) {
                    $affTag = $gAffTags[$id];
                    break;
                }
            }
        }
        return $affTag;
    }
}