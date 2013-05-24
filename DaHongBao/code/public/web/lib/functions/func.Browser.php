<?php
/*
File        : func.Browser.php
Description : Functions for work with browsers
Author      : Valeriy Zavolodko
Date        : 18.09.2001
*/

if (!defined("FUNCTION_FOR_BROWSERS_PHP")){
   define("FUNCTION_FOR_BROWSERS_PHP","YES");

   function get_browser_template($tpl){
      return $tpl;
   }

   function my_get_browser(){
      global $HTTP_SERVER_VARS;
      if ( preg_match("/MSIE\s6/",$HTTP_SERVER_VARS["HTTP_USER_AGENT"]) ) return "MSIE6";
      return "UNKNOWN";
   }
}
?>