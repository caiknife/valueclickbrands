<?php
    require_once("../etc/const.inc.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Customer.php");

if ( $remove == "yes" ){
   header("Location: ".__LINK_ROOT."account.php?action=remove&p=".$p);
}
else{
   header("Location: ".__LINK_ROOT."account.php?action=save&p=".$p);
}
?>