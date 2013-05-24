<?php
if ( !defined("CLASS_CATEGORY_PHP") ){
   define("CLASS_CATEGORY_PHP","YES");

   //require_once(__INCLUDE_ROOT."/lib/classes/class.Merchant.php");
	//require_once(__INCLUDE_ROOT."/lib/classes/class.Coupon.php");
   //require_once(__INCLUDE_ROOT."/lib/classes/class.Meta.php");
   require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
   if (!defined("__MALL_ID"))   define("__MALL_ID", 98);
   
   class Category {
      var $ClassName = "Category";
      var $Key       = "Category_";
	  var $CategoryInfo = array();
	  var $CategoryList = array();
      var $FeaturedMerchant   = array();
	  var $Coupons = 0;
	  var $Merchants = 0;
	  var $ID       = -1;       // key value

	
      function Category($id =-1){
	  	 if ( $id > 0 ){
	  	     //CategoryInfo
			 $sql = "SELECT * FROM Category WHERE Category_= $id";
			 $this->CategoryInfo = DBQuery::instance()->getRow($sql);
		 }
 
      }

	public function getCategoryKeywords(){
		$keywords = $this->CategoryInfo['Keywords'];
		$keywordsarray = explode(",",$keywords);
		shuffle($keywordsarray);
		return $keywordsarray[0];
	}

	public function getCategoryAllList(){
		$sql = "SELECT * FROM Category";
		$result = DBQuery::instance()->executeQuery($sql);
		return $result;
	}

	public function updateKeywords($cid,$keywords){
		$sql = "UPDATE Category SET Keywords = '$keywords' WHERE Category_ = $cid";
		$result = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
		return $result;
	}

      function loadFeatured(){
         if ($this->CategoryInfo["Category_"] > 0 ){
		 	$sql = "SELECT m.* FROM Merchant m INNER JOIN MerCat c ON m.Merchant_=c.Merchant_ " .
			       "WHERE c.isFeatured>0 AND c.Category_=".$this->CategoryInfo["Category_"]." ORDER BY c.isFeatured";
			$this->FeaturedMerchant = DBQuery::instance()->executeQuery($sql);
         }
      }

      function search_($name){
		  $sql = "SELECT * FROM Category WHERE Name REGEXP '$name' ORDER BY Name";
		  $rs = DBQuery::instance()->executeQuery($sql);
		  return $rs;
      }

      function isFound(){
         if ($this->CategoryInfo["Category_"] > 0 ){
            $this->countCoupon(1);
            if ($this->Coupons > 0 ) return true;
         }
      }


      function countCoupon($flag =0){
         if ( $flag == 0 ){
             $sql = "SELECT COUNT(Category_) coup FROM CoupCat WHERE Category_=".$this->CategoryInfo["Category_"];
			 $tmp1 = DBQuery::instance()->getOne($sql);
         }
         else{
		 	$sql = "SELECT COUNT(p.Coupon_) coup FROM Coupon p INNER JOIN CoupCat c " .
			       "ON c.Coupon_=p.Coupon_ WHERE c.Category_=".$this->CategoryInfo["Category_"].
				   " AND (p.ExpireDate='0000-00-00' OR p.ExpireDate>CURDATE()) AND p.StartDate<=CURDATE() AND p.isActive=1";
			$tmp1 = DBQuery::instance()->getOne($sql);
//			$sql = "SELECT COUNT(p.Coupon_) coup FROM Coupon p INNER JOIN MerCat c " .
//			       "ON c.Merchant_=p.Merchant_ WHERE c.Category_=".$this->CategoryInfo["Category_"].
//				   " AND (p.ExpireDate='0000-00-00' OR p.ExpireDate>CURDATE()) AND p.StartDate<=CURDATE() AND p.isActive=1");
//			$tmp2 = DBQuery::instance()->getOne($sql);
         }
		 $this->Coupons = $tmp1;
      }


      function countMerchant($flag =0){
         if ( $flag == 0 ){
		 	$sql = "SELECT COUNT(*) merch FROM MerCat WHERE Category_=".$this->CategoryInfo["Category_"];
            $tmp = DBQuery::instance()->getOne($sql);
         }
         else{
		 	$sql = "SELECT COUNT(*) merch FROM MerCat m, Merchant t WHERE m.Category_=".
					$this->CategoryInfo["Category_"]." AND m.Merchant_=t.Merchant_ AND t.isActive=1";
            $tmp = DBQuery::instance()->getOne($sql);
            
         }
		 $this->Merchants = $tmp;
      }
  
	  function update($params =array()){
         
      }
	  
	  function get($name){
         return $this->CategoryInfo[$name];
      }
	  
	  function set($name,$value){
         $this->CategoryInfo[$name] = $value;
      }
	  
	  function setAll($array){
         $this->CategoryInfo = $array;
      }
	  
	  function getNextID($name) {
	  	$sql = "SELECT ID FROM Sequence WHERE Name = '$name'";
		$rs = DBQuery::instance()->getOne($sql);
		$newID = $rs+1;
		$sql = "UPDATE Sequence SET ID = $newID WHERE Name = '$name'";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		return $newID;
	  }

      function MustUpdate(){
         return 0;
      }
	  
	  function getSelect($cateList,$key_field,$name_field,$select_field =0,$key_list =array(), $maxlen =0){
         $result = "";
         if (sizeof($key_list) == 0){
            if (Count($cateList) > 0){
               reset($cateList);
               if (!is_array($select_field)){
                  while (list($key,$oItem) = @each($cateList)){
                     $result .= "<OPTION VALUE=\"".$oItem[$key_field]."\"".($oItem[$key_field]==$select_field ? " SELECTED" : "").($oItem["isGray"]==1?" class=\"nullcoupon\"":"").">".($maxlen > 0 ? substr($oItem[$name_field],0,$maxlen-1).(strlen($oItem[$name_field]) > $maxlen ? "..." : "") : $oItem[$name_field])."</OPTION>";
                  }
               }
               else{
               }
               reset($cateList);
            }
         }
         else{
            if ( Count($cateList) > 0 ){
               reset($cateList);
               if ( !is_array($select_field) ){
                  while ( list($key,$oItem) = @each($cateList) ){
                     if ( in_array($oItem[$key_field],$key_list) ){
                        $result .= "<OPTION VALUE=\"".$oItem[$key_field]."\"".($oItem[$key_field]==$select_field ? " SELECTED" : "").($oItem["isGray"]==1?" class=\"nullcoupon\"":"").">".($maxlen > 0 ? substr($oItem[$name_field],0,$maxlen-1).(strlen($oItem[$name_field]) > $maxlen ? "..." : "") : $oItem[$name_field])."</OPTION>";
                     }
                  }
               }
               reset($cateList);
            }
         }
		 //$result = iconv('UTF-8','GB2312',$result);
         return $result;
      }
	  
	  function getMeta($field){
         $result = $this->CategoryInfo[$field];
         if ( '' == trim($result) ){
		 	$oMeta = new Meta();
		 	$oMeta->find('ItemType',$this->ClassName);
            $result = $oMeta->get($field);
         }
         return str_replace('{Category Name}',$this->CategoryInfo["Name"],$result);
      }
	  
	  function getCategoryList($order="") {
	  	  $sql = "SELECT Category_,Name,NameURL FROM Category WHERE isActive = 1";
		  if($order != "") {
		  	$sql .= " ORDER BY $order DESC";
		  }
		  $this->CategoryList = DBQuery::instance()->executeQuery($sql);
		  return $this->CategoryList;
	  }
	  
	  function getCouponCategoryList($couponID=0) {
	  	  if(!$couponID) {
		  	  return null;
		  }
	  	  $sql = "SELECT Category.* FROM Category INNER JOIN CoupCat ON Category.Category_ = CoupCat.Category_ " .
		         "WHERE Category.isActive = 1 AND CoupCat.Coupon_ = $couponID";
		  //$this->CategoryList = DBQuery::instance()->executeQuery($sql);
		  return DBQuery::instance()->executeQuery($sql);
	  }

	  function gethotcategorycouponlist($categoryid){
		$sql = "select DISTINCT(c.Coupon_),c.Descript,m.name1,m.NameURL,m.Merchant_,m.Name ";
		$sql .= "FROM Coupon c ";
		$sql .= "INNER JOIN pw_threads p ON p.dhbid=c.Coupon_ ";
		$sql .= "INNER JOIN CoupCat cc ON cc.Coupon_=c.Coupon_ ";
		$sql .= "LEFT join Merchant m on (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE cc.Category_='$categoryid' AND (c.ExpireDate='0000-00-00' OR c.ExpireDate>CURDATE()) AND c.StartDate<=CURDATE() AND c.isActive=1 AND c.CouponType!=9 ORDER BY p.hits DESC limit 10";
		$allhotcategorycouponlist = DBQuery::instance()->executeQuery($sql);
		return $allhotcategorycouponlist;

	  }

	  function getnewcategorycouponlist(){
		$sql = "select Coupon.Descript,m.NameURL,Coupon.Coupon_,m.name1,m.Merchant_,m.name from Coupon ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_=Coupon.Merchant_) ";
		$sql .= "INNER join pw_threads ON pw_threads.dhbid=Coupon.Coupon_ WHERE Coupon.CouponType!=9 AND Coupon.isActive=1 AND (Coupon.ExpireDate='0000-00-00' OR Coupon.ExpireDate>CURDATE()) AND Coupon.StartDate<=CURDATE() AND Coupon.isActive=1 ORDER BY Coupon.Coupon_ DESC limit 20";
		$allhotcategorycouponlist = DBQuery::instance()->executeQuery($sql);
		shuffle($allhotcategorycouponlist);
		return $allhotcategorycouponlist;

	  }

	function getEricCategorylist($categoryid,$pg,$cityid,$sort=NULL){
		if(empty($cityid) || $cityid=='all') $cityid=0;
		$start = ($pg-1)*10;		
		$sql = "SELECT GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,m.name1,m.name,w.digest,w.authorid,w.author,w.replies,p.Coupon_,p.CouponType,p.Merchant_,p.Descript,p.Detail,ExpireDate,StartDate,Amount,City,CityID,Hasmap," .
			        "p.isFeatured,p.isActive,p.isDelete,isFreeShipping," .
					"IF(p.Descript <> p.Detail,1,0) isMore,IF(p.ExpireDate >= CURDATE() || p.ExpireDate='0000-00-00',1,0) isExpire," .
					"IF(length(p.Detail) > 0,1,0) hasDetail," .
					"ImageURL1,p.ImageDownload,p.isFree,m.Name,m.NameURL,m.isShow FROM Coupon p " .
			        "LEFT join CouponTag ON(CouponTag.couponid = p.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) INNER JOIN CoupCat c ON c.Coupon_=p.Coupon_ " .
					"LEFT JOIN Merchant m ON m.Merchant_=p.Merchant_ " .
					"INNER JOIN pw_threads w ON w.dhbid=p.Coupon_ " .
					"WHERE (p.ExpireDate >= CURDATE() OR p.ExpireDate = '0000-00-00') AND ";
		// cityid==0, show all districts' coupons
		if ($cityid==0) {
		    $sql.= "p.isActive=1 AND p.CouponType!=9 AND c.Category_='".$categoryid."' AND LENGTH(p.Descript) >= 4 ";
		} else {		    
            $sql.= "(p.CityID=".$cityid." OR p.CityID=0) AND p.isActive=1 AND p.CouponType!=9 AND c.Category_='".$categoryid."' AND LENGTH(p.Descript) >= 4 ";
		}
		$sql.= "GROUP BY p.Coupon_ ORDER BY isExpire DESC ";
		
		if($sort == NULL){
			$sql .= ",p.CityID DESC";
			$sql .= ",w.digest DESC,p.Coupon_ DESC LIMIT ".$start.",10";
		}elseif($sort=='time'){
			$sql .= ",p.Coupon_ DESC LIMIT ".$start.",10";
		}
		
			$allCategoryCoupon = DBQuery::instance()->executeQuery($sql);
			for($i=0; $i<count($allCategoryCoupon); $i++) {
			 	if($allCategoryCoupon[$i]["isFree"] == 0){
					$allCategoryCoupon[$i]["couponUrl"] = Utilities::getURL("couponUnion", array("Category" => $categoryid,
				                        "Coupon_" => $allCategoryCoupon[$i]["Coupon_"]));
			}
		}
		return $allCategoryCoupon;
	}
	  
	function getEricCategorylistCount($categoryid,$cityid){
		if(empty($cityid) || $cityid=='all') $cityid=0;
		$sql = "SELECT count(distinct(p.Coupon_)) ";
		$sql .= "FROM Coupon p ";
		$sql .= "INNER JOIN CoupCat c ON c.Coupon_=p.Coupon_  INNER JOIN pw_threads w ON w.dhbid=p.Coupon_ ";
		$sql .= "WHERE (p.ExpireDate >= CURDATE() OR p.ExpireDate = '0000-00-00') AND ";
        // cityid==0, show all districts' coupons
		if ($cityid==0) {
            $sql .= "p.CouponType!=9 AND ";
        } else {
    		$sql .= "(p.CityID=".$cityid." or p.CityID=0) AND p.CouponType!=9 AND ";	
        }
	
		$sql .= "p.isActive=1 AND c.Category_='".$categoryid."' AND LENGTH(p.Descript) >= 4";
		$count = DBQuery::instance()->getOne($sql);
		return $count;
	}

	function getEricCategoryCitylist($categoryid){
		$sql = "SELECT p.City,p.CityID FROM Coupon p INNER JOIN CoupCat c ON c.Coupon_=p.Coupon_ WHERE p.isActive=1 AND c.Category_='".$categoryid."' AND LENGTH(p.Descript) >= 4 LIMIT 1000";
		$cityarray = DBQuery::instance()->executeQuery($sql);
		return $cityarray;
	}

	  function getTopicCouponList($couponlist){
			$sql = "SELECT m.name1,w.digest,w.authorid,w.author,w.replies,p.Coupon_,p.CouponType,p.Merchant_,p.Descript,p.Detail,ExpireDate,StartDate,Amount,City,CityID,Hasmap," .
			        "p.isFeatured,p.isActive,p.isDelete,isFreeShipping,c.Category_,cc.Name AS categoryname,cc.NameURL as categorynameurl," .
					"IF(p.Descript <> p.Detail,1,0) isMore,IF(p.ExpireDate >= CURDATE() || p.ExpireDate='0000-00-00',1,0) isExpire," .
					"IF(length(p.Detail) > 0,1,0) hasDetail," .
					"ImageURL1,p.ImageDownload,p.isFree,m.Name,m.NameURL,m.isShow FROM Coupon p " .
			        "INNER JOIN CoupCat c ON c.Coupon_=p.Coupon_ " .
			        "INNER JOIN Category cc ON cc.Category_=c.Category_ " .
					"INNER JOIN Merchant m ON m.Merchant_=p.Merchant_ " .
					"INNER JOIN pw_threads w ON w.dhbid=p.Coupon_ " .
					"WHERE c.Coupon_ IN (".$couponlist.")".
					" AND (m.isFree=1 or m.isFree IS NULL or m.isFree=0)" .
				    " AND LENGTH(p.Descript) >= 4" .
					" ORDER BY isExpire DESC, w.digest DESC,p.Coupon_ DESC";
			$allCategoryCoupon = DBQuery::instance()->executeQuery($sql);
			for($i=0; $i<count($allCategoryCoupon); $i++) {
			 	if($allCategoryCoupon[$i]["isFree"] == 0){
					$allCategoryCoupon[$i]["couponUrl"] = Utilities::getURL("couponUnion", array("Category" => $categoryid,
				                        "Coupon_" => $allCategoryCoupon[$i]["Coupon_"]));
				}
			 }
			return $allCategoryCoupon;

	  }
	  
      function LastDate(){
         return time();
      }


	  function getMerchantNewPageStr($nowPage,$maxPage,$nameurl,$id) {
	  	  
	  	  $str = "<div class=\"page\"><ul><li>当前第".$nowPage."页,共".$maxPage."页</li>";
		  if($nowPage > 1) {
		  	  $url = Utilities::getNewURL("merchant", 
                        array("NameURL" => $nameurl,"Id" => $id,"Page" => $nowPage-1));
		  	  $str .= "<li><a href='$url'>上一页</a></li>";  
		  }
		  if($maxPage <= 6) {
		  	  for($i=1; $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("merchant", 
							array("NameURL" => $nameurl,"Id" => $id,"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  if($nowPage == $maxPage && $maxPage!="1") {
			  	$str .= "";
			  }
		  }elseif($nowPage > 4 && $nowPage < ($maxPage - 2)) {
		  	  $starti = $nowPage -2;
			  $url = Utilities::getNewURL("merchant", 
                        array("NameURL" => $nameurl,"Id" => $id,"Page" => 1));
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
			  $str .= "<li>...</li>";
			  
			  if($nowPage > ($maxPage-6)){
			  	$startlimit = $maxPage;
			  }else{
			  	$startlimit = $starti+4;
			  }
			  
			  for($i=$starti; $i<=$startlimit && $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("merchant", 
							array("NameURL" => $nameurl,"Id" => $id,"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "<li>$i</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  
			  if($nowPage > ($maxPage-6)){
			  	
			  }else{
			  	$str .= "<li>...</li>";
			  }
			  
			  
		  } elseif($nowPage <= 4) {
		  	  for($i=1; $i<=5; $i++) {
				  $url = Utilities::getNewURL("merchant", 
							array("NameURL" => $nameurl,"Id" => $id,"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  $str .= "<li>...</li>";
		  } else {
		  	  $url = Utilities::getNewURL("merchant", 
                        array("NameURL" => $nameurl,"Id" => $id,"Page" => 1));
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
			  if($nowPage >= ($maxPage - 2)){
					$str .= "<li>...</li>";
				}else{
			  		
				}
			  $starti = $maxPage -4;
		  	  for($i=$starti; $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("merchant", 
							array("NameURL" => $nameurl,"Id" => $id,"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  if($nowPage != $maxPage) {
			  	if($nowPage >= ($maxPage - 2)){

				}else{
					$str .= "<li>...</li>";	
				}
			  }
		  }
		  
			if($maxPage>6 &&($nowPage < $maxPage-5) ) {
		  	  $url = Utilities::getNewURL("merchant", 
							array("NameURL" => $nameurl,"Id" => $id,"Page" => $maxPage));
		  	  $str .= "<li><a href='$url'>".$maxPage."</a></li>";  
		  }

		  if($nowPage != $maxPage) {
		  	  $url = Utilities::getNewURL("merchant", 
                        array("NameURL" => $nameurl,"Id" => $id,"Page" => $nowPage+1));
		  	  $str .= "<li><a href='$url'>下一页</a></li>";  
		  }
		  $str .= "</ul></div>";
	  	  return $str;
	  }

		function getNewPageStr($nowPage,$maxPage,$sort = NULL) {
	  	  
	  	  $str = "<div class=\"page\"><ul><li>当前第".$nowPage."页,共".$maxPage."页</li>";
		  if($nowPage > 1) {
		  	  $url = Utilities::getNewURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $nowPage-1,"Sort" => $sort));
		  	  $str .= "<li><a href='$url'>上一页</a></li>";  
		  }
		  if($maxPage <= 6) {
		  	  for($i=1; $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("category", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i,"Sort" => $sort));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  if($nowPage == $maxPage && $maxPage!="1") {
			  	$str .= "";
			  }
		  }elseif($nowPage > 4 && $nowPage < ($maxPage - 2)) {
		  	  $starti = $nowPage -2;
			  $url = Utilities::getNewURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => 1,"Sort" => $sort));
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
			  $str .= "<li>...</li>";
			  
			  if($nowPage > ($maxPage-6)){
			  	$startlimit = $maxPage;
			  }else{
			  	$startlimit = $starti+4;
			  }
			  
			  for($i=$starti; $i<=$startlimit && $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("category", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i,"Sort" => $sort));
				  if($nowPage == $i) {
					  $str .= "<li>$i</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  
			  if($nowPage > ($maxPage-6)){
			 
			  }else{
			  	$str .= "<li>...</li>";
			  }
			  
			  
		  } elseif($nowPage <= 4) {
		  	  for($i=1; $i<=5; $i++) {
				  $url = Utilities::getNewURL("category", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i,"Sort" => $sort));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  $str .= "<li>...</li>";
		  } else {
		  	  $url = Utilities::getNewURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => 1,"Sort" => $sort));
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
				if($nowPage >= ($maxPage - 2)){
					$str .= "<li>...</li>";
				}else{
			  		
				}
			  $starti = $maxPage -4;
		  	  for($i=$starti; $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("category", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i,"Sort" => $sort));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  if($nowPage != $maxPage) {
				if($nowPage >= ($maxPage - 2)){

				}else{
			  		$str .= "<li>...</li>";
				}
			  }
		  }
		  
		   if($maxPage>6 &&($nowPage < $maxPage-5) ) {
		  	  $url = Utilities::getNewURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $maxPage,"Sort" => $sort));
		  	  $str .= "<li><a href='$url'>".$maxPage."</a></li>";  
		  }
		  
		  if($nowPage != $maxPage) {
		  	  $url = Utilities::getNewURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $nowPage+1,"Sort" => $sort));
		  	  $str .= "<li><a href='$url'>下一页</a></li>";  
		  }
		  $str .= "</ul></div>";
	  	  return $str;
	  }

	  function getNewSitemapPageStr($nowPage,$maxPage) {
	  	  
	  	  $str = "<div class=\"page\"><ul>";
		  if($nowPage > 1) {
		  	  $url = Utilities::getNewURL("sitemap", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $nowPage-1));
		  	  $str .= "<li><a href='$url'>上一页</a></li>";  
		  }
		  if($maxPage <= 5) {
		  	  for($i=1; $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("sitemap", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  if($nowPage == $maxPage && $maxPage!="1") {
			  	$str .= "<li></li>";
			  }
		  }elseif($nowPage > 4 && $nowPage < ($maxPage - 2)) {
		  	  $starti = $nowPage -2;
			  $url = Utilities::getNewURL("sitemap", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => 1));
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
			  $str .= "<li>...</li>";
			  for($i=$starti; $i<=$starti+4 && $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("sitemap", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "<li>$i</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  $str .= "<li>...</li>";
			  
		  } elseif($nowPage <= 4) {
		  	  for($i=1; $i<=5; $i++) {
				  $url = Utilities::getNewURL("sitemap", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  $str .= "<li>...</li>";
		  } else {
		  	  $url = Utilities::getNewURL("sitemap", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => 1));
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
			  $starti = $maxPage -4;
		  	  for($i=$starti; $i<=$maxPage; $i++) {
				  $url = Utilities::getNewURL("sitemap", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  if($nowPage != $maxPage) {
			  	$str .= "<li>...</li>";
			  }
		  }
		  
		  
		  if($nowPage != $maxPage) {
		  	  $url = Utilities::getNewURL("sitemap", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $nowPage+1));
		  	  $str .= "<li><a href='$url'>下一页</a></li>";  
		  }
		  $str .= "</ul></div>";
	  	  return $str;
	  }

	  function getPageStr($nowPage,$maxPage) {
	  	  
	  	  $str = "<div class=\"right\">";
		  if($nowPage > 1) {
		  	  $url = Utilities::getURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $nowPage-1));
		  	  $str .= "<a href='$url'>&#60;</a><a href='$url' class='blue'>上一页</a>&nbsp;";  
		  }
		  if($maxPage <= 5) {
		  	  for($i=1; $i<=$maxPage; $i++) {
				  $url = Utilities::getURL("category", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "|&nbsp;".$i."&nbsp;";
				  } else {
					  $str .= "|&nbsp;<a href='$url' class='blue'>$i</a>&nbsp;";
				  }
			  }
			  if($nowPage == $maxPage) {
			  	$str .= "|";
			  }
		  }elseif($nowPage > 4 && $nowPage < ($maxPage - 2)) {
		  	  $starti = $nowPage -2;
			  $url = Utilities::getURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => 1));
			  $str .= "|&nbsp;<a href='$url' class='blue'>1</a>&nbsp;...";
			  
			  for($i=$starti; $i<=$starti+4 && $i<=$maxPage; $i++) {
				  $url = Utilities::getURL("category", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "&nbsp;$i";
				  } else {
					  $str .= "&nbsp;<a href='$url' class='blue'>$i</a>";
				  }
			  }
			  $str .= "&nbsp;...";
			  
		  } elseif($nowPage <= 4) {
		  	  for($i=1; $i<=5; $i++) {
				  $url = Utilities::getURL("category", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "|&nbsp;".$i."&nbsp;";
				  } else {
					  $str .= "|&nbsp;<a href='$url' class='blue'>$i</a>&nbsp;";
				  }
			  }
			  $str .= "|&nbsp;...";
		  } else {
		  	  $url = Utilities::getURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => 1));
			  $str .= "|&nbsp;<a href='$url' class='blue'>1</a>&nbsp;...";
			  $starti = $maxPage -4;
		  	  for($i=$starti; $i<=$maxPage; $i++) {
				  $url = Utilities::getURL("category", 
							array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $i));
				  if($nowPage == $i) {
					  $str .= "&nbsp;".$i."&nbsp;|";
				  } else {
					  $str .= "&nbsp;<a href='$url' class='blue'>$i</a>&nbsp;|";
				  }
			  }
			  if($nowPage != $maxPage) {
			  	$str .= "&nbsp;...";
			  }
		  }
		  
		  
		  if($nowPage != $maxPage) {
		  	  $url = Utilities::getURL("category", 
                        array("NameURL" => $this->CategoryInfo["Category_"],"Name" => $this->CategoryInfo["NameURL"],"Page" => $nowPage+1));
		  	  $str .= "&nbsp;|&nbsp;<a href='$url' class='blue'>下一页</a><a href='$url'>&#62;</a>";  
		  }
		  $str .= "</div>";
	  	  return $str;
	  }
   }

}
?>
