<?php

require_once("../etc/const.inc.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");

if (trim($_GET['params'])) {
	$params = urlParamToArray(trim($_GET['params']));
	$sourceFlag = $params['sourceFlag'];
	$adsParams  = $params['adsParams'];
	$ads = new GoogleAds();
	$adsContent = $ads->getAdsScript($adsParams);
	if($adsContent) {
		foreach($adsContent as $key => $adsContents) {
			$html = '';
			$keys = $key + 1;
			foreach($adsContents as $key => $gContent) {
				$html .= "<div>";
				$html .= "<p class=\"ad_title\"><a href=\"{$gContent['url']}\" target=\"_blank\">{$gContent['LINE1']}</a></p>";
				$html .= "<p class=\"ad_text\">{$gContent['LINE2']}</p>";
				$html .= "<p class=\"ad_url\"><a href=\"{$gContent['url']}\" target=\"_blank\">{$gContent['SiteUrl']}</a></p>";
				$html .= "</div>";
			}
			echo "var requestGoogle_".$keys." = '$html';";
		}
	}
}

function urlParamToArray($urlParam) {
	if (trim($urlParam)) {
		$params = unserialize(Utilities::decode($urlParam));
		return $params;
	}
	return NULL;
}
?>