<?PHP

class FastCacheBase {   

	
	public static function getMerchantCacheFile($merchantid = NULL) {
		//$id = $merchantid % 100;
		//$filesrc = __ROOT_PATH.__SETTING_PATH."config_main/merchant/".$id;
		//createdir($filesrc);
		$cachefile = self::getMerchantCacheSrc($merchantid)."/merchant_".$merchantid.".php";
		return $cachefile;
	}

	public static function getMerchantCacheSrc($merchantid = NULL) {
		$id = $merchantid % 100;
		return  __SETTING_FULLPATH."config_main/merchant/".$id;
	}

	public static function getCategoryCacheFile($chid = NULL) {
		$cachefile = __SETTING_FULLPATH."config_main/category_".$chid.".php";
		return $cachefile;
	}

	public static function getChannelCacheFile() {
		$cachefile = __SETTING_FULLPATH."config_main/channel.php";
		return $cachefile;
	}

	public static function getCategoryCacheName($chid=NULL){
		return "categorycache_".$chid;	
	}
	
	public static function getChannelCacheName(){
		return "channelcache";
	}

	public static function getMerchantCacheName($merchantid=NULL){
		return "merchantcache_".$merchantid;	
	}


}

?>