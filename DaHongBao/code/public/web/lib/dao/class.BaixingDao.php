<?PHP
	
class BaixingDao {
	
	const TIME_LIMIT = 3;    //default cityid 21.SHANGHAI

	function getBaixingDetail($nowkjjcityid,$baixingID){
		$sql = "SELECT * FROM BaixingDetail WHERE CityID=$nowkjjcityid AND BaixingID=$baixingID";
		$re = DBQuery::instance()->getRow($sql);
		if(!empty($re)){
			return $re;
		}else{
			$re = BaixingDao::getBaixingDetailByURL($nowkjjcityid,$baixingID);
			return $re;
		}
	}

	function getBaixingDetailByURL($nowkjjcityid,$baixingID){
		$nameurl = BaixingDao::getNameUrlByCityID($nowkjjcityid);
		$url = "http://".$nameurl.".baixing.com/root/a".$baixingID.".html";

		$curl = CURL::getInstance();
		$curl->setTimeout(self::TIME_LIMIT);
		$contents = $curl->get_contents($url);
		$contents = iconv( "UTF-8", "GBK//IGNORE" , $contents);

		preg_match("/\">(.*?)<\/a><br \/>/i",$contents,$url);
		$url = $url[1];

		if(strpos($url,"http")!==false){
			$curl->setTimeout(self::TIME_LIMIT);
			$contents = $curl->get_contents($url);
			$contents = iconv( "UTF-8", "GBK//IGNORE" , $contents);
		}

		$contents = str_replace("\n","",$contents);
		$contents = str_replace("\r","",$contents);

		preg_match("/<h1 id=\"toRootTitle\" align=\"center\">(.*?)<\/h1/i",$contents,$title);
		$dataarray['Title'] = $title[1];

		//解析属性，拼装成content
		preg_match("/<table id=\"viewAttributes\">(.*?)<\/table/i",$contents,$attr);
		preg_match_all("/<tr><td>(.*?)<\/td/i",$attr[1],$attr);
		$attr = $attr[1];
		$attrstring = "";
		for($i=0;$i<count($attr)-1;$i++){
			$attrstring .= $attr[$i];
			$attrstring .= "MYBR";
		}
		$attrstring = strip_tags($attrstring);
		$attrstring = str_replace("MYBR","<BR>",$attrstring);
		$dataarray['Content'] = $attrstring."<BR>";

		//解析详细信息
		preg_match("/<\/h4>(.*?)<\/div/i",$contents,$content);
		$content[1] = preg_replace("/<a (.*?)>/i","",$content[1]);
		$content[1] = preg_replace("/<\/a>/i","",$content[1]);
		$dataarray['Content'] .= $content[1]."</div>";

		preg_match("/发布时间：(.*?)<\/td/i",$contents,$posttime);
		$dataarray['PostTime'] .= $posttime[1];

		preg_match("/alt=\"点击看大图\" src=\"(.*?)\"/i",$contents,$imageurl);
		$dataarray['ImageUrl'] .= str_replace("_sm","",$imageurl[1]);

		$sql = "INSERT INTO BaixingDetail (`BaixingID`,`CityID`,`Title`,`Content`,`PostTime`,`AddTime`,`ImageUrl`) VALUES (".$baixingID.",".$nowkjjcityid.",'".DBQuery::filter($dataarray['Title'])."','".DBQuery::filter($dataarray['Content'])."','".DBQuery::filter($dataarray['PostTime'])."',NOW(),'".DBQuery::filter($dataarray['ImageUrl'])."')";
		$re = DBQuery::instance()->executeQuery($sql);
		return $dataarray;


	}

	function getBaixingSearch($nowkjjcityid,$keywords){
		$sql = "SELECT Data FROM BaixingSearch WHERE CityID=$nowkjjcityid AND Keyword='".DBQuery::filter($keywords)."'";
		$re = DBQuery::instance()->getOne($sql);
		if($re){
			return unserialize($re);
		}else{
			$re = BaixingDao::getBaixingSearchByURL($nowkjjcityid,$keywords);
			return $re;
		}
	}
	
	function getBaixingSearchByURL($nowkjjcityid,$keywords){
		$utf8keywords = iconv( "GBK", "UTF-8//IGNORE" , $keywords);

		//shanghaineed to do
		$nameurl = BaixingDao::getNameUrlByCityID($nowkjjcityid);
		$url = "http://".$nameurl.".baixing.com/root/W0QQqZ".urlencode($utf8keywords);

		$curl = CURL::getInstance();
		$curl->setTimeout(self::TIME_LIMIT);
		$contents = $curl->get_contents($url);
		
		$contents = iconv( "UTF-8", "GBK//IGNORE" , $contents);
		$contents = str_replace("\n","",$contents);
		$contents = str_replace("\r","",$contents);

		preg_match("/<tbody id=\"listBody\">(.*?)<\/tbody>/i",$contents,$contentbody);
		unset($contents);
		
		preg_match_all("/<td style=\"width:64px\">(.*?)<\/tr>/i",$contentbody[1],$contentdetail);
		unset($contentbody[1]);

		$dataarray = array();
		foreach($contentdetail[1] as $key=>$value){
			
			preg_match("/alt=\"(.*?)\"/i",$value,$title);
			$row['Title'] = $title[1];

			preg_match("/<\/big><br \/>(.*?)<\/p>/i",$value,$content);
			$row['Content'] = $content[1];

			preg_match("/src=\"(.*?)\"/i",$value,$imgsrc);
			$row['ImageSrc'] = $imgsrc[1];

			preg_match("/\/a(.*?).html\" target=/i",$value,$baixingid);
			$row['BaiXingID'] = $baixingid[1];
			
			$dataarray[] = $row;
			unset($row);
		}
		unset($contentdetail[1]);

		$serializedata = serialize($dataarray);
		$sql = "INSERT INTO BaixingSearch (`CityID`,`Keyword`,`Data`,`AddTime`) VALUES (".$nowkjjcityid.",'".DBQuery::filter($keywords)."','$serializedata',NOW())";
		$re = DBQuery::instance()->executeQuery($sql);
		return $dataarray;
	}

	function getNameUrlByCityID($cityid){
		$sql = "SELECT EnName FROM City WHERE CityID=$cityid";
		$re = DBQuery::instance()->getOne($sql);
		if($re){
			return $re;
		}
	}
	

}