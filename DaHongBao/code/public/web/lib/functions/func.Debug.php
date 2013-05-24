<?php

if ( !defined("FUNC_DEBUG_PHP") ){
   define("FUNC_DEBUG_PHP","YES");

   function halt($class =0, $msg ="", $err ="", $errno =0){
      if ( is_object($class) ){
         $class->Error  = $err;
         $class->Errno  = $errno;

         if ( $class->Halt_On_Error == "no" ){
            debug($class, 1, $msg, $err, $errno);
            return;
         }
         haltmsg($class->ClassName, $msg, $err, $errno);

         if ($class->Halt_On_Error != "report")
            die("Session halted.");
      }
      else{
         if ( __HALT_ON_ERROR == "no" ){
            debug($class, 1, $msg, $err, $errno);
            return;
         }
         haltmsg("", $msg, $err, $errno);

         if ( __HALT_ON_ERROR != "report" )
            die("Session halted.");

      }
   }

   function haltmsg($classname, $msg, $err, $errno){
      if ( "" != $classname ){
         printf("<b>Critical error in class '%s':</b> %s<br>\n", $classname, $msg);
         printf("<b>PHP Error</b>: %s (%s)<br>\n",$err, $errno);
      }
      else{
         printf("<b>Critical error:</b> %s<br>\n", $msg);
         printf("<b>PHP Error</b>: %s (%s)<br>\n",$err, $errno);
      }
   }

   function debug($class =0, $level =0, $msg =""){
      if ( is_object($class) ){
         if ( $class->DebugLevel >= $level ){
            debugmsg($class->ClassName,$msg);
         }
      }
      else{
         if ( __DEBUG_LEVEL >= $level ){
            debugmsg("",$msg);
         }
      }
   }

   function debugmsg($classname,$msg){
      if ( !($f = @fopen(__DEBUG_FILE,"a")) ) die("Can not open debug file!!!");
      if ( "" != $classname ){
         $s = sprintf("%s, class '%s': %s\n",getDateTime("m/d/Y H:i:s"),$classname,$msg);
      }
      else{
         $s = sprintf("%s: %s\n",getDateTime("m/d/Y H:i:s"),$classname,$msg);
      }
      fputs($f,$s);
      fclose($f);
   }
}
?>