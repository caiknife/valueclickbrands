<?PHP

class Discount {

	static $discountN = "9";
	
	public function addNewDiscount() {
		$a =  self::$discountN;
		echo $a;

		$sql = "INSERT INTO Coupon ";
	}

	public function getDiscountList($date,$city) {
		$a =  self::$discountN;
		//echo $a;
        if (empty($city) || $city=="all") {
            $city = 0;
        }
	
		$sql = "SELECT GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.Coupon_,c.ImageDownload,c.Descript,c.StartDate,c.ExpireDate,c.Detail,p.author,p.authorid,p.replies,p.digest,p.delate ";
		$sql .= "FROM Coupon c LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) LEFT JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=$a AND c.StartDate = '$date' ";
		if ($city!=0) {		    
		  $sql .= "AND (c.CityID='$city' OR c.CityID=0) ";
		}
		$sql .= "GROUP BY c.Coupon_ ORDER BY p.digest DESC limit 100";
		return DBQuery::instance()->executeQuery($sql);
	}

	public function getDiscountListForWeek($date,$city,$size) {
		$a =  self::$discountN;
		//echo $a;
	   if (empty($city) || $city=="all") {
            $city = 0;
        }

		$dateweek = getDateTime("Y-m-d"); 
	
		$sql = "SELECT GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.Descript,c.StartDate,c.ExpireDate,c.Detail,p.author,p.authorid,p.replies,p.digest,p.delate ";
		$sql .= "FROM Coupon c LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) LEFT JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=$a AND (c.StartDate < '$date' AND c.StartDate >'$dateweek') ";
		if ($city!=0) {		    
		    $sql .= "AND (c.CityID='$city' OR c.CityID=0) ";
		}
		$sql .= "GROUP BY c.Coupon_ limit $size";
		return DBQuery::instance()->executeQuery($sql);
	}

	public function getNewDiscountList($city,$page) {
		$a =  self::$discountN;
		//echo $a;
        if (empty($city) || $city=="all") {
            $city = 0;
        }

		$dateweek = getDateTime("Y-m-d"); 
	
		$start = ($page-1)*20;

		$sql = "SELECT c.Coupon_,c.Descript,c.AddDate,c.StartDate,c.ExpireDate,c.Detail,p.author,p.authorid,p.replies,p.digest,p.delate ";
		$sql .= "FROM Coupon c LEFT JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=$a ";
		if ($city!=0) {		    
		    $sql .= "AND (c.CityID='$city' OR c.CityID=0)"; 
		}
		$sql .= "AND c.ExpireDate >= CURDATE() ORDER BY c.AddDate DESC limit $start,20";
		return DBQuery::instance()->executeQuery($sql);
	}

	public function getNewDiscountListCount($city) {
		$a =  self::$discountN;
		//echo $a;
	    if (empty($city) || $city=="all") {
            $city = 0;
        }
		$dateweek = date("Y-m-d"); 
	

		$sql = "SELECT count(*) ";
		$sql .= "FROM Coupon c LEFT JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=$a ";
		if ($city!=0) {		    
            $sql .= "AND (c.CityID='$city' OR c.CityID=0) ";
		}
		$sql .= "AND c.ExpireDate >= CURDATE()";
		return DBQuery::instance()->getOne($sql);
	}

	public function getExpireDiscountList($city,$page) {
		$a =  self::$discountN;
	    if (empty($city) || $city=="all") {
            $city = 0;
        }
		$date = getDateTime("Y-m-d",time()+72*60*60); 
		$datenow = getDateTime("Y-m-d"); 
		$start = ($page-1)*10;
		$sql = "SELECT  GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.ImageDownload,c.Coupon_,c.Descript,c.AddDate,c.StartDate,c.ExpireDate,c.Detail,p.author,p.authorid,p.replies,p.digest,p.delate ";
		$sql .= "FROM Coupon c LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) LEFT JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=$a ";
		if ($city!=0) {	    
    		$sql .= "AND (c.CityID='$city' OR c.CityID=0) ";
		}
		$sql .= "AND c.ExpireDate <= '$date' AND c.ExpireDate >= '$datenow' GROUP BY c.Coupon_ ORDER BY p.digest DESC limit $start,10";
		return DBQuery::instance()->executeQuery($sql);
	}

	public function getExpireDiscountCount($city) {
		$a =  self::$discountN;
	    if (empty($city) || $city=="all") {
            $city = 0;
        }
		$date = getDateTime("Y-m-d",time()+72*60*60); 
		$datenow = getDateTime("Y-m-d"); 

		$sql = "SELECT count(*) ";
		$sql .= "FROM Coupon c LEFT JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=$a ";
		if ($city!=0) {	    
    		$sql .= "AND (c.CityID='$city' OR c.CityID=0) ";
		}
		$sql .="AND c.ExpireDate <= '$date' AND c.ExpireDate >= '$datenow' ORDER BY p.digest DESC";
		return DBQuery::instance()->getOne($sql);
	}

	

	public function getHotDiscountList($city,$page) {
		$a =  self::$discountN;
	    if (empty($city) || $city=="all") {
            $city = 0;
        }
		$start = ($page-1)*10;
		$sql = "SELECT GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.ImageDownload,c.Coupon_,c.Descript,c.AddDate,c.StartDate,c.ExpireDate,c.Detail,p.author,p.authorid,p.replies,p.digest,p.delate ";
		$sql .= "FROM Coupon c LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) inner JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=$a ";
		if ($city!=0) {		    
		  $sql .= "AND (c.CityID='$city' OR c.CityID=0) ";
		}
		$sql .= "AND c.ExpireDate >= NOW() GROUP BY c.Coupon_ ORDER BY p.digest DESC limit $start,10";
		Debug::pr($sql);
		return DBQuery::instance()->executeQuery($sql);
	}

	public function getHotDiscountCount($city) {
		$a =  self::$discountN;
	    if (empty($city) || $city=="all") {
            $city = 0;
        }
		$sql = "SELECT count(*) ";
		$sql .= "FROM Coupon c LEFT JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=$a ";
		if ($city!=0) {
		    $sql .= "AND (c.CityID='$city' OR c.CityID=0) ";
		}
		$sql .= "AND c.ExpireDate >= NOW()";
		Debug::pr($sql);
		return DBQuery::instance()->getOne($sql);
	}


	public function getDiscountReply($id) {
		$sql = "SELECT pw_posts.content,pw_posts.postdate,pw_posts.icon as picon,pw_members.icon,pw_members.username ";
		$sql .= "FROM pw_posts INNER JOIN pw_members ON (pw_members.uid = pw_posts.authorid) WHERE pw_posts.tid='$id' ORDER BY pw_posts.pid DESC limit 0,10";
		return DBQuery::instance()->executeQuery($sql);
	}

	public function getDiscountReplyCount($id) {
		$sql = "SELECT count(*) ";
		$sql .= "FROM pw_posts WHERE pw_posts.tid='$id' ";
		return DBQuery::instance()->getOne($sql);
	}

	public function getDiscountDetail($id) {
		$sql = "SELECT GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.Coupon_,c.ImageDownload,p.digest,p.delate,p.replies,p.author,p.authorid,c.Descript,c.Detail,c.StartDate,c.ExpireDate,p.tid,p.fid ";
		$sql .= "FROM Coupon c LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) LEFT JOIN pw_threads p ON(p.dhbid = c.Coupon_) WHERE c.Coupon_='$id' AND c.IsActive=1 GROUP BY c.Coupon_";
		return DBQuery::instance()->getRow($sql);
	}

	public function UpdateDiscountByUser($cid,$sd,$ed,$city) {
		$sql = "SELECT CityName FROM City WHERE CityID='".$city."'";
		$cityname = DBQuery::instance()->getOne($sql);

		if(empty($cityname)){
			$cityname = "È«¹ú";
		}

		$sql = "UPDATE Coupon SET StartDate='$sd',ExpireDate='$ed',City='$cityname',CityID='$city' WHERE Coupon_='$cid'";
		return DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
		
	}

	

	public function addDiscountByUser($arrValue,$tag,$userid){
		$sql = "SELECT MAX(Coupon_) FROM Coupon";
		$coupon_ = DBQuery::instance()->getOne($sql);
		$coupon_ = $coupon_ + 1;
		$arrValue['Coupon_'] = $coupon_;
		$arrValue['isActive'] = 0;
		$arrValue['AddDate'] = getDateTime("Y-m-d H:i:s");

		$arrValue['CouponType']=9;
		

		@DBQuery::instance()->autoExecute('Coupon',$arrValue);

	

		if(empty($userid)){    //random user
			$sql = "select * from pw_members WHERE oicq='mezi' ORDER BY RAND() limit 1";
			$us = DBQuery::instance()->getRow($sql);
			$author = $us['username'];
			$authorid = $us['uid'];
		}else{
			$sql = "select username from pw_members WHERE uid='$userid'";
			$author = DBQuery::instance()->getOne($sql);
			$authorid = $userid;
		}

		$sql = "INSERT INTO pw_threads (tid,fid,subject,ifcheck,author,authorid,dhbid) values ('','$i','".$coupon_."',1,'$author','$authorid','".$coupon_."')";
		@DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql); 

		$tag = str_replace("£»",",",$tag);
		$tag = str_replace("£º",",",$tag);
		$tag = str_replace(";",",",$tag);
		$tag = str_replace(":",",",$tag);
		$tag = str_replace("£¬",",",$tag);
		$tag = str_replace(".",",",$tag);
		$tag = explode(',',$tag);
		foreach ($tag as $key=>$value){
			if(trim($value)=="") continue;
			$sql = "SELECT id FROM Tag WHERE tagname='$value'";
			$tagid = DBQuery::instance()->getOne($sql);
			if($tagid==""){
				$sql = "SELECT MAX(id) from Tag";
				$maxid = DBQuery::instance()->getOne($sql);
				$maxid = $maxid+1;
				$sql = "INSERT INTO Tag (id,tagname) VALUES ('$maxid','$value')";
				@DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
				$tagid = $maxid;
			}
			$sql = "INSERT INTO CouponTag (id,tagid,couponid) VALUES ('','$tagid','$coupon_')";
			@DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
		}


		return $coupon_;
		
	}

	




	

}

?>