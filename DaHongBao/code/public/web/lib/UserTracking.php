<?php

$ltuid = "unset";
$sessionID = "0";
// Check if it is a new user
if (!isset($_COOKIE["LUID"])) {
    require_once(__INCLUDE_ROOT . "lib/classes/class.LifeTimeUserDAO.php");

    $dao = new LifeTimeUserDAO();
    $ltuid = $dao->addUser();
    $dao = null;
} else {
    $ltuid = $_COOKIE["LUID"];
} 
// it is suppose to be forever, so I use 10 years! For some reason, IE browser does not take
// 50 years for the expiration time span. I should look into the issue more.
$ok = setCookie("LUID", $ltuid, time() + 311040000, '/', '.couponmountain.com');
// Check if it is a new session
if (!isset($_COOKIE["CMSESSION"])) {
    // Check if the traffic is routed from a specific SourceGroup.
    // Note: This code expects the javascript to set the cookies correctly.
    // $sourceGroup = isset($_COOKIE["SOURCEGROUP"]) ? rawurldecode($_COOKIE["SOURCEGROUP"]) : "Unknown";
    $src = 'Unset';
    if (isset($_GET["source"])) {
        $src = rawurldecode($_GET["source"]);
    } else {
        $src = isset($_COOKIE["SOURCE"]) ? rawurldecode($_COOKIE["SOURCE"]) : "Unknown";
    } 

    $sourceGroup = 'Unknown';
    $gp_pos = strpos($src, '_');
    if ($gp_pos !== false) {
        $sourceGroup = substr($src, 0, $gp_pos);
    }
/*
    else {
        $sourceGroup = is_numeric($src) ? 'affiliate' : $src;
    } 
*/
    if ( $sourceGroup == 'Unknown' ){
       if (isset($_GET["sourcegroup"])) {
           $sourceGroup = rawurldecode($_GET["sourcegroup"]);
       } else if ( isset($_COOKIE["SOURCEGROUP"]) ){
           $sourceGroup = rawurldecode($_COOKIE["SOURCEGROUP"]);
       }
    }

    require_once(__INCLUDE_ROOT . "lib/classes/class.UserSessionDAO.php");

    $dao = new UserSessionDAO();
    $sessionID = $dao->addSession($ltuid, $sourceGroup, $src);
    $dao = null;

    $ok = setCookie("CMSESSION", $sessionID, 0, '/', '.couponmountain.com');
} else {
    $sessionID = $_COOKIE["CMSESSION"];
} 

?>
