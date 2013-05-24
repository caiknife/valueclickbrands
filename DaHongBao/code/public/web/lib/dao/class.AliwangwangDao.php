<?PHP
	
class AliwangwangDao {
	function __construct() {
		
	}
	
	function getCategorylist($categoryid = "",$pg,$cityid,$sort=NULL,$pagecount){
		if(empty($cityid)) $cityid=0;
		$start = ($pg-1)*$pagecount;		
		$sql = "SELECT w.authorid,w.author,w.replies,p.Coupon_,p.CouponType,p.Merchant_,p.Descript,ExpireDate,StartDate,Amount,City,CityID,Hasmap," .
			        "p.isFeatured,p.isActive,p.isDelete,isFreeShipping," .
					"IF(p.Descript <> p.Detail,1,0) isMore,IF(p.ExpireDate >= CURDATE() || p.ExpireDate='0000-00-00',1,0) isExpire," .
					"IF(length(p.Detail) > 0,1,0) hasDetail," .
					"ImageURL1,p.ImageDownload,p.isFree FROM Coupon p " .
					"INNER JOIN CoupCat c ON c.Coupon_=p.Coupon_ " .
					"INNER JOIN pw_threads w ON w.dhbid=p.Coupon_ " .
					"WHERE ";
		
		$sql.= "(p.ExpireDate > CURDATE() || p.ExpireDate='0000-00-00') AND (p.CityID=".$cityid." OR p.CityID=0) AND p.isActive=1 AND p.CouponType=0 AND p.isFree=1 AND p.ImageDownload=1 AND ";
		if($categoryid){
			$sql .= "c.Category_ IN (".$categoryid.") AND ";
		}
		$sql .= "LENGTH(p.Descript) >= 4 ";
		$sql.= "GROUP BY p.Coupon_ ORDER BY p.Coupon_ DESC,isExpire DESC ";
	
		$sql .= ",p.CityID DESC";
		$sql .= ",w.digest DESC,p.Coupon_ DESC LIMIT ".$start.",$pagecount";

		$allCategoryCoupon = DBQuery::instance()->executeQuery($sql);
		return $allCategoryCoupon;
	}
	  
	function getCategorylistCount($categoryid = "",$cityid){
		if(empty($cityid)) $cityid=0;
		$sql = "SELECT count(distinct(p.Coupon_)) ";
		$sql .= "FROM Coupon p ";
		$sql .= "INNER JOIN CoupCat c ON c.Coupon_=p.Coupon_ INNER JOIN pw_threads w ON w.dhbid=p.Coupon_ ";
		$sql .= "WHERE (p.ExpireDate > CURDATE() || p.ExpireDate='0000-00-00') AND p.isFree=1 AND p.ImageDownload=1 AND ";  //ֻ��ʾ��Ч�ġ������ڵ�,ֻ��ʾ�Ż�ȯ

		$sql .= "(p.CityID=".$cityid." or p.CityID=0) AND ";	
	
		$sql .= "p.isActive=1 AND p.CouponType=0 AND ";
		if($categoryid){
			$sql .= "c.Category_ IN (".$categoryid.") AND ";
		}
		$sql .= "LENGTH(p.Descript) >= 4";

		//echo $sql;

		$count = DBQuery::instance()->getOne($sql);
	
		return $count;
	}

	public function getNewDiscountList($city,$page,$type="") {
		$dateweek = date("Y-m-d"); 
		$start = ($page-1)*6;
		$sql = "SELECT c.Coupon_,c.Descript,c.AddDate,c.StartDate,c.ExpireDate,c.Detail ";
		$sql .= "FROM Coupon c ";
		$sql .= "WHERE c.isActive=1 AND (c.ExpireDate > CURDATE() || c.ExpireDate='0000-00-00') AND c.CouponType=9 AND (c.CityID='$city' OR c.CityID=0) ORDER BY ";
		if($type=="startdate"){
			$sql .= "c.StartDate ";
		}else{
			$sql .= "c.AddDate ";
		}
		$sql .= "DESC limit $start,6";
		return DBQuery::instance()->executeQuery($sql);
	}

	public function getNewDiscountListCount($city) {
		$dateweek = date("Y-m-d"); 
		$sql = "SELECT count(distinct(c.Coupon_)) ";
		$sql .= "FROM Coupon c ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=9 AND (c.CityID='$city' OR c.CityID=0) AND (c.ExpireDate > CURDATE() || c.ExpireDate='0000-00-00')";
		return DBQuery::instance()->getOne($sql);
	}

	public function getHotDiscountList($city) {
		$sql = "SELECT GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.ImageDownload,c.Coupon_,c.Descript,c.AddDate,c.StartDate,c.ExpireDate,c.Detail,p.author,p.authorid,p.replies,p.digest,p.delate ";
		$sql .= "FROM Coupon c LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) inner JOIN pw_threads p ON(p.dhbid = c.Coupon_) ";
		$sql .= "WHERE c.isActive=1 AND c.CouponType=9 AND (c.CityID='$city' OR c.CityID=0) AND c.ExpireDate >= NOW() GROUP BY c.Coupon_ ORDER BY p.digest DESC limit 0,6";
		return DBQuery::instance()->executeQuery($sql);
	}

	public function getCategory(){
		$array = array("0"=>array("Name"=>"������ʳ","Category"=>"72"),
						"1"=>array("Name"=>"��������","Category"=>"68"),
						"2"=>array("Name"=>"�Ƶ��Ʊ","Category"=>"70"),
						"3"=>array("Name"=>"������ѵ","Category"=>"94"),
						"4"=>array("Name"=>"Ӱ��Ʊ��","Category"=>"76"),
						"5"=>array("Name"=>"�ҵĶ���","Category"=>"9"));
		return $array;
	}

	public function getCategoryAll(){
		return "72,68,70,94,76";
	}

	public function getDingZhiCategory(){
		$array = array("0"=>array("Name"=>"������ʳ","Category"=>"72","Descript"=>"�ϵ»������������Ż�ȯ��"),
						"1"=>array("Name"=>"��������","Category"=>"68","Descript"=>"ʱ�����͡����������Ż�ȯ��"),
						"2"=>array("Name"=>"�Ƶ��Ʊ","Category"=>"70","Descript"=>"�������С��Ƶ��Ż��ۿ�ȯ��"),
						"3"=>array("Name"=>"������ѵ","Category"=>"94","Descript"=>"���������ѵ�Ż��ۿ�ȯ��"),
						"4"=>array("Name"=>"Ӱ��Ʊ��","Category"=>"76","Descript"=>"���ֻ�볡��Ӧ�о��С�"));
		return $array;
	}

	public function getSearchCategory(){
		$array = array("0"=>array("Name"=>"ȫ���Ż�ȯ","Category"=>"1"),
						"1"=>array("Name"=>"&nbsp;&nbsp;������ʳ","Category"=>"72"),
						"2"=>array("Name"=>"&nbsp;&nbsp;��������","Category"=>"68"),
						"3"=>array("Name"=>"&nbsp;&nbsp;�Ƶ��Ʊ","Category"=>"70"),
						"4"=>array("Name"=>"&nbsp;&nbsp;������ѵ","Category"=>"94"),
						"5"=>array("Name"=>"&nbsp;&nbsp;Ӱ��Ʊ��","Category"=>"76"),
						"6"=>array("Name"=>"ȫ���ۿ���Ϣ","Category"=>"2"));
		return $array;
	}

	public function getWWUserCategory($wwuserid,$cityid){
		$sql = "SELECT GROUP_CONCAT(Category_) as Category FROM UserWangwang where WWID ='$wwuserid' AND CityID='$cityid'";
		return DBQuery::instance()->getRow($sql);

	}

	public function searchByCategory($cid,$searchlist,$type=1){
		$search = "";
		$rtndiscountsearchlist = $searchlist;
		foreach ($searchlist as $key=>$value){
			$search .= $value['NewsId'].",";
		}
		$search  = substr($search,0,-1);
		$searchlist = explode(",",$searchlist);
		if($cid==2){
			return $rtndiscountsearchlist;
		}else{
			$sql = "SELECT a.Coupon_ AS NewsId FROM CoupCat a LEFT JOIN Coupon b ON (b.Coupon_ = a.Coupon_) WHERE a.Coupon_ IN ($search) ";
			if($cid==1){
				$cid = AliwangwangDao::getCategoryAll();
				$sql .="AND a.Category_ IN ($cid) AND ";
			}else{
				$sql .="AND a.Category_=$cid AND ";
			}
			$sql .= "b.isFree=1 AND b.ImageDownload=1 ";	
		}
		$sql .= "ORDER BY FIND_IN_SET(a.Coupon_, '$search')";
		return DBQuery::instance()->executeQuery($sql);
	}

	public function addDingzhi($carray,$city,$wwid){
		if(empty($wwid) || empty($city)) return;
		$sql = "DELETE FROM UserWangwang WHERE WWID='$wwid' AND CityID=$city";
		$re = DBQuery::instance()->executeQuery($sql);
		$sql = "INSERT INTO UserWangwang (`ID`,`WWID`,`Category_`,`Addtime`,`CityID`) VALUES ";
		foreach ($carray as $key=>$value){
			$sql .= "('', '$wwid', '".DBQuery::filter($value)."', NOW(), '$city'),";
		}
		$sql = substr($sql,0,-1);
		return DBQuery::instance()->executeQuery($sql);
	}

	public function delDingzhi($city,$wwid){
		if(empty($wwid) || empty($city)) return;
		$sql = "DELETE FROM UserWangwang WHERE WWID='$wwid' AND CityID=$city";
		return DBQuery::instance()->executeQuery($sql);
	
	}

	public function getCouponDetailList($idarray,$page=1,$perPage=10){
			$idstring ="";
			foreach($idarray as $key=>$value){
				$idstring .= $value['NewsId'].",";
			}
			$idstring = substr($idstring,0,-1);
			$start = ($page-1) * $perPage;
			$sql = "SELECT Category.Name as categoryname,m.NameURL,c.ImageDownload,c.Coupon_,GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.Descript,c.Detail,p.hits,p.digest,p.replies,c.AddDate,c.ExpireDate,c.StartDate,m.Name name,m.name1 name1,m.Merchant_ Merchant_ FROM Coupon c ";
			$sql .= "LEFT join CoupCat ON(CoupCat.Coupon_ = c.Coupon_) LEFT join Category ON(Category.Category_ = CoupCat.Category_) LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) INNER JOIN pw_threads p ON (p.dhbid = c.Coupon_) LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) WHERE c.Coupon_ IN (".$idstring.") AND c.IsActive=1 GROUP BY c.Coupon_ ORDER BY FIND_IN_SET(c.Coupon_,'".$idstring."') LIMIT $start,$perPage";
		
			$re = DBQuery::instance()->executeQuery($sql);


			$newre = array();
			foreach($re as $key=>$value){
				$k = $value['Coupon_'];
				$newre[$k] = $value;
			}
			return $newre;
		}



}