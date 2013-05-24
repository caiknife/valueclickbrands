<?php

class UrlManager {
	
	public static function aliUrl($switch,$pageid=0,$type='',$id=0,$q='') {
		if(is_array($switch)) {
			extract($switch, EXTR_OVERWRITE);
		}

		if(empty($switch)) return "/ali/";

		if($switch=='detail'){
			return "/ali/?switch=detail&id=".$id;
		}

		if($switch=='dofind'){
			return "/ali/?switch=dofind";
		}

		if($switch=='tuijian'){
			return "/ali/?switch=tuijian&id=".$id;
		}

		if($switch=='search'){
			$url = "/ali/?switch=search&";
			if($q){
				$url .="q=".urlencode($q)."&";
			}
			if($type){
				$url .="type={$type}&";
			}
			if($pageid){
				$url .="pageid={$pageid}&";
			}
			$url = substr($url,0,-1);
			return $url;
		}

		if($switch=='coupon'){
			$url = "/ali/?";
			if($id){
				$url .="cid={$id}&";
			}
			if($pageid){
				$url .="pageid={$pageid}&";
			}
			$url = substr($url,0,-1);
			return $url;
		}

		if($switch=='discount'){
			$url = "/ali/?switch=discount";
			if($type){
				$url .="&type=".$type;
			}
			if($pageid){
				$url .="&pageid={$pageid}";
			}
			return $url;
		}

		if($switch=='dingzhi'){
			$url = "/ali/?switch=dingzhi";
			if($type){
				$url .="&type=".$type;
			}
			if($pageid){
				$url .="&pageid={$pageid}";
			}
			return $url;
		}
	}



}
?>