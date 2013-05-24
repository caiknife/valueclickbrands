<?php
if (!defined("FUNCTION_URL_PHP")){
   define("FUNCTION_URL_PHP","YES");
   function getNavigation($paths, $type = 0){
      if ( sizeof($paths) == 1 ){
         list($page,$url) = @each($paths);
         if ( strtoupper($page) == "INDEX" ){
            return "<font class=\"navigationLinkActive\">Ê×Ò³</font>";
         }
         reset($paths);
      }
      $navPath = "<font class=\"navigationSpecial\"><a class=\"navigationSpecial\" onclick=\"top.MyClose=false;\"  href=\"/\">Ê×Ò³</a>";
      $link_cnt = 0;
      
      if ($type == 0) {
      	while (list($page,$url) = @each($paths)){
         $link_cnt++;
         if ( $link_cnt < sizeof($paths) ){
            $navPath .= "&nbsp;>&nbsp;<a class=\"navigationSpecial\" onclick=\"top.MyClose=false;\" href=\"$url\">$page</a>";
         }
         else{
		 	if($url != "") {
				$navPath .= "&nbsp;>&nbsp;<a class=\"navigationSpecial\" href=\"$url\">$page</a>";
			} else {
            	$navPath .= "&nbsp;>&nbsp;<font class=\"navigationSpecial\">$page</font>";
         	}
		 }
        }
      }else {
      	while (list($page,$url) = @each($paths)){
      		$link_cnt++;
      		$navPath .= "&nbsp;>&nbsp;<a class=\"navigationSpecial\" onclick=\"top.MyClose=false;\" href=\"$url\">$page</a>";
      	}
      }
      $navPath .= "</font>";
      return $navPath;
   }

   function dynamicURL($oCoupon, $oMerchant, $source, $do_tracking ="NO"){
         require_once(__INCLUDE_ROOT."lib/classes/class.TrackingURL.php");
         require_once(__INCLUDE_ROOT."lib/classes/class.Source.php");

      if ( $oCoupon->ClassName == "Coupon" ){
         $trackURL = $oCoupon->getURL();
      }
      else{
         $trackURL = $oMerchant->get("URL");
      }

      if ( __DO_TRACKING == "YES" || $do_tracking == "YES"){
         $oTrackingURL = new TrackingURL();
         $oTrackingURL->find($trackURL);
		
         if ( $oTrackingURL->get("TrackingURL_") > 0 ){
            $oSource = new Source($source);
            $URLformat = $oTrackingURL->get("Format");
            if ( preg_match("/([^\ ]*\~\|SOURCE\|\~[^\ ]*)/",$URLformat,$Sourceformat) && strlen($oSource->get("SourceID")) > 0 ){
               $sourceSearch = str_replace("~|SOURCE|~","[^\ \&]*",$Sourceformat[1]);
               $sourceReplace= str_replace("~|SOURCE|~",$oSource->get("SourceID"),$Sourceformat[1]);
               $trackURL = preg_replace("/".$sourceSearch."/",$sourceReplace,$trackURL);
            }
            if ( preg_match("/([^\ ]*\~\|UNIQID\|\~[^\ ]*)/",$URLformat,$Sourceformat) && strlen($oSource->get("UniqueID")) > 0 ){
               $sourceSearch = str_replace("~|UNIQID|~","[^\ \&]*",$Sourceformat[1]);
               $sourceReplace= str_replace("~|UNIQID|~",$oSource->get("UniqueID"),$Sourceformat[1]);
               $trackURL = preg_replace("/".$sourceSearch."/",$sourceReplace,$trackURL);
            }
            if ( preg_match("/([^\ ]*\~\|SITEID\|\~[^\ ]*)/",$URLformat,$Sourceformat) && strlen($oSource->get("SiteID")) > 0 ){
               $sourceSearch = str_replace("~|SITEID|~","[^\ \&]*",$Sourceformat[1]);
               $sourceReplace= str_replace("~|SITEID|~",$oSource->get("SiteID"),$Sourceformat[1]);
               $trackURL = preg_replace("/".$sourceSearch."/",$sourceReplace,$trackURL);
            }
            if ( preg_match("/([^\ ]*\~\|GROUPID\|\~[^\ ]*)/",$URLformat,$Sourceformat) ){
               $groupid = $oTrackingURL->getUniq($oSource->get("SourceGroup_"));
               if ( strlen($groupid) > 0 ){
                  $sourceSearch = str_replace("~|GROUPID|~","[^\ \&]*",$Sourceformat[1]);
                  $sourceReplace= str_replace("~|GROUPID|~",$groupid,$Sourceformat[1]);
                  $trackURL = preg_replace("/".$sourceSearch."/",$sourceReplace,$trackURL);
               }
            }
            if ( strlen($oTrackingURL->get("AddValue")) > 0 ){
               $AddValueFormat = $oTrackingURL->get("AddValue");
               if ( preg_match("/([^\ ]*\~\|SOURCE\|\~[^\ ]*)/",$AddValueFormat,$Sourceformat)  && strlen($oSource->get("SourceID")) > 0 ){
                  $sourceSearch = str_replace("~|SOURCE|~","[^\ \&]*",$Sourceformat[1]);
                  $sourceReplace= str_replace("~|SOURCE|~",$oSource->get("SourceID"),$Sourceformat[1]);
                  $trackURL .= preg_replace("/".$sourceSearch."/",$sourceReplace,$AddValueFormat);
               }
               if ( preg_match("/([^\ ]*\~\|UNIQID\|\~[^\ ]*)/",$AddValueFormat,$Sourceformat) && strlen($oSource->get("UniqueID")) > 0 ){
                  $sourceSearch = str_replace("~|UNIQID|~","[^\ \&]*",$Sourceformat[1]);
                  $sourceReplace= str_replace("~|UNIQID|~",$oSource->get("UniqueID"),$Sourceformat[1]);
                  $trackURL .= preg_replace("/".$sourceSearch."/",$sourceReplace,$AddValueFormat);
               }
               if ( preg_match("/([^\ ]*\~\|SITEID\|\~[^\ ]*)/",$AddValueFormat,$Sourceformat) && strlen($oSource->get("SiteID")) > 0 ){
                  $sourceSearch = str_replace("~|SITEID|~","[^\ \&]*",$Sourceformat[1]);
                  $sourceReplace= str_replace("~|SITEID|~",$oSource->get("SiteID"),$Sourceformat[1]);
                  $trackURL .= preg_replace("/".$sourceSearch."/",$sourceReplace,$AddValueFormat);
               }
               if ( preg_match("/([^\ ]*\~\|GROUPID\|\~[^\ ]*)/",$AddValueFormat,$Sourceformat) ){
                  $groupid = $oTrackingURL->getUniq($oSource->get("SourceGroup_"));
                  if ( strlen($groupid) > 0 ){
                     $sourceSearch = str_replace("~|GROUPID|~","[^\ \&]*",$Sourceformat[1]);
                     $sourceReplace= str_replace("~|GROUPID|~",$groupid,$Sourceformat[1]);
                     $trackURL .= preg_replace("/".$sourceSearch."/",$sourceReplace,$AddValueFormat);
                  }
               }
            }
         }
      }
      return $trackURL;
   }

   function three_digit($source){
      if ( $source > 99 )
         return "$source";
      else if ( $source > 9 )
         return "0$source";
      else return "00$source";
   }

   function getSource(){
      $source ='';
      if(isset($_COOKIE['SOURCE'])) {
        $source = $_COOKIE['SOURCE'];
      }
      $tmpArr = array_keys ($_REQUEST);
      if(in_array('afid',$tmpArr)) {
        $source = $_REQUEST['afid'];
      }
      elseif (in_array('AFID',$tmpArr)){
        $source = $_REQUEST['AFID'];
      }
      elseif (in_array('source',$tmpArr)){
        $source = $_REQUEST['source'];
      }
      elseif (in_array('SOURCE',$tmpArr)){
        $source = $_REQUEST['SOURCE'];
      }
      if($source=='') {
        $source ='Unknown';
      }
      return $source;
   }

     function redirect301($url) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url");
		exit();
	}

	function redirect302($url) {
		header("HTTP/1.1 302 Moved Temporarily");
		header("Location: $url");
		exit();
	}
}
?>
