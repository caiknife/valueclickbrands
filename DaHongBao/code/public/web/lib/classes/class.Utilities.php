<?PHP


class Utilities {
	protected static $a = 0;
	protected static $A = 0;
	protected static $z = 0;
	protected static $Z = 0;
	protected static $n0 = 0;
	protected static $n9 = 0;
	protected static $filter = array();

	protected static function init() {
		if(self::$a != 0) {
			return;
		}
		self::$a = ord('a');
		self::$A = ord('A');
		self::$z = ord('z');
		self::$Z = ord('Z');
		self::$n0 = ord('0');
		self::$n9 = ord('9');
//		self::$filter = array(ord('.')=>1, ord(',')=>1);
		self::$filter = array(ord('.')=>1);
	}

	/**
	 * 中文编码
	 */
	public static function &encode($str) {
		if(!is_string($str)) {
			return $str;
		}
		self::init();
		//return base64_encode($str);
		$ret = "";
		$len = strlen($str);
		$in = false;
		for($loop=0; $loop<$len; $loop++) {
			$ch = ord($str[$loop]);
			if(($ch >= self::$a && $ch <= self::$z)
				|| ($ch >= self::$A && $ch <= self::$Z)
				|| ($ch >= self::$n0 && $ch <= self::$n9)
				|| isset(self::$filter[$ch])) {
				if($in) {
					$ret .= "_";
					$in = false;
				}
				$ret .= chr($ch);
				continue;
			}
			if($in == false) {
				$ret .= "_";
				$in = true;
			}
			$ret .= chr(self::$a + intval($ch/16));
			$ret .= chr(self::$a + intval($ch%16));
			if($ch > 127 && $loop+1<$len) { //中文字的第二字节处理
				$ch = ord($str[++$loop]);
				$ret .= chr(self::$a + intval($ch/16));
				$ret .= chr(self::$a + intval($ch%16));
			}
		}
		return $ret;
	}


	/**
	 * 中文解码
	 */
	public static function &decode($str) {
		self::init();
		//return base64_decode($str);
		$ret = "";
		$len = strlen($str);
		for($loop=0; $loop<$len; $loop++) {
			if($str[$loop] == '_') {
				for($loop++; $loop<$len; $loop+=2) {
					if($str[$loop] == '_' || $loop+1 == $len) { //最后一位不加'_'
						break;
					}
					$ch = $str[$loop];
					$ch2 = $str[$loop+1];
					$word = (ord($ch) - self::$a) * 16;
					$word += ord($ch2) - self::$a;
					if($word < 0) {
						$word = ord('?');
					}
					$ret .= chr($word);
				}
			} else {
				$ret .= $str[$loop];
			}
		}
		return $ret;
	}

	/**
	 * 过滤标点符号等
	 */
	public static function filterChar($str) {
		self::init();
		$str = self::encode($str);
		$ret = "";
		$len = strlen($str);
		for($loop=0; $loop<$len; $loop++) {
			if($str[$loop] == '_') {
				$tmp = "";
				for($loop++; $loop<$len; $loop+=2) {
					if($str[$loop] == '_' || $loop+1 == $len) { //最后一位不加'_'
						break;
					}
					$ch = $str[$loop];
					$ch2 = $str[$loop+1];

					$word = (ord($ch) - self::$a) * 16;
					$word += ord($ch2) - self::$a;
					if($word < 0x80) { //单字节
						if($word == 0x20) { //空格
						//	$tmp .= substr($str, $loop, 2);
						}
						$tmp .= "ca"; //空格的编码
						continue;
					}
					if($word < 0xB0) { //双字节
						$loop += 2;
						$tmp .= "ca"; //空格的编码
						continue;
					}
					if($word > 0xF7) { //双字节
						$loop += 2;
						$tmp .= "ca"; //空格的编码
						continue;
					}
					$tmp .= substr($str, $loop, 4);
					$loop += 2;
				}
				if($tmp != "") {
					if($loop == $len) {
						$ret .= "_".$tmp;
					} else {
						$ret .= "_".$tmp."_";
					}
				}
			} else {
				$ret .= $str[$loop];
			}
		}

		return self::decode($ret);
	}

	/**
	 * 对二维数组以列名排序
	 */
	public static function sortby($arr2, $fieldName, $sortType=SORT_ASC) {
		foreach($arr2 as $key => $arr) {
			$keys[$key] = $arr[$fieldName];
		}
		if($sortType == SORT_ASC) {
			asort($keys);
		} else {
			arsort($keys);
		}
		reset($keys);
		$data = array();
		foreach($keys as $key => $val) {
			$data[] = $arr2[$key];
		}
		return $data;
	}

	/**
	 * 转换数据编码(忽略不可转换字)
	 * @param $data 可以是字符串或数组
	 * @param $srcEncoding 源编码 如GB2312
	 * @param $destEncoding 目标编码 如UTF-8
	 */
	public static function &convertEncoding($data, $srcEncoding, $destEncoding) {
		if(is_array($data)) {
			foreach($data as $key => $val) {
				if(is_array($val)) {
					$data[$key] = self::convertEncoding($val, $srcEncoding, $destEncoding);
				} else {
					$data[$key] = iconv($srcEncoding, $destEncoding."//IGNORE", $val);
				}
			}
		} else {
			$data = iconv($srcEncoding, $destEncoding."//IGNORE", $data);
		}
		return $data;
	}

	/**
	 * 取得评分等级的图片(包括IMG标签)
	 * @param $score 分数
	 *
	 * getRatingImg($RatingValue = 0) is used to fetch the rating image by a rating value
	 */
	public static function getRatingImageHtml($RatingValue = 0)
	{
	  	 $sRatingImage = "";
		 $sRatingImageAlt = "";
	  	 //if($RatingValue == -1) $RatingValue = $this->get("r_AvgRating");
		 if(is_numeric($RatingValue))
		 {
	        if($RatingValue <= 0) $RatingValue = 0;
			else if($RatingValue <= 5) $RatingValue = $RatingValue * 20;
			else if($RatingValue >= 100) $RatingValue = 100;

			if($RatingValue >= 5)
			{
				$n = intval($RatingValue / 10) * 10;
				if($RatingValue >= $n + 5)
				{
					$sRatingImage = "bar_overall_" . ($n + 5) . ".gif";
					$sRatingImageAlt = ($n + 5) . "%";
				} else {
					$sRatingImage = "bar_overall_" . $n . ".gif";
					$sRatingImageAlt = ($n) . "%";
				}
			}
		 }
		 if($sRatingImage == "") return "";
		 else return("<img align=\"middle\" src=\"".__HOME_ROOT_PATH."images/".__SETTING_STYLE . $sRatingImage
		 . "\" width=\"100\" height=\"15\" border=0 alt=\"" . $sRatingImageAlt . "\">");
	}

	public static function getAvgRating($RatingValue = 0) {

	  	 $sRating = "";
		 $sRatingAlt = "";
		 if(is_numeric($RatingValue))
		 {
		    if($RatingValue <= 0) $RatingValue = 0;
			else if($RatingValue <= 5) $RatingValue = $RatingValue * 20;
			else if($RatingValue >= 100) $RatingValue = 100;

			if($RatingValue >= 5)
			{
				$n = intval($RatingValue / 10) * 10;
				if($RatingValue >= $n + 5)
				{
					$sRating = ($n + 5). "%";
					//$sRatingAlt = ($n + 5) . "%";
				} else {
					$sRating = ($n) . "%";
					//$sRatingAlt = ($n) . "%";
				}
			}
		 }
		 if($sRating == "") return "";
		 else return $sRating;
	}

	/**
	 * 转换为一维数组
	 * @param $arr2 二维数据
	 * @param $key 第二维的Key
	 */
	public static function convertSimpleArray($arr2, $field) {
		$arr = array();
		for($loop=0; $loop<count($arr2); $loop++) {
			$arr[] = $arr2[$loop][$field];
		}
		return $arr;
	}

	/**
	 * 取唯一数组
	 */
	public static function distinct($arr, $field=NULL) {
		if($arr == NULL) {
			return NULL;
		}
		$dis = array();
		foreach($arr as $val) {
			if($field == NULL) {
				$key = $val;
			} else {
				$key = $val[$field];
			}
			if(empty($key)) {
				continue;
			}
			$dis[$val[$field]] = NULL;
		}
		if(count($dis) == 0) {
			return NULL;
		}
		$ret = array();
		foreach($dis as $key=>$val) {
			$ret[] = $key;
		}
		return $ret;
	}

    /**
     * 动态计算price range
     * @param $prices 价格的数组
     * @return Array(array('start'=>0, 'end'=>10),
     * 		array('start'=>10, 'end'=>30),..,array('start'=>100, 'end'=>-1))
     * 		其中0,显示为"小于..", -1为"大于.."
     */
    public static function createPriceRange($prices) {
    	if(count($prices) < 10) {
    		return NULL;
    	}
    	$range = array();
    	sort($prices);
		if(($cnt = count($prices))>5) {
			for($i = 0; $i < $cnt; $i++){
				$pri[$prices[$i]] = $prices[$i];
			}
			sort($pri);
			$prices = $pri;
			$cnt = count($prices);
			if($cnt >= 10) {
				$suffix[] = intval($cnt / 5);
				$suffix[] = intval($cnt / 5) * 2;
				$suffix[] = intval($cnt / 5) * 3;
				$suffix[] = intval($cnt / 5) * 4;

				$range[] = array('Start'=>0, 'End'=>$prices[$suffix[0]]);
				$range[] = array('Start'=>$prices[$suffix[0]], 'End'=>$prices[$suffix[1]]);
				$range[] = array('Start'=>$prices[$suffix[1]], 'End'=>$prices[$suffix[2]]);
				$range[] = array('Start'=>$prices[$suffix[2]], 'End'=>$prices[$suffix[3]]);
				$range[] = array('Start'=>$prices[$suffix[3]], 'End'=>-1);
			} elseif(5 < $cnt && $cnt < 10) {
				$suffix[] = intval($cnt / 3);
				$suffix[] = intval($cnt / 3) * 2;

				$range[] = array('Start'=>0, 'End'=>$prices[$suffix[0]]);
				$range[] = array('Start'=>$prices[$suffix[0]], 'End'=>$prices[$suffix[1]]);
				$range[] = array('Start'=>$prices[$suffix[1]], 'End'=>-1);
			} else {
				$range[] = array('Start'=>$prices[0], 'End'=>($prices[$cnt - 1] + 1));
			}
		} else {
			$range[] = array('Start'=>$prices[0], 'End'=>($prices[$cnt - 1] + 1));
		}

    	return $range;
    }

	/**
	 * 转换为HTML代码
	 */
	public static function toHtml($content) {
		return nl2br(str_replace(" ", "&nbsp;", htmlspecialchars($content)));
	}

	/**
	 * 格式化价格
	 */
	public static function formatPrice($price, $flag=false) {
		$money = number_format($price, 2, '.', ',');
		if($flag) {
			$pos = strpos($money, ".");
			$money = substr($money, 0, $pos);
			$n = intval(substr($money, $pos+1));
			if($n > 45) {
				$money++;
			}
		}
		return "&yen;".$money;
	}

	/**
	 * 截取字符串
	 * @param $str 原字符串
	 * @param $len 截取的长度
	 */
	public static function cutString($str, $len) {
		$str = self::stringguolv($str);
		if(strlen($str) <= $len) {
			return $str;
		}
		for($loop=0; $loop<$len; $loop++) {
			if(ord($str[$loop]) > 127) {
				$loop++;
			}
		}
		if($loop == $len + 1) {
			$len--;
		}

		return substr($str, 0, $len) . "...";
	}

	public static function stringguolv($str){
		$strarray = array("<b>","<B>","</b>","</B>","<P>","<p>");
		$str = str_replace ($strarray, "", $str);
		return $str;
	}
	/**
	 * 截取段落
	 * @param $str 原字符串
	 * @param $maxLine 截取的行数
	 * @param $lineSize 截取行的字符数
	 */
	public static function cutLine($str, $maxLine, $lineSize) {
		//$str = explode("<br />", nl2br(htmlspecialchars($str));
		$str = split('<br />', nl2br($str));
		$string = "";
		$m = "";
		if(is_array($str)) {
			$n = count($str);
			$maxLine = ($n > $maxLine) ? $maxLine : $n;
			if($n == "1") {
				return self::cutString($str[0], $lineSize*$maxLine);
			}
			for($i = 0; $i<$maxLine; $i++) {
				if($i < ($maxLine-1)) {
					//$m = "\n";
				}
				if($i == ($maxLine-1) and ($n > $maxLine)) {
					$m = "..";
				}
				if(strlen($str[$i]) <= $lineSize) {
					$string .= $str[$i].$m;
					continue;
				}
				for($j = 1; $j<$maxLine; $j++){
					if(strlen($str[$i]) <= $lineSize*$j){
						$string .= self::cutString($str[$i], $lineSize*$j).$m;
						continue $j+1;
					}
				}
				if(strlen($str[$i]) > $lineSize*$maxLine){
					return self::cutString($str[$i], $lineSize*$maxLine);
				}

				$string .= self::cutString($str[$i], $lineSize).$m;
			}
		}
		return $string;
	}

	/**
	 * 取得时间
	 */
	function getMicrotime(){
    	list($usec, $sec) = explode(" ",microtime());
    	return ((float)$usec + (float)$sec);
    }

	/**
	 * 取得客户IP
	 */
	public static function OnLineIp() {
		/*if($_SERVER['HTTP_CLIENT_IP']){
			 $onlineip=$_SERVER['HTTP_CLIENT_IP'];
		}elseif($_SERVER['HTTP_X_FORWARDED_FOR']){
			 $onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			 $onlineip=$_SERVER['REMOTE_ADDR'];
		}*/
		if(isset($_REQUEST['X-Forwarded-For']) && $_REQUEST['X-Forwarded-For'] !=""){
			$ip = $_REQUEST['X-Forwarded-For'];
		} else {
			//check the remote x client ip address
			if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !="") {
				$ip = $_SERVER["HTTP_RLNCLIENTIPADDR"];
			}elseif($_SERVER['HTTP_CLIENT_IP']){
			    $ip=$_SERVER['HTTP_CLIENT_IP'];
		    }elseif($_SERVER['HTTP_X_FORWARDED_FOR']){
			    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				//set the default client ip
				$ip = $_SERVER['REMOTE_ADDR'];
			}
		}
		return $ip;
	}

	/**
	 * 转化为<input type='hidden' ...>
	 */
	public static function toHiddenStr($params, $filter=NULL) {
		if($params == NULL) {
			return "";
		}
		$str = "";
		foreach($params as $key=>$val) {
			if($filter != NULL) {
				if(is_array($filter)) {
					for($loop=0; $loop<count($filter); $loop++) {
						if($filter[$loop] == $key) {
							break;
						}
					}
					if($loop<count($filter)) {
						continue;
					}
				} else if($filter == $key) {
					continue;
				}
			}
			$str .= "	<input type='hidden' name='$key' value='$val'>\r\n";
		}
		return $str;
	}

	/**
	 * return URL
	 */
	public static function getNewURL($tag, $params=NULL) {
		if($params == NULL) {
			return "";
		}
		switch($tag) {
			case "category":
				$nameURL = str_replace(' ','_',$params["NameURL"]);
				$name = str_replace(' ','_',$params["Name"]);
				$sort = str_replace(' ','_',$params["Sort"]);
				if($params["Page"] > 1) {
					$html = "--Pg-" . $params["Page"] . ".html";
					if(empty($sort)){
						return "/Ca-" . $name."--Ci-" . $nameURL . $html;
					}else{
						return "/Ca-" . $name."--Ci-" . $nameURL ."--sortby-time". $html;
					}
				} else {
					if(empty($sort)){
						$html = "";
						return "/Ca-" . $name."--Ci-" . $nameURL .".html";
					}else{
						$html = "";
						return "/Ca-" . $name."--Ci-" . $nameURL ."--sortby-time.html";
					}
				}
			case "merchant":
				$nameURL = $params["NameURL"];
				$id = $params["Id"];
				if($params["Page"] > 1) {
					$html = "--Pg-" . $params["Page"] . ".html";
						return "/Me-" . $nameURL."--Mi-" . $id . $html;
						break;

				} else {
						$html = "";
						return "/Me-" . $nameURL."--Mi-" . $id .".html";
						break;

				}
			case "sitemap":
				$nameURL = str_replace(' ','_',$params["NameURL"]);
				$name = str_replace(' ','_',$params["Name"]);
				if($params["Page"] > 1) {
					$html = "--Pg-" . $params["Page"] . ".html";
					if(empty($_GET['cityselect'])){
						return "/Si-" . $name."--Ci-" . $nameURL . $html;
						break;
					}else{
						return "/Si-" . $name."--Ci-" . $nameURL ."--City-".$_GET['cityselect']. $html;
						break;
					}
				} else {
					if($_GET['cityselect']==""){
						$html = "";
						return "/Si-" . $name."--Ci-" . $nameURL .".html";
						break;
					}else{
						$html = "";
						return "/Si-" . $name."--Ci-" . $nameURL ."--City-".$_GET['cityselect'].".html";
						break;
					}
				}
			case "mycoupon":
				$switch = str_replace(' ','_',$params["switch"]);
				if($params["Page"] > 1) {
					return "/profile/mycoupon.php?switch=".$switch."&page=".$params["Page"];
				} else {
					return "/profile/mycoupon.php?switch=".$switch;
				}
			default:
				return "";
		}
	}

	public static function getURL($tag, $params=NULL) {
		if($params == NULL) {
			return "";
		}
		switch($tag) {
		case "merchant":
			if($params["NameURL"] == "") {
				return "/";
			}
			$nameURL = str_replace(' ','_',$params["NameURL"]);
			return "/".$nameURL."/index.html";
		case "couponFree":
			$nameURL = str_replace(' ','_',$params["NameURL"]);
			if($nameURL == "") {
				$nameURL = "merchant";
			}
			return "/".$nameURL."/coupon-" . $params["Coupon_"] . "/";
		case "couponUnion":
			if(isset($params["Category"])) {
                return Tracking_Uri::build(array(
                    Tracking_Uri::BUILD_TYPE    => 'coupon',
                    Tracking_Uri::COUPON_ID     => $params["Coupon_"],
                    Tracking_Uri::CATEGORY_ID   => $params["Category"],
                ));
			} elseif(isset($params["Merchant_"])) {
                return Tracking_Uri::build(array(
                    Tracking_Uri::BUILD_TYPE    => 'coupon',
                    Tracking_Uri::COUPON_ID     => $params["Coupon_"],
                    Tracking_Uri::MERCHANT_ID   => $params["Merchant_"],
                ));
			}
			break;
		case "couponPrint":
			return self::getImageURL($params["Coupon_"]);
		case "category":
			$nameURL = str_replace(' ','_',$params["NameURL"]);
			if($params["Page"] > 1) {
				$html = "--Pg-" . $params["Page"] . "";
				if(empty($_GET['cityselect'])){
					return "/category.php?cid=" . $nameURL . $html;
				}else{
					return "/category.php?cid=" . $nameURL . $html."&cityselect=".$_GET['cityselect'];
				}
			} else {
				$html = "";
				return "/Ca-".$params["NameURL"]."--Ci-".$params["Cid"].".html";
			}

		case "sitemap":
			$nameURL = str_replace(' ','_',$params["NameURL"]);
			if($params["Page"] > 1) {
				$html = "--Pg-" . $params["Page"] . "";
				if(empty($_GET['cityselect'])){
					return "/category.php?cid=" . $nameURL . $html;
					break;
				}else{
					return "/category.php?cid=" . $nameURL . $html."&cityselect=".$_GET['cityselect'];
					break;
				}
			} else {
				$html = "";
				return "/Si-".$params["NameURL"]."--Ci-".$params["Cid"].".html";
				break;
			}

		case "search":
			if(!$params["keywords"]) {
				return "/";
			}
			$keywords = self::encode($params["keywords"]);
			if($params["Page"] > 1) {
				return "/se-" . $keywords . "-" . $params["Page"] ."-" . $params["CityId"] . "/";
			} else {
				return "/se-" . $keywords . "-1-" . $params["CityId"] . "/";
			}
			break;
		default:
			return "";
		}
	}

	/**
	 * return Status
	 */
	public static function getCouponStatus($end="",$tag=1) {
		if($end == "") {
			return "";
		}
		if($tag == 2) {
			if($end == "0000-00-00" or substr($end,0,4) == "3333") {
				return "优惠进行中";
			} else {
				$array = split("-",$end);
				return $array[0]."年".$array[1]."月".$array[2]."日";
			}
		} else {
			if($end == "0000-00-00" or substr($end,0,4) == "3333") {
				return "优惠进行中";
			} else {
				$array = split("-",$end);
				return $array[0]."年".$array[1]."月".$array[2]."日";
			}
		}
	}

	/**
	* tag format
	*/
	public static function getTagSrc($string, $maxCount=5) {
		$tagstring = "";
		if($string!=""){
			$tagarray = explode(",",$string);
			$count = 0;
			foreach($tagarray as $key=>$value){
				if(++$count > $maxCount) break;
				$url = self::encode($value);
				$url = "se-".$url."-1-/";
				$tagstring .= "<span class='tag'><a href='/".$url."'>".$value."</a></span>,";
			}
			$tagstring = substr($tagstring,0,-1);
		}
		return $tagstring;
	}

	public static function getTagSrcForDiscount($string) {
		$tagstring = "";
		if($string!=""){
			$tagarray = explode(",",$string);
			foreach($tagarray as $key=>$value){
				$url = self::encode($value);
				$url = "se-".$url."-1-/";
				$tagstring .= "<a href='/".$url."' target='_blank'>".$value."</a> | ";
			}
			$tagstring = substr($tagstring,0,-2);
		}
		return $tagstring;
	}

	/**
	 * tag format
	 */
	public static function getTagSrcForSearch($string,$highstring="") {
		$tagstring = "";
		if($string!=""){
			$tagarray = explode(",",$string);
			foreach($tagarray as $key=>$value){
				$url = self::encode($value);
				$url = "se-".$url."-1-/";
				if($highstring){ //highstring strong
					$value = str_replace($highstring,"<strong>".$highstring."</strong>",$value);
				}
				$tagstring .= "<a href='/".$url."'>".$value."</a> ";
			}
			$tagstring = substr($tagstring,0,-1);
		}
		return $tagstring;
	}

	/**
	 * return ImageURL
	 */
	public static function getImageURL($couponID) {
		$num = $couponID % 100;
		return __IMAGE_SRC."add/".$num."/".$couponID.".jpg";
	}

	public static function getMiddleImageURL($couponID) {
		$num = $couponID % 100;
		return __IMAGE_SRC."add/".$num."/".$couponID."_middle.jpg";
	}

	public static function getSmallImageURL($couponID) {
		$num = $couponID % 100;
		return "add/".$num."/".$couponID."_small.jpg";
	}

	/**
	 * return City String
	 */
	public static function getCity($city) {
		$pos = strpos($city,">>");
		if($pos > 0) {
			return substr($city, 0, $pos);
		}
		return $city;
	}

	/**
	 * 字符串转换为十六进制编码(For GBK)
	 */
	function str2hex($str) {
		$str = (string)$str;
		$result = "";
		$len = strlen($str);
		for($i = 0; $i < $len; $i++) {
			if(ord($str[$i]) < 128 || $i + 1 == $len) {
				$result .= bin2hex($str[$i]) . "S";
			} else {
				$result .= bin2hex($str[$i] . $str[$i+1]) . "S";
				$i++;
			}
		}
		return trim($result, "S");
	}

	function my_strtolower($str) {
		$len = strlen($str);
		$A = ord('A');
		$Z = ord('Z');
		for($i = 0; $i < $len; $i++) {
			if(ord($str[$i]) >= $A && ord($str[$i]) <= $Z) {
				$str[$i] = chr(ord($str[$i]) + 32);
			}
		}
		return $str;
	}

	/**
	 * 取得客户IP
	 */
	public static function onlineUserAgent() {
		if(isset($_SERVER["HTTP_USER_AGENT"])) {
			return $_SERVER["HTTP_USER_AGENT"];
		} else {
			return $_ENV["HTTP_USER_AGENT"];
		}
	}

  function getArrayCache($file) {
    return include $file;
  }

}
?>