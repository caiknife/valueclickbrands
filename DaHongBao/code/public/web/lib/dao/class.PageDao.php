<?PHP
	
class PageDao {
	function __construct() {
		
	}
	
	public function getHotCoupon($catid=0) {
		$cachefile = __FILE_FULLPATH."cache/hotdata/hotcoupon_{$catid}.cache";
		if(file_exists($cachefile) && filemtime($cachefile) >= time() - 1800) {
			$allhotcategorycouponlist = unserialize(file_get_contents($cachefile));
		} else {
			$sql = "select DISTINCT(c.Coupon_),c.Descript,m.name1,m.NameURL,m.Merchant_,m.Name, ";
            $sql .= "c.Detail, c.ImageDownload, c.ExpireDate ";
			$sql .= "FROM Coupon c ";
			$sql .= "INNER JOIN pw_threads p ON p.dhbid=c.Coupon_ ";
			$sql .= "INNER JOIN CoupCat cc ON cc.Coupon_=c.Coupon_ ";
			$sql .= "LEFT join Merchant m on (m.Merchant_ = c.Merchant_) ";
			$sql .= "WHERE (c.ExpireDate='0000-00-00' OR c.ExpireDate>CURDATE()) AND c.StartDate<=CURDATE() AND c.isActive=1 AND c.CouponType!=9";
			if($catid > 0) {
				$sql .= " AND cc.Category_='$catid'";
			}
			$sql .= " ORDER BY p.hits DESC limit 10";
			$allhotcategorycouponlist = DBQuery::instance()->executeQuery($sql);
			if(!file_exists($cachefile) && !file_exists(dirname($cachefile))) {
				mkdir(dirname($cachefile), 0777, true);
			}
			file_put_contents($cachefile, serialize($allhotcategorycouponlist));
		}
		return $allhotcategorycouponlist;
	}
	
	public function getHotBBS() {
		$cachefile = __FILE_FULLPATH."cache/hotdata/hotbbs.cache";
		if(file_exists($cachefile) && filemtime($cachefile) >= time() - 1800) {
			$r = unserialize(file_get_contents($cachefile));
		} else {
			$sql = "SELECT pw_threads.tid,pw_threads.subject,pw_forums.fid,pw_forums.name 
							        FROM pw_threads 
									INNER JOIN pw_forums ON (pw_forums.fid=pw_threads.fid) 
									WHERE pw_threads.fid <17 OR pw_threads.fid>53 
									ORDER BY pw_threads.hits DESC 
									limit 20";
			$r = DBQuery :: instance()->executeQuery($sql);
			shuffle($r);
			if(!file_exists($cachefile) && !file_exists(dirname($cachefile))) {
				mkdir(dirname($cachefile), 0777, true);
			}
			file_put_contents($cachefile, serialize($r));
		}
		return $r;
	}
	
	function getPage($page){
		$sql = "SELECT * FROM Page WHERE Name= '$page'";
		$re = DBQuery::instance()->executeQuery($sql);

		return $re[0]['Content'];
	}


}