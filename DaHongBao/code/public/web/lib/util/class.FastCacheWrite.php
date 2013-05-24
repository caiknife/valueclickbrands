<?PHP

class FastCacheWrite {   
	
	public static function createMerchantCache($needupdatemerchantid=NULL){
		$channels = CommonDao::channel(null, "ID");
		//create merchantcache by channel

		foreach($channels as $key=>$value){
			$minfo = MerchantDao::ChannelMer($value);
			foreach ($minfo as $merchantid){
				$merid = $merchantid['MerchantID'];
				
				if($needupdatemerchantid){
					if(! in_array($merid, $needupdatemerchantid)){
						continue;
					}	
				}
				
				$cachesrc = FastCacheBase::getMerchantCacheSrc($merid);
				createdir($cachesrc);
				$cachefile = FastCacheBase::getMerchantCacheFile($merid);
				$cachearray = array();
				
				$merchantinfo = MerchantDao::getMerchantCache($merid);
				$cachearray[$merid] = $merchantinfo; 
				
				$payment = MerchantDao::StorePayment($merid);
				$cachearray[$merid]['Payments'] = $payment;
				$cachearray[$merid]['PaymentsCnt'] = count($payment);
					
				$ship = MerchantDao::StoreShip($merid);
				$cachearray[$merid]['Ships'] = $ship;
				$cachearray[$merid]['ShipsCnt'] = count($ship);
				
				$name = FastCacheBase::getMerchantCacheName($merid);
				Utilities::setArrayCache($cachefile,$cachearray);
				if(defined('__APC_CACHE') && (constant('__APC_CACHE') == true)){
					apc_store($name, $cachearray);
				}
			
			}
		}
	}

	//store file cache wether use apc
	public static function createChannelCache(){
		//add channel cache
		$cachefile = FastCacheBase::getChannelCacheFile();
		$name = FastCacheBase::getChannelCacheName();
		$temparray = CommonDao::getChannel();
		$num = count($temparray);
		$cachearray = array();
		for($i = 0; $i < $num; $i++) {
			$cid = $temparray[$i]['ID'];
			$cachearray[$cid] = $temparray[$i];
		}
		Utilities::setArrayCache($cachefile,$cachearray);
		if(defined('__APC_CACHE') && (constant('__APC_CACHE') == true)){
			apc_store($name, $cachearray);
		}
	}
	
	public static function createCategoryCache(){
		$channels = CommonDao::channel(null, "ID");
		//create merchantcache by channel

		foreach($channels as $key=>$value){

			$cachefile = FastCacheBase::getCategoryCacheFile($value);
			$cachearray = array();
			
			$cinfo = CategoryDao::getCategoryByChannel($value);
			foreach ($cinfo as $categoryid){
				$cid = $categoryid['CategoryID'];
				//$merchantinfo = MerchantDao::getMerchantCache($merid);
				$cachearray[$cid] = $categoryid; 
				$cachearray[$cid]['CategoryParentID'] = CategoryDao::getCategoryParent($value,$cid); 
			}
			
			$name = FastCacheBase::getCategoryCacheName($value);
			Utilities::setArrayCache($cachefile,$cachearray);
			if(defined('__APC_CACHE') && (constant('__APC_CACHE') == true)){
				apc_store($name, $cachearray);
			}
			
		}	
	}
	
	

}

?>