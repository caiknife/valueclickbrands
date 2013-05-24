<?php
/*
*
*  File        : const.inc.php
*  Description : Constatnts for server
*
*/

if ( !defined("CONST_INC_PHP") ){
      define("CONST_INC_PHP","YES");

      umask(0002);
      define('__ETC_PATH', dirname(__FILE__) . '/' );
      define('__ROOT_PATH', dirname(__ETC_PATH) . '/' );
      define('__INCLUDE_ROOT', __ROOT_PATH );
      //define("__WRITABLE", __ROOT_PATH);
      define("__WRITABLE", '/mezi/sites/apache/writable/www.dahongbao.com/');
      define("__FILE_FULLPATH", __WRITABLE.'files/');
      define("__IMAGE_ADD", "/mezi/sites/uploads/dahongbao.com/");
      define("__ROOT_LOGS_PATH", __FILE_FULLPATH."logs/");
      define("__SE_CACHE_DIR", __FILE_FULLPATH."cache/");


      define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants/");
      define("__MERCHANT_IMAGES","images/merchants/");
      define("__OLD_MERCHANT_IMAGES","data/image/logos/");
      define("__LINK_ROOT","/");
          define("__ROOT_TPLS_TPATH", __INCLUDE_ROOT."templates/");

  define("__IMAGE_SRC","http://images.dahongbao.com/");

   define("__DO_TRACKING","YES");
   define("__DEBUG_FILE",__INCLUDE_ROOT."/site.log");
   define("__DEBUG_LEVEL","");

   define("__PAGE_SIZE","50");
   define("__NEW_COUPON_PERIOD","7");
   define("__TOP_MERCHANT_COUNT","20");

   define('COUPONLINKINACTIVE',''); // #FFDF5E'
   define('COUPONLINKACTIVE',''); // #FBE89A'

   define("__DEFAULT_PROMO_HEAD","&nbsp;");
   define("__DEFAULT_PROMO_TEXT","&nbsp;");
   define("__DEFAULT_PROMO_FOOTER_1","");
   define("__DEFAULT_PROMO_FOOTER_2","");
   define("__DEFAULT_PROMO_FOOTER_3","");
   define("__DEFAULT_PROMO_URL","");

   define("__REQUEST_EMAIL","taiwancoupons@mezimedia.com");
   define("__SEND_FRIEND_DEFAULT_TEXT","www.dahongbao.com   ^_^");

   define("__MERCHANT_IMAGES","images/merchants/");
   define("__MERCHANT_UPLOAD_IMAGES","main_admin/images/merchants");

   define("__NEED_DISTRIBUTE", true);
   define("MAIN_HOSTNAME","w0.dahongbao.com");

   define("__DB_TYPE","MySQL");



      define("__MYSQL_PATH","/usr/bin/");
      define("__GZIP_PROG","/bin/gzip");
      define("__GUNZIP_PROG","/bin/gunzip -c");
      define("__TAR_PROG","/bin/tar");
      define("__IFCONFIG_PROG","/sbin/ifconfig");
      define("__GREP_PROG","/bin/grep");
      define("__WGET_PROG","/usr/bin/wget");
      define("__CURL_PROG","/usr/bin/curl -m 3600 -s ");

      define("BASE_HOSTNAME","www.dahongbao.com");
      define("COOKIE_HOSTNAME","www.dahongbao.com");
      define("ADMIN_SERVER_NAME","admin.dahongbao.com");
      define("__SERVER_NUM","1");

      define("__SERVER_1_NAME","www.dahongbao.com");

      define("__DB_TYPE","MySQL");
      define("__DB_HOST","192.168.101.104");
      define("__DB_BASE","dahongbao_FrontEnd");
      define("__DB_USER","dahongbao");
      define("__DB_PASS","E4hiUO5E");

      define("__DB_FRONT","dahongbao_FrontEnd");  //1116
      define("__DB_SE","dahongbao_se");//1116

      define("__PREV_SITE_COUNT","0");

      #define("__DAHONGBAO","mysql://dahongbao:E4hiUO5E@10.40.137.55/dahongbao_FrontEnd?charset=latin1");
      define("__DAHONGBAO","mysql://dahongbao:E4hiUO5E@10.40.137.55/dahongbao_FrontEnd?charset=latin1");
      define("__DAHONGBAO_Master","mysql://dahongbao:E4hiUO5E@10.40.3.11/dahongbao_FrontEnd?charset=latin1");

          define("__ETL","mysql://dahongbao:sbMw3d0m@10.40.3.21/dahongbao_ETL?charset=latin1");
          define("__DEFAULT_DSN",__DAHONGBAO);


          define("__SE","mysql://dahongbao:E4hiUO5E@10.40.137.55/dahongbao_se?charset=latin1");

          define("__TRACK","mysql://dahongbao:E4hiUO5E@192.168.103.56/dahongbao_Reporting?charset=latin1");

          define('__TRACK_DB_HOST', '192.168.103.56');
          define('__TRACK_DB_USER', 'dahongbao');
          define('__TRACK_DB_PASS', 'E4hiUO5E');
          define('__TRACK_DB_PORT', '3306');
          define('__TRACK_DB_BASE', 'dahongbao_Tracking');
          define('__ASKFORMOREKEYHOST', '192.168.103.54:5432');
          define('__COOKIE_DOMAIN', '.dahongbao.com');
          define('__KIJIJI_OPEN', true);
          define('__ADHOST', 'http://www.smarter.com.cn/ats/');
          //define('__TICKET_SERVICE_ADDRESS', 'http://smcnmix101:8081/rest');
          //define('__TICKET_SERVICE_ADDRESS', 'http://10.40.137.55:8081/rest'); 
          define('__TICKET_SERVICE_ADDRESS', 'http://10.40.5.11:8081/rest');

         define("__AD_BAIDUDOMAIN","http://www.baidu.com/s?tn=dahongbao_pg");
          define("TAG_SERVICE_BASE_URL", "http://10.40.137.55:8890");
	  define("__BAIDU_TAG_SERVICE", "http://10.40.137.55:8891");

         define("__TEST_ALLOW_KEYWORD",true);
         define("__GOOGLE_ADS_TEST", "OFF");


         //FrontEnd PHP running on the timezone with Asia/ShangHai. 
         //If system timezone is UTC0, set 8. (means +8 hours)
         //If system timezone is Asia/ShangHai, set 0. (means +0 hours)
         define("__ADJUST_TIMEZONE", 8);



          define("__SETTING_PATH", "files/setting/");

          define("__SETTING_FULLPATH", __ROOT_PATH.__SETTING_PATH);


        define("FRONT_END_ROOT", __ROOT_PATH);
        define("__TRACK_ROOT_PATH", __ROOT_PATH."/scripts/track/");
        define("__INIT_FILES_DIR", true);
       require_once(__ROOT_PATH . "lib/functions/func.AutoLoad.php");


   umask(0002);
}
?>