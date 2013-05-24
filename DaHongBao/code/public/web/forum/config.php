<?php


// phpBB 2.x auto-generated config file
// Do not change anything in this file!

$dbms = 'mysql';

   if ( ereg("forumcm2beta\.couponmountain\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      $dbhost = 'localhost';
      $dbname = 'cm2beta';
      $dbuser = 'cm2beta';
      $dbpasswd = 'sqly8s5z';
   }
   else if ( ereg("forum\.cm2\.ace.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      $dbhost = 'localhost';
      $dbname = 'couponmountain';
      $dbuser = 'couponmountain';
      $dbpasswd = 'sqly8s5z';
   }

   else if ( ereg("forum\.couponmountain\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      $dbhost = 'localhost';
      $dbname = 'couponmountain';
      $dbuser = 'couponmountain';
      $dbpasswd = 'sqly8s5z';
   }
   else if ( ereg("forums\.couponmountain\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      $dbhost = 'localhost';
      $dbname = 'couponmountain';
      $dbuser = 'couponmountain';
      $dbpasswd = 'sqly8s5z';
   }


$table_prefix = 'phpbb_';

define('PHPBB_INSTALLED', true);

?>
