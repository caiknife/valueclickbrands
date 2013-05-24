<?php
     require_once("../etc/const.inc.php");

   // UserTracking.php sets LUID and CMESSION cookies.
   // It also stores source information to table UserSession.
   if( isset($_GET["c"]) && $_GET["c"] == -1 && isset($_GET["m"]) && $_GET["m"] == -1 ) {
      require_once(__INCLUDE_ROOT."lib/UserTracking.php");
   }

   header("Content-type: image/gif");
   readfile(__INCLUDE_ROOT."images/bgim.gif");
   $c = 0;
   $p = 0;
   $m = 0;
      require_once(__INCLUDE_ROOT."lib/session.php");
?>