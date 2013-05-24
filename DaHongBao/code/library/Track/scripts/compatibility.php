<?php
$_GET_ORIG  = $_GET;
$_GET = Tracking_Uri::extract($_SERVER['REQUEST_URI']);

/* for sponsor */

/* channel id */
if (isset($_GET[Tracking_Uri::CHANNEL_ID])){
    $_GET['chid'] = $_GET[Tracking_Uri::CHANNEL_ID];
}

/* channel tag */
if (isset($_GET[Tracking_Uri::CHANNEL_TAG])){
    $_GET['ct'] = base64_encode($_GET[Tracking_Uri::CHANNEL_TAG]);
}

/* destined url */
if (isset($_GET[Tracking_Uri::DESTINED_URL])){
    $_GET['statDestUrl'] = base64_encode($_GET[Tracking_Uri::DESTINED_URL]);
}

/* display position */
if (isset($_GET[Tracking_Uri::DISPLAY_POSITION])){
    $_GET['statBidPosition'] = $_GET[Tracking_Uri::DISPLAY_POSITION];
}

/* keyword */
if (isset($_GET[Tracking_Uri::KEYWORD])){
    $_GET['searchTerm'] = $_GET[Tracking_Uri::KEYWORD];
}

/* keyword2 */
if (isset($_GET[Tracking_Uri::KEYWORD_SUPPLEMENTARY])){
    $_GET['kw2'] = $_GET[Tracking_Uri::KEYWORD_SUPPLEMENTARY];
}

/* for offer */

/* bid postion */
if (isset($_GET[Tracking_Uri::BID_POSITION])){
    $_GET['statBidPos'] = $_GET[Tracking_Uri::BID_POSITION];
}

/* channel id */
if (isset($_GET[Tracking_Uri::CHANNEL_ID])){
    $_GET['statChannelID'] = $_GET[Tracking_Uri::CHANNEL_ID];
}

/* coupon id */
if (isset($_GET[Tracking_Uri::COUPON_ID])){
    $_GET['couponid'] = $_GET[Tracking_Uri::COUPON_ID];
}

/* display postion */
if (isset($_GET[Tracking_Uri::DISPLAY_POSITION])){
    $_GET['statDisplayPos'] = $_GET[Tracking_Uri::DISPLAY_POSITION];
}

/* merchant count */
if (isset($_GET[Tracking_Uri::MERCHANT_COUNT])){
    $_GET['statMerCount'] = $_GET[Tracking_Uri::MERCHANT_COUNT];
}

/* offer id */
if (isset($_GET[Tracking_Uri::OFFER_ID])){
    $_GET['statOfferID'] = $_GET[Tracking_Uri::OFFER_ID];
}

/* rate rank */
if (isset($_GET[Tracking_Uri::RATE_RANK])){
    $_GET['statRateRank'] = $_GET[Tracking_Uri::RATE_RANK];
}

/* price rank */
if (isset($_GET[Tracking_Uri::PRICE_RANK])){
    $_GET['statPriceRank'] = $_GET[Tracking_Uri::PRICE_RANK];
}

/* sort by */
if (isset($_GET[Tracking_Uri::SORT_BY])){
    $_GET['statSortBy'] = $_GET[Tracking_Uri::SORT_BY];
}


/* router */
switch (isset($_GET[Tracking_Uri::BUILD_TYPE]) ? strtolower($_GET[Tracking_Uri::BUILD_TYPE]): '') {
    case 'sponsor':
        require_once '../../stats/scripts/sponsorRedir_core.php';
        break;
    case 'offer':
        require_once '../../stats/scripts/redir_core.php';
        break;

    case 'dilingling':
        if (isset($_GET[Tracking_Uri::DISPLAY_POSITION])){
            list($adsCount, $adsPosition) = explode('|', $_GET[Tracking_Uri::DISPLAY_POSITION]);
        } else {
            $adsCount = $adsPosition = 0;
        }

        if (isset($_GET[Tracking_Uri::REMARK])){
            list($phone, $extension) = explode('|', $_GET[Tracking_Uri::REMARK]);
        } else {
        	$phone = $extension = '';
        }

        $logDilinglingOutgoing = array(
            'SessionID'     => isset($_COOKIE['TRACKING_SESSION_ID'])   ? (integer) $_COOKIE['TRACKING_SESSION_ID'] : $nStatSessionID,
            'CHID'          => isset($_GET[Tracking_Uri::CHANNEL_ID])   ? (integer) $_GET[Tracking_Uri::CHANNEL_ID] :  0,
            'CATID'         => isset($_GET[Tracking_Uri::CATEGORY_ID])  ? (integer) $_GET[Tracking_Uri::CATEGORY_ID]:  0,
            'ProductID'     => isset($_GET[Tracking_Uri::PRODUCT_ID])   ? (integer) $_GET[Tracking_Uri::PRODUCT_ID] :  0,
            'Title'         => isset($_GET[Tracking_Uri::PRODUCT_NAME]) ? $_GET[Tracking_Uri::PRODUCT_NAME]         : '',
            'URL'           => isset($_GET[Tracking_Uri::DESTINED_URL]) ? $_GET[Tracking_Uri::DESTINED_URL]         : '',
            'Keyword'       => isset($_GET[Tracking_Uri::KEYWORD])      ? $_GET[Tracking_Uri::KEYWORD]              : '',
            'Phone'         => $phone,
            'Extension'     => $extension,
            'AdsCount'      => $adsCount,
            'AdsPosition'   => $adsPosition,
        );
        DilinglingDao::logTracking($logDilinglingOutgoing);
        break;

    case 'taoke':
        $logTaokeOutgoing = array(
            'catid'         => isset($_GET[Tracking_Uri::CATEGORY_ID])  ? (integer) $_GET[Tracking_Uri::CATEGORY_ID]:  0,
            'nickname'      => isset($_GET[Tracking_Uri::MERCHANT_NAME])? $_GET[Tracking_Uri::MERCHANT_NAME]        : '',
            'title'         => isset($_GET[Tracking_Uri::PRODUCT_NAME]) ? $_GET[Tracking_Uri::PRODUCT_NAME]         : '',
            'aid'           => isset($_GET[Tracking_Uri::OFFER_ID])     ? (integer) $_GET[Tracking_Uri::OFFER_ID]   :  0,
            'mid'           => isset($_GET[Tracking_Uri::MERCHANT_ID])  ? (integer) $_GET[Tracking_Uri::MERCHANT_ID]:  0,
        );
        C2CDao::c2cLog($logTaokeOutgoing);
        break;

    case 'affiliate':
    default:
        break;
}


$_GET  = $_GET_ORIG;
unset($_GET['statMid'], $_GET['statKwd'], $_GET['statRank'], $_GET['statUrl'], $_GET['channelTag']);
/** end old db tracking **/