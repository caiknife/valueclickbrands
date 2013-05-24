<?php

if ( !defined("CLASS_CATEGORY_PHP") ){
   define("CLASS_CATEGORY_PHP","YES");

    require_once(__INCLUDE_ROOT."/lib/classes/class.VItem.php");
    require_once(__INCLUDE_ROOT."/lib/classes/class.Merchant.php");
    require_once(__INCLUDE_ROOT."/lib/classes/class.Coupon.php");
    require_once(__INCLUDE_ROOT."/lib/classes/class.Meta.php");

   class Category extends VItem {
      var $ClassName = "Category";
      var $Key       = "Category_";
      var $FeaturedMerchant   = array();

      function Category($id =-1){
         VItem::VItem();
         $this->SQL[QSELECT]  = "SELECT * FROM Category WHERE Category_=::Category_::";
         $this->SQL[QUPDATE]  = "UPDATE Category SET Name='::Name::', NameURL='::NameURL::', Descript='::Descript::', MetaTitle='::MetaTitle::', MetaDescription='::MetaDescription::', MetaKeywords='::MetaKeywords::', MetaFrame='::MetaFrame::', isActive=::isActive::, LastGenerate=(::LastGenerate::), LastDate=NULL WHERE Category_=::Category_::";
         $this->SQL[QINSERT]  = "INSERT INTO Category (Category_,Name,NameURL,Descript,MetaTitle,MetaDescription,MetaKeywords,MetaFrame,isActive,LastGenerate) VALUES(::Category_::,'::Name::','::NameURL::','::Descript::','::MetaTitle::','::MetaDescription::','::MetaKeywords::','::MetaFrame::',::isActive::,'00000000000000')";
         $this->SQL[QDELETE]  = "DELETE FROM Category WHERE Category_=::Category_::";
         if ( $id > 0 ){
            $this->select(array("Category_" => $id));
            $this->load();
            $this->countCoupon();
            $this->countMerchant();
         }
      }

      function loadFeatured(){
         if ( $this->get('Category_') > 0 ){
            $this->FeaturedMerchant = new MerchantList("SELECT m.* FROM MerCat c, Merchant m WHERE m.Merchant_=c.Merchant_ AND c.isFeatured>0 AND c.Category_=".$this->get("Category_")." ORDER BY c.isFeatured",FAST_LOAD);
         }
      }

      function saveFeatured(){
         if ( is_object($this->FeaturedMerchant) ){
         }
         else if ( is_array($this->FeaturedMerchant) ){
            $this->run_spec("UPDATE MerCat SET isFeatured=0 WHERE Category_=".$this->get("Category_"));
            foreach ( $this->FeaturedMerchant as $key => $oMerchant ){
               $this->run_spec("REPLACE INTO MerCat (Merchant_,Category_,isFeatured) VALUES(".$oMerchant->get("Merchant_").",".$this->get("Category_").",".$key.")");
            }
         }
      }

      function search_($name){
         $tmpSelect = $this->SQL[QSELECT];
         $this->SQL[QSELECT] = "SELECT * FROM Category WHERE Name REGEXP '::Name::' ORDER BY Name";
            $this->select(array("Name" => $name));
            $this->load();
         $this->SQL[QSELECT] = $tmpSelect;
      }

      function isFound(){
         if ( $this->get("Category_") > 0 ){
            $this->countCoupon(1);
            if ( $this->get("Coupons") > 0 ) return true;
         }
      }

      function delete(){
         $this->run_spec("DELETE FROM CoupCat WHERE Category_=".$this->get("Category_"));
         $this->run_spec("DELETE FROM MerCat WHERE Category_=".$this->get("Category_"));
         $this->run_spec("DELETE FROM SCategory WHERE Category_=".$this->get("Category_"));
         $this->rec2param();
         Query::delete();
      }

      function countCoupon($flag =0){
         if ( $flag == 0 ){
            $tmp1 = $this->run_spec("SELECT COUNT(*) coup FROM CoupCat WHERE Category_=".$this->get("Category_"));
            $tmp2 = $this->run_spec("SELECT COUNT(*) coup FROM MerCat, Coupon WHERE Category_=".$this->get("Category_")." AND Coupon.Merchant_=MerCat.Merchant_");
         }
         else{
            $tmp1 = $this->run_spec("SELECT COUNT(*) coup FROM CoupCat c, Coupon p WHERE c.Category_=".$this->get("Category_")." AND c.Coupon_=p.Coupon_ AND (p.ExpireDate='0000-00-00' OR p.ExpireDate>CURDATE()) AND p.StartDate<=CURDATE() AND p.isActive=1");
            $tmp2 = $this->run_spec("SELECT COUNT(*) coup FROM MerCat c, Coupon p WHERE c.Merchant_=p.Merchant_ AND c.Category_=".$this->get("Category_")." AND (p.ExpireDate='0000-00-00' OR p.ExpireDate>CURDATE()) AND p.StartDate<=CURDATE() AND p.isActive=1");
         }
         $this->set("Coupons", $tmp1["coup"] + $tmp2["coup"]);
      }


      function countMerchant($flag =0){
         if ( $flag == 0 ){
            $tmp = $this->run_spec("SELECT COUNT(*) merch FROM MerCat WHERE Category_=".$this->get("Category_"));
         }
         else{
            $tmp = $this->run_spec("SELECT COUNT(*) merch FROM MerCat m, Merchant t WHERE m.Category_=".$this->get("Category_")." AND m.Merchant_=t.Merchant_ AND t.isActive=1");
         }
         $this->set("Merchants", $tmp["merch"]);
      }

      function insert($flag =1){
         if ( $flag == 1 ){
            Query::insert(array("Category_" => $this->nextid()));
         }
         else{
            Query::insert();
         }
      }

      function createImage($img_path, $font_path, $type){
         $image_name = $img_path."category_".$type.".png";
         $im = @ImageCreateFromPNG($image_name);
         if ( $im ){
            if ( $type == "off" ){
               $col = @ImageColorAllocate($im, 102, 102, 0);
            }
            else{
               $col = @ImageColorAllocate($im, 46, 46, 0);
            }
            @ImageTTFText ($im, 11, 0, 10, 12, $col, $font_path."arial.ttf",$this->get("Name"));
         }
         @ImagePNG($im,$img_path."category_".$this->ID.$type.".png");
      }

      function createHeader($img_path, $font_path){
         $image_name = $img_path."header_category.png";
         $im = @ImageCreateFromPNG($image_name);
         $col = @ImageColorAllocate($im, 50, 50, 50);
         @ImageTTFText ($im, 18, 0, 0, 15, $col, $font_path."arial.ttf",$this->get("Name"));
         @ImagePNG($im,$img_path."header_category".$this->ID.".png");
         $image_name = $img_path."all_header_category.png";
         $im = @ImageCreateFromPNG($image_name);
         $col = @ImageColorAllocate($im, 50, 50, 50);
         @ImageTTFText ($im, 18, 0, 0, 15, $col, $font_path."arial.ttf",$this->get("Name"));
         @ImagePNG($im,$img_path."all_header_category".$this->ID.".png");
      }

      function MustUpdate(){
         return 0;
      }

      function Cache($force =false){
         $result = "";
         $must_removed = 0;

         if ( $this->MustUpdate() == 1 || $force == true){

            $this->countCoupon(1);

            if ( !($this->get("isActive") == 1 && $this->get("Coupons") > 0) ){
               $must_removed = 1;
            }

            //$this->createHeader(__INCLUDE_ROOT."images/categories/",__INCLUDE_ROOT."fonts/");

            $oMerchantList = new MerchantList();

            $searchType = array("all" => "", "merchant" => "_m", "product" => "_p");
            while ( list($ksearch,$vsearch) = @each($searchType) ){
               $orderMode = array("","_MerchantName","_Amount","_ExpireDate");
               while ( list($key,$order) = @each($orderMode) ){
                  if ( $must_removed == 1 ){
                     //@unlink(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("Name").$order.$vsearch.".html"));
                     //@unlink(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("Name").$order."_all".$vsearch.".html"));
                     continue;
                  }

                  if ( !($f = @fopen(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL").$order.$vsearch.".html"),"w")) ){
                     return ($this->get("Name")." - can't open file '".$this->get("Name").$order.$vsearch.".html'");
                  }

                  $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
                  $tpl->define(array(
                     'main'      => get_browser_template("category_frame.tpl"),
                  ));
                  $tpl->assign(array(
                     'BASE_HOSTNAME'     => BASE_HOSTNAME,
                     LINK_ROOT            => __LINK_ROOT,
                     INDEX                => "",
                     PAGE_TITLE           => $this->getMeta('Title'),
                     KEYWORDS             => $this->getMeta('Keywords'),
                     DESCRIPTION_H        => $this->getMeta('Description'),
                     FRAME_H              => $this->getMeta('Frame'),
                     //PAGE_TITLE           => $this->get("Name")." - Discount Coupon Codes for ".$this->get("Name"),
                     //KEYWORDS             => "<meta NAME=\"keywords\" CONTENT=\"".$oMerchantList->getString("Merchant_","Name").">",
                     //DESCRIPTION_H        => "<meta NAME=\"description\" CONTENT=\"Before you shop online, see if we have coupons that can save you money.\">",
                     CURRENT_URL          => __LINK_ROOT.str_replace(" ","_",$this->get("NameURL").$order.$vsearch."f.html"),
                     CATEGORY_CUR         => $this->get("Category_"),
                     MERCHANT_CUR         => -1,
                     SEARCH_METHOD        => $vsearch,
                     INCLUDE1             => '',
                     INCLUDE2             => '',
                  ));
                  $tpl->parse("MAIN","main");
                  fwrite($f,$tpl->fetch("MAIN"));
                  fclose($f);


                  if ( !($f = @fopen(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL").$order.$vsearch."f.html"),"w")) ){
                     return ($this->get("Name")." - can't open file '".$this->get("NameURL").$order.$vsearch."f.html'");
                  }
                  $oFC = new FeaturedCouponList($this->ID);
                  if ( $oFC->getItemCount() > 0 ){
                     $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
                     $tpl->define(array(
                        'main'      => get_browser_template(str_replace(" ","_","cache/".$this->get("NameURL").$vsearch.".tpl")),
                        'content'   => get_browser_template("category.tpl")
                     ));

                     $oSourceList = new SourceList("SELECT DISTINCT s.* FROM Source s, SourceGroup g WHERE s.SourceGroup_=g.SourceGroup_ AND g.isPopup=1");
                     $tpl->assign(array(
                        SOURCE_LIST_POPUP => $oSourceList->getJavaArray(),
                        IS_FRAME_LINK  => 1,
                        'BASE_HOSTNAME'     => BASE_HOSTNAME,
                        LINK_ROOT      => __LINK_ROOT,
                        INDEX          => "",
                        PAGE_TITLE           => $this->getMeta('Title'),
                        KEYWORDS             => $this->getMeta('Keywords'),
                        DESCRIPTION_H        => $this->getMeta('Description'),
                        FRAME_H              => $this->getMeta('Frame'),
                        //PAGE_TITLE     => $this->get("Name")." - Discount Coupon Codes for ".$this->get("Name"),
                        //KEYWORDS       => "<meta NAME=\"keywords\" CONTENT=\"".$oMerchantList->getString("Merchant_","Name").">",
                        //DESCRIPTION_H  => "<meta NAME=\"description\" CONTENT=\"Before you shop online, see if we have coupons that can save you money.\">",
                        NAVIGATION_PATH=> getNavigation(array($this->get("Name") => "")),
                        SCRIPT_NAME    => __LINK_ROOT.(str_replace(' ','_',$this->get("NameURL"))),
                        ORDER          => $order,
                        CATEGORY_SELECT=> $this->get("Category_"),
                        CATEGORY_SELECTE=> "_".$this->get("Category_"),
                        //PROMO_HEAD     => "How To Use Online Coupons",
                        //PROMO_TEXT     => "1.&nbsp;Click &quot;Use this Coupon&quot;.<br>2.&nbsp;Shop as usual at relevant site.<br>3.&nbsp;Write down Coupon Code at the top of the page.<br>4.&nbsp;Enter Coupon Code during check-out process.<br>",
                        PROMO_HEAD     => __DEFAULT_PROMO_HEAD,
                        PROMO_TEXT     => __DEFAULT_PROMO_TEXT,
                        PROMO_FOOTER_1 => "See our",
                        PROMO_FOOTER_2 => "FAQ",
                        PROMO_FOOTER_3 => "for more information",
                        PROMO_URL      => __LINK_ROOT."FAQ.html",
                        CATEGORY_CUR   => $this->get("Category_"),
                        MERCHANT_CUR   => -1,
                        COUPON_CUR     => -1,
                        SEARCH_METHOD     => $vsearch,
                        INCLUDE1             => '',
                        INCLUDE2             => '',
                     ));

                     if ( $order != "" ){
                        if ($order == "_Amount"){
                           $oFC->setDirection(DESC);
                           $oFC->setOrderA(substr($order,1));
                        }
                        if ($order == "_ExpireDate"){
                           $oFC->setOrderD(substr($order,1));
                        }
                        else{
                           $oFC->setOrder(substr($order,1));
                        }
                     }
                     else{
                        $oFC->setOrder("MerchantName");
                     }
                     $tpl->define_dynamic("coupon_list","content");
                     $merchant_name = "";
                     while ( $oCoupon = $oFC->nextItem() ){
                        $tpl->assign(array(
                           LINE_TYPE   => $oCoupon->get("MerchantName") == $merchant_name ? "dot" : "line",
                           MERCHANT    => $oCoupon->get("MerchantName") == $merchant_name ? "" : $oCoupon->get("MerchantName"),
                           RAWMERCHANT => (str_replace(' ','_',$oCoupon->get("MerchantName"))),
                           AMOUNT      => $oCoupon->get("Amount"),
                           DESCRIPT    => $oCoupon->get("Descript"),
                           RESTR       => strlen($oCoupon->get("LongRestr")) > 0 ? addslashes($oCoupon->get("LongRestr")) : addslashes($oCoupon->get("Restr")),
                           SEE_RESTRICTION => strlen($oCoupon->get("LongRestr")) > 0 || strlen($oCoupon->get("Restr")) > 0 ? "(See&nbsp;Restrictions)" : "",
                           EXPIRE      => $oCoupon->getDateText("ExpireDate"),
                           IS_NEW      => (($oCoupon->get("ExpireDate") == getDateTime("Y-m-d")) ? ("&nbsp;&nbsp;<img alt=\"This ".$oCoupon->get("MerchantName")." Offer Expiring Soon\" src=\"".__LINK_ROOT."images/icon_hurry.gif\" height=\"10\" width=\"35\">") : ( ( $oCoupon->isNew(__NEW_COUPON_PERIOD) == 1) ? "&nbsp;&nbsp;<img alt=\"New ".$oCoupon->get("MerchantName")." Offer\" src=\"".__LINK_ROOT."images/icon_new.gif\" height=\"9\" width=\"25\">" : "")),
                           COUPON_ID   => $oCoupon->get("Coupon_")
                        ));

                        $tpl->parse("COUPONLIST",".coupon_list");
                        $merchant_name = $oCoupon->get("MerchantName");
                     }

                     $oPage = new Page();
                     $oPage->find("MERCHANT_PROMO_INCLUDE");
                     $tpl->assign(array(
                        MERCHANT_PROMO_INCLUDE => ( strlen(trim($oPage->get("Content"))) > 0 ) ? $oPage->get("Content") : '&nbsp;',
                     ));

                     $tpl->parse("MAIN_CONTENT","content");
                     $tpl->assign(array(
                        MAIN_CONTENT   => $tpl->fetch("MAIN_CONTENT")
                     ));

                     $tpl->parse("MAIN","main");

                     fwrite($f,$tpl->fetch());

                     $result .= "CATEGORY: ".$this->get("Name")." (order: ".$order.") - update<br>";
                  }
                  else{
                     fwrite($f,"<html>\n<head>\n<script language=\"JavaScript\" src=\"".__LINK_ROOT."jscript/js.js\"></script>\n</head>\n<body>\n<script language=\"javascript\">\nmake_stat(".$this->get("Category_").",-1,-1,1);\n</script>\n<script language=\"JavaScript\">\n<!--\ntop.MyClose=false;\ntop.location.href = '".(addslashes(str_replace(" ","_",$this->get("NameURL").$order."_all".$vsearch.".html")))."';\n//-->\n</script>\n</body>\n</html>\n");
                  }
                  fclose($f);

                  /* ALL coupons */

                  if ( !($f = @fopen(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL").$order."_all".$vsearch.".html"),"w")) ){
                     return ($this->get("Name")." - can't open file '".$this->get("Name").$order.$vsearch.".html'");
                  }

                  $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
                  $tpl->define(array(
                     'main'      => get_browser_template("category_frame.tpl"),
                  ));
                  $tpl->assign(array(
                     'BASE_HOSTNAME'     => BASE_HOSTNAME,
                     LINK_ROOT            => __LINK_ROOT,
                     INDEX                => "",
                     //PAGE_TITLE           => $this->get("Name")." - Discount Coupon Codes for ".$this->get("Name"),
                     //KEYWORDS             => "<meta NAME=\"keywords\" CONTENT=\"".$oMerchantList->getString("Merchant_","Name").">",
                     //DESCRIPTION_H        => "<meta NAME=\"description\" CONTENT=\"Before you shop online, see if we have coupons that can save you money.\">",
                     PAGE_TITLE           => $this->getMeta('Title'),
                     KEYWORDS             => $this->getMeta('Keywords'),
                     DESCRIPTION_H        => $this->getMeta('Description'),
                     FRAME_H              => $this->getMeta('Frame'),
                     CURRENT_URL          => __LINK_ROOT.str_replace(" ","_",$this->get("NameURL").$order."_all".$vsearch."f.html"),
                     CATEGORY_CUR         => $this->get("Category_"),
                     MERCHANT_CUR         => -1,
                     SEARCH_METHOD        => $vsearch,
                     INCLUDE1             => '',
                     INCLUDE2             => '',
                  ));
                  $tpl->parse("MAIN","main");
                  fwrite($f,$tpl->fetch("MAIN"));
                  fclose($f);

                  if ( !($f = @fopen(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL").$order."_all".$vsearch."f.html"),"w")) ){
                     return ($this->get("Name")." - can't open file '".$this->get("Name").$order."_all".$vsearch."f.html'");
                  }

                  $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
                  $tpl->define(array(
                     'main'      => get_browser_template(str_replace(" ","_","cache/".$this->get("NameURL").$vsearch.".tpl")),
                     'content'   => get_browser_template("category_all.tpl")
                  ));

                  $oSourceList = new SourceList("SELECT DISTINCT s.* FROM Source s, SourceGroup g WHERE s.SourceGroup_=g.SourceGroup_ AND g.isPopup=1");
                  $tpl->assign(array(
                     SOURCE_LIST_POPUP => $oSourceList->getJavaArray(),
                     IS_FRAME_LINK  => 1,
                     'BASE_HOSTNAME'     => BASE_HOSTNAME,
                     LINK_ROOT      => __LINK_ROOT,
                     INDEX          => "",
                     //PAGE_TITLE     => $this->get("Name")." - Discount Coupon Codes for ".$this->get("Name"),
                     //KEYWORDS       => "<meta NAME=\"keywords\" CONTENT=\"".$oMerchantList->getString("Merchant_","Name").">",
                     //DESCRIPTION_H  => "<meta NAME=\"description\" CONTENT=\"Before you shop online, see if we have coupons that can save you money.\">",
                     PAGE_TITLE           => $this->getMeta('Title'),
                     KEYWORDS             => $this->getMeta('Keywords'),
                     DESCRIPTION_H        => $this->getMeta('Description'),
                     FRAME_H              => $this->getMeta('Frame'),
                     NAVIGATION_PATH=> getNavigation(array($this->get("Name") => "")),
                     SCRIPT_NAME    => __LINK_ROOT.(str_replace(' ','_',$this->get("NameURL"))),
                     ORDER          => $order,
                     CATEGORY_SELECT=> $this->get("Category_"),
                     CATEGORY_SELECTE=> "_".$this->get("Category_"),
                     //PROMO_HEAD     => "How To Use Online Coupons",
                     //PROMO_TEXT     => "1.&nbsp;Click &quot;Use this Coupon&quot;.<br>2.&nbsp;Shop as usual at relevant site.<br>3.&nbsp;Write down Coupon Code at the top of the page.<br>4.&nbsp;Enter Coupon Code during check-out process.<br>",
                     PROMO_HEAD     => __DEFAULT_PROMO_HEAD,
                     PROMO_TEXT     => __DEFAULT_PROMO_TEXT,
                     PROMO_FOOTER_1 => "See our",
                     PROMO_FOOTER_2 => "FAQ",
                     PROMO_FOOTER_3 => "for more information",
                     PROMO_URL      => __LINK_ROOT."FAQ.html",
                     CATEGORY_CUR   => $this->get("Category_"),
                     MERCHANT_CUR   => -1,
                     COUPON_CUR     => -1,
                     SEARCH_METHOD     => $vsearch,
                     INCLUDE1             => '',
                     INCLUDE2             => '',
                  ));

                  $oFC = new CategoryCouponListFeaturedMerchant($this->ID);
                  if ( $oFC->getItemCount() > 0 ){
                     $tpl->define_dynamic("featured_merchant","content");
                     $tpl->define_dynamic("featured_coupon_list","content");
                     $tpl->define_dynamic("featured_coupon_more","content");
                     $merchant_name = '';
                     while ( $oCoupon = $oFC->nextItem() ){
                        $oMerchantTmp  = new Merchant($oCoupon->get("Merchant_"));
                        if ( $merchant_name == $oMerchantTmp->get('Name') ) continue;
                        $merchant_name = $oMerchantTmp->get('Name');
                        $merc = $oMerchantTmp->get('Name');
                        if ( $oMerchantTmp->presentImage("OldLogo") ){
                           $size = set_image_size(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.($oMerchantTmp->get("Merchant_"))."/OldLogo.gif",100,33);
                           if ( is_array($size)){
                              if ( $size['width'] > 0 && $size['height'] > 0 ){
                                 $merc = "<img border=\"0\" width=\"".$size['width']."\" height=\"".$size['height']."\" alt=\"".($oMerchantTmp->get("Name")." Coupons - Discount Coupon Codes for ".$oMerchantTmp->get("Name"))."\" src=\"".__LINK_ROOT."images/merchants/".($oMerchantTmp->get("Merchant_"))."/OldLogo.gif\">";
                              }
                           }
                        }
                        else if ( $oMerchantTmp->presentImage("Logo") ){
                           $size = set_image_size(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.($oMerchantTmp->get("Merchant_"))."/Logo.gif",100,33);
                           if ( is_array($size)){
                              if ( $size['width'] > 0 && $size['height'] > 0 ){
                                 $merc = "<img border=\"0\" width=\"".$size['width']."\" height=\"".$size['height']."\" alt=\"".($oMerchantTmp->get("Name")." Coupons - Discount Coupon Codes for ".$oMerchantTmp->get("Name"))."\" src=\"".__LINK_ROOT."images/merchants/".($oMerchantTmp->get("Merchant_"))."/Logo.gif\">";
                              }
                           }
                        }

                        $tpl->assign(array(
                           LINE_TYPE   => $oCoupon->get("MerchantName") == $merchant_name ? "dot" : "line",
                           MERCHANT    => $merc,
                           RAWMERCHANT => (str_replace(' ','_',$oCoupon->get("MerchantName"))),
                           AMOUNT      => $oCoupon->get("Amount"),
                           DESCRIPT    => $oCoupon->get("Descript"),
                           RESTR       => strlen($oCoupon->get("LongRestr")) > 0 ? addslashes($oCoupon->get("LongRestr")) : addslashes($oCoupon->get("Restr")),
                           SEE_RESTRICTION => strlen($oCoupon->get("LongRestr")) > 0 || strlen($oCoupon->get("Restr")) > 0 ? "(See&nbsp;Restrictions)" : "",
                           EXPIRE      => $oCoupon->getDateText("ExpireDate"),
                           IS_NEW      => (($oCoupon->get("ExpireDate") == getDateTime("Y-m-d")) ? ("&nbsp;&nbsp;<img alt=\"This ".$oCoupon->get("MerchantName")." Offer Expiring Soon\" src=\"".__LINK_ROOT."images/icon_hurry.gif\" height=\"10\" width=\"35\">") : ( ( $oCoupon->isNew(__NEW_COUPON_PERIOD) == 1) ? "&nbsp;&nbsp;<img alt=\"New ".$oCoupon->get("MerchantName")." Offer\" src=\"".__LINK_ROOT."images/icon_new.gif\" height=\"9\" width=\"25\">" : "")),
                           COUPON_ID   => $oCoupon->get("Coupon_")
                        ));

                        $tpl->assign(array(
                           MERCHANT_TEXT    => $oCoupon->get("MerchantName"),
                           RAWMERCHANT => (str_replace(' ','_',$oCoupon->get("MerchantName"))),
                        ));
                        $tpl->parse("FEATUREDCOUPONMORE","featured_coupon_more");
                        $tpl->parse("FEATUREDCOUPONLIST",".featured_coupon_list");
                     }
                     $tpl->parse("FEATUREDMERCHANT","featured_merchant");
                  }

                  $oFC = new CategoryCouponListNotFeaturedMerchant($this->ID);
                  //$oFC = new CategoryCouponList($this->ID);
                  if ( $order != "" ){
                     if ($order == "_Amount"){
                        $oFC->setDirection(DESC);
                        $oFC->setOrderA(substr($order,1));
                     }
                     if ($order == "_ExpireDate"){
                        $oFC->setOrderD(substr($order,1));
                     }
                     else{
                        $oFC->setOrder(substr($order,1));
                     }
                  }
                  else{
                     $oFC->setOrder("MerchantName");
                  }
                  $tpl->define_dynamic("coupon_list","content");
                  $merchant_name = "";
                  while ( $oCoupon = $oFC->nextItem() ){
                     $tpl->assign(array(
                        LINE_TYPE   => $oCoupon->get("MerchantName") == $merchant_name ? "dot" : "line",
                        MERCHANT    => $oCoupon->get("MerchantName") == $merchant_name ? "" : $oCoupon->get("MerchantName"),
                        RAWMERCHANT => (str_replace(' ','_',$oCoupon->get("MerchantName"))),
                        AMOUNT      => $oCoupon->get("Amount"),
                        DESCRIPT    => $oCoupon->get("Descript"),
                        RESTR       => strlen($oCoupon->get("LongRestr")) > 0 ? addslashes($oCoupon->get("LongRestr")) : addslashes($oCoupon->get("Restr")),
                        SEE_RESTRICTION => strlen($oCoupon->get("LongRestr")) > 0 || strlen($oCoupon->get("Restr")) > 0 ? "(See&nbsp;Restrictions)" : "",
                        EXPIRE      => $oCoupon->getDateText("ExpireDate"),
                        IS_NEW      => (($oCoupon->get("ExpireDate") == getDateTime("Y-m-d")) ? ("&nbsp;&nbsp;<img alt=\"This ".$oCoupon->get("MerchantName")." Offer Expiring Soon\" src=\"".__LINK_ROOT."images/icon_hurry.gif\" height=\"10\" width=\"35\">") : ( ( $oCoupon->isNew(__NEW_COUPON_PERIOD) == 1) ? "&nbsp;&nbsp;<img alt=\"New ".$oCoupon->get("MerchantName")." Offer\" src=\"".__LINK_ROOT."images/icon_new.gif\" height=\"9\" width=\"25\">" : "")),
                        COUPON_ID   => $oCoupon->get("Coupon_")
                     ));
                     $tpl->parse("COUPONLIST",".coupon_list");
                     $merchant_name = $oCoupon->get("MerchantName");
                  }

                  $oPage = new Page();
                  $oPage->find("MERCHANT_PROMO_INCLUDE");
                  $tpl->assign(array(
                     MERCHANT_PROMO_INCLUDE => ( strlen(trim($oPage->get("Content"))) > 0 ) ? $oPage->get("Content") : '&nbsp;',
                  ));

                  $tpl->parse("MAIN_CONTENT","content");
                  $tpl->assign(array(
                     MAIN_CONTENT   => $tpl->fetch("MAIN_CONTENT")
                  ));

                  $tpl->parse("MAIN","main");

                  fwrite($f,$tpl->fetch());
                  fclose($f);

                  $result .= "CATEGORY: ".$this->get("Name")."(order: ".$order.") all - updated<br>";
               }
            }
            if ( $must_removed == 1 ){
               $result .= "CATEGORY: ".$this->get("Name")." - removed<br>";
            }
         }
         return $result;
      }

      function generate_tpl($oCategoryList ="", $oMerchatList ="", $oSystem =""){

         if ( !is_object($oCategoryList) ) $oCategoryList = new CategoryList("",FAST_LOAD);
         if ( !is_object($oMerchatList) )  $oMerchatList  = new DropDownMerchantList();
         if ( !is_object($oSystem) )       $oSystem = new System("__POPDOWN");
         $oPage = new Page();

         $searchType = array("all" => "", "merchant" => "_m", "product" => "_p");
         while ( list($ksearch,$vsearch) = @each($searchType) ){
            $tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
            $tpl->VerifyUnmatched = "no";
            $tpl->define(array(
                'main'      => get_browser_template("main.tpl"),
            ));

            $tpl->assign(array(
               'BASE_HOSTNAME'     => BASE_HOSTNAME,
               LINK_ROOT      => __LINK_ROOT,
               INDEX          => "",
               INCLUDE_ROOT   => __INCLUDE_ROOT,
               CATEGORY_LIST  => $oCategoryList->getJavaArray(),
               MERCHANT_LIST  => $oMerchatList->getSelect("Merchant_","CName"),
               SEARCH_METHOD     => $vsearch,
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
                     CATEGORY_ID    => $oCat->get("Category_"),
                     CATEGORY_STATE => $this->get("Category_") == $oCat->get("Category_") ? "on" : "off",
                     CATEGORY_NAME  => ($oCat->get("Name")),
                     CATEGORY_BACKGROUND  => $this->get("Category_") == $oCat->get("Category_") ? "category_background_active" : "category_background",
                     CATEGORY_IMAGE => $this->get("Category_") == $oCat->get("Category_") ? "category_arrow" : "category_background",
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
                  SRV_NAME       => __SERVER_NAME,
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

            if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."templates/cache/".$this->get("NameURL").$vsearch.".tpl"),"w")) ){
               return ("MAIN CACHE: can't open file 'templates/cache/".$this->get("NameURL").$vsearch.".tpl'<br>");
            }
            fwrite($f,$tpl->fetch());
            fclose($f);
         }
         return "MAIN CACHE: category ".$this->get("NameURL").".tpl - updated<br>";
      }

   }

   class CategoryList extends VItemList {
      var $ClassName = "CategoryList";

      function CategoryList($category_list ="", $type_load =FAST_LOAD){
         if ( is_array($category_list) ){
            VItemList::VItemList("Category",$category_list);
         }
         else{
            if ( $category_list == "" ){
               VItemList::VItemList("Category","SELECT * FROM Category",$type_load);
            }
            else{
               VItemList::VItemList("Category",$category_list,FAST_LOAD);
            }
         }
      }

      function need_update(&$categories){
         $ret_arr = $this->run_spec("SELECT COUNT(*) cnt FROM Category WHERE LastDate>LastGenerate");
         $updatecategory_cnt = $ret_arr["cnt"];
         $categories = array();
         if ( $updatecategory_cnt > 0 ){
            $oCategoryList = new CategoryList("SELECT * FROM Category ".
                                      "WHERE LastDate>LastGenerate");
            while ( $oCat = $oCategoryList->nextItem() ){
               $categories[$oCat->get("Category_")] = $oCat->get("Category_");
            }
         }
      }

      function LastDate(){
         return time();
      }

      function LastGenerate($dt){
         $this->run_spec("UPDATE Category SET LastDate=(LastDate),LastGenerate='".$dt."'");
      }

   }

function set_image_size($imagepath, $maxwidth,$maxheight ) {
   $ftype_array = array('.gif', '.jpg', 'jpeg','.png', '.swf','.psd','.bmp');
   $imagewh     = array();
   if ( !in_array(substr($imagepath,-4),$ftype_array) ) return 0 ;
   $imageprop = 0;
   $imageprop2 = 0;
   $imagewh = @GetImageSize($imagepath);
   $imagewidth = $imagewh[0];
   $imageheight = $imagewh[1];
   $imgorig = $imagewidth;
   if ( $imagewidth > $maxwidth ) {
     $imageprop = $imagewidth/$maxwidth ;
   }
   if ( $imageheight > $maxheight ) {
       $imageprop2 = $imageheight/$maxheight;
   }
   if ( $imageprop2 > $imageprop ) $imageprop = $imageprop2;
   if ( $imageprop ) {
      $imageheight = round( $imageheight/$imageprop);
      $imagewidth = round( $imagewidth/$imageprop);
   }
   $imagewh['width']  = $imagewidth;
   $imagewh['height'] = $imageheight;
   return $imagewh;
}
}
?>
