<?php
/*
 * Created on 2006-6-23
 * class.PathManager.php
 * -------------------------
 *
 * 所有路径相关(包括URL)的方法
 *
 * @author cooc
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.PathManager.php,v 1.1 2013/04/15 10:58:02 rock Exp $
 * @link       http://www.smarter.com/
 */

global $NOIMG_SELF;
$NOIMG_SELF = array(
	'553' => 'computer' ,
	'642' => 'electronics',
	'666' => 'communicate',
	'673' => 'office',
	'693' => 'cosmetics',
	'200' => 'car',
	'1007' => 'car',
	'1008' => 'sportoutdoors',
	'14'	=> 'hotel'
);

class PathManager {
	private static $URL_ROOT = "/";

	/**
	 * 取得商品参数文件
	 */
	public static function getSpecFile($chid, $prodid) {
		$filename = _XML_FILE_PATH. "$chid/".substr($prodid,-2)."/$prodid.xml";
		//if(@fopen($filename,"r")) {
			return $filename;
		//} else {
		// return NULL;
		//}
		//return _XML_FILE_PATH. "$chid/".substr($prodid,-2)."/$prodid.xml";
	}

	/**
	 * 将需要设置到URL中的值,转换为URL.
	 * @param $isFirst = true 则不加前缀"-"
	 */
	protected static function getTail($params, $isFirst=false, $default="/", $postfix=".htm") {
		//$default = "/";
		if($params == NULL) {
			return $default;
		}
		$tail = "";
		foreach($params as $key=>$val) {
			if($val == "") {
				continue;
			}
			$val = Utilities::encode($val);
			if($isFirst == false) {
				$tail .= "--$key-$val";
			} else {
				$tail .= "-$key-$val";
				$isFirst = false;
			}
		}
		if(strlen($tail) != 0) {
			return substr($tail, 1).$postfix; //留一个"-"
		} else {
			return $default;
		}
	}

	protected static function toLower($str){
		return strtolower($str);
	}

	/**
	 * 取得个人网店的URL
	 */
	public static function getC2CUrl($params=NULL) {
		if(!isset($params) || empty($params)) {
			return "/c2c/";
		}
		return "/c2c/se-" . PathManager::getTail($params, true);
	}

	/**
	 * 取得个人网店的产品详细地址
	 */
	public static function getC2CProdDetailUrl($params) {
		if(!isset($params['aid']) || !is_numeric($params['aid'])) {
			return "/c2c/";
		}
		return "/c2c/redir-" . PathManager::getTail($params, true);
	}

	public static function getSearchUrl($keyword, $chid=NULL, $catid=NULL, $params=NULL) {
		$url = self::$URL_ROOT ."se-".Utilities::encode($keyword)."-1-/";
		/*
		if($chid != NULL && $catid != NULL) {
			$url .= "-ch-".$chid."-c-".$catid;
		} else if($chid != NULL) {
			$url .= "-ch-".$chid;
		}
		$url .= self::getTail($params);
		*/
		return $url;
	}

	public static function getChannelUrl($chid, $params=NULL) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid.self::getTail($params);
	}

	public static function getChannelLinkUrl($chid, $params=NULL) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		//web/{$channelEnName}-{$chid}.html
		return self::$URL_ROOT.'web/'.self::toLower($channelEnName)."-".$chid.'/';
	}
	public static function getChannelLinkApplyUrl($chid, $params=NULL) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		//{$channelEnName}-{$chid}/link-to-us.html
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid.'/link-to-us.htm';
	}

	public static function getCategoryUrl($chid, $catid, $params=NULL) {
		$channelEnName = strtolower(CommonDao::channel($chid, "EnName"));
		if(!$channelEnName) {
			return "/";
		}
//		$categoryEnName = ($categoryEnName == NULL) ? "cate" : $categoryEnName;
//		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid."/category/".
//				self::toLower(str_replace(" ", "", $categoryEnName))."-".$catid.self::getTail($params);
		if($catid) {
			$params['catid'] = $catid;
		}
		if(isset($params['pn']) && $params['pn'] <= 1) {
			unset($params['pn']);
		}
		$url =  "/".$channelEnName.self::getTail($params, false, ".html", ".html");
		return $url;
	}

	// ^/(computers|electronics|communication|officedevice)-([0-9]+)/prod-([0-9]+)/(.*)
	//         /scripts/$1/product.php?chid=$2&prodid=$3&$4    [L]
	public static function getProdUrl($chid, $prodid, $params=NULL) {
//		$channelEnName = CommonDao::channel($chid, "EnName");
//		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid.
//								"/prod-".$prodid.self::getTail($params);
		$channelEnName = strtolower(CommonDao::channel($chid, "EnName"));
		if(!$channelEnName || !$prodid) {
			return "/";
		}
//		$url = "/prod.php?chid={$chid}&prodid={$prodid}";
		$url =  "/".$channelEnName."-".$prodid.self::getTail($params, false, ".html", ".html");
		return $url;
	}

	// ^/(computers|electronics|communication|officedevice)-([0-9]+)/preview-([0-9]+)/(.*)
	//      /scripts/prodreview.php?chid=$2&prodid=$3&$4    [L]
	public static function getProdReviewUrl($chid, $prodid, $params=NULL) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid.
							"/preview-".$prodid.self::getTail($params);
	}

	public static function getSpecUrl($chid, $prodid, $params=NULL) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid.
							"/pspec-".$prodid.self::getTail($params);
	}

	public static function getProdWriteReviewUrl($chid, $prodid, $params=NULL) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid.
							"/pwrite-".$prodid.self::getTail($params);
	}

	public static function getNewsCategory($chid, $catid, $params=NULL){
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid."/newslist-".$catid.self::getTail($params);
	}

	public static function getNewsList($chid, $catid, $params=NULL){
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid."/news-".$catid.self::getTail($params);
	}

	public static function getMerHomeUrl($chid, $merid, $enName, $catid=0) {
		return self::getMerCategoryUrl($chid, $merid, $enName, 0);
	}

	//   ^/([^\/]*)-([0-9]+)/mcate-([0-9]+)-([0-9]+)/(.*)
	//     /front_end/scripts/merprodlist.php?merid=$2&chid=$3&catid=$4&$5    [L]
	public static function getMerCategoryUrl($chid, $merid, $enName, $catid=0, $params=NULL) {
		//add by menny at May 16,2008 去掉尾部的merid (重复)
		if(isset($params['merid'])) {
			unset($params['merid']);
		}
		$MerChantNameUrl= (0 < strlen($enName) ? $enName : "merchant");
		$ChannelID      = max(0, (int)($chid));
		$CatID          = max(0, (int)($catid));
		if($ChannelID == $CatID) {
			$CatID = 0;
		}
		return self::$URL_ROOT.self::toLower($MerChantNameUrl)."-".$merid.
				"/mcate-".$ChannelID."-".$CatID.self::getTail($params);
	}

	public static function getMerInfoUrl($chid, $merid, $enName, $prodid, $params=NULL) {
		$MerChantNameUrl= (0 < strlen($enName) ? $enName : "merchant");
		$ChannelID      = max(0, (int)($chid));
		$ProdID         = max(0, (int)($prodid));
		return self::$URL_ROOT.self::toLower($MerChantNameUrl)."-".$merid.
				"/minfo-".$ChannelID."-".$ProdID.self::getTail($params);
	}

	// ^/([^\/]*)-([0-9]+)/mreview-([0-9]*)-([0-9]*)/(.*)
	//    /front_end/scripts/merreview.php?merid=$2&chid=$3&prodid=$4&$5    [L]
	public static function getMerReviewUrl($chid, $merid, $enName, $prodid, $params=NULL) {
		$MerChantNameUrl= (0 < strlen($enName) ? $enName : "merchant");
		$ChannelID      = max(0, (int)($chid));
		$ProdID         = max(0, (int)($prodid));
		return self::$URL_ROOT.self::toLower($MerChantNameUrl)."-".$merid.
				"/mreview-".$ChannelID."-".$ProdID.self::getTail($params);
	}

	public static function getMerWriteReviewUrl($chid, $merid, $enName, $prodid, $params=NULL) {
		$MerChantNameUrl= (0 < strlen($enName) ? $enName : "merchant");
		$ChannelID      = max(0, (int)($chid));
		$ProdID         = max(0, (int)($prodid));
		return self::$URL_ROOT.self::toLower($MerChantNameUrl)."-".$merid.
				"/mwrite-".$ChannelID."-".$ProdID.self::getTail($params);
	}

	// ^/([^\/]*)-([0-9]+)/mabuse-([0-9]+)/(.*)
	//		/front_end/scripts/abusereview.php?merid=$2&reviewid=$3&type=mer&$4    [L]
	public static function getMerAbuseReviewUrl($merid, $enName, $reviewid, $params=NULL) {
		if ($reviewid > 0){
			$MerChantNameUrl= (0 < strlen($enName) ? $enName : "merchant");
			return self::$URL_ROOT.self::toLower($MerChantNameUrl)."-".$merid.
							"/mabuse-".$reviewid.self::getTail($params);
		}
	}

	public static function getMerShippingUrl($chid, $merid, $enName) {
		$MerChantNameUrl= (0 < strlen($enName) ? $enName : "merchant");
		return self::$URL_ROOT.self::toLower($MerChantNameUrl)."-".$merid.
				"/mship-".$chid."-0/";
	}

	public static function getRedirectUrl($merid, $enName, $reviewid, $params=NULL) {
		$MerChantNameUrl= (0 < strlen($enName) ? $enName : "merchant");
		return self::$URL_ROOT.self::toLower($MerChantNameUrl)."-".$merid.
							"/mabuse-".$reviewid.self::getTail($params);
	}

	public static function getChannelMerUrl($chid, $params=NULL) {
		if(!$chid) {
			return self::$URL_ROOT."merchant/";
		} else {
			$channelEnName = CommonDao::channel($chid, "EnName");
			return self::$URL_ROOT."merchant/".self::toLower($channelEnName)
											."-".$chid.self::getTail($params);
		}
	}

	//car channel getCar order url
	public static function getOrderUrl($chid, $prodid, $params=NULL) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid."/order-"
				.$prodid.self::getTail($params);

	}

	/**
	 * 取得评分等级的图片地址
	 */
	public static function getRatingImage($rScore=0) {
		if ($rScore=="") $rScore = 0;
		$sReturn = "";
		switch(true){
			case ($rScore >= 0 && $rScore < 0.5) :
				$sReturn = "zero.gif"; break;
			case ($rScore >= 0.5 && $rScore < 1) :
				$sReturn = "orange-half.gif"; break;
			case ($rScore >= 1 && $rScore < 1.5) :
				$sReturn = "orange-1.gif"; break;
			case ($rScore >= 1.5 && $rScore < 2) :
				$sReturn = "orange-1-half.gif"; break;
			case ($rScore >= 2 && $rScore < 2.5) :
				$sReturn = "orange-2.gif"; break;
			case ($rScore >= 2.5 && $rScore < 3) :
				$sReturn = "orange-2-half.gif"; break;
			case ($rScore >= 3 && $rScore < 3.5) :
				$sReturn = "orange-3.gif"; break;
			case ($rScore >= 3.5 && $rScore < 4) :
				$sReturn = "orange-3-half.gif"; break;
			case ($rScore >= 4 && $rScore < 4.5) :
				$sReturn = "orange-4.gif"; break;
			case ($rScore >= 4.5 && $rScore < 5) :
				$sReturn = "orange-4-half.gif"; break;
			case ($rScore >= 5) :
				$sReturn = "orange-5.gif"; break;
			default:
				$sReturn = "zero.gif";
		} // switch
		return $sReturn;
	}

	/**
	 * 取得商品图片地址
	 * @param $chid 频道ID
	 * @param $prodid 商品ID
	 * @param $hasImage 有图,传'YES'
	 */
	public static function getProductImage($chid, $prodid, $type=NULL, $hasImage="YES"){

		global $NOIMG_SELF;
		if($hasImage == "YES") {
			if($type=="big"){
				$img = "product_image_b";
			} else {
				$img = "product_image_s";
			}
			$tPID = ltrim(substr($prodid,3),"0");
			if(strlen($prodid) >= 2) {
				$tSID = substr($prodid, -2);
			} else {
				$tSID = "0" . $prodid;
			}
			return __IMAGE_SRC.$img."/".$chid."/".$tSID."/".$prodid.".jpg";
		} else {
			if($type=="big"){
				if (isset($NOIMG_SELF[$chid])) {
					return "/images/default/no_image_".$NOIMG_SELF[$chid]."_b.gif";
				} else {
					return "/images/default/prod_noimage_b.gif";
				}
			} else {
				if (isset($NOIMG_SELF[$chid])) {
					return "/images/default/no_image_".$NOIMG_SELF[$chid]."_s.gif";
				} else {
					return "/images/default/prod_noimage_s.gif";
				}
			}
		}
	}

	/**
	 * 根据类别树,格式化类别导航
	 * @param $categoryPath 类别结构(倒序)
	 */
	public static function formatCategoryNavigation(&$categoryPath, $params = NULL) {
		if(!is_array($categoryPath)){
			return NULL;
		}
		$navigation = NULL;
		$num = count($categoryPath);
		$chid = $categoryPath[0]["CategoryID"];
		$navigation .= " &gt;  <a href='".self::getChannelUrl($chid, NULL)."'  class='blue'>"
			.htmlspecialchars($categoryPath[0]["CategoryName"])."</a>";
		for($i=1;$i<$num;$i++) {
			$catname = $categoryPath[$i]["CategoryName"];
			$catid = $categoryPath[$i]["CategoryID"];
			$categoryEnName	= $categoryPath[$i]["CategoryEnName"];
			$categoryEnName	= str_replace("-","",$categoryEnName);
			//$categoryEnName	= str_replace("_","",$categoryEnName);
			$navigation .= " &gt;  <a href='".self::getCategoryUrl($chid, $catid, $categoryEnName, $params)."'  class='blue'>"
			.htmlspecialchars($catname)."</a>";
			//print_r($navigation);
		}
		$navigation .= '  ';
		$navigation = "<a href='/' class='blue'>主页</a> " . $navigation;	//echo "<textarea cols=150>$navigation</textarea>";
		return $navigation;//"调试中……";
	}

	/**
	 * 根据类别树,格式化类别导航
	 * @param $categoryPath 类别结构(倒序)
	 */
	public static function formatMerCategoryNavigation(&$categoryPath, $merid, $enName) {
		if(!is_array($categoryPath)){
			return NULL;
		}
		$navigation = NULL;
		$num = count($categoryPath);
		$chid = $categoryPath[0]["CategoryID"];
		$navigation .= " &gt;  <a href='".self::getMerCategoryUrl($chid, $merid, $enName)."' class='blue'>"
			.htmlspecialchars($categoryPath[0]["CategoryName"])."</a>";
		for($i=1;$i<$num;$i++) {
			$catname = $categoryPath[$i]["CategoryName"];
			$catid = $categoryPath[$i]["CategoryID"];
			$navigation .= " &gt;  <a href='".self::getMerCategoryUrl($chid, $merid, $enName, $catid)."'  class='blue'>"
			.htmlspecialchars($catname)."</a>";
			//print_r($navigation);
		}
		$navigation = "<a href='/' class='blue'>主页</a> " . $navigation;	//echo "<textarea cols=150>$navigation</textarea>";
		return $navigation;//"调试中……";
	}

	public static function getNewsDetail($chid, $aid) {
		$enName = self::toLower(CommonDao::channel($chid, 'EnName'));
		return __HOME_ROOT_PATH . "{$enName}-{$chid}/news-{$aid}/";
	}

	/**
	 * @param int $chid
	 * @param int $aid news article id
	 * @return string
	 */

	public static function getNewsImagePathFile($chid, $aid, $isThumb = false, $isHeight = false) {
		if(strlen($aid) >= 2) {
			$tSID = substr($aid, -2);
		} else {
			$tSID = "0" . $aid;
		}

		if($isHeight) {
			$h='h';
		}
		else {
			$h = '';
		}

		return  "news/{$chid}/{$tSID}/{$aid}{$h}.jpg";
	}

	public static function getNewsImage($chid, $aid, $type=NULL, $hasImage="YES", $isHeight = false){
		if($hasImage == "YES") {
			$img = self::getNewsImagePathFile($chid, $aid, false, $isHeight);
			if($type=="big"){
				$package = __BIG_IMGAES_PACKAGE;
			} else {
				$package = __SMALL_IMGAES_PACKAGE;
			}
			return __IMAGE_DOMAIN_NAME.$package."/".$img;
		} else {
			if($type=="big"){
				return __WEB_DOMAIN_NAME."images/default/prod_noimage_b.gif";
			} else {
				return __WEB_DOMAIN_NAME."images/default/prod_noimage_s.gif";
			}
		}
	}

	public static function getBrandImage($chid, $manafacturerId, $type=NULL, $hasImage="YES"){
		if($hasImage == "YES") {
			$img = self::getBrandImagePathFile($chid, $manafacturerId, $type);
			if($type=="big"){
				$package = __BIG_IMGAES_PACKAGE;
			} else {
				$package = __SMALL_IMGAES_PACKAGE;
			}
			return __IMAGE_DOMAIN_NAME.$package."/".$img;
		} else {
			if($type=="big"){
				return __WEB_DOMAIN_NAME."images/default/prod_noimage_b.gif";
			} else {
				return __WEB_DOMAIN_NAME."images/default/prod_noimage_s.gif";
			}
		}
	}

	public static function getBrandImagePathFile($chid, $manafacturerId) {
		return  "brand/{$chid}/".$manafacturerId.".jpg";
	}

	public static function getBrandListUrl($chid) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid."/brand/";
	}

	public static function getBrandDetail($chid, $brandid) {
		$channelEnName = CommonDao::channel($chid, "EnName");
		return self::$URL_ROOT.self::toLower($channelEnName)."-".$chid."/brand/{$brandid}/";
	}

	public static function getDepreciateUrl($chid = 0, $catid = 0, $params=array()) {
		if($chid == 0) {
			return "/depreciate/";
		}
		$url = "/depreciate/list-{$chid}-{$catid}";
		if($params) {
			$url .= PathManager::getTail($params);
		}
		else {
			$url .= "/";
		}
		return $url;
	}

	public static function getNewProductUrl($chid = 0, $catid = 0, $params=array()) {
		if($chid == 0) {
			return "/newproduct/";
		}
		$url = "/newproduct/list-{$chid}-{$catid}";
		if($params) {
			$url .= PathManager::getTail($params);
		}
		else {
			$url .= "/";
		}
		return $url;
	}

	public static function getBannerAdUrl($url) {
        return Tracking_Uri::build(array(
            Tracking_Uri::BUILD_TYPE    => 'banner',
            Tracking_Uri::DESTINED_URL  => $url,
        ));
	}

	public static function sponsorLink($searchText,$uri,$postion,$tag="Search", $sponsorType = "") {
        if (empty($sponsorType)) {   //默认是GOOGLE 广告类型
            $sponsorType = Tracking_Constant::SPONSOR_GOOGLE;
        }
        $baiduSponsorTypeArr = array(
            Tracking_Constant::SPONSOR_BAIDU_PROMOTION,
            Tracking_Constant::SPONSOR_BAIDU_ACCURATE,
            Tracking_Constant::SPONSOR_BAIDU_INTELLIGENT
        );
        if ($sponsorType == Tracking_Constant:: SPONSOR_GOOGLE) {
            $tagParams = GoogleTagDao::getTagParams($searchText);
        } else if (in_array($sponsorType, $baiduSponsorTypeArr)) {
            $tagParams = BaiduTagDao::getTagParams($searchText);
        } else {
            throw new Exception("sponsorType {$extraParams['sponsorType']} does not exists.");
        }
        return Tracking_Uri::build(array(
            Tracking_Uri::BUILD_TYPE        => 'sponsor',
            Tracking_Uri::KEYWORD           => $searchText,
            Tracking_Uri::DISPLAY_POSITION  => $postion,
            Tracking_Uri::CHANNEL_TAG       => $tag ? $tag : '',
            Tracking_Uri::DESTINED_URL      => $uri,
            Tracking_Uri::SPONSOR_TYPE      => $sponsorType,
            Tracking_Uri::IS_MATCHED        => $tagParams['isMatched'],
            Tracking_Uri::EXPIRED_TIME      => $tagParams['expireTime'],
            Tracking_Uri::REQUEST_COUNTRY   => $tagParams['country'],
        ));
    }

	public static function getOfferUrl($chid, $prodid, $merid, $url) {
        return Tracking_Uri::build(array(
            Tracking_Uri::BUILD_TYPE        => 'offer',
            Tracking_Uri::PRODUCT_ID        => $prodid,
            Tracking_Uri::CHANNEL_ID        => $chid,
            Tracking_Uri::DESTINED_URL      => $url,
            Tracking_Uri::MERCHANT_ID       => $merid,
        ));
	}

	public static function getTourUrl($tourID = 0) {
		$tourID = $tourID * 1;
		return "/travel-tour-{$tourID}.html";
	}

	//hot tag
	public static function getTourTag($tagInfo) {
		$regionName = urlencode($tagInfo['RegionName']);
		if ($tagInfo["pn"] || $tagInfo["page"]) {
			$page = "-pg-".$tagInfo["pn"];
		}
		if ($tagInfo["RegionType"] == 1) {
			$rType = "-1";
		}
		else if ($tagInfo["RegionType"] == 0) {
			return "/travel-places/{$regionName}{$page}.html";
		}
		return "/travel-places-{$tagInfo['DestinationType']}/{$regionName}{$rType}{$page}.html";
	}

	//hotel product url
	public static function getHotelProductUrl($child, $proid) {
		$proid = $proid * 1;
		return "/travel-hotel-{$proid}.html";
	}
	//get travel offerurl
	public static function getTravelOfferUrl($url, $type) {
        return Tracking_Uri::build(array(
            Tracking_Uri::BUILD_TYPE    => $type,
            Tracking_Uri::DESTINED_URL  => $url,
        ));
	}

	public static function getTourPlaces($url, $type = "tour") {
        return Tracking_Uri::build(array(
            Tracking_Uri::BUILD_TYPE    => $type,
            Tracking_Uri::DESTINED_URL  => $url,
        ));
    }

    //获取验证码地址
    public static function getAuthNumUrl() {
        return "/async_authNum.php?switch=createImage";
	}
}
?>