<?php
if ( !defined("CLASS_SEARCH_PHP") )
{
	define("CLASS_SEARCH_PHP","YES");

	//include(__INCLUDE_ROOT."lib/classes/class.QueryMySQL.php");
	//include(__INCLUDE_ROOT."lib/classes/class.PrePostWord.php");
	//require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
	//require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
	require_once(__INCLUDE_ROOT."lib/classes/class.WordSplitter.php");
	
	class Search
	{//{{{
		
		var $ClassName = "Search";
		var $SearchExpr = "";
		var $CM2Count = 0;
		var $SearchList = array();
		var $SE_FIELD_WEIGHT = array("Title" => "40","Descript" => "16");
		var $KeywordsSql = "";
		var $SearchListCount = 0;
		 
		function Search($sExpr){
			$this->SearchExpr = $sExpr;
			$this->writeKeyLog();
		}
		
		function search_(){
			if ( $this->SearchExpr != " " && !empty($this->SearchExpr) ){
				//$this->prepare_search();
				$this->cleanKeywords();
				$this->doSearch();
			}
			return $this->SearchList;
		}
		
		function searchForSTX($p, $perPage){
			if ( $this->SearchExpr != " " && !empty($this->SearchExpr) ){
				//$this->prepare_search();
				$this->cleanKeywords();
				$this->doSearchForSTX($p, $perPage);
			}
			return $this->SearchList;
		}
		
		function cleanKeywords() {
		
			$oSplitter = new WordSplitter($this->SearchExpr);
			$this->KeywordsSql = DBQuery::filter($oSplitter->execute("+(",")"));
			return $this->KeywordsSql;
		}
		
		function doSearch(){

			$sql = "SELECT p.CityId,p.NewsId,p.Type,(50*(MATCH(p.Title) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+5*(MATCH(p.Descript) AGAINST('" . addslashes($this->KeywordsSql) . "'))+50*(MATCH(p.Tag) AGAINST('" . addslashes($this->KeywordsSql) . "'))+30*(MATCH(p.Category) AGAINST('" . addslashes($this->KeywordsSql) . "'))+p.Rank+0) AS Relevance FROM ".__DB_SE.".News_SE p WHERE MATCH(p.Title,p.Descript,p.Tag,p.Category) AGAINST('" . addslashes($this->KeywordsSql) . "' IN BOOLEAN MODE) ORDER BY Relevance DESC";
			$result = DBQuery::instance(__SE)->executeQuery($sql);
			$cityid = empty($_COOKIE['cityid'])?21:$_COOKIE['cityid'];
			$array1 = $array2 = array();
			foreach($result as $key=>$value){
				if($cityid == $value['CityId']){
					$array1[] = $value;
				}else{
					$array2[] = $value;
				}
			}
			$result = array_merge($array1,$array2);
			$this->SearchList = $result;
		}

		function getCouponDetailList($idarray,$page=1,$perPage=10){
			$idstring ="";
			foreach($idarray as $key=>$value){
				$idstring .= $value['NewsId'].",";
			}
			$idstring = substr($idstring,0,-1);
			$start = ($page-1) * $perPage;
			$sql = "SELECT m.NameURL,c.ImageDownload,c.Coupon_,GROUP_CONCAT(Tag.tagname ORDER BY Tag.weight ASC,Tag.count DESC) as tagname,c.Descript,c.Detail,p.hits,p.digest,p.replies,c.AddDate,c.ExpireDate,m.Name name,m.name1 name1,m.Merchant_ Merchant_ FROM Coupon c ";
			$sql .= "LEFT join CouponTag ON(CouponTag.couponid = c.Coupon_) LEFT join Tag ON (Tag.id = CouponTag.tagid) INNER JOIN pw_threads p ON (p.dhbid = c.Coupon_) LEFT JOIN Merchant m ON (m.Merchant_ = c.Merchant_) WHERE c.Coupon_ IN (".$idstring.") GROUP BY c.Coupon_ ORDER BY FIND_IN_SET(c.Coupon_,'".$idstring."') LIMIT $start,$perPage";
			//echo $sql;
			return DBQuery::instance()->executeQuery($sql);
		}

		function getInfoDetailList($idarray,$page=1,$perPage=10){
			$idstring ="";
			foreach($idarray as $key=>$value){
				$idstring .= $value['NewsId'].",";
			}
			$idstring = substr($idstring,0,-1);
			$start = ($page-1) * $perPage;
			$sql = "SELECT p.fid,p.tid,p.subject,p.author,p.authorid,TRIM(SUBSTRING(pt.content,1,400)) AS content,p.hits,p.postdate ";
			$sql .= "FROM pw_threads p INNER JOIN pw_tmsgs pt ON (pt.tid = p.tid) WHERE p.tid IN (".$idstring.") ORDER BY FIND_IN_SET(p.tid,'".$idstring."') LIMIT $start,$perPage";
			$result = DBQuery::instance()->executeQuery($sql);
			foreach ($result as $key=>$value){
				//$result[$key]['content'] = trim($value['content']);
				$result[$key]['content'] = preg_replace("/\s/","",$result[$key]['content']);
				//$result[$key]['content'] = ltrim($result[$key]['content']);

				$a = array("17", "18", "19","20","21","22","23","24","25","26","27","28","29","30","31","32","51");
				$b = array("food", "travel", "gift","Cosmestics","video","homegarden","apparel","electronics","maketdetail","training","toys","books","cartoon","digital","Sports","auto","adult");
				$result[$key]['NameURL'] = str_replace($a, $b, $value['fid']);
				//$result[$key]['content'] = str_replace(" ","",$result[$key]['content']);
				//$result[$key]['content'] = preg_replace("/&nbsp;/","",$result[$key]['content']);
			}
			return $result;
			//print_r($result);
			//exit();
		}

		function getNewPageStr($nowPage,$maxPage,$type,$se) {
	  	  
	  	  $str = "<div class=\"page\"><ul><li>当前第".$nowPage."页,共".$maxPage."页</li>";
		  if($nowPage > 1) {
		  	  $url = "/se-".$se."-".($nowPage-1)."-".$type."/";
		  	  $str .= "<li><a href='$url'>上一页</a></li>";  
		  }
		  if($maxPage <= 5) {
		  	  for($i=1; $i<=$maxPage; $i++) {
				  $url = "/se-".$se."-".$i."-".$type."/";
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
			  $url = "/se-".$se."-1-".$type."/";
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
			  $str .= "<li>...</li>";
			  for($i=$starti; $i<=$starti+4 && $i<=$maxPage; $i++) {
				  $url = "/se-".$se."-".$i."-".$type."/";
				  if($nowPage == $i) {
					  $str .= "<li>$i</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  $str .= "<li>...</li>";
			  
		  } elseif($nowPage <= 4) {
		  	  for($i=1; $i<=5; $i++) {
				  $url = "/se-".$se."-".$i."-".$type."/";
				  if($nowPage == $i) {
					  $str .= "<li>".$i."</li>";
				  } else {
					  $str .= "<li><a href='$url'>$i</a></li>";
				  }
			  }
			  $str .= "<li>...</li>";
		  } else {
		  	   $url = "/se-".$se."-1-".$type."/";
			  $str .= "<li><a href='$url' class='blue'>1</a></li>";
				if($nowPage >= ($maxPage - 2)){
					$str .= "<li>...</li>";
				}else{
			  		
				}
			  $starti = $maxPage -4;
		  	  for($i=$starti; $i<=$maxPage; $i++) {
				  $url = "/se-".$se."-".$i."-".$type."/";
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
		  	  $url = "/se-".$se."-".$maxPage."-".$type."/";
		  	  $str .= "<li><a href='$url'>".$maxPage."</a></li>";  
		  }
		  
		  if($nowPage != $maxPage) {
			  $url = "/se-".$se."-".($nowPage+1)."-".$type."/";
		  	  $str .= "<li><a href='$url'>下一页</a></li>";  
		  }
		  $str .= "</ul></div>";
	  	  return $str;
	  }
		
		function doSearchForSTX($page=1,$perPage=10){
			$limitStart = ($page -1) * $perPage;
			$sql = "SELECT m.isActive as MerIsActive,m.NameURL,m.Name,m.Merchant_,t.StartDate,t.ExpireDate,t.Descript as DescriptOri,t.Detail as DetailOri,t.Coupon_,p.CityId,p.NewsId,p.Type, ";
			$sql .="(50*(MATCH(p.Title) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "'))+5*(MATCH(p.Descript) AGAINST('" . addslashes($this->KeywordsSql) . "'))+50*(MATCH(p.Tag) AGAINST('" . addslashes($this->KeywordsSql) . "'))+30*(MATCH(p.Category) AGAINST('" . addslashes($this->KeywordsSql) . "'))+p.Rank+0) AS Relevance ";
			$sql .= "FROM ".__DB_SE.".News_SE p ";
			$sql .= "INNER JOIN ".__DB_FRONT.".Coupon t ON (t.Coupon_ = p.NewsId) INNER JOIN ".__DB_FRONT.".Merchant m ON (m.Merchant_ = t.Merchant_) INNER JOIN ".__DB_FRONT.".CoupCat ca ON (t.Coupon_ = ca.Coupon_) INNER JOIN ".__DB_FRONT.".Category c ON (c.Category_ = ca.Category_)";
			$sql .= "WHERE MATCH(p.Title,p.Descript,p.Tag,p.Category) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "' IN BOOLEAN MODE)  AND Type=1 ";
			$sql .= "ORDER BY Relevance DESC ";
			$sql .= "limit $limitStart,10";
			//echo $sql;
			$this->SearchList = DBQuery::instance(__SE)->executeQuery($sql);
		}
		function doSearchCountForSTX($page=1,$perPage=10){
			$sql = "SELECT count(*) FROM ".__DB_SE.".News_SE p ";
			$sql .= "WHERE MATCH(p.Title,p.Descript,p.Tag,p.Category) AGAINST('" . DBQuery::filter($this->KeywordsSql) . "' IN BOOLEAN MODE) AND Type=1";
			$this->SearchListCount = DBQuery::instance(__SE)->getOne($sql);
			return $this->SearchListCount;
		}
		function writeKeyLog(){
			$sql = "INSERT INTO KeyLog(Keywords,UpdateDate) VALUES('$this->SearchExpr',NOW())";
			DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		}

	}//}}}

}
?>
