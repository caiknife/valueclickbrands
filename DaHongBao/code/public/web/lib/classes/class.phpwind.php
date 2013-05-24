<?php
require_once (__INCLUDE_ROOT."lib/classes/class.Utilities.php");

class PHPWIND {

	function getforumlist($fidlist) {
		$sql = "select * FROM pw_threads LEFT JOIN pw_tmsgs ON ( pw_threads.tid = pw_tmsgs.tid ) WHERE fid IN ($fidlist) AND topped > 0 ORDER BY postdate DESC";
		$r = DBQuery :: instance()->executeQuery($sql);
		$newr = array ();
		foreach ($r as $key => $value) {
			$newk = $value['fid'];
			//echo $newk;
			$newr[$newk][] = $value;
		}
		return $newr;
	}

	public function getBBSNotify($fid) {
		$sql = "select fid,tid,subject 
								 FROM pw_threads 
							     WHERE fid = $fid AND topped > 0 
							     ORDER BY postdate DESC 
								 limit 2 ";
		$r = DBQuery :: instance()->executeQuery($sql);
		$newr = array ();
		foreach ($r as $key => $value) {
			$newk = $value['fid'];
			//echo $newk;
			$newr[$newk][] = $value;
		}
		return $newr;
	}

	public function createPHPWINDIndex() {
		$split = new WordSplitter();

		/* 去除咨询信息索引
		$sql = "SELECT pw_threads.tid,pw_threads.subject,SUBSTRING(pw_tmsgs.content,1,200) AS content FROM pw_threads INNER JOIN pw_tmsgs ON (pw_tmsgs.tid = pw_threads.tid) WHERE (pw_threads.fid >16 AND pw_threads.fid<33) OR pw_threads.fid=51";
		$result = DBQuery::instance()->executeQuery($sql);
		foreach($result as $record) {	
			$record['subject'] = $split->executeForCreate($record['subject']);
			$record['subject'] = my_encode($record['subject']);
			$record['content'] = $split->executeForCreate($record['content']);
			$record['content'] = my_encode($record['content']);
			
		
			$sql = "INSERT INTO News_SE_swap (Id,Title,Descript,NewsId,Type) VALUES ('','".$record['subject']."','".$record['content']."','".$record['tid']."','2')";
			$re = DBQuery::instance(__SE)->executeUpdate($sql);
		
		}
		*/

		$sql = "SELECT pw_threads.tid,pw_threads.subject,SUBSTRING(pw_tmsgs.content,1,200) AS content FROM pw_threads INNER JOIN pw_tmsgs ON (pw_tmsgs.tid = pw_threads.tid) WHERE pw_threads.fid <17 OR pw_threads.fid>53";
		$result = DBQuery :: instance()->executeQuery($sql);
		foreach ($result as $record) {
			$record['subject'] = $split->executeForCreate($record['subject']);
			$record['subject'] = my_encode($record['subject']);
			$record['content'] = $split->executeForCreate($record['content']);
			$record['content'] = my_encode($record['content']);

			$sql = "INSERT INTO News_SE_swap (Id,Title,Descript,NewsId,Type) VALUES ('','".$record['subject']."','".$record['content']."','".$record['tid']."','3')";
			$re = DBQuery :: instance(__SE)->executeUpdate($sql);

		}
	}

	/* get city name */
	public function getNowCityName($id) {
		if ($id == "") {
			setcookie("cityid", "21", time() + 9999999, "/");
			return "上海";
		}
		$sql = "select CityName FROM City WHERE CityID='$id'";
		$result = DBQuery :: instance()->getOne($sql);
		if (empty ($result)) {
			setcookie("cityid", "21", time() + 9999999, "/");
			return "上海";
		}
		return $result;
	}

	/* get city list */
	public function getCityList() {
		$sql = "select CityName,CityID FROM City ORDER BY CityID";
		$result = DBQuery :: instance()->executeQuery($sql);
		return $result;
	}

	/* get forum keywords */
	public function getForumKeywords() {
		$sql = "select fid,GROUP_CONCAT(category) as keywords FROM pw_category_keywords GROUP BY fid";
		$result = DBQuery :: instance()->executeQuery($sql);
		return $result;
	}

	public function updateKeywords($fid, $keywords) {
		$sql = "DELETE FROM pw_category_keywords WHERE fid= $fid";
		$result = DBQuery :: instance()->executeQuery($sql);

		$keywords = explode(",", $keywords);
		foreach ($keywords as $key => $value) {
			$sql = "INSERT INTO pw_category_keywords (id,fid,category) VALUES ('','$fid','$value')";
			$result = DBQuery :: instance()->executeQuery($sql);
		}
		return 1;
	}

	public function getindexforumlist($fidlist) {
		$sql = "select p.subject,p.fid,p.tid,t.content 
						        FROM pw_threads p 
							    LEFT JOIN pw_tmsgs t ON ( p.tid = t.tid ) 
							    WHERE p.fid IN ($fidlist) AND p.topped > 0 
							    ORDER BY p.postdate DESC";
		$r = DBQuery :: instance()->executeQuery($sql);
		$newr = array ();
		foreach ($r as $key => $value) {
			$newk = $value['fid'];
			$newr[$newk][] = $value;
		}
		return $newr;
	}

	function getinfotemp() {
		$sql = "SELECT * from infotemp limit 1";
		$r = DBQuery :: instance()->executeQuery($sql);

		$iid = $r[0]['id'];
		$title = $r[0]['title'];
		$content = $r[0]['content'];
		$category = $r[0]['category'];

		if ($iid == 0) {
			exit ();
		}
		$time = time();
		$sql = "INSERT INTO pw_threads (tid,fid,author,authorid,subject,ifcheck,postdate) values ('','$category','admin','1','$title','1','$time')";
		$r = DBQuery :: instance()->executeUpdate($sql);

		$tid = DBQuery :: instance()->getInsertID();

		$sql = "INSERT INTO pw_tmsgs (tid,content) values ('$tid','$content')";
		$r = DBQuery :: instance()->executeUpdate($sql);

		$sql = "DELETE FROM infotemp WHERE id='$iid'";
		$r = DBQuery :: instance()->executeUpdate($sql);

	}

	function getImgSrc($id) {
		$sql = "select attachurl FROM pw_attachs WHERE aid='$id'";
		$r = DBQuery :: instance()->getOne($sql);
		return $r;
	}

	function getmerchant($text) {
		$sql = "select * FROM Merchant WHERE NameURL='$text'";
		$r = DBQuery :: instance()->executeQuery($sql);
		return $r;
	}

	function createcouponline() {
		$d = date("Y-n-j");
		$d = explode("-", $d);
		$sql = "SELECT count(*) from couponline WHERE year='".$d[0]."' and month='".$d[1]."' and day='".$d[2]."'";
		$r = DBQuery :: instance()->getOne($sql);
		if ($r > 0) {

		} else {
			$sql = "SELECT count(*) from Coupon WHERE (ExpireDate='0000-00-00' OR ExpireDate>CURDATE()) AND StartDate<=CURDATE()";
			$r = DBQuery :: instance()->getOne($sql);

			$sql = "INSERT couponline (year,month,day,num) VALUES ('".$d[0]."','".$d[1]."','".$d[2]."','".$r."')";
			$r = DBQuery :: instance()->executeUpdate($sql);
		}
		//exit();

	}

	function getNewPageStr($nowPage, $maxPage) {

		$str = "<div class=\"page\"><ul>";
		if ($maxPage == 1) {
			$str .= "<li>1</li></div>";
			return $str;
		}
		if ($maxPage) {
			for ($i = 1; $i <= $maxPage; $i ++) {
				if ($nowPage == $i) {
					$str .= "<li>".$i."</li>";
				} else {
					$str .= "<li><a href='javascript:page($i)'>$i</a></li>";
				}
			}
		}

		$str .= "</ul></div>";
		return $str;
	}

	function getNewslistPageStr($nowPage, $maxPage, $nameurl, $cid) {
		$str = "<div class=\"page\"><ul><li>当前第".$nowPage."页,共".$maxPage."页</li>";
		if ($maxPage == 1) {
			$str .= "<li>1</li></div>";
			return $str;
		}
		if ($maxPage) {
			for ($i = 1; $i <= $maxPage; $i ++) {
				if ($nowPage == $i) {
					$str .= "<li>".$i."</li>";
				} else {
					$str .= "<li><a href='/news---Ca-$nameurl--Ci-$cid--Pg-$i.html'>$i</a></li>";
				}
			}
		}

		$str .= "</ul></div>";
		return $str;
	}

	function getlastandnext($id, $cid) {
		$sql = "SELECT * from pw_threads WHERE fid='$cid' ORDER BY postdate DESC";
		$r = DBQuery :: instance()->executeQuery($sql);
		foreach ($r as $key => $value) {
			if ($value['tid'] == $id)
				$k = $key;
		}
		if ($k != count($r))
			$next = $k +1;
		if ($k != 0)
			$last = $k -1;
		$re[1] = $r[$next];
		$re[0] = $r[$last];
		return $re;
	}

	function getlist($id, $pg) {
		if ($pg == 0) {
			$pg = 1;
		}
		$start = ($pg -1) * 30;

		$sql = "select * FROM pw_threads WHERE fid ='$id' ORDER BY postdate DESC limit $start,30";

		$r = DBQuery :: instance()->executeQuery($sql);

		return $r;
	}

	function gettopiclist($idarray) {
		$sql = "select * FROM pw_threads WHERE tid IN ($idarray)";
		// echo $sql;
		$r = DBQuery :: instance()->executeQuery($sql);
		foreach ($r as $key => $value) {
			$a = array ("17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "51");
			$b = array ("food", "travel", "gift", "Cosmestics", "video", "homegarden", "apparel", "electronics", "maketdetail", "training", "toys", "books", "cartoon", "digital", "Sports", "auto", "adult");
			$r[$key]['NameURL'] = str_replace($a, $b, $value['fid']);
		}
		return $r;

	}

	function getlistCountAll($id) {
		$sql = "select count(*) FROM pw_threads WHERE fid ='$id'";
		$r = DBQuery :: instance()->getOne($sql);
		return $r;
	}

	function getforumdetail($id) {
		$sql = "select t.type,t.postdate,t.subject,t.hits,t.replies,m.content,pw_forums.name FROM pw_threads t LEFT JOIN pw_tmsgs m ON ( t.tid = m.tid ) LEFT JOIN pw_forums ON (pw_forums.fid = t.fid) WHERE t.tid = '$id'";
		$r = DBQuery :: instance()->executeQuery($sql);
		return $r;
	}

	function getTopicRowById($id) {
		$sql = "select topic.title FROM topic WHERE id = '$id'";
		$r = DBQuery :: instance()->getRow($sql);
		return $r;

	}

	function getforumreview($id) {
		$sql = "select * FROM pw_posts WHERE tid = '$id' ORDER BY postdate DESC";
		$r = DBQuery :: instance()->executeQuery($sql);
		//print_r($r);
		return $r;
	}

	function getuserinfo($id) {
		$sql = "select pm.username,pm.uid,pm.icon,pw_memberdata.lastvisit,pw_memberdata.money ";
		$sql .= "FROM pw_members pm ";
		$sql .= "LEFT JOIN pw_memberdata ON (pw_memberdata.uid = pm.uid) ";
		$sql .= "WHERE pm.uid = '$id'";
		$r = DBQuery :: instance()->executeQuery($sql);
		return $r;
	}

	function addcouponclick($id) {
		if (empty ($id))
			return;
//		$sql = "UPDATE pw_threads SET hits=hits+1 WHERE dhbid = '$id' LIMIT 1";
//		return DBQuery :: instance()->executeUpdate($sql);
		return true;
	}

	function addforumclick($id) {
		$sql = "UPDATE pw_threads SET hits=hits+1 WHERE tid = '$id'";
		$r = DBQuery :: instance()->executeUpdate($sql);
		//print_r($r);
		return $r;
	}

	function delate($id) {
		if (empty ($id))
			return;
		$sql = "UPDATE pw_threads SET delate=delate+1 WHERE dhbid = '$id' LIMIT 1";
		return DBQuery :: instance()->executeUpdate($sql);
	}

	function gethotbbs($id) {
		if ($id == "") {
			$sql = "SELECT pw_threads.tid,pw_threads.subject,pw_threads.fid FROM pw_threads ORDER BY hits DESC limit 20";
		} else {
			$sql = "SELECT pw_threads.tid,pw_threads.subject,pw_threads.fid FROM pw_threads WHERE fid=$id ORDER BY hits DESC limit 20";
		}
		$r = DBQuery :: instance()->executeQuery($sql);
		shuffle($r);
		foreach ($r as $key => $value) {
			$a = array ("17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "51");
			$b = array ("food", "travel", "gift", "Cosmestics", "video", "homegarden", "apparel", "electronics", "maketdetail", "training", "toys", "books", "cartoon", "digital", "Sports", "auto", "adult");
			$r[$key]['NameURL'] = str_replace($a, $b, $value['fid']);
		}
		return $r;
	}

	function gethotallbbs($id) {
		$sql = "SELECT pw_threads.tid,pw_threads.fid,pw_threads.subject FROM pw_threads WHERE fid IN ('$id') ORDER BY hits DESC limit 20";
		$r = DBQuery :: instance()->executeQuery($sql);
		shuffle($r);
		foreach ($r as $key => $value) {
			$a = array ("17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "51");
			$b = array ("food", "travel", "gift", "Cosmestics", "video", "gift", "homegarden", "apparel", "electronics", "maketdetail", "training", "toys", "books", "cartoon", "digital", "Sports", "auto", "adult");
			$r[$key]['NameURL'] = str_replace($a, $b, $value['fid']);
		}
		return $r;
	}

	function gethotrealbbs() {
		$sql = "SELECT pw_threads.tid,pw_threads.subject,pw_forums.fid,pw_forums.name 
						        FROM pw_threads 
								INNER JOIN pw_forums ON (pw_forums.fid=pw_threads.fid) 
								WHERE pw_threads.fid <17 OR pw_threads.fid>53 
								ORDER BY pw_threads.hits DESC 
								limit 20";
		$r = DBQuery :: instance()->executeQuery($sql);
		shuffle($r);
		return $r;
	}

	function gethotrealinfo() {
		$sql = "SELECT pw_threads.tid,pw_threads.subject,pw_forums.fid,pw_forums.name FROM pw_threads INNER JOIN pw_forums ON (pw_forums.fid=pw_threads.fid) WHERE (pw_threads.fid >16 AND pw_threads.fid<33) OR pw_threads.fid=51 ORDER BY pw_threads.hits DESC limit 20";
		$r = DBQuery :: instance()->executeQuery($sql);
		shuffle($r);
		foreach ($r as $key => $value) {
			$a = array ("17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "51");
			$b = array ("food", "travel", "gift", "Cosmestics", "video", "gift", "homegarden", "apparel", "electronics", "maketdetail", "training", "toys", "books", "cartoon", "digital", "Sports", "auto", "adult");
			$r[$key]['NameURL'] = str_replace($a, $b, $value['fid']);
		}
		return $r;
	}

	function gettodayaddcoupon() {
		$tomorrow = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
		$tomorrow = date("Y-m-d", $tomorrow);
		$sql = "SELECT count(*) FROM Coupon WHERE AddDate LIKE '%$tomorrow%'";
		//echo $sql;
		$r = DBQuery :: instance()->getOne($sql);
		return $r;

	}

	function getad($page) {
		$sql = "SELECT * from Ad WHERE PageType='$page'";
		$r = DBQuery :: instance()->executeQuery($sql);
		return $r;
	}

	function getreplies($tid) {
		$sql = "select * from pw_threads inner join pw_posts ON (pw_threads.tid=pw_posts.tid) where pw_threads.dhbid = '$tid' order by pw_posts.postdate DESC";
		$r = DBQuery :: instance()->executeQuery($sql);
		return $r;
	}

	function getrepliesajax($tid, $page) {
		$start = ($page -1) * 10;
		$sql = "SELECT pw_posts.author,pw_posts.content,pw_posts.postdate,pw_posts.icon as picon,pw_members.icon as icon ";
		$sql .= "FROM pw_threads ";
		$sql .= "INNER JOIN pw_posts ON (pw_threads.tid=pw_posts.tid) ";
		$sql .= "LEFT JOIN pw_members ON (pw_members.uid=pw_posts.authorid) ";
		$sql .= "WHERE pw_threads.tid = '$tid' ";
		$sql .= "ORDER BY pw_posts.postdate DESC limit $start,10";

		$r = DBQuery :: instance()->executeQuery($sql);
		return $r;
	}

	function addposts($fid, $tid, $author, $authorid, $content) {
		$postdate = time();
		$sql = "insert into pw_posts (pid,fid,tid,author,authorid,postdate,subject,userip,content,ifconvert,ifwordsfb,ifsign,ifcheck) VALUES ('','$fid','$tid','$author','$authorid','$postdate','re','','$content',1,1,1,1)";
		$r = DBQuery :: instance()->executeUpdate($sql);

		$sql = "UPDATE pw_threads SET replies=replies+1 WHERE tid='$tid' ";
		$r = DBQuery :: instance()->executeUpdate($sql);
		//print_r($r);
		return $r;
	}

	function addpostsForDiscount($fid, $tid, $author, $authorid, $content, $icon) {
		$postdate = time();
		$sql = "insert into pw_posts (pid,fid,tid,author,authorid,postdate,subject,userip,content,ifconvert,ifwordsfb,ifsign,ifcheck,icon) VALUES ('','$fid','$tid','$author','$authorid','$postdate','re','','$content',1,1,1,1,'$icon')";
		$r = DBQuery :: instance()->executeUpdate($sql);

		if ($icon == 1) {
			$sql = "UPDATE pw_threads SET digest=digest+1 WHERE tid='$tid' ";
			$re = DBQuery :: instance()->executeUpdate($sql);
		}
		elseif ($icon == 2) {
			$sql = "UPDATE pw_threads SET delate=delate+1 WHERE tid='$tid' ";
			$re = DBQuery :: instance()->executeUpdate($sql);
		}

		$sql = "UPDATE pw_threads SET replies=replies+1 WHERE tid='$tid' ";
		$r = DBQuery :: instance()->executeUpdate($sql);
		//print_r($r);
		return $r;
	}

	function getMoread() {
		$sql = "select * from Merchant";
		$r = DBQuery :: instance()->executeQuery($sql);
		$array = array ();
		foreach ($r as $key => $value) {
			for ($i = 1; $i < 7; $i ++) {
				$a = "name".$i;
				$b = trim($value[$a]);
				array_push($array, $b);
			}
		}
		$array = array_unique($array);
		foreach ($array as $key => $value) {
			echo $value."<BR>";
		}

	}
	
	function getUserAward($uid){
		$sql = "SELECT a.Type,a.ID,a.AddTime,aw.AwardName from AwardUser a INNER JOIN Award aw ON (aw.ID = a.AwardID)";
		$sql .= "WHERE a.UserID=$uid AND a.Type!='NO' ";
		$sql .= "ORDER BY a.ID DESC";
		return DBQuery :: instance()->executeQuery($sql);	
	}
	
	function getUserAddDiscount($uid,$page=0){
		if($page==0) $page=1;
		$start = ($page-1)*10;
		$sql = "SELECT c.isActive,c.Descript,c.Coupon_,m.NameURL,c.StartDate,c.ExpireDate from Coupon c ";
		$sql .= "LEFT JOIN pw_threads p ON (p.dhbid = c.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE p.authorid=$uid AND c.isActive!=1 AND c.CouponType=9 ";
		$sql .= "ORDER BY c.Coupon_ DESC limit $start,10";
		return DBQuery :: instance()->executeQuery($sql);
	}
	
	function getUserAddDiscountCount($uid) {
		$sql = "SELECT count(*) from Coupon c ";
		$sql .= "LEFT JOIN pw_threads p ON (p.dhbid = c.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE p.authorid=$uid AND c.isActive!=1 AND c.CouponType=9 ";
		return DBQuery :: instance()->getOne($sql);
	}
	
	function getUserDiscount($uid,$page=0) {
		if($page==0) $page=1;
		$start = ($page-1)*10;
		$sql = "SELECT c.Descript,c.Coupon_,m.NameURL,c.StartDate,c.ExpireDate from Coupon c ";
		$sql .= "LEFT JOIN pw_threads p ON (p.dhbid = c.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE p.authorid=$uid AND c.isActive=1 AND c.CouponType=9 ";
		$sql .= "ORDER BY c.Coupon_ DESC limit $start,10";
		return DBQuery :: instance()->executeQuery($sql);
	}
	
	function getUserDiscountCount($uid) {
		$sql = "SELECT count(*) from Coupon c ";
		$sql .= "LEFT JOIN pw_threads p ON (p.dhbid = c.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE p.authorid=$uid AND c.isActive=1 AND c.CouponType=9 ";
		return DBQuery :: instance()->getOne($sql);
	}
	
	function getUserAddCoupon($uid,$page=0){
		if($page==0) $page=1;
		$start = ($page-1)*10;
		$sql = "SELECT c.isActive,c.Descript,c.Coupon_,m.NameURL,c.StartDate,c.ExpireDate from Coupon c ";
		$sql .= "LEFT JOIN pw_threads p ON (p.dhbid = c.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE p.authorid=$uid AND c.isActive!=1 AND c.CouponType!=9 ";
		$sql .= "ORDER BY c.Coupon_ DESC limit $start,10";
		return DBQuery :: instance()->executeQuery($sql);
	}
	
	function getUserAddCouponCount($uid){
		$sql = "SELECT count(*) from Coupon c ";
		$sql .= "LEFT JOIN pw_threads p ON (p.dhbid = c.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE p.authorid=$uid AND c.isActive!=1 AND c.CouponType!=9 ";
		return DBQuery :: instance()->getOne($sql);
	}
	
	function getUserCoupon($uid,$page=0) {
		if($page==0) $page=1;
		$start = ($page-1)*10;
		$sql = "SELECT c.Descript,c.Coupon_,m.NameURL,c.StartDate,c.ExpireDate from Coupon c ";
		$sql .= "LEFT JOIN pw_threads p ON (p.dhbid = c.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE p.authorid=$uid AND c.isActive=1 AND c.CouponType!=9 ";
		$sql .= "ORDER BY c.Coupon_ DESC limit $start,10";
		return DBQuery :: instance()->executeQuery($sql);
	}
	
	function getUserCouponCount($uid) {
		$sql = "SELECT count(*) from Coupon c ";
		$sql .= "LEFT JOIN pw_threads p ON (p.dhbid = c.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE p.authorid=$uid AND c.isActive=1 AND c.CouponType!=9 ";
		return DBQuery :: instance()->getOne($sql);
	}
	
	function getUserFavorCoupon($uid,$page=0) {
		if($page==0) $page=1;
		$start = ($page-1)*10;
		$sql = "SELECT c.Coupon_,c.Descript,c.StartDate,c.ExpireDate,m.NameURL ";
		$sql .= "FROM Wallet LEFT JOIN Coupon c ON (c.Coupon_=Wallet.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE Wallet.Customer_='$uid' AND c.isActive=1 AND c.CouponType!=9 "; 
		$sql .= "ORDER BY Wallet.LastUpdate DESC limit $start,10";
		return DBQuery :: instance()->executeQuery($sql);
	}
	
	function getUserFavorCouponCount($uid) {
		$sql = "SELECT count(*) ";
		$sql .= "FROM Wallet LEFT JOIN Coupon c ON (c.Coupon_=Wallet.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE Wallet.Customer_='$uid' AND c.isActive=1 AND c.CouponType!=9 "; 
		return DBQuery :: instance()->getOne($sql);
	}
	
	function getUserFavorDiscount($uid,$page=0) {
		if($page==0) $page=1;
		$start = ($page-1)*10;
		$sql = "SELECT c.Coupon_,c.Descript,c.StartDate,c.ExpireDate,m.NameURL ";
		$sql .= "FROM Wallet LEFT JOIN Coupon c ON (c.Coupon_=Wallet.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE Wallet.Customer_='$uid' AND c.isActive=1 AND c.CouponType=9 "; 
		$sql .= "ORDER BY Wallet.LastUpdate DESC limit $start,10";
		return DBQuery :: instance()->executeQuery($sql);
	}
	
	function getUserFavorDiscountCount($uid) {
	
		$sql = "SELECT count(*) ";
		$sql .= "FROM Wallet LEFT JOIN Coupon c ON (c.Coupon_=Wallet.Coupon_) ";
		$sql .= "LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) ";
		$sql .= "WHERE Wallet.Customer_='$uid' AND c.isActive=1 AND c.CouponType=9 "; 
		return DBQuery :: instance()->getOne($sql);
	}

	function updateMoread() {
		$sql = "select * from Merchant";
		$r = DBQuery :: instance()->executeQuery($sql);
		$array = array ();
		foreach ($r as $key => $value) {
			$res = 0;
			$name = "";
			for ($i = 1; $i < 7; $i ++) {
				$a = "name".$i;
				$b = trim($value[$a]);
				$sql = "SELECT ad from keywordlist WHERE keyword = '$b'";
				$result = DBQuery :: instance()->getOne($sql);
				if ($result > $res) {
					$res = $result;
					$name = $a;
				}
			}
			$id = $value['Merchant_'];
			$sql = "UPDATE Merchant SET adwhichmore= '$name' WHERE Merchant_='$id'";
			$result = DBQuery :: instance()->executeQuery($sql);

		}

	}

	function bbsusercoupon() {
		$sql = "select * from pw_members WHERE oicq='mezi'";
		$r = DBQuery :: instance()->executeQuery($sql);

		$sql = "select * from pw_threads WHERE authorid=0";
		$rr = DBQuery :: instance()->executeQuery($sql);

		foreach ($rr as $key => $value) {
			$tid = $value['tid'];
			$rand_keys = array_rand($r, 1);
			$username = $r[$rand_keys]['username'];
			$uid = $r[$rand_keys]['uid'];
			$sql = "update pw_threads SET author='$username',authorid='$uid' WHERE tid='$tid'";
			$resul = DBQuery :: instance()->executeQuery($sql);
		}

		//echo $rand_keys;
		//print_r($r);

		return 1;

	}

	function gethotuser() {
		$sql = "select pw_memberdata.money,pw_members.username,pw_members.icon from pw_memberdata inner join pw_members ON (pw_members.uid = pw_memberdata.uid) order By money DESc limit 1,20";
		$result = DBQuery :: instance()->executeQuery($sql);
		foreach ($result as $key => $value) {
			$a = explode("|", $value['icon']);
			$result[$key]['icon'] = $a[0];
		}
		return $result;
	}

	function couponline() {
		$sql = "SELECT * from couponline ORDER by id desc limit 301";
		$result = DBQuery :: instance()->executeQuery($sql);
		$j = 0;
		$new = array ();
		for ($i = 30; $i > -1; $i --) {
			if ($j % 3 == 0) {
				$new[$j] = $result[$i];
			}
			$j ++;
		}

		$j = 0;
		$new1 = array ();
		for ($i = 70; $i > -1; $i --) {
			if ($j % 7 == 0) {
				$new1[$j] = $result[$i];
			}
			$j ++;
		}

		$j = 0;
		$new2 = array ();
		for ($i = 300; $i > -1; $i --) {
			if ($j % 30 == 0) {
				$new2[$j] = $result[$i];
			}
			$j ++;
		}

		//for($i)

		$example_data = array (array ($new[0][day], 1, $new[0][num]), array ($new[3][day], 4, $new[3][num]), array ($new[6][day], 7, $new[6][num]), array ($new[9][day], 10, $new[9][num]), array ($new[12][day], 13, $new[12][num]), array ($new[15][day], 16, $new[15][num]), array ($new[18][day], 19, $new[18][num]), array ($new[21][day], 22, $new[21][num]), array ($new[24][day], 25, $new[24][num]), array ($new[27][day], 28, $new[27][num]), array ($new[30][day], 31, $new[30][num]),);

		$example_data1 = array (array ($new1[0][month].".".$new1[0][day], 1, $new1[0][num]), array ($new1[7][month].".".$new1[7][day], 2, $new1[7][num]), array ($new1[14][month].".".$new1[14][day], 3, $new1[14][num]), array ($new1[21][month].".".$new1[21][day], 4, $new1[21][num]), array ($new1[28][month].".".$new1[28][day], 5, $new1[28][num]), array ($new1[35][month].".".$new1[35][day], 6, $new1[35][num]), array ($new1[42][month].".".$new1[42][day], 7, $new1[42][num]), array ($new1[49][month].".".$new1[49][day], 8, $new1[49][num]), array ($new1[56][month].".".$new1[56][day], 9, $new1[56][num]), array ($new1[63][month].".".$new1[63][day], 10, $new1[63][num]), array ($new1[70][month].".".$new1[70][day], 11, $new1[70][num]),);

		$example_data2 = array (array ($new2[0][month], 1, $new2[0][num]), array ($new2[30][month], 2, $new2[30][num]), array ($new2[60][month], 3, $new2[60][num]), array ($new2[90][month], 4, $new2[90][num]), array ($new2[120][month], 5, $new2[120][num]), array ($new2[150][month], 6, $new2[150][num]), array ($new2[180][month], 7, $new2[180][num]), array ($new2[210][month], 8, $new2[210][num]), array ($new2[240][month], 9, $new2[240][num]), array ($new2[270][month], 10, $new2[270][num]), array ($new2[300][month], 11, $new2[300][num]),);

		//print_r($example_data);
		//$example_data = array("example_data"=>$example_data);
		$tomorrow = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
		$tomorrow = date("Y-m-d", $tomorrow);
		$sql = "select * from Coupon WHERE AddDate>'$tomorrow'";
		$cadd = DBQuery :: instance()->getOne($sql);

		$file = fopen(__SETTING_FULLPATH."array/array_day_coupon.php", "w");
		fwrite($file, "<?php");
		fwrite($file, "\n");
		//$keys = array_keys($example_data);
		//for ($i=0;$i<count($example_data);$i++){
		//$rs = $example_data[$keys[$i]];
		$string_to_be_written_to_file = var_export($example_data, true);
		fwrite($file, "$");
		fwrite($file, "example_data=");
		fwrite($file, $string_to_be_written_to_file);
		fwrite($file, ";");

		$string_to_be_written_to_file = var_export($example_data1, true);
		fwrite($file, "$");
		fwrite($file, "example_data1=");
		fwrite($file, $string_to_be_written_to_file);
		fwrite($file, ";");

		$string_to_be_written_to_file = var_export($example_data2, true);
		fwrite($file, "$");
		fwrite($file, "example_data2=");
		fwrite($file, $string_to_be_written_to_file);
		fwrite($file, ";");

		fwrite($file, "$");
		fwrite($file, "day=\"");
		fwrite($file, $new[0]['year'].".".$new[0]['month'].".".$new[0]['day']);
		fwrite($file, "\";");

		fwrite($file, "$");
		fwrite($file, "week=\"");
		fwrite($file, $new1[0]['year'].".".$new1[0]['month'].".".$new1[0]['day']);
		fwrite($file, "\";");

		fwrite($file, "$");
		fwrite($file, "week2=\"");
		fwrite($file, $new1[70]['year'].".".$new1[70]['month'].".".$new1[70]['day']);
		fwrite($file, "\";");

		
		$yeary = $new2[0]['year'];
		$monthy = $new2[0]['month'];
		

		fwrite($file, "$");
		fwrite($file, "month=\"");
		fwrite($file, $yeary.".".$monthy);
		fwrite($file, "\";");

		fwrite($file, "$");
		fwrite($file, "month2=\"");
		fwrite($file, $new2[300]['year'].".".$new2[300]['month']);
		fwrite($file, "\";");

		fwrite($file, "$");
		fwrite($file, "day2=\"");
		fwrite($file, $new[30]['year'].".".$new[30]['month'].".".$new[30]['day']);
		fwrite($file, "\";");

		fwrite($file, "$");
		fwrite($file, "cnum=\"");
		fwrite($file, $new[30]['num']);
		fwrite($file, "\";");

		fwrite($file, "$");
		fwrite($file, "cadd=\"");
		fwrite($file, $cadd);
		fwrite($file, "\";");
		//}
		fwrite($file, "\n");
		fwrite($file, "?>");
		fclose($file);

	}


}
?>