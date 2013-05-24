<?php
/**
 * 
 * @version $Id: hidden.php,v 1.1 2013/04/15 10:58:06 rock Exp $
 * @copyright 2003
 */ 
// It is a prviate function. The purpose of this
// function is constructing the redir url framed
// merchant page.
// I don't want to assume that the register_globals
// is set. I don't recommend setting it on.
function _build_redir_url()
{
    $redir_url = '';

    // I tried using reset. However, I somehow
        // don't feel comfortable wiht that.
        // I don't want to reset the internal
        // pointer. It seems to me that PHP is
        // doing something about it.
        
    $int = count($_GET) ;

    if ($int > 0) {     
        $i = 0;
        $keylist = array_keys($_GET);

        foreach( $keylist as $key ) {

            $redir_url .= ($i == 0) ? '?' : '&';
            $i += 1;
                
            $redir_url .= $key . '=' . $_GET["$key"];
        } 
    } 

    return $redir_url;
} 

   require_once("links.inc.php"); 
// UserTracking.php sets LUID and CMESSION cookies.
// It also stores source information to table UserSession.
// If we could do that, hidden.php is the single place
// to prepare for user activiities tracking.
   require_once(__INCLUDE_ROOT . "lib/UserTracking.php"); 
// Should we use template?

$isMerchant = isset( $_GET["m"] ) && isset( $_GET["fff"] ) && ($_GET["fff"] == "yes");

if ($isMerchant === TRUE)
 {
    require_once("hidden_merchant.php");
}
else {
    require_once("hidden_no_merchant.php");
}
?>
