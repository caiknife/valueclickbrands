<?php
/*
File        : func.include_file.php
Description : Functions for iclude files in pages
Author      : Valeriy Zavolodko
Date        : 18.09.2001
*/

if (!defined("INCLUDE_FILE_PHP")){
   define("INCLUDE_FILE_PHP","Y");

         require_once("$INCLUDE_ROOT/lib/classes/class.rFastTemplate.php");

   function include_file($fname1, $fname2 ="", $template ="", $var =array()){
      global $INCLUDE_ROOT;

      $file_content = "File not found!";
      if (file_exists($fname1) && filesize($fname1)){
         $f = fopen($fname1,"r");
         $file_content = fread($f,filesize($fname1));
         fclose($f);
      }
      else{
         if (file_exists($fname2) && filesize($fname2)){
            $f = fopen($fname2,"r");
            $file_content = fread($f,filesize($fname2));
            fclose($f);
         }
      }

      if ($template != ""){
         $tpl = new rFastTemplate("$INCLUDE_ROOT/templates");
         $tpl->define(array(inc_file => $template));
         $tpl->assign(INCLUDE_FILE,$file_content);
         reset($var);
         while (list($var_name,$var_value) = @each($var)){
            $tpl->assign($var_name,$var_value);
         }
         $tpl->parse("INC_FILE","inc_file");
         return $tpl->fetch("INC_FILE");
      }
      else{
         return $file_content;
      }
   }
}
?>