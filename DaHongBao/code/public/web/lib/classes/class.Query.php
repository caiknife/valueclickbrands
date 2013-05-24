<?php
if ( !defined("CLASS_QUERY_PHP") ){
   define("CLASS_QUERY_PHP","YES");

    require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
   
   switch ( __DB_TYPE ){
      case "MySQL":
         require_once(__INCLUDE_ROOT."/lib/classes/class.QueryMySQL.php");
         break;
      case "Oracle":
         require_once(__INCLUDE_ROOT."/lib/classes/class.QueryOracle.php");
         break;
      default:
         halt(0,"Unknown DataBase TYPE!!!");
   }
}
?>