<?php
/*
*
*  File        : links.inc.php
*  Description : Constatnts for PATH on server
*
*/
if ( !defined("LINKS_INC_PHP") ){
   define("LINKS_INC_PHP","YES");

	 if ( ereg("bt1admin\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])    ||
        ereg(".*bt1preview.*\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      define("__INCLUDE_ROOT","/home/sites/dahongbao/web/");
      define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants/");
      define("__MERCHANT_IMAGES","images/merchants/");
      define("__OLD_MERCHANT_IMAGES","data/image/logos/");
      define("__LINK_ROOT","/");
   }
   else if ( ereg("admin\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])    ||
        ereg(".*www.*\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      define("__INCLUDE_ROOT","/home/sites/dahongbao/web/");
      define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants/");
      define("__MERCHANT_IMAGES","images/merchants/");
      define("__OLD_MERCHANT_IMAGES","data/image/logos/");
      define("__LINK_ROOT","/");
	  define("__ROOT_TPLS_TPATH", __INCLUDE_ROOT."templates/");
   }else if ( ereg(".*\.dhb\.dev.*",$HTTP_SERVER_VARS["HTTP_HOST"])    ||
        ereg(".*\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      define("__INCLUDE_ROOT","D:\\DevRep\\SmarterV2CN\\Source_Code\\dahongbao\\web\\");
      define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants/");
      define("__MERCHANT_IMAGES","images/merchants/");
      define("__OLD_MERCHANT_IMAGES","data/image/logos/");
      define("__LINK_ROOT","/");
	  define("__ROOT_TPLS_TPATH", __INCLUDE_ROOT."templates/");
   }
   else if ( ereg("beta\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])    ||
        ereg("adminbeta\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      define("__INCLUDE_ROOT","/home/sites/dahongbaobeta/web/");
      define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants/");
      define("__MERCHANT_IMAGES","images/merchants/");
      define("__OLD_MERCHANT_IMAGES","data/image/logos/");
      define("__LINK_ROOT","/");
	  define("__ROOT_TPLS_TPATH", __INCLUDE_ROOT."templates/");
   }
   else if ( ereg("dahongbao\.net.*",$HTTP_SERVER_VARS["HTTP_HOST"])        ||
             ereg("www\.dahongbao\.net.*",$HTTP_SERVER_VARS["HTTP_HOST"])   ){

      define("__INCLUDE_ROOT","/home/sites/dahongbao/web/");
      define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants/");
      define("__MERCHANT_IMAGES","images/merchants/");
      define("__OLD_MERCHANT_IMAGES","data/image/logos/");
      define("__LINK_ROOT","/");
   }
   else if ( ereg("dahongbaodev\.sh\.mezimedia\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])        ||
             ereg("dahongbaoadmin\.sh\.mezimedia\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])   ){

      define("__INCLUDE_ROOT","/home/sites/dahongbaodev/web/");
      define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants/");
      define("__MERCHANT_IMAGES","images/merchants/");
      define("__OLD_MERCHANT_IMAGES","data/image/logos/");
      define("__LINK_ROOT","/");
	  define("__ROOT_TPLS_TPATH", __INCLUDE_ROOT."templates/");
   }
   else if ( ereg("www\.couponsmountain\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])    ||
             ereg("couponsmountain\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])         ||
             ereg("www\.dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])    ||
             ereg("dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])         ||
             ereg("dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])          ||
             ereg("www\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])     ||
             ereg("www1\.dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])   ||
             ereg("www2\.dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])   ||
             ereg("www3\.dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])   ||
             ereg("test\.dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])   ||
             ereg("admin\.dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])  ||
             ereg("forums\.dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])  ||
             ereg("forum\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"])  ||
             ereg("madmin\.dahongbaos\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ||
             ereg("admin1\.dahongbao\.com.*",$HTTP_SERVER_VARS["HTTP_HOST"]) ){

      define("__INCLUDE_ROOT","/home/sites/dahongbao/web/");
      define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants/");
      define("__MERCHANT_IMAGES","images/merchants/");
      define("__OLD_MERCHANT_IMAGES","data/image/logos/");
      define("__LINK_ROOT","/");
   }

}
?>
