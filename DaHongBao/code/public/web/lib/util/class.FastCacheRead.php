<?PHP

class FastCacheRead {   
	
	public static function getChannelCache() {
		if(defined('__APC_CACHE') && (constant('__APC_CACHE') == true)){
			//use apc cache
			$getarray = self::getChannelCacheByAPC();
			
			//if apc cache is empty, use file cache instead
			if(empty($getarray)){
				return self::getChannelCacheByFile();
			}	
			return $getarray;
		}else{
			//use file cache
			return self::getChannelCacheByFile();
		}
	}
	
	private function getChannelCacheByAPC(){
		$name = FastCacheBase::getChannelCacheName();
		if(function_exists(apc_fetch)){
			$getarray = apc_fetch($name);
			return $getarray;		
		}else{
			return NULL;
		}		
	}
	
	private function getChannelCacheByFile(){
		$filename = FastCacheBase::getChannelCacheFile();
		return Utilities::getArrayCache($filename);
	}
	
	public static function getMerchantCacheByMerchantID($mid = NULL) {
		if(defined('__APC_CACHE') && (constant('__APC_CACHE') == true)){
			//use apc cache
			$getarray = self::getMerchantCacheByAPC($mid);
			
			//if apc cache is empty, use file cache instead
			if(empty($getarray)){
				return self::getMerchantCacheByFile($mid);
			}	
			return $getarray;
		}else{
			//use file cache
			return self::getMerchantCacheByFile($mid);
		}
	}
	
	private function getMerchantCacheByAPC($chid = NULL){
		$name = FastCacheBase::getMerchantCacheName($chid);
		if(function_exists(apc_fetch)){
			$getarray = apc_fetch($name);
			return $getarray;		
		}else{
			return NULL;
		}
	}
	
	private function getMerchantCacheByFile($chid = NULL){
		$filename = FastCacheBase::getMerchantCacheFile($chid);
		return Utilities::getArrayCache($filename);
	}
	
	public static function getCategoryCacheByChannel($chid = NULL) {
		if(defined('__APC_CACHE') && (constant('__APC_CACHE') == true)){
			//use apc cache
			$getarray = self::getCategoryCacheByAPC($chid);
			
			//if apc cache is empty, use file cache instead
			if(empty($getarray)){
				return self::getCategoryCacheByFile($chid);
			}	
			return $getarray;
		}else{
			//use file cache
			return self::getCategoryCacheByFile($chid);
		}
	}
	
	private function getCategoryCacheByAPC($chid = NULL){
		$name = FastCacheBase::getCategoryCacheName($chid);
		if(function_exists(apc_fetch)){
			$getarray = apc_fetch($name);
			return $getarray;		
		}else{
			return NULL;
		}
	}
	
	private function getCategoryCacheByFile($chid = NULL){
		$filename = FastCacheBase::getCategoryCacheFile($chid);
		return Utilities::getArrayCache($filename);
	}
	
	

}

?>