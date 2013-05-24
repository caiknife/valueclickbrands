<?php
if ( !defined("CLASS_CACHE_PHP") ){
   define("CLASS_CACHE_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
    require_once(__INCLUDE_ROOT."lib/functions/func.TrackURL.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
    //require_once(__INCLUDE_ROOT."lib/classes/class.VItem.php");
    //require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
	require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.System.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.Source.php");
    require_once(__INCLUDE_ROOT."/lib/classes/class.Meta.php");
	require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
	
   class Cache{
      var $ClassName = "Cache";
      var $Key       = "Cache_";

      function Cache($Cache =0){

      }

      function precompileTemplates(){
         $res = "";
         $res .= $this->PrecompileMain();
         return $res;
      }

      function PrecompileMain($with_category =1){
         $result = "";


         $oSystem = new System("__POPDOWN");
		 
		 $oCategory = new Category();
		 $categoryList = $oCategory->getCategoryList("SitemapPriority");
         $categoryListCopy = $categoryList;
		 
		 $oMerchat  = new Merchant();
		 $merchatList = $oMerchat->getDropDownMerchantList();

         $oPage = new Page();

         //if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."pages/merchant_list.html"),"w")) ){
//            return ("MAIN CACHE: can't open file 'pages/merchant_list.html'<br>");
//         }
//         fwrite($f,"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n<td><img src=\"http://www.dahongbao.com/images/header_display_pulldown.gif\"></td>\n</tr>\n<tr>\n<td><img src=\"http://www.dahongbao.com/images/bgim.gif\" width=\"5\" height=\"5\"></td>\n</tr>\n<tr>\n<td valign=\"top\"><form>\n<select name=\"merchantList\" style=\"width:200px\" class=\"pulldown\" onChange=\"JavaScript:top.MyClose=false;top.location.href = 'http://www.dahongbao.com/' + this.options[this.selectedIndex].text.replace(/ /g,'_') + '/index.html'\">\n<option value=\"0\">Select Merchant</option>");
//         fwrite($f,$oMerchatList->getSelect("Merchant_","CName"));
//         fwrite($f,"</select>\n</form>\n</td>\n</tr>\n</table>");
//         fclose($f);

         $tmp = '\'';
		 for ($i=0; $i<count($merchatList); $i++) {
		 	$tmp .= $merchatList[$i]["NameURL"] . "\',\'";
		 }
         $tmp = substr($tmp,0,strlen($tmp)-2);

         $searchType = array("all" => "", "merchant" => "_m", "product" => "_p");
         while ( list($ksearch, $vsearch) = @each($searchType) ){
            $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
            $tpl->VerifyUnmatched = "no";
            $tpl->define(array(
               'main'      => get_browser_template("main.tpl")
            ));

            $tpl->assign(array(
               'BASE_HOSTNAME'     => BASE_HOSTNAME,
               MERCHANT_ARRAY    => $tmp,
               LINK_ROOT         => __LINK_ROOT,
               INCLUDE_ROOT      => __INCLUDE_ROOT,
               CATEGORY_LIST     => $oCategoryList->getJavaArray(),
               MERCHANT_LIST     => $oMerchatList->getSelect("Merchant_","CName"),
               SEARCH_METHOD     => $vsearch,
            ));

            $oPage->find("RESOURCE_INCLUDE");
            $tpl->assign(array(
               RESOURCE_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
            ));
            $oPage->find("MY_ACCOUNT_INCLUDE");
            $tpl->assign(array(
               MY_ACCOUNT_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
            ));
            $oPage->find("BOTTOM_MENU_INCLUDE");
            $tpl->assign(array(
               BOTTOM_MENU_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
            ));

            $tpl->define_dynamic("category_list","main");
            while ( $oCat = $oCategoryList->nextItem() ){
               $oCat->countCoupon(1);
               if ( $oCat->get("Coupons") > 0 && $oCat->get("isActive") == 1){
                  $tpl->assign(array(
                     CATEGORY_URL   => (str_replace(' ','_',$oCat->get("NameURL"))),
                     CATEGORY_URLJ  => addslashes(str_replace(' ','_',$oCat->get("NameURL"))),
                     CATEGORY_ID    => $oCat->ID,
                     CATEGORY_STATE => "off",
                     CATEGORY_NAME  => $oCat->get("Name"),
                     CATEGORY_BACKGROUND  => "category_background",
                     CATEGORY_IMAGE => "category_background",
                  ));
                  $tpl->parse("CATEGORYLIST",".category_list");
               }
            }
            if ( $oSystem->get("Value") == 1 ){
               $tpl->define_dynamic("popdown","main");
               $oSourceList = new SourceList();
               $oSourceList->run("SELECT DISTINCT s.Name FROM Source s, SourceGroup g WHERE s.SourceGroup_=g.SourceGroup_ AND g.isPopup=1");
               $lsource = "";
               while ( $rec = $oSourceList->get_record() ){
                  $lsource .= "'".$rec["Name"]."',";
                  $oSourceList->next();
               }
               if ( strlen($lsource) > 0 ){
                  $lsource = substr($lsource,0,strlen($lsource)-1);
               }
               $tpl->assign(array(
                  SRV_NAME          => __SERVER_NAME,
                  SOURCE_LIST_POPUP => $lsource,
               ));
               $tpl->parse("POPDOWN",".popdown");
            }
/*
            switch ($ksearch){
               case "all":
                  $tpl->define_dynamic("search_all","main");
                  $tpl->parse("SEARCH_BOX","search_all");
                  break;
               case "product":
                  $tpl->define_dynamic("search_product","main");
                  $tpl->parse("SEARCH_BOX","search_product");
                  break;
               case "merchant":
                  $tpl->define_dynamic("search_merchant","main");
                  $tpl->parse("SEARCH_BOX","search_merchant");
                  break;
            }
*/
            $tpl->define_dynamic("search_merchant","main");
            $tpl->parse("SEARCH_BOX","search_merchant");

            $tpl->parse("MAIN","main");

            if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."templates/cache/main".$vsearch.".tpl"),"w")) ){
               return ("MAIN CACHE: can't open file 'templates/cache/main".$vsearch.".tpl'<br>");
            }
            fwrite($f,$tpl->fetch());
            fclose($f);


            //////////////////////////////////////////////////////
            // PriceGrabber templates
            //////////////////////////////////////////////////////
/*
            $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
            $tpl->VerifyUnmatched = "no";
            $tpl->define(array(
               'main'      => get_browser_template("main_pricegrabber.tpl")
            ));

            $tpl->assign(array(
               LINK_ROOT         => "http://www.dahongbao.com".__LINK_ROOT,
               INCLUDE_ROOT      => __INCLUDE_ROOT,
               CATEGORY_LIST     => $oCategoryList->getJavaArray(),
               MERCHANT_LIST     => $oMerchatList->getSelect("Merchant_","Name"),
               PAGE_TITLE        => "dahongbao.com - Comparison Shopping",
               KEYWORDS          => "",
               DESCRIPTION_H     => "",
               NAVIGATION_PATH   => getNavigation(array("Comparison Shopping" => "")),
               PROMO_HEAD        => "How To Use Online Coupons",
               PROMO_TEXT        => "1.&nbsp;Click &quot;Use this Coupon&quot;.<br>2.&nbsp;Shop as usual at relevant site.<br>3.&nbsp;Write down Coupon Code at the top of the page.<br>4.&nbsp;Enter Coupon Code during check-out process.<br>",
               PROMO_FOOTER_1    => "See our",
               PROMO_FOOTER_2    => "FAQ",
               PROMO_FOOTER_3    => "for more information",
               PROMO_URL         => "http://www.dahongbao.com".__LINK_ROOT."FAQ.html",
               SEARCH_METHOD     => $vsearch,
            ));

            $oPage->find("RESOURCE_INCLUDE");
            $tpl->assign(array(
               RESOURCE_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
            ));
            $oPage->find("MY_ACCOUNT_INCLUDE");
            $tpl->assign(array(
               MY_ACCOUNT_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
            ));

            $tpl->define_dynamic("category_list","main");
            while ( $oCat = $oCategoryList->nextItem() ){
               $oCat->countCoupon(1);
               if ( $oCat->get("Coupons") > 0 && $oCat->get("isActive") == 1){
                  $tpl->assign(array(
                     CATEGORY_URL   => (str_replace(' ','_',$oCat->get("Name"))),
                     CATEGORY_ID    => $oCat->ID,
                     CATEGORY_STATE => "off",
                     CATEGORY_NAME  => addslashes($oCat->get("Name")),
                     CATEGORY_BACKGROUND  => "category_background",
                     CATEGORY_IMAGE => "category_background",
                  ));
                  $tpl->parse("CATEGORYLIST",".category_list");
               }
            }

            switch ($ksearch){
               case "all":
                  $tpl->define_dynamic("search_all","main");
                  $tpl->parse("SEARCH_BOX","search_all");
                  break;
               case "product":
                  $tpl->define_dynamic("search_product","main");
                  $tpl->parse("SEARCH_BOX","search_product");
                  break;
               case "merchant":
                  $tpl->define_dynamic("search_merchant","main");
                  $tpl->parse("SEARCH_BOX","search_merchant");
                  break;
            }

            $tpl->parse("MAIN","main");

            if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."pages/main_pricegrabber".$vsearch.".html"),"w")) ){
               return ("MAIN CACHE: can't open file 'pages/main_pricegrabber".$vsearch.".html'<br>");
            }
            fwrite($f,$tpl->fetch());
            fclose($f);
*/
         }

         $result .= "MAIN CACHE: main.tpl - updated<br>";


/*
         ///////////////////////////////////////////////////////////
         // PriceGrabber templates (footer)
         ///////////////////////////////////////////////////////////

         $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
         $tpl->VerifyUnmatched = "no";
         $tpl->define(array(
            'main'      => get_browser_template("footer_pricegrabber.tpl")
         ));

         $tpl->assign(array(
            LINK_ROOT         => "http://www.dahongbao.com".__LINK_ROOT,
            INCLUDE_ROOT      => __INCLUDE_ROOT,
         ));
         $oPage->find("BOTTOM_MENU_INCLUDE");
         $tpl->assign(array(
            BOTTOM_MENU_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
         ));

         $tpl->parse("MAIN","main");

         fwrite($f,$tpl->fetch());
         fclose($f);

         if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."pages/footer_pricegrabber.html"),"w")) ){
            return ("MAIN CACHE: can't open file 'pages/footer_pricegrabber.html'<br>");
         }
*/

         if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."templates/cache/main_account.tpl"),"w")) ){
            return ("MAIN CACHE: can't open file 'templates/cache/main_account.tpl'<br>");
         }

         $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
         $tpl->VerifyUnmatched = "no";
         $tpl->define(array(
            'main'      => get_browser_template("main_account.tpl")
         ));

         $tpl->assign(array(
            'BASE_HOSTNAME'     => BASE_HOSTNAME,
            MERCHANT_ARRAY    => $tmp,		//added by Alan.
            SEARCH_METHOD     => $vsearch,	//added by Alan.
            LINK_ROOT         => __LINK_ROOT,
            INCLUDE_ROOT      => __INCLUDE_ROOT,
            CATEGORY_LIST     => $oCategoryList->getJavaArray(),
            MERCHANT_LIST     => $oMerchatList->getSelect("Merchant_","CName"),
         ));
         $oPage->find("RESOURCE_INCLUDE");
         $tpl->assign(array(
            RESOURCE_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
         ));
         $oPage->find("MY_ACCOUNT_INCLUDE");
         $tpl->assign(array(
            MY_ACCOUNT_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
         ));
         $oPage->find("BOTTOM_MENU_INCLUDE");
         $tpl->assign(array(
            BOTTOM_MENU_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
         ));

         $tpl->define_dynamic("category_list","main");
         while ( $oCat = $oCategoryList->nextItem() ){
            $oCat->countCoupon(1);
            if ( $oCat->get("Coupons") > 0 && $oCat->get("isActive") == 1){
               $tpl->assign(array(
                  CATEGORY_URL   => (str_replace(' ','_',$oCat->get("NameURL"))),
                  CATEGORY_URLJ  => addslashes(str_replace(' ','_',$oCat->get("NameURL"))),
                  CATEGORY_ID    => $oCat->ID,
                  CATEGORY_STATE => "off",
                  CATEGORY_NAME        => $oCat->get("Name"),
                  CATEGORY_BACKGROUND  => "category_background",
                  CATEGORY_IMAGE => "category_background",
               ));
               $tpl->parse("CATEGORYLIST",".category_list");
            }
         }
         if ( $oSystem->get("Value") == 1 ){
            $oSourceList = new SourceList();
            $oSourceList->run("SELECT DISTINCT s.Name FROM Source s, SourceGroup g WHERE s.SourceGroup_=g.SourceGroup_ AND g.isPopup=1");
            $lsource = "";
            while ( $rec = $oSourceList->get_record() ){
               $lsource .= "'".$rec["Name"]."',";
               $oSourceList->next();
            }
            if ( strlen($lsource) > 0 ){
               $lsource = substr($lsource,0,strlen($lsource)-1);
            }
            $tpl->define_dynamic("popdown","main");
            $tpl->assign(array(
               SRV_NAME       => __SERVER_NAME,
               SOURCE_LIST_POPUP => $lsource,
            ));
            $tpl->parse("POPDOWN",".popdown");
         }

         $tpl->parse("MAIN","main");

         fwrite($f,$tpl->fetch());
         fclose($f);

         $result .= "MAIN CACHE: main_account.tpl - updated<br>";

         if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."templates/cache/main_affiliate.tpl"),"w")) ){
            return ("MAIN CACHE: can't open file 'templates/cache/main.tpl'<br>");
         }

         $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
         $tpl->VerifyUnmatched = "no";
         $tpl->define(array(
            'main'      => get_browser_template("main_affiliate.tpl")
         ));

         $tpl->assign(array(
            'BASE_HOSTNAME'     => BASE_HOSTNAME,
            LINK_ROOT         => 'http://www.dahongbao.com'.__LINK_ROOT,
            INCLUDE_ROOT      => __INCLUDE_ROOT,
            CATEGORY_LIST     => $oCategoryList->getJavaArray(),
            MERCHANT_LIST     => $oMerchatList->getSelect("Merchant_","CName"),
            'FORUMACTIVE'        => COUPONLINKINACTIVE,
            'HOTCACTIVE'         => COUPONLINKINACTIVE,
            'NEWCACTIVE'         => COUPONLINKINACTIVE,
            'EXPCACTIVE'         => COUPONLINKINACTIVE,
            'FRSCACTIVE'         => COUPONLINKINACTIVE,
         ));
         $oPage->find("RESOURCE_INCLUDE");
         $tpl->assign(array(
            RESOURCE_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
         ));
         $oPage->find("MY_ACCOUNT_INCLUDE");
         $tpl->assign(array(
            MY_ACCOUNT_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
         ));
         $oPage->find("BOTTOM_MENU_INCLUDE");
         $tpl->assign(array(
            BOTTOM_MENU_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
         ));

         $tpl->define_dynamic("category_list","main");
         while ( $oCat = $oCategoryList->nextItem() ){
            $oCat->countCoupon(1);
            if ( $oCat->get("Coupons") > 0 && $oCat->get("isActive") == 1){
               $tpl->assign(array(
                  CATEGORY_URL   => (str_replace(' ','_',$oCat->get("NameURL"))),
                  CATEGORY_URLJ  => addslashes(str_replace(' ','_',$oCat->get("NameURL"))),
                  CATEGORY_ID    => $oCat->ID,
                  CATEGORY_STATE => "off",
                  CATEGORY_NAME        => ($oCat->get("Name")),
                  CATEGORY_BACKGROUND  => "category_background",
                  CATEGORY_IMAGE => "category_background",
               ));
               $tpl->parse("CATEGORYLIST",".category_list");
            }
         }
         if ( $oSystem->get("Value") == 1 ){
            $oSourceList = new SourceList();
            $oSourceList->run("SELECT DISTINCT s.Name FROM Source s, SourceGroup g WHERE s.SourceGroup_=g.SourceGroup_ AND g.isPopup=1");
            $lsource = "";
            while ( $rec = $oSourceList->get_record() ){
               $lsource .= "'".$rec["Name"]."',";
               $oSourceList->next();
            }
            if ( strlen($lsource) > 0 ){
               $lsource = substr($lsource,0,strlen($lsource)-1);
            }
            $tpl->define_dynamic("popdown","main");
            $tpl->assign(array(
               SRV_NAME       => __SERVER_NAME,
               SOURCE_LIST_POPUP => $lsource,
            ));
            $tpl->parse("POPDOWN",".popdown");
         }

         $tpl->parse("MAIN","main");

         fwrite($f,$tpl->fetch());
         fclose($f);

         $result .= "MAIN CACHE: main_affiliate.tpl - updated<br>";

         if ( $with_category == 1 ){
            while ( $oCategory = $oCategoryListCopy->nextItem() ){
               $oCategory->generate_tpl($oCategoryList,$oMerchatList);
               $result .= "MAIN CACHE: category ".$oCategory->get("NameURL").".tpl - updated<br>";
            }
         }

         if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."pages/hot_coupons.html"),"w")) ){
            return ("MAIN CACHE: can't open file 'hot_coupons.html'<br>");
         }
         $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
         $tpl->define(array(
             'main'      => get_browser_template("hot_coupons.tpl"),
         ));

         $tpl->define_dynamic("frame_list","main");
         $cnt = "";
         while ( $oMerchant = $oMerchatList->nextItem() ){
            if ( $oMerchant->get("isPopdown") == 1 ){
               $mUrl = $oMerchant->get("URL");
               if(!eregi('amazon',$oMerchant->get('Name'))) {
                  $mUrl = __LINK_ROOT.'frame_redir.php?mUrl='.base64_encode($mUrl);
               }
               $tpl->assign(array(
                  FRAME_URL   => $mUrl,
               ));
               $tpl->parse("FRAMELIST",".frame_list");
               $cnt .= ",1";
            }
         }
         if ( strlen($cnt) == 0 ){
            $tpl->clear_dynamic("frame_list");
         }

         $mMeta = new Meta();
         $mMeta->find('ItemType','Page');

         $tpl->assign(array(
            'BASE_HOSTNAME'  => BASE_HOSTNAME,
            LINK_ROOT      => __LINK_ROOT,
            FRAME_COUNT    => $cnt,
            FRAME_H        => $mMeta->get('MetaFrame'),
         ));

         $tpl->parse("MAIN","main");

         fwrite($f,$tpl->fetch());
         fclose($f);
         $result .= "MAIN CACHE: hot_coupons.html - updated<br>";




/*
         if ( $oSystem->get("Value") == 1 ){
            if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."pages/popdown.html"),"w")) ){
               return ("MAIN CACHE: can't open file 'popdown.html'<br>");
            }
            $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
            $tpl->define(array(
                'main'      => get_browser_template("popdown_frame.tpl"),
            ));

            $tpl->define_dynamic("frame_list","main");
            $cnt = "";
            while ( $oMerchant = $oMerchatList->nextItem() ){
               if ( $oMerchant->get("isPopdown") == 1 ){
                  $tpl->assign(array(
                     FRAME_URL   => $oMerchant->get("URL")
                  ));
                  $tpl->parse("FRAMELIST",".frame_list");
                  $cnt .= ",1";
               }
            }
            if ( strlen($cnt) == 0 ){
               $tpl->clear_dynamic("frame_list");
            }
            $oSystem = new System("__POPDOWN_TIMEOUT");
            $tpl->assign(array(
               LINK_ROOT      => __LINK_ROOT,
               FRAME_COUNT    => $cnt,
               CLOSE_TIMEOUT  => (integer)$oSystem->get("Value"),
            ));

            $tpl->parse("MAIN","main");

            fwrite($f,$tpl->fetch());
            fclose($f);
            $result .= "MAIN CACHE: popdown.html - updated<br>";
         }
         else{
            @unlink(str_replace(" ","_",__INCLUDE_ROOT."pages/popdown.html"));
            $result .= "MAIN CACHE: popdown.html - removed<br>";
         }
*/

         // precompile for FORUM

            $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
            $tpl->VerifyUnmatched = "no";
            $tpl->define(array(
               'main'      => get_browser_template("main_account.tpl")
            ));

            $tpl->assign(array(
               'BASE_HOSTNAME'     => BASE_HOSTNAME,
               LINK_ROOT         => 'http://www.dahongbao.com'.__LINK_ROOT,
               INCLUDE_ROOT      => __INCLUDE_ROOT,
               CATEGORY_LIST     => $oCategoryList->getJavaArray(),
               MERCHANT_LIST     => $oMerchatList->getSelect("Merchant_","CName"),
               SEARCH_METHOD     => $vsearch,
            ));

            $oPage->find("RESOURCE_INCLUDE");
            $tpl->assign(array(
               RESOURCE_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
            ));
            $oPage->find("MY_ACCOUNT_INCLUDE");
            $tpl->assign(array(
               MY_ACCOUNT_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
            ));
            $oPage->find("BOTTOM_MENU_INCLUDE");
            $tpl->assign(array(
               BOTTOM_MENU_INCLUDE  => strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "",
            ));

            $tpl->define_dynamic("category_list","main");
            while ( $oCat = $oCategoryList->nextItem() ){
               $oCat->countCoupon(1);
               if ( $oCat->get("Coupons") > 0 && $oCat->get("isActive") == 1){
                  $tpl->assign(array(
                     CATEGORY_URL   => (str_replace(' ','_',$oCat->get("NameURL"))),
                     CATEGORY_URLJ  => addslashes(str_replace(' ','_',$oCat->get("NameURL"))),
                     CATEGORY_ID    => $oCat->ID,
                     CATEGORY_STATE => "off",
                     CATEGORY_NAME  => $oCat->get("Name"),
                     CATEGORY_BACKGROUND  => "category_background",
                     CATEGORY_IMAGE => "category_background",
                  ));
                  $tpl->parse("CATEGORYLIST",".category_list");
               }
            }
            if ( $oSystem->get("Value") == 1 ){
               $tpl->define_dynamic("popdown","main");
               $oSourceList = new SourceList();
               $oSourceList->run("SELECT DISTINCT s.Name FROM Source s, SourceGroup g WHERE s.SourceGroup_=g.SourceGroup_ AND g.isPopup=1");
               $lsource = "";
               while ( $rec = $oSourceList->get_record() ){
                  $lsource .= "'".$rec["Name"]."',";
                  $oSourceList->next();
               }
               if ( strlen($lsource) > 0 ){
                  $lsource = substr($lsource,0,strlen($lsource)-1);
               }
               $tpl->assign(array(
                  SRV_NAME          => __SERVER_NAME,
                  SOURCE_LIST_POPUP => $lsource,
               ));
               $tpl->parse("POPDOWN",".popdown");
            }
            $tpl->define_dynamic("search_merchant","main");
            $tpl->parse("SEARCH_BOX","search_merchant");

            $tpl->parse("MAIN","main");
            $full_tpl = $tpl->fetch("MAIN");
/*
         if ( !($f = @fopen(__INCLUDE_ROOT."templates/cache/main.tpl","r")) ){
            return ("MAIN CACHE: can't open file 'templates/cache/main.tpl'<br>");
         }
         $full_tpl   = @fread($f,filesize(__INCLUDE_ROOT."templates/cache/main.tpl"));
         @fclose($f);
*/

         $head_tpl   = str_replace('{LINK_ROOT}','http://'.BASE_HOSTNAME.'/',str_replace('</head>',
"<!-- link rel=\"stylesheet\" href=\"templates/subSilver/{T_HEAD_STYLESHEET}\" type=\"text/css\" -->
<style type=\"text/css\">
<!--
body {
   background-color: {T_BODY_BGCOLOR};
   scrollbar-face-color: {T_TR_COLOR2};
   scrollbar-highlight-color: {T_TD_COLOR2};
   scrollbar-shadow-color: {T_TR_COLOR2};
   scrollbar-3dlight-color: {T_TR_COLOR3};
   scrollbar-arrow-color:  {T_BODY_LINK};
   scrollbar-track-color: {T_TR_COLOR1};
   scrollbar-darkshadow-color: {T_TH_COLOR1};
}
font,th,td,p { font-family: {T_FONTFACE1} }
#a:link,a:active,a:visited { color : {T_BODY_LINK}; }
#a:hover     { text-decoration: underline; color : {T_BODY_HLINK}; }
hr { height: 0px; border: solid {T_TR_COLOR3} 0px; border-top-width: 1px;}
.bodyline   { background-color: {T_TD_COLOR2}; border: 1px {T_TH_COLOR1} solid; }
.forumline  { background-color: {T_TD_COLOR2}; border: 2px {T_TH_COLOR2} solid; }
td.row1  { background-color: {T_TR_COLOR1}; }
td.row2  { background-color: {T_TR_COLOR2}; }
td.row3  { background-color: {T_TR_COLOR3}; }
td.rowpic {
      background-color: {T_TD_COLOR2};
      background-image: url(templates/subSilver/images/{T_TH_CLASS3});
      background-repeat: repeat-y;
}
th {
   color: {T_FONTCOLOR3}; font-size: {T_FONTSIZE2}px; font-weight : bold;
   background-color: {T_BODY_LINK}; height: 25px;
   background-image: url(templates/subSilver/images/{T_TH_CLASS2});
}
td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
         background-image: url(templates/subSilver/images/{T_TH_CLASS1});
         background-color:{T_TR_COLOR3}; border: {T_TH_COLOR3}; border-style: solid; height: 28px;
}
td.cat,td.catHead,td.catBottom {
   height: 29px;
   border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
   font-weight: bold; border: {T_TD_COLOR2}; border-style: solid; height: 28px;
}
td.row3Right,td.spaceRow {
   background-color: {T_TR_COLOR3}; border: {T_TH_COLOR3}; border-style: solid;
}
th.thHead,td.catHead { font-size: {T_FONTSIZE3}px; border-width: 1px 1px 0px 1px; }
th.thSides,td.catSides,td.spaceRow   { border-width: 0px 1px 0px 1px; }
th.thRight,td.catRight,td.row3Right  { border-width: 0px 1px 0px 0px; }
th.thLeft,td.catLeft   { border-width: 0px 0px 0px 1px; }
th.thBottom,td.catBottom  { border-width: 0px 1px 1px 1px; }
th.thTop  { border-width: 1px 0px 0px 0px; }
th.thCornerL { border-width: 1px 0px 0px 1px; }
th.thCornerR { border-width: 1px 1px 0px 0px; }
.maintitle  {
   font-weight: bold; font-size: 22px; font-family: \"{T_FONTFACE2}\",{T_FONTFACE1};
   text-decoration: none; line-height : 120%; color : {T_BODY_TEXT};
}
.gen { font-size : {T_FONTSIZE3}px; }
.genmed { font-size : {T_FONTSIZE2}px; }
.gensmall { font-size : {T_FONTSIZE1}px; }
.gen,.genmed,.gensmall { color : {T_BODY_TEXT}; }
a.gen,a.genmed,a.gensmall { color: {T_BODY_LINK}; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover  { color: {T_BODY_HLINK}; text-decoration: underline; }
.mainmenu      { font-size : {T_FONTSIZE2}px; color : {T_BODY_TEXT} }
a.mainmenu     { text-decoration: none; color : {T_BODY_LINK};  }
a.mainmenu:hover{ text-decoration: underline; color : {T_BODY_HLINK}; }
.cattitle      { font-weight: bold; font-size: {T_FONTSIZE3}px ; letter-spacing: 1px; color : {T_BODY_LINK}}
a.cattitle     { text-decoration: none; color : {T_BODY_LINK}; }
a.cattitle:hover{ text-decoration: underline; }
.forumlink     { font-weight: bold; font-size: {T_FONTSIZE3}px; color : {T_BODY_LINK}; }
a.forumlink    { text-decoration: none; color : {T_BODY_LINK}; }
a.forumlink:hover{ text-decoration: underline; color : {T_BODY_HLINK}; }
.nav        { font-weight: bold; font-size: {T_FONTSIZE2}px; color : {T_BODY_TEXT};}
a.nav       { text-decoration: none; color : {T_BODY_LINK}; }
a.nav:hover    { text-decoration: underline; }
.topictitle,h1,h2 { font-weight: bold; font-size: {T_FONTSIZE2}px; color : {T_BODY_TEXT}; }
a.topictitle:link   { text-decoration: none; color : {T_BODY_LINK}; }
a.topictitle:visited { text-decoration: none; color : {T_BODY_VLINK}; }
a.topictitle:hover   { text-decoration: underline; color : {T_BODY_HLINK}; }
.name       { font-size : {T_FONTSIZE2}px; color : {T_BODY_TEXT};}
.postdetails      { font-size : {T_FONTSIZE1}px; color : {T_BODY_TEXT}; }
.postbody { font-size : {T_FONTSIZE3}px; line-height: 18px}
a.postlink:link   { text-decoration: none; color : {T_BODY_LINK} }
a.postlink:visited { text-decoration: none; color : {T_BODY_VLINK}; }
a.postlink:hover { text-decoration: underline; color : {T_BODY_HLINK}}
.code {
   font-family: {T_FONTFACE3}; font-size: {T_FONTSIZE2}px; color: {T_FONTCOLOR2};
   background-color: {T_TD_COLOR1}; border: {T_TR_COLOR3}; border-style: solid;
   border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.quote {
   font-family: {T_FONTFACE1}; font-size: {T_FONTSIZE2}px; color: {T_FONTCOLOR1}; line-height: 125%;
   background-color: {T_TD_COLOR1}; border: {T_TR_COLOR3}; border-style: solid;
   border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}
.copyright     { font-size: {T_FONTSIZE1}px; font-family: {T_FONTFACE1}; color: {T_FONTCOLOR1}; letter-spacing: -1px;}
a.copyright    { color: {T_FONTCOLOR1}; text-decoration: none;}
a.copyright:hover { color: {T_BODY_TEXT}; text-decoration: underline;}
input,textarea, select {
   color : {T_BODY_TEXT};
   font: normal {T_FONTSIZE2}px {T_FONTFACE1};
   border-color : {T_BODY_TEXT};
}
input.post, textarea.post, select {
   background-color : {T_TD_COLOR2};
}
input { text-indent : 2px; }
input.button {
   background-color : {T_TR_COLOR1};
   color : {T_BODY_TEXT};
   font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1};
}
input.mainoption {
   background-color : {T_TD_COLOR1};
   font-weight : bold;
}
input.liteoption {
   background-color : {T_TD_COLOR1};
   font-weight : normal;
}

.helpline { background-color: {T_TR_COLOR2}; border-style: none; }

@import url(\"templates/subSilver/formIE.css\");
-->
</style>
<!-- BEGIN switch_enable_pm_popup -->
<script language=\"Javascript\" type=\"text/javascript\">
<!--
   if ( {PRIVATE_MESSAGE_NEW_FLAG} )
   {
      window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
   }
//-->
</script>
<!-- END switch_enable_pm_popup -->
</head>"
         ,substr($full_tpl,0,strpos($full_tpl,'<td width="*">{MAIN_CONTENT}')))).
'<td width="10"><img width="10" height="10" src="'.__LINK_ROOT.'images/bgim.gif"></td><td width="*"><a name="top"></a>
<table width="100%" cellspacing="0" cellpadding="10" border="0" align="center">
   <tr>
      <td class="bodyline"><table width="100%" cellspacing="0" cellpadding="0" border="0">
         <tr>
            <table cellspacing="0" cellpadding="2" border="0">
               <tr>
                  <td align="left" valign="top" nowrap="nowrap">
                     <a onclick="top.MyClose=false;" href="{U_PROFILE}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_profile.gif" width="12" height="13" border="0" alt="{L_PROFILE}" hspace="3" />{L_PROFILE}</a>
                     &nbsp;
                     <a onclick="top.MyClose=false;" href="{U_PRIVATEMSGS}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_message.gif" width="12" height="13" border="0" alt="{PRIVATE_MESSAGE_INFO}" hspace="3" />{PRIVATE_MESSAGE_INFO}</a>
                     &nbsp;
                     <a onclick="top.MyClose=false;" href="{U_LOGIN_LOGOUT}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_login.gif" width="12" height="13" border="0" alt="{L_LOGIN_LOGOUT}" hspace="3" />{L_LOGIN_LOGOUT}</a>
                     &nbsp;
                     <a onclick="top.MyClose=false;" href="{U_FAQ}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_faq.gif" width="12" height="13" border="0" alt="{L_FAQ}" hspace="3" />{L_FAQ}</a>
                     &nbsp;
                     <a onclick="top.MyClose=false;" href="{U_SEARCH}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_search.gif" width="12" height="13" border="0" alt="{L_SEARCH}" hspace="3" />{L_SEARCH}</a>
                     &nbsp;
                     <!-- BEGIN switch_user_logged_out -->
                     &nbsp;<a onclick="top.MyClose=false;" href="{U_REGISTER}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_register.gif" width="12" height="13" border="0" alt="{L_REGISTER}" hspace="3" />{L_REGISTER}</a>&nbsp;
                     <!-- END switch_user_logged_out -->
                  </td>
               </tr>
            </table></td>
         </tr>
      </table>';

$head_tpl   = str_replace('{FORUMACTIVE}',COUPONLINKACTIVE,$head_tpl);
$head_tpl   = str_replace('{HOTCACTIVE}',COUPONLINKINACTIVE,$head_tpl);
$head_tpl   = str_replace('{NEWCACTIVE}',COUPONLINKINACTIVE,$head_tpl);
$head_tpl   = str_replace('{EXPCACTIVE}',COUPONLINKINACTIVE,$head_tpl);
$head_tpl   = str_replace('{FRSCACTIVE}',COUPONLINKINACTIVE,$head_tpl);
$head_tpl   = str_replace('{BASE_HOSTNAME}',BASE_HOSTNAME,$head_tpl);

         $footer_tpl   = substr($full_tpl,strpos($full_tpl,'{MAIN_CONTENT}')+14);

         if ( !($f = @fopen(__INCLUDE_ROOT."templates/cache/forum_head.tpl","w")) ){
            return ("MAIN CACHE: can't open file 'templates/cache/forum_head.tpl'<br>");
         }
         @fputs($f,$head_tpl);
         @fclose($f);

         if ( !($f = @fopen(__INCLUDE_ROOT."templates/cache/forum_footer.tpl","w")) ){
            return ("MAIN CACHE: can't open file 'templates/cache/forum_footer.tpl'<br>");
         }
         @fputs($f,$footer_tpl);
         @fclose($f);

         return $result;
      }
   }
}
?>
