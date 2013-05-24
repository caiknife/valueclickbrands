<?php
if ( !defined("CLASS_MERCHANT_PHP") ){
   define("CLASS_MERCHANT_PHP","YES");

   require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
   require_once(__INCLUDE_ROOT."/lib/classes/class.Category.php");
   require_once(__INCLUDE_ROOT."/lib/classes/class.Coupon.php");
   require_once(__INCLUDE_ROOT."/lib/classes/class.Page.php");
   require_once(__INCLUDE_ROOT."/lib/classes/class.Meta.php");
   require_once(__INCLUDE_ROOT."/lib/classes/class.SourceGroup.php");
   require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
   require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");

   class Merchant {
      var $ClassName = "Merchant";
      var $Key       = "Merchant_";
      var $Categories= "";
      var $Coupons   = "";
      var $Sales     = "";
	  var $MerchantInfo = array();
	  var $MerchantList = array();
	  var $ActiveMerchantList = array();
	  var $FeaturedMerchantList = array();
	  var $DropDownMerchantList = array();
	  var $AllMerchantList = array();
	  var $ClickMerchantList = array();
	  var $TopMerchantList = array();
	  var $NonTopMerchantList = array();
	  var $EditMerchantList = array();
	  var $CouponMerchantList = array();
	  var $SearchMerchantList = array();
	  var $ValidMerchantList = array();
	  var $metaArr = array();
	  var $StoreForShare = array();
	  var $ListCurPage = 0;
      var $ListPageSize = 0;
	  var $ListPagePos = 0;

      function Merchant($id =-1){
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM Merchant WHERE Merchant_= '$id'";
			$this->MerchantInfo = DBQuery::instance()->getRow($sql);
			if(empty($this->MerchantInfo["Descript"]) || empty($this->MerchantInfo["MetaTitle"]) || empty($this->MerchantInfo["MetaKeywords"]) || empty($this->MerchantInfo["MetaDescription"])) {
					if($this->MerchantInfo["MerTemplateID"]==0){
						$tid = 1;
					}else{
						$tid = $this->MerchantInfo["MerTemplateID"];
					}

				$sql = "SELECT * FROM SeoTemplate WHERE Standard_ = '".$tid."'";

				$templateInfo = DBQuery::instance()->getRow($sql);
		 	}
			$this->MerchantInfo["MetaTitle"] = empty($this->MerchantInfo["MetaTitle"])?$templateInfo["MetaTitle"]:$this->MerchantInfo["MetaTitle"];

			$this->MerchantInfo["MetaKeywords"] = empty($this->MerchantInfo["MetaKeywords"])?$templateInfo["MetaKeywords"]:$this->MerchantInfo["MetaKeywords"];

			$this->MerchantInfo["MetaDescription"] = empty($this->MerchantInfo["MetaDescription"])?$templateInfo["MetaDescription"]:$this->MerchantInfo["MetaDescription"];

			$this->MerchantInfo["DescriptMore"] = empty($this->MerchantInfo["Description"])?str_replace('{Merchant Name}',$this->MerchantInfo["Name"],$templateInfo["MerDescription"]):$this->MerchantInfo["DescriptMore"];

			//print_r($this->MerchantInfo);
            $this->loadCouponCount();
            $this->loadCategory();
         }
      }

	  function loadInfo($id) {
	  	  if ( $id > 0 ){
			  $sql = "SELECT * FROM Merchant WHERE Merchant_= $id";
			  $this->MerchantInfo = DBQuery::instance()->getRow($sql);
		  }
	  }

      function search_($name){
	  	 $sql = "SELECT * FROM Merchant WHERE isActive = 1 AND Name REGEXP '$name' ORDER BY Name";
         $rs = DBQuery::instance()->executeQuery($sql);
		  return $rs;
      }

	  function get($name){
         return $this->MerchantInfo[$name];
      }

	  function set($name,$value){
         $this->MerchantInfo[$name] = $value;
      }

	  function setAll($array){
         $this->MerchantInfo = $array;
		 if($this->MerchantInfo["MerTemplateID"]) {
			 	if($this->MerchantInfo["MerTemplateID"]==1 || $this->MerchantInfo["MerTemplateID"]==""){
					$a = array(1,5,6,7,8);
					$b = array_rand($a,1);
					$c = $a[$b];

					$sql = "SELECT * FROM SeoTemplate WHERE Standard_ = ".$c;
				}else{
					$sql = "SELECT * FROM SeoTemplate WHERE Standard_ = ".$this->MerchantInfo["MerTemplateID"];
				}
			$templateInfo = DBQuery::instance()->getRow($sql);
			if($templateInfo["GroupTag"]) {
				$this->MerchantInfo["MetaTitle"] = $templateInfo["MetaTitle"];
				$this->MerchantInfo["MetaKeywords"] = $templateInfo["MetaKeywords"];
				$this->MerchantInfo["MetaDescription"] = $templateInfo["MetaDescription"];
				$this->MerchantInfo["Headline"] = str_replace('{Merchant Name}',$this->MerchantInfo["Name"],$templateInfo["Navigation"]);
				//$this->MerchantInfo["Descript"] = str_replace('{Merchant Name}',$this->MerchantInfo["Name"],$templateInfo["TagDescription"]);
				$this->MerchantInfo["DescriptMore"] = str_replace('{Merchant Name}',$this->MerchantInfo["Name"],$templateInfo["MerDescription"]);
			}
		}
      }

	  function setList($array){
         $this->MerchantList = $array;
      }

      function isFound(){
         if ( $this->get("Merchant_") > 0 && $this->get("isActive") == 1 ){
            $this->loadCouponCount();
            if ( $this->get("Coupons") > 0 ) return true;
         }
         return false;
      }

      function loadSales($bd,$ed){
	  	 $oSales = new SalesGroup();
		 $this->Sales = $oSales->getSalesList($this->get("Merchant_"),$bd,$ed);
      }

      function getSale($name){
	  	 for($i=0; $i<count($this->Sales); $i++) {
		 	if($this->Sales[$i]["Name"] == $name) {
				if($this->Sales[$i]["Sales"] > 0.0) return $this->Sales[$i]["Sales"];
			}
		 }
         return 0.0;
      }

      function loadCategory(){
	  	 $sql = "SELECT Category.* FROM Category, MerCat WHERE  MerCat.Merchant_='".
		 	    $this->MerchantInfo["Merchant_"].
				"' AND MerCat.Category_=Category.Category_ ORDER BY SitemapPriority DESC";
		 $this->Categories = DBQuery::instance()->executeQuery($sql);
      }

      function loadCategoryAll(){
	  	 $sql = "SELECT Category.* FROM Category, MerCat WHERE  MerCat.Merchant_=".
		         $this->MerchantInfo["Merchant_"].
				 " AND MerCat.Category_=Category.Category_ ORDER BY Category.SitemapPriority DESC";
		 $this->Categories = DBQuery::instance()->executeQuery($sql);
      }

      function getCategoryArray(){
         $result = array(0);
		 for($i=0; $i<count($this->Categories); $i++) {
		 	array_push($result,$this->Categories[$i]["Category_"]);
		 }
         return $result;
      }

      function setCategoryArray($category_array){
	     $sql = "SELECT c.*, m.isFeatured FROM Category c, MerCat m " .
		        "WHERE c.Category_=m.Category_ AND m.Merchant_=".
				$this->MerchantInfo["Merchant_"]." AND isFeatured>0";
		 $aCatList = DBQuery::instance()->executeQuery($sql);

		 $sql = "DELETE FROM MerCat WHERE Merchant_=".$this->MerchantInfo["Merchant_"];
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);

         while ( list($key,$val) = @each($category_array) ){
            if ( $val > 0 ){
				  $flag = 1;
				  for($i=0; $i<count($aCatList); $i++) {
				  	  if($val == $aCatList[$i]["Category_"]) {
					  	  $sql = "INSERT INTO MerCat (Merchant_,Category_,isFeatured) VALUES(".
						         $this->MerchantInfo["Merchant_"].",".$val.",".$aCatList[$i]["isFeatured"].")";
						  DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
						  $flag = 2;
						  break;
					  }
				  }
		          if($flag == 1) {
				  	  $sql = "INSERT INTO MerCat (Merchant_,Category_,isFeatured) VALUES(".
							 $this->MerchantInfo["Merchant_"].",".$val.",0)";
					  DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
				  }
            }
         }
      }

      function loadCouponCount(){
	  	 $sql = "SELECT COUNT(*) coup FROM Coupon WHERE Merchant_='".$this->MerchantInfo["Merchant_"]."'";
		 $this->Coupons = DBQuery::instance()->getOne($sql);
      }

	  function getNextID($name) {
	  	$sql = "SELECT ID FROM Sequence WHERE Name = '$name'";
		$rs = DBQuery::instance()->getOne($sql);
		$newID = $rs+1;
		$sql = "UPDATE Sequence SET ID = $newID WHERE Name = '$name'";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		return $newID;
	  }

      function insert($flag =1){
         if ( $flag == 1 ){
		 	$new_id = $this->getNextID("Merchant");
			if(strlen($this->MerchantInfo["Logo"]) > 0) {
				$this->MerchantInfo["ImageDownload"] = 1;
			} else {
				$this->MerchantInfo["ImageDownload"] = 0;
			}
			if(!$this->MerchantInfo["Union_"]) {
				$this->MerchantInfo["Union_"] = 0;
			}
		    $sql = "INSERT INTO Merchant (Merchant_,Name,NameURL,URL,isBold,Rating,Headline," .
			"isPremium,isActive,Descript,MetaTitle,MetaDescription,MetaKeywords,MetaFrame,HiddenWords," .
			"Keywords,Logo,OldLogo,EnterCodeText,EnterCodeImage,Image1,Image1Type,Image1URL,Image2," .
			"Image2Type,Image2URL,Image3,Image3Type,Image3URL,Image4,Image4Type,Image4URL,Image5," .
			"Image5Type,Image5URL,Image6,Image6Type,Image6URL,Image7,Image7Type,Image7URL,Image8," .
			"Image8Type,Image8URL,Image9,Image9Type,Image9URL,isPopup,isPopdown,isFrame,isTop," .
			"isAdSenseCode,LastGenerate,ShippingPolicy,TaxPolicy,PaymentPolicy," .
			"ReturnPolicy,PrivacyPolicy,CSPhone,Certificate,Catalog,ShippingPolicyURL," .
			"TaxPolicyURL,PaymentPolicyURL,ReturnPolicyURL,CSEmail," .
			"CertificateURL,StoreReview,ShortDescr,SitemapPriority,isFree,isShow,ImageDownload,MerTemplateID,Union_) " .
			"VALUES(".$new_id.",'".$this->MerchantInfo["Name"]."','".$this->MerchantInfo["NameURL"]."'," .
			"'".$this->MerchantInfo["URL"]."',".$this->MerchantInfo["isBold"].",".$this->MerchantInfo["Rating"]."," .
			"'".$this->MerchantInfo["Headline"]."',".$this->MerchantInfo["isPremium"].",".$this->MerchantInfo["isActive"]."," .
			"'".$this->MerchantInfo["Descript"]."','".$this->MerchantInfo["MetaTitle"]."','".$this->MerchantInfo["MetaDescription"]."'," .
			"'".$this->MerchantInfo["MetaKeywords"]."','".$this->MerchantInfo["MetaFrame"]."','".$this->MerchantInfo["HiddenWords"]."'," .
			"'".$this->MerchantInfo["Keywords"]."','".$this->MerchantInfo["Logo"]."','".$this->MerchantInfo["OldLogo"]."'," .
			"'".$this->MerchantInfo["EnterCodeText"]."','".$this->MerchantInfo["EnterCodeImage"]."','".$this->MerchantInfo["Image1"]."'," .
			"".$this->MerchantInfo["Image1Type"].",'".$this->MerchantInfo["Image1URL"]."','".$this->MerchantInfo["Image2"]."'," .
			"".$this->MerchantInfo["Image2Type"].",'".$this->MerchantInfo["Image2URL"]."','".$this->MerchantInfo["Image3"]."'," .
			"".$this->MerchantInfo["Image3Type"].",'".$this->MerchantInfo["Image3URL"]."','".$this->MerchantInfo["Image4"]."'," .
			"".$this->MerchantInfo["Image4Type"].",'".$this->MerchantInfo["Image4URL"]."','".$this->MerchantInfo["Image5"]."'," .
			"".$this->MerchantInfo["Image5Type"].",'".$this->MerchantInfo["Image5URL"]."','".$this->MerchantInfo["Image6"]."'," .
			"".$this->MerchantInfo["Image6Type"].",'".$this->MerchantInfo["Image6URL"]."','".$this->MerchantInfo["Image7"]."'," .
			"".$this->MerchantInfo["Image7Type"].",'".$this->MerchantInfo["Image7URL"]."','".$this->MerchantInfo["Image8"]."'," .
			"".$this->MerchantInfo["Image8Type"].",'".$this->MerchantInfo["Image8URL"]."','".$this->MerchantInfo["Image9"]."'," .
			"".$this->MerchantInfo["Image9Type"].",'".$this->MerchantInfo["Image9URL"]."',".$this->MerchantInfo["isPopup"]."," .
			"".$this->MerchantInfo["isPopdown"].",'".$this->MerchantInfo["isFrame"]."',".$this->MerchantInfo["isTop"]."," .
			"".$this->MerchantInfo["isAdSenseCode"].",'00000000000000'," .
			"'".$this->MerchantInfo["ShippingPolicy"]."','".$this->MerchantInfo["TaxPolicy"]."','".$this->MerchantInfo["PaymentPolicy"]."'," .
			"'".$this->MerchantInfo["ReturnPolicy"]."','".$this->MerchantInfo["PrivacyPolicy"]."','".$this->MerchantInfo["CSPhone"]."'," .
			"'".$this->MerchantInfo["Certificate"]."','".$this->MerchantInfo["Catalog"]."','".$this->MerchantInfo["ShippingPolicyURL"]."'," .
			"'".$this->MerchantInfo["TaxPolicyURL"]."','".$this->MerchantInfo["PaymentPolicyURL"]."','".$this->MerchantInfo["ReturnPolicyURL"]."'," .
			"'".$this->MerchantInfo["CSEmail"]."','".$this->MerchantInfo["CertificateURL"]."','".$this->MerchantInfo["StoreReview"]."'," .
			"'".$this->MerchantInfo["ShortDescr"]."',".$this->MerchantInfo["SitemapPriority"].
			",".$this->MerchantInfo["isFree"].",0,".$this->MerchantInfo["ImageDownload"].
			",'".$this->MerchantInfo["MerTemplateID"]."',".$this->MerchantInfo["Union_"].")";

			DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            @mkdir(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$new_id,0777);
			@chmod(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$new_id, 0777);
    		@mkdir(__INCLUDE_ROOT.__MERCHANT_IMAGES.$new_id,0777);
			@chmod(__INCLUDE_ROOT.__MERCHANT_IMAGES.$new_id, 0777);
            //mkdir(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.str_replace(" ","_",$this->get("Name")),0777);
            $sql = "SELECT * FROM Merchant WHERE Merchant_= $new_id";
			$this->MerchantInfo = DBQuery::instance()->getRow($sql);
			$this->uploadImages($this);
         }
         else{
		 	if(!$this->MerchantInfo["Union_"]) {
				$this->MerchantInfo["Union_"] = 0;
			}
            $sql = "INSERT INTO Merchant (Merchant_,Name,NameURL,URL,isBold,Rating,Headline," .
			"isPremium,isActive,Descript,MetaTitle,MetaDescription,MetaKeywords,MetaFrame,HiddenWords," .
			"Keywords,Logo,OldLogo,EnterCodeText,EnterCodeImage,Image1,Image1Type,Image1URL,Image2," .
			"Image2Type,Image2URL,Image3,Image3Type,Image3URL,Image4,Image4Type,Image4URL,Image5," .
			"Image5Type,Image5URL,Image6,Image6Type,Image6URL,Image7,Image7Type,Image7URL,Image8," .
			"Image8Type,Image8URL,Image9,Image9Type,Image9URL,isPopup,isPopdown,isFrame,isTop," .
			"isAdSenseCode,TrackingURL_,LastGenerate,ShippingPolicy,TaxPolicy,PaymentPolicy," .
			"ReturnPolicy,PrivacyPolicy,CSPhone,Certificate,Catalog,ShippingPolicyURL," .
			"TaxPolicyURL,PaymentPolicyURL,ReturnPolicyURL,CSEmail," .
			"CertificateURL,StoreReview,ShortDescr,SitemapPriority,isFree,isShow,ImageDownload,MerTemplateID,Union_) " .
			"VALUES(".$this->MerchantInfo["Merchant_"].",'".$this->MerchantInfo["Name"]."','".$this->MerchantInfo["NameURL"]."'," .
			"'".$this->MerchantInfo["URL"]."',".$this->MerchantInfo["isBold"].",".$this->MerchantInfo["Rating"]."," .
			"'".$this->MerchantInfo["Headline"]."',".$this->MerchantInfo["isPremium"].",".$this->MerchantInfo["isActive"]."," .
			"'".$this->MerchantInfo["Descript"]."','".$this->MerchantInfo["MetaTitle"]."','".$this->MerchantInfo["MetaDescription"]."'," .
			"'".$this->MerchantInfo["MetaKeywords"]."','".$this->MerchantInfo["MetaFrame"]."','".$this->MerchantInfo["HiddenWords"]."'," .
			"'".$this->MerchantInfo["Keywords"]."','".$this->MerchantInfo["Logo"]."','".$this->MerchantInfo["OldLogo"]."'," .
			"'".$this->MerchantInfo["EnterCodeText"]."','".$this->MerchantInfo["EnterCodeImage"]."','".$this->MerchantInfo["Image1"]."'," .
			"".$this->MerchantInfo["Image1Type"].",'".$this->MerchantInfo["Image1URL"]."','".$this->MerchantInfo["Image2"]."'," .
			"".$this->MerchantInfo["Image2Type"].",'".$this->MerchantInfo["Image2URL"]."','".$this->MerchantInfo["Image3"]."'," .
			"".$this->MerchantInfo["Image3Type"].",'".$this->MerchantInfo["Image3URL"]."','".$this->MerchantInfo["Image4"]."'," .
			"".$this->MerchantInfo["Image4Type"].",'".$this->MerchantInfo["Image4URL"]."','".$this->MerchantInfo["Image5"]."'," .
			"".$this->MerchantInfo["Image5Type"].",'".$this->MerchantInfo["Image5URL"]."','".$this->MerchantInfo["Image6"]."'," .
			"".$this->MerchantInfo["Image6Type"].",'".$this->MerchantInfo["Image6URL"]."','".$this->MerchantInfo["Image7"]."'," .
			"".$this->MerchantInfo["Image7Type"].",'".$this->MerchantInfo["Image7URL"]."','".$this->MerchantInfo["Image8"]."'," .
			"".$this->MerchantInfo["Image8Type"].",'".$this->MerchantInfo["Image8URL"]."','".$this->MerchantInfo["Image9"]."'," .
			"".$this->MerchantInfo["Image9Type"].",'".$this->MerchantInfo["Image9URL"]."',".$this->MerchantInfo["isPopup"]."," .
			"".$this->MerchantInfo["isPopdown"].",'".$this->MerchantInfo["isFrame"]."',".$this->MerchantInfo["isTop"]."," .
			"".$this->MerchantInfo["isAdSenseCode"].",".$this->MerchantInfo["TrackingURL_"].",'00000000000000'," .
			"'".$this->MerchantInfo["ShippingPolicy"]."','".$this->MerchantInfo["TaxPolicy"]."','".$this->MerchantInfo["PaymentPolicy"]."'," .
			"'".$this->MerchantInfo["ReturnPolicy"]."','".$this->MerchantInfo["PrivacyPolicy"]."','".$this->MerchantInfo["CSPhone"]."'," .
			"'".$this->MerchantInfo["Certificate"]."','".$this->MerchantInfo["Catalog"]."','".$this->MerchantInfo["ShippingPolicyURL"]."'," .
			"'".$this->MerchantInfo["TaxPolicyURL"]."','".$this->MerchantInfo["PaymentPolicyURL"]."','".$this->MerchantInfo["ReturnPolicyURL"]."'," .
			"'".$this->MerchantInfo["CSEmail"]."','".$this->MerchantInfo["CertificateURL"]."','".$this->MerchantInfo["StoreReview"]."'," .
			"'".$this->MerchantInfo["ShortDescr"]."',".$this->MerchantInfo["SitemapPriority"].
			",".$this->MerchantInfo["isFree"].",0,".$this->MerchantInfo["ImageDownload"].
			",'".$this->MerchantInfo["MerTemplateID"]."',".$this->MerchantInfo["Union_"].")";
			DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         }
      }

      function delete(){
         exec("rm -rf ".__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$this->get("Merchant_"));
         //exec("rm -rf ".str_replace(" ","_",__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$this->get("Name")));
         $this->setCategoryArray(array());
		 $sql = "DELETE FROM Coupon WHERE Merchant_=".$this->get("Merchant_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		 $sql = "DELETE FROM MerCat WHERE Merchant_=".$this->get("Merchant_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		 $sql = "DELETE FROM Notify WHERE Merchant_=".$this->get("Merchant_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		 $sql = "DELETE FROM SMerchant WHERE Merchant_=".$this->get("Merchant_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		 $sql = "DELETE FROM Sales WHERE Merchant_=".$this->get("Merchant_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);

		 $sql = "DELETE FROM Merchant WHERE Merchant_=".$this->get("Merchant_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }

      function loadRelated($category){
	  	 $sql = "SELECT Merchant.* FROM Merchant, MerCat WHERE Merchant.isActive = 1 AND " .
		        "Merchant.Merchant_=MerCat.Merchant_ AND Merchant.Merchant_<>".$this->get("Merchant_").
				" AND MerCat.Category_=".$category." ORDER BY Name";
		 $this->Related = DBQuery::instance()->executeQuery($sql);

//         $tmp = new MerchantList("SELECT Merchant.* FROM Merchant, CoupCat, Coupon WHERE Merchant.isActive = 1 AND Merchant.Merchant_=Coupon.Merchant_ AND Merchant.Merchant_<>".$this->ID." AND CoupCat.Category_=".$category." AND Coupon.Coupon_=CoupCat.Coupon_ ORDER BY Name");
//         while ( $oMer = $tmp->nextItem() ){
//            $this->Related->Items[$oMer->get("Merchant_")] = $oMer;
//         }
      }


      function update($oldMerchant =""){
	  	 if ( !is_object($oldMerchant) ) return;
		 if(!$oldMerchant->get("Union_")) {
		 	$oldMerchant->set("Union_",0);
		 }
	     $sql = "UPDATE Merchant SET Name='".$oldMerchant->get("Name")."',name1='".$oldMerchant->get("name1")."',name2='".$oldMerchant->get("name2")."',name3='".$oldMerchant->get("name3")."',name4='".$oldMerchant->get("name4")."',name5='".$oldMerchant->get("name5")."',name6='".$oldMerchant->get("name6")."', NameURL='".$oldMerchant->get("NameURL").
		        "', URL='".$oldMerchant->get("URL")."', isBold=".$oldMerchant->get("isBold").", Rating='".$oldMerchant->get("Rating").
				"',Headline='".$oldMerchant->get("Headline")."', isPremium=".$oldMerchant->get("isPremium").", isActive=".$oldMerchant->get("isActive").
				", Descript='".$oldMerchant->get("Descript")."', MetaTitle='".$oldMerchant->get("MetaTitle").
				"', MetaDescription='".$oldMerchant->get("MetaDescription")."', MetaKeywords='".$oldMerchant->get("MetaKeywords").
				"', MetaFrame='".$oldMerchant->get("MetaFrame")."', HiddenWords='".$oldMerchant->get("HiddenWords").
				"', Keywords='".$oldMerchant->get("Keywords")."', Logo='".$oldMerchant->get("Logo")."', OldLogo='".$oldMerchant->get("OldLogo").
				"', EnterCodeText='".$oldMerchant->get("EnterCodeText")."', EnterCodeImage='".$oldMerchant->get("EnterCodeImage").
				"', Image1='".$oldMerchant->get("Image1")."', Image1Type=".$oldMerchant->get("Image1Type").", Image1URL='".$oldMerchant->get("Image1URL").
				"', Image2='".$oldMerchant->get("Image2")."', Image2Type=".$oldMerchant->get("Image2Type").", Image2URL='".$oldMerchant->get("Image2URL").
				"', Image3='".$oldMerchant->get("Image3")."', Image3Type=".$oldMerchant->get("Image3Type").", Image3URL='".$oldMerchant->get("Image3URL").
				"', isPopup=".$oldMerchant->get("isPopup").", isPopdown=".$oldMerchant->get("isPopdown").", isFrame=".$oldMerchant->get("isFrame").
				", isTop=".$oldMerchant->get("isTop").", isAdSenseCode=".$oldMerchant->get("isAdSenseCode").", LastGenerate='".$oldMerchant->get("LastGenerate").
				"', ShippingPolicy='".$oldMerchant->get("ShippingPolicy")."', TaxPolicy='".$oldMerchant->get("TaxPolicy").
				"', PaymentPolicy='".$oldMerchant->get("PaymentPolicy")."', ReturnPolicy='".$oldMerchant->get("ReturnPolicy").
				"', PrivacyPolicy='".$oldMerchant->get("PrivacyPolicy")."', CSPhone='".$oldMerchant->get("CSPhone").
				"', Certificate='".$oldMerchant->get("Certificate")."', Catalog='".$oldMerchant->get("Catalog").
				"', ShippingPolicyURL='".$oldMerchant->get("ShippingPolicyURL")."', TaxPolicyURL='".$oldMerchant->get("TaxPolicyURL").
				"', PaymentPolicyURL='".$oldMerchant->get("PaymentPolicyURL")."', ReturnPolicyURL='".$oldMerchant->get("ReturnPolicyURL").
				"', CSEmail='".$oldMerchant->get("CSEmail")."', CertificateURL='".$oldMerchant->get("CertificateURL").
				"', StoreReview='".$oldMerchant->get("StoreReview")."', ShortDescr='".$oldMerchant->get("ShortDescr").
				"', SitemapPriority='".$oldMerchant->get("SitemapPriority").
				"', isFree='".$oldMerchant->get("isFree").
				"', MerTemplateID='".$oldMerchant->get("MerTemplateID").
				"', Union_=".$oldMerchant->get("Union_").
				" WHERE Merchant_=".$oldMerchant->get("Merchant_")."";
         DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);

         //if ( $this->get("Name") != $oldMerchant->get("Name") ){
         //   @rename(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.str_replace(" ","_",stripslashes($oldMerchant->get("Name"))), __INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.str_replace(" ","_",stripslashes($this->get("Name"))));
         //}
         //else{
         //   @mkdir(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.str_replace(" ","_",stripslashes($this->get("Name"))),0777);
         //}

         @mkdir(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_"),0777);
		 @chmod(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_"), 0777);
		 @mkdir(__INCLUDE_ROOT.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_"),0777);
		 @chmod(__INCLUDE_ROOT.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_"), 0777);
		 //echo "123";
		 //exit();
         $this->uploadImages($oldMerchant);
      }

      function uploadImages($oldMerchant){

         $this->copyImage("Logo",$oldMerchant);
         $this->copyImage("OldLogo",$oldMerchant);
         $this->copyImage("EnterCodeImage",$oldMerchant);
         $this->copyImage("Image1",$oldMerchant);
         $this->copyImage("Image2",$oldMerchant);
         $this->copyImage("Image3",$oldMerchant);
//         $this->copyImage("Image4",$oldMerchant);
//         $this->copyImage("Image5",$oldMerchant);
//         $this->copyImage("Image6",$oldMerchant);
//         $this->copyImage("Image7",$oldMerchant);
//         $this->copyImage("Image8",$oldMerchant);
//         $this->copyImage("Image9",$oldMerchant);
      }

      function copyImage($field,$oldMerchant){
         global $HTTP_POST_FILES;

         if ( strlen($oldMerchant->get($field)) != 0 && strlen($oldMerchant->get($field)) == 0 ){
            unlink(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_")."/".$field.".gif");
            //@unlink(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.str_replace(" ","_",stripslashes($this->get("Name"))."/".$field.".gif"));
         }
         if ( strlen($oldMerchant->get($field)) != 0 ){
            reset($HTTP_POST_FILES);
            while ( list($fl,$vr) = @each($HTTP_POST_FILES) ){
               if ( $vr['name'] == $oldMerchant->get($field) ){
                  //copy($HTTP_POST_FILES[$fl]['tmp_name'],__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.str_replace(" ","_",stripslashes($this->get("Name")))."/".$field.".gif");
                  //echo $HTTP_POST_FILES[$fl]['tmp_name'].'---'.__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$this->get("Merchant_")."/".$field.".gif";exit;
				  if(is_dir(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_"))) {
				  	  @chmod(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_"), 0777);
				  }
				  if(is_dir(__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_"))) {
				  	  @chmod(__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_"), 0777);
				  }
				  if(is_file(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_")."/".$field.".gif")) {
				  	  @unlink(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_")."/".$field.".gif");
				  	  system('rm -rf "'.__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_").'"');
					  @mkdir(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_"), 0777);
					  @chmod(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_"), 0777);
				  }
                  copy($HTTP_POST_FILES[$fl]['tmp_name'],__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_")."/".$field.".gif");
				  if(is_file(__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_")."/".$field.".gif")) {
				  	 @unlink(__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_")."/".$field.".gif");
					 system('rm -rf "'.__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_").'"');
					 @mkdir(__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_"), 0777);
					 @chmod(__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_"), 0777);
				  }
				  copy(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$oldMerchant->get("Merchant_")."/".$field.".gif",__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_")."/".$field.".gif");
				  if(is_file(__IMAGE_ADD.__MERCHANT_IMAGES.$oldMerchant->get("Merchant_")."/Logo.gif")) {
				  	  $sql = "UPDATE Merchant set ImageDownload = 1 where Merchant_ = ".$oldMerchant->get("Merchant_");
					  DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
				  } else {
				  	  $sql = "UPDATE Merchant set ImageDownload = 0 where Merchant_ = ".$oldMerchant->get("Merchant_");
					  DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
				  }
               }
            }
         }
      }

      function setImage($field,$image,$isDelete,$isNone){
         global $HTTP_POST_FILES,$HTTP_POST_VARS;
         if ( strlen($this->get($field)) == 0 ){
            $this->set($field, "");
         }
         if ( ((integer)$HTTP_POST_VARS[$isDelete]) == 1 || $isNone == 0 ){
            $this->set($field, "");
         }
         else{
            if ( strlen($HTTP_POST_FILES[$image]['name']) > 0 ){
               $this->set($field, $HTTP_POST_FILES[$image]['name']);
               //$this->set($field, $field.".gif");
            }
         }
      }

      function presentImage($field){
         if ( strncmp("Image",$field,5) < 0 || $this->get($field."Type") == 1 ){
            //if ( file_exists(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.str_replace(" ","_",$this->get("Name"))."/".$field.".gif") && $this->get($field) != "" ){
            if ( file_exists(__IMAGE_ADD.__MERCHANT_UPLOAD_IMAGES.$this->get("Merchant_")."/".$field.".gif") && $this->get($field) != "" ){
               return "present";
            }
            else{
               return "absent";
            }
         }
         else{
            if ( $this->get($field) != "" ){
               return "present";
            }
            else{
               return "absent";
            }
         }
      }

      function hasPromotion(){
         for ( $i=1; $i<=9; $i++ ){
            if ( $this->presentImage("Image".$i) == "present" ) return (1);
         }
         return (0);
      }

      function loadCoupons(){
	     $oCoupon = new Coupon();
		 $this->Coupons = $oCoupon->getMerchantCouponList($this->get("Merchant_"));
      }

      function countDeals(){
         $result = 0;
         if ( count($this->Coupons) > 0 ){
		 	for($i=0; $i<count($this->Coupons); $i++) {
				if($this->Coupons[$i]["isCoupon"] != 1) {
					$result++;
				}
			}
         }
         return $result;
      }

      function countCoupons(){
         $result = 0;
         if ( count($this->Coupons) > 0 ){
		 	for($i=0; $i<count($this->Coupons); $i++) {
				if($this->Coupons[$i]["isCoupon"] == 1) {
					$result++;
				}
			}
         }
         return $result;
      }

      function MustUpdate(){
         return 0;
      }

	  function deleteAllMerFile() {
	  	  //delete
			$d = @dir(__INCLUDE_ROOT."pages");
			while($entry=$d->read()) {
				 if ( $entry == "." || $entry == ".." ) continue;
				 if(is_dir(__INCLUDE_ROOT."pages/".$entry)) {
				 	$dd = @dir(__INCLUDE_ROOT."pages/".$entry);
					while($newEntry=$dd->read()) {
						if ( $newEntry == "." || $newEntry == ".." ) continue;
						@unlink(__INCLUDE_ROOT."pages/".$entry."/".$newEntry);
					}
					$dd->close();
					@rmdir(__INCLUDE_ROOT."pages/".$entry);
				 }
			}
			$d->close();
	  }

	  function storeShare($categoryList) {
	  	  $oPage = new Page();
		  $oPage->find("RESOURCE_INCLUDE");
		  $this->StoreForShare["resource_include"] = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

		  $oPage->find("NEWCOUPON_INCLUDE");
		  $this->StoreForShare["newcoupon_include"] = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

		  $oPage->find("HOTCOUPON_INCLUDE");
		  $this->StoreForShare["hotcoupon_include"] = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";

		  for($j=0; $j<count($categoryList); $j++) {
			$categoryForShow[$j]["category_url"] = Utilities::getURL("category",
					array("NameURL" => $categoryList[$j]["NameURL"],"Name" => $categoryList[$j]["NameURL"],"Page" => 1));
			$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
		  }
		  $this->StoreForShare["categoryForShow"] = $categoryForShow;
	  }

	  function setMeta(){
	 	$oMeta = new Meta();
		$oMeta->find('ItemType',$this->ClassName);
		$this->metaArr["MetaTitle"] = $oMeta->get("MetaTitle");
		$this->metaArr["MetaDescription"] = $oMeta->get("MetaDescription");
		$this->metaArr["MetaKeywords"] = $oMeta->get("MetaKeywords");
      }

	  function getMeta($field){
         $result = $this->MerchantInfo[$field];
         if ( '' == trim($result)) {
            $result = $this->metaArr[$field];
         }
         return str_replace('{Merchant Name}',$this->MerchantInfo["Name"],$result);
      }

      function Cache($force =false){
         $result = "";
         $must_removed = 0;

         if ( $this->MustUpdate() == 1 || $force == true){
		    $sql = "UPDATE Merchant SET LastGenerate=(NOW() + 0) WHERE Merchant_=".$this->get("Merchant_");
			DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);

			//delete
			if(is_dir(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL"))) && str_replace(" ","_",$this->get("NameURL")) != '') {
			    $d = @dir(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL")));
				while($entry=$d->read()) {
					 if ( $entry == "." || $entry == ".." ) continue;
					 @unlink(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL"))."/".$entry);
	            }
				$d->close();
				@rmdir(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL")));
			}
			//creat
			@mkdir(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL")), 0777);
			@chmod(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL")), 0777);
			if ( ! file_exists(__INCLUDE_ROOT.__MERCHANT_IMAGES.$this->get("Merchant_")) ){
                @mkdir(__INCLUDE_ROOT.__MERCHANT_IMAGES.str_replace(" ","_",$this->get("NameURL")), 0777);
				@chmod(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL")), 0777);
            } else {
				  $d = @dir(__INCLUDE_ROOT.__MERCHANT_IMAGES.$this->get("Merchant_"));
                  if ( is_object($d) ){
                     while($entry = $d->read()) {
                        if ( $entry == "." || $entry == ".." ) continue;
                        @unlink(__INCLUDE_ROOT.__MERCHANT_IMAGES.$this->get("Merchant_")."/".$entry);
                     }
                     $d->close();
                  }
			}

			if ($this->presentImage("OldLogo")){
                  @copy(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$this->get("Merchant_")."/OldLogo.gif",
				  				__INCLUDE_ROOT.__OLD_MERCHANT_IMAGES.$this->get("Merchant_").".gif");
            }
            if ( file_exists(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$this->get("Merchant_")) ){
                  $d = @dir(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$this->get("Merchant_"));
                  while($entry=$d->read()) {
                     if ( $entry == "." || $entry == ".." ) continue;
                     @copy(__INCLUDE_ROOT.__MERCHANT_UPLOAD_IMAGES.$this->get("Merchant_")."/".$entry,
					 			__INCLUDE_ROOT.__MERCHANT_IMAGES.$this->get("Merchant_")."/".$entry);
                  }
                  $d->close();
            }
            $result .= "MERCHANT: ".$this->get("Name")." - directory & images updated<br>";

			if ( !($f = @fopen(__INCLUDE_ROOT."pages/".str_replace(" ","_",$this->get("NameURL"))."/index.html" , "w")) ){
				 return ($this->get("Name")." - can't open file '".$this->get("NameURL").".html'<br>");
			}

			$oCoupon = new Coupon;
			$couponList = $oCoupon->getMerchantCouponList($this->get("Merchant_"));
			for($i=0; $i<count($couponList); $i++) {
				if($couponList[$i]["isFree"] == '0') {
					$couponInfo[$i]["url"] = Utilities::getURL("couponUnion", array("Merchant_" => $this->MerchantInfo["Merchant_"],
				                        "Coupon_" => $couponList[$i]["Coupon_"]));
				} else {
					if(($couponList[$i]["isMore"] == 0 || $couponList[$i]["hasDetail"] == 0) && $couponList[$i]["ImageDownload"] != 1) {
						$couponInfo[$i]["url"] = "";
					} else {
						$couponInfo[$i]["url"] = Utilities::getURL("couponFree", array("NameURL" => $this->MerchantInfo["NameURL"],
				                        "Coupon_" => $couponList[$i]["Coupon_"]));
					}

				}
				if($couponList[$i]["ImageDownload"] == 1) {
					$couponInfo[$i]["image"] = Utilities::getImageURL($couponList[$i]["Coupon_"]);
					$image = getimagesize("../..".$couponInfo[$i]["image"]);
					$imgx = $image[0];
					$imgy = $image[1];
					if(!($imgx > 0 && $imgy > 0)) {
						$couponInfo[$i]["image"] = "";
					} elseif($imgx>100 || $imgy>100 ) {
						$diveX = $imgx / 100;
						$diveY = $imgy / 100;
						$diveMax = $diveX > $diveY?$diveX:$diveY;
						$imgx = floor($imgx/$diveMax);
						$imgy = floor($imgy/$diveMax);
						$couponInfo[$i]["imageX"] = $imgx;
						$couponInfo[$i]["imageY"] = $imgy;
					} else {
						$couponInfo[$i]["imageX"] = $imgx;
						$couponInfo[$i]["imageY"] = $imgy;
					}
				} else {
					$couponInfo[$i]["image"] = "";
				}
				$couponInfo[$i]["title"] = Utilities::cutString($couponList[$i]["Descript"],80);
				$couponInfo[$i]["detail"] = Utilities::cutString($couponList[$i]["Detail"],380);
				$couponInfo[$i]["start"] = ($couponList[$i]["StartDate"] == "0000-00-00"?$couponList[$i]["AddDate1"]:$couponList[$i]["StartDate"]);
				$couponInfo[$i]["end"] = (($couponList[$i]["ExpireDate"] == "0000-00-00" or $couponList[$i]["ExpireDate"] >= '2100-1-1')?"<span class=\"red\">优惠进行中</span>":$couponList[$i]["ExpireDate"]);
				$couponInfo[$i]["saveUrl"] = "/account.php?action=save&p=".$couponList[$i]["Coupon_"];
				$couponInfo[$i]["couponID"] = $couponList[$i]["Coupon_"];

			}

			$metaTitle = $this->getMeta('MetaTitle');
			$metaDescription = $this->getMeta('MetaDescription');
			$metaKeywords = $this->getMeta('MetaKeywords');

			if(file_exists(__INCLUDE_ROOT.__MERCHANT_IMAGES.$this->get("Merchant_")."/Logo.gif")) {
			//if(strlen($this->get("Logo")) > 0) {
				$merLogPath = "/images/merchants/".$this->get("Merchant_")."/Logo.gif";
			} else {
				$merLogPath = "";
			}
			if(strlen($this->get("URL")) > 0) {
                $merchantURL = Tracking_Uri::build(array(
                    Tracking_Uri::BUILD_TYPE        => 'coupon',
                    Tracking_Uri::MERCHANT_ID       => $this->get("Merchant_"),
                ));
			} else {
				$merchantURL = "";
			}
			$merchantCouponURL = Utilities::getURL("merchant", array("NameURL" => $this->get("NameURL")));

			if($this->get("Merchant_") == 1015) {//麦当劳
				$specWord = "以下关键词也可找到麦当劳优惠券：McDonald’s, McDonalds," .
				            " 麦当老，买当老，卖当老，麦当老电子优惠券，麦当老优惠卷，麦当捞，麦当牢，麦当烙 ，麦当唠";
			}
			if($this->get("Merchant_") == 899) {//肯德基
				$specWord = "以下关键词也可找到肯德基优惠券：肯德基优惠卷, 肯德基官方网站, 肯德鸡, 肯德机, 肯德几, 肯德机优惠卷";
			}
			  $tpl = new sTemplate();
			  $tpl->setTemplate("merchant_all.tpl");
			  $tpl->assign("LINK_ROOT", __LINK_ROOT);
			  $tpl->assign("title", $metaTitle);
			  $tpl->assign("description", $metaDescription);
			  $tpl->assign("keywords", $metaKeywords);
			  $tpl->assign("category_cur", "-1");
			  $tpl->assign("merchant_cur", $this->get("Merchant_"));
			  $tpl->assign("coupon_cur", "-1");
			  //$tpl->assign("navigation_path", getNavigation(array($this->get("Name")."优惠券、折扣券、购物券" => $merchantCouponURL)));
			  $tpl->assign("navigation_path", getNavigation(array($this->get("Headline") => $merchantCouponURL)));

			  $tpl->assign("RESOURCE_INCLUDE", $this->StoreForShare["resource_include"]);
			  $tpl->assign("newCoupon", $this->StoreForShare["newcoupon_include"]);
			  $tpl->assign("hotCoupon", $this->StoreForShare["hotcoupon_include"]);
			  $tpl->assign("category", $this->StoreForShare["categoryForShow"]);

			  $tpl->assign("merchantLogo", $merLogPath);
			  $tpl->assign("merchantName", $this->get("Name"));
			  if($this->get("Descript")==""){
				$tpl->assign("merchantDescript", $this->get("DescriptMore"));
			  }else{
				$tpl->assign("merchantDescript", $this->get("Descript")."<br /><br />".$this->get("DescriptMore"));
			  }
			  $tpl->assign("merchantURL", $merchantURL);
			  $tpl->assign("couponlist", $couponInfo);
			  $tpl->assign("specWord", $specWord);

              fwrite($f,$tpl->getTemplateContents());
              fclose($f);
              $result .= "MERCHANT: ".$this->get("Name")." - 'Where enter code' updated<br>";
         }
         return $result;
      }

	  function getMerchantList($filter=""){
	  	$sql = "SELECT * FROM Merchant WHERE isActive = 1 ".($filter != "" ? " AND ".$filter : "")." ORDER BY NameURL";
		$this->MerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->MerchantList;
	  }

	  function getSelect($merList,$key_field,$name_field,$select_field =0,$key_list =array(), $maxlen =0){
         $result = "";
         if (sizeof($key_list) == 0){
            if (Count($merList) > 0){
               reset($merList);
               if (!is_array($select_field)){
                  while (list($key,$oItem) = @each($merList)){
                     $result .= "<OPTION VALUE=\"".$oItem[$key_field]."\"".($oItem[$key_field]==$select_field ? " SELECTED" : "").($oItem["isGray"]==1?" class=\"nullcoupon\"":"").">".($maxlen > 0 ? substr($oItem[$name_field],0,$maxlen-1).(strlen($oItem[$name_field]) > $maxlen ? "..." : "") : $oItem[$name_field])."</OPTION>";
                  }
               }
               else{
               }
               reset($merList);
            }
         }
         else{
            if ( Count($merList) > 0 ){
               reset($merList);
               if ( !is_array($select_field) ){
                  while ( list($key,$oItem) = @each($merList) ){
                     if ( in_array($oItem[$key_field],$key_list) ){
                        $result .= "<OPTION VALUE=\"".$oItem[$key_field]."\"".($oItem[$key_field]==$select_field ? " SELECTED" : "").($oItem["isGray"]==1?" class=\"nullcoupon\"":"").">".($maxlen > 0 ? substr($oItem[$name_field],0,$maxlen-1).(strlen($oItem[$name_field]) > $maxlen ? "..." : "") : $oItem[$name_field])."</OPTION>";
                     }
                  }
               }
               reset($merList);
            }
         }
		 //$result = iconv('UTF-8','GB2312',$result);
         return $result;
      }

	  function setPage($merList,$page_num,$page_size =__PAGE_SIZE){
         if (count($merList) > 0 && $page_num > 0){
            reset($merList);
            $this->ListCurPage = $page_num;
            $this->ListPageSize = $page_size;
//            $num_item   = ($page_num * $this->ListPageSize - $this->ListPageSize + 1);
//            if ($num_item <= count($merList)){
//               for ($i = 1; $i < $num_item; $i++){
//                  @each($merList);
//               }
//            }
         }
         else{
            $this->ListPageSize = $page_size;
            $this->ListCurPage  = ($page_num == 0) ? $this->ListCurPage : $page_num;
         }
         $this->ListPagePos = 0;
      }

	  function get_total(){
	  	 $sql = "SELECT COUNT(*) cnt FROM Merchant";
		 $rs = DBQuery::instance()->getOne($sql);
         return $rs;
      }

	  function getSpInfo($id){
			$sql = "SELECT MerchantInfo FROM MerchantSpInfo WHERE MerchantId=$id";
		 $rs = DBQuery::instance()->getOne($sql);
         return $rs;
	  }

	  function getMerchantAddress($id,$cid,$type,$area){
		  if($cid==0){
				$sql = "SELECT * FROM MerchantAdress WHERE Merchant_=$id ORDER BY rand() limit 10";
		  }else{
				$sql = "SELECT * FROM MerchantAdress WHERE Merchant_=$id AND City=$cid ORDER BY rand() limit 10";
		  }
		  if($type==1){
				 if($cid==0){
						$sql = "SELECT * FROM MerchantAdress WHERE Merchant_=$id ORDER BY rand()";
				  }else{
					   if($area==0){
						$sql = "SELECT * FROM MerchantAdress WHERE Merchant_=$id AND City=$cid ORDER BY rand()";
					  }else{
							$sql = "SELECT * FROM MerchantAdress WHERE Merchant_=$id AND City=$cid AND Area=$area ORDER BY rand()";
					  }

				  }
		  }
		 $rs = DBQuery::instance()->executeQuery($sql);
         return $rs;
	  }

	  function getMerchantArea($id,$cid){

		$sql = "SELECT distinct(AreaName),Area FROM MerchantAdress WHERE Merchant_='$id' AND City='$cid'";
		 $rs = DBQuery::instance()->executeQuery($sql);
         return $rs;
	  }

      function get_active(){
	  	 $sql = "SELECT COUNT(*) cnt FROM Merchant WHERE isActive=1";
		 $rs = DBQuery::instance()->getOne($sql);
         return $rs;
      }

      function LastGenerate($dt){
	     $sql= "UPDATE Merchant SET LastDate=(LastDate),LastGenerate='".$dt."'";
         DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }

      function need_update(&$merchants){
	  	 $sql = "SELECT COUNT(*) cnt FROM Merchant WHERE LastDate>LastGenerate";
		 $updatemerchant_cnt = DBQuery::instance()->getOne($sql);
         $merchants = array();
         if ( $updatemerchant_cnt > 0 ){
		    $sql = "SELECT Merchant_ FROM Merchant WHERE isActive = 1 AND LastDate>LastGenerate";
			$rs = DBQuery::instance()->executeQuery($sql);
			for($i=0; $i<count($rs); $i++) {
				$merchants[$rs[$i]["Merchant_"]] = $rs[$i]["Merchant_"];
			}
         }
      }

      function LastDate(){
         return time();
      }

	  function getActiveMerchantList(){
	  	$sql = "SELECT * FROM Merchant WHERE isActive=1 ORDER BY Name";
		$this->ActiveMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->ActiveMerchantList;
	  }

	  function getImportantMerchantList($merID=0){
	  	if($merID > 0) {
	  		$sql = "SELECT * FROM Merchant WHERE (isActive=1 AND isPremium = 1) OR (Merchant_ = $merID) ORDER BY NameURL";
		} else {
			$sql = "SELECT * FROM Merchant WHERE isActive=1 AND isPremium = 1 ORDER BY NameURL";
		}
		$this->ActiveMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->ActiveMerchantList;
	  }

	  function getFeaturedMerchantList($category){
	  	$sql = "SELECT m.* FROM Merchant m, Coupon c, CoupCat t WHERE M.isActive = 1 " .
		       "AND t.Category_=$category AND c.Coupon_=t.Coupon_ AND c.Merchant_=m.Merchant_";
		$this->FeaturedMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->FeaturedMerchantList;
	  }

	  function getDropDownMerchantList(){
	  	$sql = "SELECT DISTINCT m.*, CONCAT(m.Name,' (',COUNT(IF(p.isActive=1 AND " .
		       "(p.ExpireDate>=CURDATE() OR p.ExpireDate='0000-00-00') AND " .
			   "p.StartDate<=CURDATE(),1,NULL)),')') CName, IF(COUNT(IF(p.isActive=1 AND " .
			   "(p.ExpireDate>=CURDATE() OR p.ExpireDate='0000-00-00') AND " .
			   "p.StartDate<=CURDATE(),1,NULL))>0,0,1) isGray " .
			   "FROM Merchant m LEFT OUTER JOIN Coupon p ON m.Merchant_=p.Merchant_ " .
			   "WHERE m.isActive=1 GROUP BY m.Merchant_ ORDER BY m.NameURL";
		$this->DropDownMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->DropDownMerchantList;
	  }

	  function getAllMerchantList($orderby="NameURL"){
	  	/*$sql = " SELECT m.Merchant_,m.Name,m.URL,m.Logo,m.NameURL,m.isFree,m.isShow,".
			   " COUNT(IF(p.isActive=1 AND (p.ExpireDate>=CURDATE() OR " .
		       " p.ExpireDate='0000-00-00') AND p.StartDate<=CURDATE(),1,NULL)) AS CouponCount " .
			   " FROM Merchant m LEFT OUTER JOIN Coupon p ON m.Merchant_=p.Merchant_ " .
			   " WHERE m.isActive=1 AND m.Merchant_ <> 0 AND m.isShow=1 ".
			   " GROUP BY m.Merchant_ ,m.Name,m.Logo,m.NameURL,m.isFree,m.isShow HAVING CouponCount > 0 ".
			   " ORDER BY m.".$orderby;
        */
		//优化语句
		$sql = "SELECT m.Merchant_,m.Name,m.URL,m.Logo,m.NameURL,m.isFree,".
			   "m.isShow, COUNT(m.Merchant_) AS CouponCount ".
			   "FROM Merchant m ,Coupon p ".
			   "WHERE (p.ExpireDate>=CURDATE() OR  p.ExpireDate='0000-00-00') AND p.StartDate<=CURDATE() ".
			   "AND p.isActive=1 AND m.Merchant_=p.Merchant_ AND p.CouponType!=9 and m.isActive=1 AND ".
			   "m.Merchant_ <> 0 AND m.isShow=1 ".
			   " GROUP BY m.Merchant_ ,m.Name,m.Logo,m.NameURL,m.isFree,m.isShow  ORDER BY m.".$orderby;
		$this->AllMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->AllMerchantList;
	  }

	  function getClickMerchantList($bd,$ed){
	  	$sql = "SELECT m.*, SUM(Visitor) SumClick FROM Merchant m " .
		       "LEFT JOIN SMerchant s ON (m.Merchant_=s.Merchant_ AND " .
			   "s.Dat>='".to_mysql_date($bd)."' AND s.Dat<='".to_mysql_date($ed)."') " .
			   "WHERE m.isActive=1 GROUP BY m.Merchant_ ORDER BY m.Name";
		$this->ClickMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->ClickMerchantList;
	  }


	  function getEditMerchantList($filter ="",$order="",$limitStart=0,$pageSize=__PAGE_SIZE){
	    if(!$order) {
			$order = "Name";
		}
	  	$sql = "SELECT m.*, COUNT(DISTINCT p.Coupon_) Coupons FROM Merchant m " .
		       "LEFT JOIN Coupon p ON m.Merchant_=p.Merchant_ ".
			   ($filter != "" ? " WHERE 1 = 1 AND ".$filter : "").
			   " GROUP BY m.Merchant_ ORDER BY m.$order";
		$sql .= " LIMIT $limitStart,$pageSize";
		$this->EditMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->EditMerchantList;
	  }

	  function getEditMerchantListCount($filter =""){
	  	$sql = "SELECT COUNT(DISTINCT m.Merchant_) FROM Merchant m " .
		       "LEFT JOIN Coupon p ON m.Merchant_=p.Merchant_ ".
			   ($filter != "" ? " WHERE 1 = 1 AND ".$filter : "").
			   " ORDER BY m.Name";
		return DBQuery::instance()->getOne($sql);
	  }

	  function getCouponMerchantList(){
	  	$sql = "SELECT m.*, COUNT(w.Coupon_) Coupons FROM Coupon p " .
		       "RIGHT JOIN Merchant m USING(Merchant_) LEFT JOIN Wallet w USING(Coupon_) " .
			   "WHERE m.isActive = 1 GROUP BY m.Merchant_ ORDER BY Coupons";
		$this->CouponMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->CouponMerchantList;
	  }

	  function getForumMerchantList($search_str){
	  	$sql = "SELECT m.* FROM Merchant m, TopicMer tm WHERE m.isFrame=1 AND m.isActive=1 AND " .
		       "m.merchant_=tm.merchant_ AND m.isFrame=1 AND tm.topic_id='".$search_str."'";
		$this->ForumMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->ForumMerchantList;
	  }

	  function getValidMerchantList(){
	  	$sql = "SELECT Merchant_,Name,MetaTitle,MetaKeywords,MetaDescription,URL,Descript,MerTemplateID,Union_,Headline," .
		       "Logo,OldLogo,NameURL,isAdSenseCode FROM Merchant WHERE isActive=1 AND isShow = 1 ORDER BY Name";
		$this->ValidMerchantList = DBQuery::instance()->executeQuery($sql);
		return $this->ValidMerchantList;
	  }

	  function updateValidMerchant() {
	    //update coupon endtime before half one year
		$sql = "update Coupon set ExpireDate = '1900-1-1' where ExpireDate = '0000-00-00' and StartDate < DATE_SUB(CURDATE(),INTERVAL 6 month)";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  	//init
		$sql = "UPDATE Merchant SET isActive = 0,isShow=0";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  	$sql = "SELECT m.Merchant_, COUNT(DISTINCT p.Coupon_) Coupons FROM Merchant m " .
		       "LEFT JOIN Coupon p ON m.Merchant_=p.Merchant_ ".
			   "WHERE p.isActive=1 AND (p.ExpireDate >= CURDATE() OR p.ExpireDate='0000-00-00') AND p.StartDate <= CURDATE() " .
			   "GROUP BY m.Merchant_";

		$rs = DBQuery::instance()->executeQuery($sql);

		for($i=0;$i<count($rs);$i++){
			$id = $rs[$i]['Merchant_'];
			$count = $rs[$i]["Coupons"];
			if($count >= 1) {
				$isShow = "";
				if($count > 1) {
					$isShow = ",isShow=1";
				} else {
					$isShow = ",isShow=1";
				}
				$sql = "UPDATE Merchant SET isActive=1".$isShow." WHERE Merchant_='$id'";
			}
			DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		}
		//FOR NOT FREE MERCHANT
		$sql = "UPDATE Merchant SET isActive = 1,isShow=1 WHERE isFree = 0";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);

		$sql = "UPDATE Merchant SET isActive = 0,isShow=0 WHERE Merchant_ = 0";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);

		$sql = "UPDATE Merchant SET isActive = 1,isShow=1 WHERE Merchant_ IN ('899','1015','898')";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  }

	  function updateSpec($field_name) {
	  	$value = $this->get($field_name);
		$merchantID = $this->get("Merchant_");
		$sql = "UPDATE Merchant SET $field_name = $value WHERE Merchant_ = $merchantID";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  }

	  function checked($field_name) {
	  	if($this->get($field_name) == 1) {
			return "CHECKED";
    	} else {
			return "";
		}
	  }

	  function selected($field, $value2){
         if ( $this->get($field) == $value2 ){
            return "SELECTED";
         }
      }

	  function uniqstr($name){
         if ( strlen($this->get($name)) == 0 ) return true;
		 $sql = "SELECT COUNT(*) cnt FROM ".$this->ClassName." WHERE ".$name."='".$this->get($name)."' AND ".$this->Key."<> '".$this->get("Merchant_") ."'";
         $rs = DBQuery::instance()->getOne($sql);
         if ( $rs > 0 ){
            return false;
         }
         else{
            return true;
         }
      }

	  function getPageList($num){
         $result  = "";
         $page_cnt= (floor($num/$this->ListPageSize)+(($num%$this->ListPageSize)>0));
         for ($i=1; $i<=$page_cnt;$i++){
            $result .= "<option value=\"$i\"".($i==$this->ListCurPage ? " selected" : "").">$i";
         }
         return $result;
      }

	  function getMerchantMore($merchantid){
		  $sql = "SELECT id,url,title FROM MerchantMore WHERE merchantid=$merchantid";
		  $rs = DBQuery::instance()->executeQuery($sql);
		  return $rs;
      }

	  function addMerchantMore($mid,$title,$url){
		  $sql = "INSERT INTO MerchantMore(id,url,merchantid,title) VALUES ('','$url','$mid','$title')";
		  $rs = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		  return $rs;
	  }

	  function deleteMerchantMore($id){
		$sql = "DELETE FROM MerchantMore WHERE id=$id";
		  $rs = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		  return $rs;
	  }

      /**
	   * @功    能 : 获取某一个栏目下的商家列表
	   * @开发人员 : menny
	   * @开发时间 : June 10,2008
	   */
	  function getCategoryMerchantList($catid,$limit = 15) {
		  $result = array();
		  if(!$catid) {
			  return $result;
		  }
		  $sql = "SELECT p.Merchant_,m.Name,m.NameURL,m.isFree,COUNT(p.Coupon_) AS CouponCount ".
			     " FROM Merchant m left join (Coupon p,CoupCat c) ON m.Merchant_=p.Merchant_  ".
			     "WHERE (p.ExpireDate>=CURDATE() OR p.ExpireDate='0000-00-00') ".
			     "AND p.StartDate<=CURDATE() AND Category_=$catid AND p.Coupon_=c.Coupon_  ".
			     " AND p.isActive=1 AND m.isActive=1 AND m.Merchant_ <> 0 AND m.isShow=1 ".
			     " GROUP BY m.Merchant_ ORDER BY m.isFree ASC,CouponCount DESC limit $limit";
		  $result = DBQuery::instance()->executeQuery($sql);
		  $count = count($result);
		  for($i=0;$i<$count;$i++) {
			  $result[$i]["MerURL"] = "/Me-".$result[$i]["NameURL"].
				                      "--Mi-".$result[$i]["Merchant_"].".html";
		  }
		  return $result;

	  }
   }
}
?>
