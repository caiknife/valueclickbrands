<?php
require_once(__INCLUDE_ROOT."lib/classes/class.thumb.php");

if ( !defined("CLASS_COUPON_PHP") ){
   define("CLASS_COUPON_PHP","YES");

   

   class Coupon {
      var $ClassName = "Coupon";
      var $Key       = "Coupon_";
      var $Categories= array();
	  var $CouponInfo =array();
	  var $CouponList = array();
	  var $FeaturedCouponList = array();
	  var $NewCouponList = array();
	  var $FreeShippingCouponList = array();
	  var $CategoryCouponList = array();
	  var $MerchantCouponList = array();
	  var $CustomerCouponList = array();
	  var $ActiveCouponList = array();
	  var $mActiveCouponList = array();
	  var $ClickCouponList = array();
	  var $ExpiringCouponList = array();
	  var $HotCouponList = array();
	  var $CategoryCouponListFeaturedMerchant = array();
	  var $CategoryCouponListNotFeaturedMerchant = array();
	  var $NotFreeCouponList = array();
	  
      var $uniqCode  = '';

      function Coupon($id =-1){
		  if ( $id > 0 ){
		 	$sql = "SELECT GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,
					Coupon.*,
					Category.NameURL AS cnameurl,
						Category.Category_,
				p.digest,p.tid,p.fid,p.replies, m.Name MerchantName,m.name1, m.NameURL MerchantNameURL, m.URL MerchantURL,m.isShow Mshow,p.author,p.authorid ";
					
			$sql .= "FROM Coupon inner join pw_threads p on ( p.dhbid = Coupon.Coupon_) 
					 left join Merchant m on (Coupon.Merchant_= m.Merchant_) 
					 left join CoupCat on (CoupCat.Coupon_=Coupon.Coupon_) 
					 left join Category on (Category.Category_=CoupCat.Category_) 
					 LEFT join CouponTag ON(CouponTag.couponid = Coupon.Coupon_) 
					 LEFT join Tag ON (Tag.id = CouponTag.tagid) 
					 WHERE Coupon.Coupon_= '$id' AND Coupon.IsActive=1 GROUP BY Coupon.Coupon_";
            $this->CouponInfo = DBQuery::instance()->getRow($sql);
            //$this->loadCategory();
         }
      }

      function isCoupon(){
         if ($this->get('isCoupon') == 1 ) return true;
         else return false;
      }

	  function getindexcouponlist($fidlist){
	  	 $sql = "select Merchant.name1,Merchant.NameURL,pw_threads.*,Coupon.* 
				 FROM pw_threads 
				 inner JOIN Coupon ON ( pw_threads.dhbid = Coupon.Coupon_ ) 
				 inner JOIN Merchant ON ( Merchant.Merchant_ = Coupon.Merchant_ ) 
				 WHERE fid IN ($fidlist) AND topped > 0 
				 ORDER BY postdate DESC";
		 $r = DBQuery::instance()->executeQuery($sql);
		 $newr = array();
		 foreach($r as $key=>$value){
			 $newk = $value['fid'];
			 //echo $newk;
			 $value['Descript'] = Utilities::cutString($value['Descript'],27);
			 $newr[$newk][] = $value;
		 }
		 //print_r($newr);
		 return $newr;
      }

      function set($name,$value ='',$type =0){
	  	$this->CouponInfo[$name] = $value;
         //if ( $name == 'Code' && $type == 0 ){
//            foreach ( $value as $key => $val ){
//               if ( empty($val) || strlen(trim($val)) == 0 ) continue;
//			   $sql = "INSERT INTO Code (Coupon_,Name,isUsed) VALUES(".$this->get('Coupon_').',\''.trim($val)."\',0)";
//               DBQuery::instance()->executeUpdate($sql);
//            }
//         }
//         else{
//            $this->CouponInfo[$name] = $value;
//         }
      }

      function get($name){
         $result =$this->CouponInfo[$name];
         if(strlen($result)==0){
            if(ereg("^Merchant(.+)" , $name , $merfield_r) && $merfield_r[1]!='_'){
			   $sql = "SELECT ".$merfield_r[1]." FROM Merchant WHERE Merchant_=".$this->get("Merchant_");
               $result = DBQuery::instance()->getOne($sql);
               $this->set($name , $result);
            }
         }
         return $result;
      }
	  
	  function getCouponInfo(){
          return $this->CouponInfo;
      }
	  
	  function updateSpec($field_name) {
	  	$value = $this->get($field_name);
		$couponID = $this->get("Coupon_");
		$sql = "UPDATE Coupon SET $field_name = $value WHERE Coupon_ = '$couponID'";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  }	  

      function getURL(){
         if ( strcmp(trim(strtoupper($this->get("URL"))),"HTTP://")==0 ||
              strcmp(trim(strtoupper($this->get("URL"))),"HTTP://NONE")==0 ||
              strcmp(trim(strtoupper($this->get("URL"))),"NONE")==0 ||
              strlen($this->get("URL"))==0 ){
            return $this->get("MerchantURL");
         }
         else{
            return $this->get("URL");
         }
      }


      function emptyURL(){
         if ( strcmp(trim(strtoupper($this->get("URL"))),"HTTP://")==0 ||
              strcmp(trim(strtoupper($this->get("URL"))),"HTTP://NONE")==0 ||
              strcmp(trim(strtoupper($this->get("URL"))),"NONE")==0 ||
              strlen($this->get("URL"))==0 ){
            return 1;
         }
         else{
            return 0;
         }
      }

      function verified($fl){
         if ( $fl == "yes" ){
		 	$sql = "UPDATE VerifyCoupon SET YesCount=(YesCount+1) WHERE Dat=CURDATE() AND Coupon_=".$this->get("Coupon_");
			$back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            if ( $back == 0 ){
			   $sql = "INSERT INTO VerifyCoupon (Coupon_,Dat,YesCount,NoCount) VALUES(".$this->get("Coupon_").",CURDATE(),1,0)";
			   DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            }
         }
         else{
		 	$sql = "UPDATE VerifyCoupon SET NoCount=(NoCount+1) WHERE Dat=CURDATE() AND Coupon_=".$this->get("Coupon_");
			$back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            if ( $back == 0 ){
			   $sql = "INSERT INTO VerifyCoupon (Coupon_,Dat,YesCount,NoCount) VALUES(".$this->get("Coupon_").",CURDATE(),0,1)";
			   DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            }
         }
      }

      function canShow(){
         if ( $this->get("isActive") == 0 ){
            return 0;
         }
         return 1;
      }
	  
	  function getNextID($name) {
	  	$sql = "SELECT MAX(Coupon_) FROM Coupon";
		$rs = DBQuery::instance()->getOne($sql);
		$newID = $rs+1;
		$sql = "UPDATE Sequence SET ID = $newID WHERE Name = '$name'";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		return $newID;
	  }	
	  
	  function updateUserCouponPic($couponid){
	  	if(empty($couponid)) return;
	  	$sql = "UPDATE Coupon SET ImageDownload=1 WHERE Coupon_=$couponid limit 1";
	  	return DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  }

	  function addUserCoupon($couponlist){
	  	$sql = "SELECT MAX(Coupon_) FROM Coupon";
		$rs = DBQuery::instance()->getOne($sql);
		$newID = $rs+1;
		
		$descript = trim($couponlist['CouponName']);
		$expiredate = $couponlist['End'];
		$startdate = $couponlist['Start'];
		$userid = $couponlist['AddUser'];
		$tagname = $couponlist['TagName'];
		if($couponlist['MerName']){
			$detail = trim($couponlist['Description']."(".$couponlist['MerName'].")");
		}else{
			$detail = trim($couponlist['Description']);
		}
		$cityid = $couponlist['CityID'];
		
		$categoryid = (int)$couponlist['Category_'];
		
		$sql = "SELECT CityName FROM City WHERE CityID='$cityid'";
		$cityname = DBQuery::instance()->getOne($sql);
		
		$sql = "INSERT INTO Coupon ";
		$sql .= "(`Coupon_`,`Descript`,`ExpireDate`,`StartDate`,`isActive`,`AddDate`,`Detail`,`City`,`ImageDownload`,`isFree`,`CityID`,`CouponType`) ";
		$sql .= "VALUES('$newID','".DBQuery::filter($descript)."','".DBQuery::filter($expiredate)."','".DBQuery::filter($startdate)."','0',NOW(),'".addslashes($detail)."','$cityname','0','1','$cityid','0') ";
	  	$r =  DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
	  	
	  	
	  	$sql = "INSERT INTO CoupCat (`Coupon_`,`Category_`) VALUES ('$newID','$categoryid')";
	  	$r =  DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
	  	
	  	
	  	
	  	if(empty($userid)){    //random user
			$sql = "select * from pw_members WHERE oicq='mezi' ORDER BY RAND() limit 1";
			$us = DBQuery::instance()->getRow($sql);
			$author = $us['username'];
			$authorid = $us['uid'];
		}else{
			$sql = "select username from pw_members WHERE uid='".(int)$userid."'";
			$author = DBQuery::instance()->getOne($sql);
			$authorid = $userid;
		}

		$sql = "INSERT INTO pw_threads (tid,fid,subject,ifcheck,author,authorid,dhbid) values ('','','".$newID."',1,'$author','$authorid','".$newID."')";
		$r = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql); 
		$tagname = strip_tags($tagname);
		$tagname = str_replace("；",",",$tagname);
        $tagname = str_replace("，",",",$tagname);
		$tagname = str_replace(";",",",$tagname);
		$tagname = str_replace(":",",",$tagname);
		$tagname = str_replace("。",",",$tagname);
		$tagname = str_replace(".",",",$tagname);
		$tagname = explode(',',$tagname);
		foreach ($tagname as $key=>$value){
			if(trim($value)=="") continue;
			$sql = "SELECT id FROM Tag WHERE tagname='$value'";
			$tagid = DBQuery::instance()->getOne($sql);
			if($tagid==""){
				$sql = "SELECT MAX(id) from Tag";
				$maxid = DBQuery::instance()->getOne($sql);
				$maxid = $maxid+1;
				$sql = "INSERT INTO Tag (id,tagname) VALUES ('$maxid','$value')";
				$re = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
				$tagid = $maxid;
			}
			$sql = "INSERT INTO CouponTag (id,tagid,couponid) VALUES ('','$tagid','$newID')";
			$re = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
		}
	  	
	  	return $newID;
	  }

	  function createdir($dir)
		{
			if(file_exists($dir) && is_dir($dir)){
				return 0;
			}
			else{
				mkdir ($dir,0777);
				chmod ($dir, 0777);
				return 1;
			}
		}


      function loadCategory(){
		 $sql = "SELECT Category.* FROM Category, CoupCat WHERE CoupCat.Coupon_=".$this->CouponInfo["Coupon_"].
		        " AND CoupCat.Category_=Category.Category_ ORDER BY SitemapPriority DESC";
		 $this->Categories = DBQuery::instance()->executeQuery($sql);
      }

      function getCategoryArray(){
         $result = array(0);
		 for($i=0; $i<count($this->Categories); $i++) {
		 	array_push($result,$this->Categories[$i]["Category_"]);
		 }
         return $result;
      }

      function getCategoryString(){
	  	 $oCategory = new Category();
         $categoryList = $oCategory->getCategoryList("SitemapPriority");
		 $result = '';
		 if(count($categoryList) >0) {
			 $tmpArray = $this->getCategoryArray();
			 for($i=0; $i<count($categoryList); $i++) {
				if ( in_array($categoryList[$i]["Category_"],$tmpArray) ){
					$result .= $categoryList[$i]["Name"].";";
				}
			 }
		 }
         return $result;
      }

      function getCategoryCount(){
         return sizeof($this->getCategoryArray());
      }

      function setCategoryArray($category_array){
	  	 $sql = "DELETE FROM CoupCat WHERE Coupon_=".$this->CouponInfo["Coupon_"];
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         while ( list($key,$val) = @each($category_array) ){
            if ( $val > 0 ){
			   $sql = "INSERT INTO CoupCat (Coupon_,Category_) VALUES(".$this->CouponInfo["Coupon_"].",".$val.")";
  			   DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            }
         }
      }
	  
	  function get_total(){
	  	 $sql = "SELECT COUNT(*) cnt FROM Coupon";
		 $rs = DBQuery::instance()->getOne($sql);
         return $rs;
      }

      function get_active(){
	  	 $sql = "SELECT COUNT(*) cnt FROM Coupon WHERE isActive=1";
		 $rs = DBQuery::instance()->getOne($sql);
         return $rs;
      }

      function get_expired(){
	  	 $sql = "SELECT COUNT(*) cnt FROM Coupon WHERE ExpireDate<CURDATE() AND ExpireDate<>'0000-00-00'";
		 $rs = DBQuery::instance()->getOne($sql);
         return $rs;
      }

      function get_not_started(){
	  	 $sql = "SELECT COUNT(*) cnt FROM Coupon WHERE StartDate>CURDATE()";
		 $rs = DBQuery::instance()->getOne($sql);
         return $rs;
      }

      function LastDate(){
         return time();
      }
	  
	  function getCouponList($filter=""){
		  throw new Exception("this function is not userd forever");
      }
	  
	  function getNewCouponList($days =0){
			$sql = "SELECT DISTINCT c.Coupon_,c.Merchant_,c.AddDate,c.Descript,c.Amount,c.City," .
		           "c.ExpireDate,c.isFree,m.Name,m.NameURL,m.isShow " .
		          "FROM Coupon c, Merchant m " .
		          "WHERE c.Merchant_=m.Merchant_ AND m.isActive=1 AND c.isActive=1 AND " .
				  "(c.ExpireDate >= CURDATE() OR c.ExpireDate='0000-00-00') AND " .
				  "DATE_FORMAT(AddDate,'%Y-%m-%d')=DATE_ADD(CURDATE(), INTERVAL '-".$days."' DAY) " .
				  "ORDER BY m.isPremium DESC,m.isBold DESC,Rating DESC, m.Merchant_ DESC limit 0,500";
		   $this->NewCouponList = DBQuery::instance()->executeQuery($sql);
		   return $this->NewCouponList;
      }
	  
	  function getNewCouponListForCoupon(){
		   $sql = "SELECT DISTINCT c.Coupon_,c.Merchant_,c.AddDate,c.Descript " .
		          "FROM Coupon c " .
		          "WHERE c.isActive=1 AND " .
				  "(c.ExpireDate >= CURDATE() OR c.ExpireDate='0000-00-00') AND StartDate <= CURDATE() AND " .
				  "c.isFree = 0 AND LENGTH(c.Descript) < 40 " .
				  "ORDER BY c.AddDate DESC limit 0,20";
		   return DBQuery::instance()->executeQuery($sql);
      }
	  
	  function getExpireCouponList($days =0){
		   $sql = "SELECT DISTINCT c.Coupon_,c.Merchant_,c.AddDate,c.Descript,c.Amount,c.City," .
		          "c.ExpireDate,c.isFree,m.Name,m.NameURL,m.isShow " .
		          "FROM Coupon c, Merchant m " .
		          "WHERE c.Merchant_=m.Merchant_ AND m.isActive=1 AND c.isActive=1 AND " .
				  "(c.ExpireDate >= CURDATE() OR c.ExpireDate='0000-00-00') AND StartDate <= CURDATE() AND " .
				  "DATE_FORMAT(ExpireDate,'%Y-%m-%d')=DATE_ADD(CURDATE(), INTERVAL '+".$days."' DAY) " .
				  "ORDER BY m.isPremium DESC,m.isBold DESC,Rating DESC, m.Merchant_ DESC";
		   $this->ExpireCouponList = DBQuery::instance()->executeQuery($sql);
		   return $this->ExpireCouponList;
      }
	  
	  function getFreeShippingCouponList(){
		   $sql = "SELECT DISTINCT  c.Coupon_,c.Merchant_,c.AddDate,c.Descript,c.Amount,c.ExpireDate,c.City," .
		          "c.isFree,m.Name,m.NameURL,m.isShow " .
		          "FROM Coupon c, Merchant m " .
		          "WHERE c.Merchant_=m.Merchant_ AND m.isActive=1 AND c.isActive=1 AND " .
				  "(c.ExpireDate >= CURDATE() OR c.ExpireDate='0000-00-00') AND StartDate <= CURDATE() AND isFreeShipping=1 " .
				  "ORDER BY m.isPremium DESC,m.isBold DESC,Rating DESC,m.Merchant_ DESC";
		   $this->FreeShippingCouponList = DBQuery::instance()->executeQuery($sql);
		   return $this->FreeShippingCouponList;
      }
	  
	  function getMerchantCouponList($merchant,$page){
		$start = ($page-1) * 10;
		if($start<0) $start = 0;
		   $sql = "SELECT GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,Coupon.isFree,Coupon.Coupon_,Coupon.Descript,Coupon.Detail,Coupon.City,Coupon.StartDate,Coupon.ExpireDate,Coupon.ImageDownload,pw_threads.*,Merchant.NameURL,CoupCat.Category_,Category.NameURL AS cnameurl,IF(Coupon.ExpireDate >= CURDATE() || Coupon.ExpireDate='0000-00-00',1,0) isExpire,Category.Name 
				   FROM Coupon 
				   INNER JOIN pw_threads ON (pw_threads.dhbid = Coupon.Coupon_) 
				   INNER JOIN Merchant ON (Merchant.Merchant_=Coupon.Merchant_) 
				   INNER JOIN CoupCat ON (CoupCat.Coupon_=Coupon.Coupon_) 
				   INNER JOIN Category ON(Category.Category_=CoupCat.Category_) 
				   LEFT join CouponTag ON(CouponTag.couponid = Coupon.Coupon_) 
				   LEFT join Tag ON (Tag.id = CouponTag.tagid) 
				   INNER JOIN CoupCat c ON c.Coupon_=Coupon.Coupon_ 
				   WHERE Coupon.isActive=1 AND Coupon.Merchant_='$merchant' AND 
					 (Coupon.ExpireDate='0000-00-00' OR Coupon.ExpireDate >= CURDATE()) GROUP BY Coupon.Coupon_ 
				   ORDER BY isExpire DESC, pw_threads.digest DESC limit $start,10";
		   return DBQuery::instance()->executeQuery($sql);  
      }
      
      function getMerchantCouponCountList($merchant){
		   $sql = "SELECT count(distinct(Coupon.Coupon_)) FROM Coupon 
				   INNER JOIN pw_threads ON (pw_threads.dhbid = Coupon.Coupon_) 
				   INNER JOIN CoupCat ON (CoupCat.Coupon_=Coupon.Coupon_) 
				   INNER JOIN CoupCat c ON c.Coupon_=Coupon.Coupon_ 
				   WHERE (Coupon.ExpireDate='0000-00-00' OR Coupon.ExpireDate >= CURDATE()) AND 
					 Coupon.isActive=1 AND Coupon.Merchant_='$merchant'";
		   return DBQuery::instance()->getOne($sql);
		   
      }
	  
	  function getCustomerCouponList($customer){
		   $sql = "SELECT DISTINCT p.*, m.Name MerchantName FROM Coupon p, Wallet w, Merchant m WHERE w.Customer_=".
		          $customer." AND w.Coupon_=p.Coupon_ AND p.Merchant_=m.Merchant_ AND p.isActive=1 AND " .
				  "(p.ExpireDate >= CURDATE() OR p.ExpireDate='0000-00-00')";
		   $this->CustomerCouponList = DBQuery::instance()->executeQuery($sql);
		   return $this->CustomerCouponList;
      }
	  
	  function getActiveCouponList($bd,$ed){
		   $sql = "SELECT p.*, m.Name MerchantName, SUM(s.YesCount) SumYes, SUM(s.NoCount) SumNo " .
		          "FROM Coupon p, Merchant m , VerifyCoupon s WHERE s.Coupon_=p.Coupon_ AND " .
				  "p.Merchant_=m.Merchant_ AND p.isActive=1 AND m.isActive=1 AND " .
				  "(p.ExpireDate>=CURDATE() || p.ExpireDate='0000-00-00') AND p.StartDate<=CURDATE() AND " .
				  "s.Dat>='".to_mysql_date($bd)."' AND s.Dat<='".to_mysql_date($ed)."' GROUP BY p.Coupon_";
		   $this->ActiveCouponList = DBQuery::instance()->executeQuery($sql);
		   return $this->ActiveCouponList;
      }
	  
	  function getmActiveCouponList(){
		   $sql = "SELECT p.*, m.Name MerchantName FROM Coupon p, Merchant m " .
		          "WHERE p.Merchant_=m.Merchant_ AND p.isActive=1 AND m.isActive=1 AND " .
				  "(p.ExpireDate>=CURDATE() || p.ExpireDate='0000-00-00') AND p.StartDate<=CURDATE() GROUP BY p.Coupon_";
		   $this->mActiveCouponList = DBQuery::instance()->executeQuery($sql);
		   return $this->mActiveCouponList;
      }
	  
	  function getExpiringCouponList($days){
		   $sql = "SELECT p.*, m.Name,m.isShow MerchantName FROM Merchant m, Coupon p " .
		          "WHERE p.Merchant_=m.Merchant_ AND m.isActive=1 AND p.isActive=1 AND " .
				  "p.ExpireDate>=CURDATE() AND p.ExpireDate<=DATE_ADD(CURDATE(),INTERVAL ".
				  $days." DAY) ORDER BY p.Descript";
		   $this->ExpiringCouponList = DBQuery::instance()->executeQuery($sql);
		   return $this->ExpiringCouponList;
      }
	  
	  function getHotCouponList(){
		   $sql = "SELECT sc.Coupon_, SUM(sc.Visitor) SVisitor,m.Merchant_," .
		          "m.Name,m.NameURL,c.Amount,c.City,c.isFree,c.Descript,c.ExpireDate,m.isShow " .
		          "FROM SCoupon sc, Merchant m, Coupon c " .
		          "WHERE c.Coupon_=sc.Coupon_ AND c.Merchant_=m.Merchant_ AND " .
				  "sc.Dat>='2007-1-1' AND m.isActive=1 AND c.isActive=1 AND c.isFree = 0 AND " .
				  //"sc.Dat=DATE_ADD(curdate(), INTERVAL '-1' DAY) AND m.isActive=1 AND c.isActive=1 AND " .
				  "(c.ExpireDate>=CURDATE() OR c.ExpireDate='0000-00-00') AND StartDate<=CURDATE() " .
				  "GROUP BY sc.Coupon_ ORDER BY SVisitor DESC, m.Name ASC";
		   $this->HotCouponList = DBQuery::instance()->executeQuery($sql);
		   return $this->HotCouponList;
      }

	  function getRSSnewCouponList(){
		   $sql = "SELECT DISTINCT c.Coupon_,c.Merchant_,c.AddDate,c.Descript,Merchant.NameURL 
		   FROM Coupon c LEFT JOIN Merchant ON(c.Merchant_=Merchant.Merchant_)
		          WHERE c.isActive=1 AND 
				 (c.ExpireDate >= CURDATE() OR c.ExpireDate='0000-00-00') AND StartDate> CURDATE()-7 AND 
				  c.isFree = 0 AND LENGTH(c.Descript) < 40 
				  ORDER BY c.AddDate DESC";
		   return DBQuery::instance()->executeQuery($sql);
      }
	  
	  function getEditCouponListCount($filter =""){
	  	$sql = "SELECT COUNT(DISTINCT p.Coupon_) " .
			   "FROM Coupon p, Merchant m WHERE " .
			   "p.Merchant_=m.Merchant_".($filter != "" ? " AND ".$filter : "");
		return DBQuery::instance()->getOne($sql);
	  }
	  
	  function getPageList($num,$pageSize,$curPage){
         $result  = "";
         $page_cnt= (floor($num/$pageSize)+(($num%$pageSize)>0));
         for ($i=1; $i<=$page_cnt;$i++){
            $result .= "<option value=\"$i\"".($i==$curPage ? " selected" : "").">$i";
         }
         return $result;
      }

	  function updateCouponCityID(){
			$sql = "select * from City";
			$cityarray = DBQuery::instance()->executeQuery($sql);
			//print_r($cityarray);
			$sql = "select Coupon_,City from Coupon";
			$allcoupon = DBQuery::instance()->executeQuery($sql);
			foreach($allcoupon as $key=>$value){
				$c=-1;
				if($value['City']==""){
					$c=0;
				}elseif($value['City']=="全国" || $value['City']=="全国>>全国"){
					$c=0;
				}elseif($value['City']=="香港"){
					$c=999;
				}else{
					for($i=0;$i<count($cityarray);$i++){
						if(strpos($value['City'],$cityarray[$i]['CityName'])===false){
							
						}else{
						
							$c=$cityarray[$i]['CityID'];
						}
						
					}
					if($c==0){
						$c=999;
					}
				}

				/*

				$filename = __INCLUDE_ROOT."/images/map/".$value['Coupon_'].".gif";

				if (file_exists($filename)) {
					$sql = "UPDATE Coupon SET Hasmap=1 WHERE Coupon_=".$value['Coupon_'];
					$go = DBQuery::instance()->executeQuery($sql);
				} else {
				
				}
				*/

				//echo $c;
				//echo $value['City'];
				//echo "<BR>";
				if($c>-2){
					$sql = "UPDATE Coupon SET CityID=$c WHERE Coupon_=".$value['Coupon_'];
					//echo $sql;
					$go = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
				}
				//print_r($value);
				//echo "<BR>";
			}
	
	  }

	  function getuseraddcouponlist(){
		$sql = "select * from UserCoupon ORDER by ID DESC";
		$array = DBQuery::instance()->executeQuery($sql);
		return $array;
	  }

	  function getuseraddcoupondetial($id){
		$sql = "select * from UserCoupon WHERE ID='$id'";
		$array = DBQuery::instance()->getRow($sql);
		print_r($array);
		return $array;
	  }

	  function deluseraddcoupon($id){
		$sql = "delete from UserCoupon WHERE ID='$id'";
		$array = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
		//print_r($array);
		return $array;	

	  }
	  
	  function setdate($name,$value){
         if ( strtoupper($value) == "N/A" ){
            $value_new = '00/00/0000';
         }
         else if ( strtoupper($value) == "O/G" ){
            $value_new = '03/03/3333';
         }
         else{
            $value_new = $value;
         }
         return $this->set($name,to_mysql_date($value_new));
      }
      
      function getCouponNew($couponType){
      	$sql = "select c.NameUrl,c.Merchant_,c.name,c.name1,a.Descript,a.Coupon_ " .
      			"from Coupon a " .
      			"INNER JOIN CoupCat b ON (b.Coupon_=a.Coupon_) " .
      			"LEFT JOIN Merchant c ON (c.Merchant_=a.Merchant_) " .
      			"WHERE a.isActive=1 AND (a.ExpireDate='0000-00-00' OR a.ExpireDate>CURDATE()) AND b.Category_=$couponType AND a.CouponType=0 " .
      			"ORDER BY a.Coupon_ DESC limit 5";
      	return DBQuery::instance()->executeQuery($sql);
      	
      }

   }  
  
}
?>
