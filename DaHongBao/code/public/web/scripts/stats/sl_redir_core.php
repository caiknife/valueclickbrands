<?php
require_once dirname(__FILE__) . '/../../etc/const.inc.php';

/** get request url params */
$bid_pos    = isset($_GET['pos'])? base64_decode($_GET['pos']) : 0;
$keyword    = isset($_GET['slkwd'])? trim($_GET['slkwd']) : "";
$q_tag      = isset($_GET['sltag'])? base64_decode($_GET['sltag']) : "";

// 0: Unknown   1: Overture   2: Google   3: AdKnowledge    4: Baidu
$sponsorType= isset($_GET['st'])? (integer) $_GET['st'] : 0;

/** filter duplicate clicks */
$dup_flag           = 0;
$term_arr           = array();
$compare_term       = md5($keyword);
if (isset($_COOKIE['rank']) && is_array($_COOKIE['rank']) && array_key_exists($bid_pos, $_COOKIE['rank'])) {
    /** get serach term array */
    $term_arr       = explode("||", $_COOKIE['rank'][$bid_pos]);
    if (in_array($compare_term, $term_arr)){
        $dup_flag   = 1;
    } else {
        array_push($term_arr, $compare_term);
    }
} else {
    array_push($term_arr, $compare_term);
}
$cookie_str     = "";
$cookie_str     = implode("||", $term_arr);

/** get destination url */
$dest_url       = base64_decode($_GET['url']);

/** get traffic type & session id */
$nTrafficType   = isset($_COOKIE['TRACKING_TRAFFIC_TYPE']) ? trim($_COOKIE['TRACKING_TRAFFIC_TYPE']) : $nTrafficType;
$nSessionID     = isset($_COOKIE['TRACKING_USER_SESSION']) ? trim($_COOKIE['TRACKING_USER_SESSION']) : $nSessionID;

/** record outgoing click */
if ($nTrafficType >= 0 && $nSessionID > 0 && $dup_flag == 0){
    $logSponsorOutgoing = array(
        'keyword'           => $keyword,
        'bidPosition'       => $bid_pos,
        'channelTag'        => $q_tag,
        'destinedUrl'       => $dest_url,
        'sponsorType'       => $sponsorType,
    );
    Tracking::getInstance()->addSponsorOutgoingLog($logSponsorOutgoing);
}

/** set cookie */
setcookie("rank[{$bid_pos}]", $cookie_str, 0, "/", __T_COOKIE_DOMAIN);
