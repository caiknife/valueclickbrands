<?php
require_once('settings.php');
require_once "Cache/Lite.php";
if (empty($apiAddress) || empty($developerId) || empty($developerKey)) {
	die('error. set in file settings.php.');
}

class Oem{  
	var $apiAddress;
	var $developerId;
	var $developerKey;	
	var $requestUrl;

	function __construct($apiAddress = null, $developerId = null, $developerKey = null) {   
		$this->apiAddress = $apiAddress;   
		$this->developerId = $developerId;
		$this->developerKey = $developerKey;   
		$this->requestUrl = $this->apiAddress .'?developerId=' . $this->developerId .'&developerKey=' . $this->developerKey .'&';
	}
	
	function getService($service, $options ,$cachetime=null){
		if (!empty($service) && is_array($options)){
			$param = 'method='.$service.'&';
			foreach ($options as $key => $value){
				$param .= $key.'='.$value.'&';
			}			
			$url = $this->requestUrl . $param ;	
			//echo $url;
			//echo "<BR>";
			
			//pear cache
			$options = array(
				'cacheDir' => __FILE_FULLPATH.'cache/',
				'lifeTime' => 3600*24*365,
				'pearErrorMode' => CACHE_LITE_ERROR_DIE
			);
			$cache = new Cache_Lite($options);
			if ($contents = $cache->get(base64_encode($url))) {
				//use cache
			} else { 
				if(__KIJIJI_OPEN){
					$contents = file_get_contents($url);
					$b = $cache->save($contents,base64_encode($url));
				}else{
					return false;
				}
			}

			//
			return simplexml_load_string($contents);
		}
		return false;
	}
}

$oem = new Oem($apiAddress, $developerId, $developerKey);
?>