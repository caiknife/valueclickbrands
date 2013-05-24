<?php
/*
 * Created on 2006-7-20
 * SyncFrontEnd.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: SyncFrontEnd.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 * @link       http://www.smarter.com/
 */
require_once("../etc/const.inc.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");


//WARN: check remote IP
$ip = Utilities::onlineIP();
$officeIPs = array("66.161.95.134"=>1, "203.156.246.2"=>1);
if($ip != '127.0.0.1' && $ip != '10.40.4.11' && strpos($ip, '192.168.') !== 0 && !isset($officeIPs[$ip])) {
	logFatal("not permission visit(IP:$ip).");
	die("Error.");
} else if(!empty($_REQUEST['X-Forwarded-For'])) {
	logFatal("fraud ip visit(IP:$ip).");
	die("Error.");
}

set_time_limit(0);

if(is_array($_REQUEST['syncId'])) {
	foreach($_REQUEST['syncId'] as $id) {
		$startTime = Utilities :: getMicrotime();
		echo "Fun:".$id."    startTime:".date("Y-m-d H:i:s")."<br/>\n";
		switch($id) {
		case "CommonData":
			SystemConfigDao::init();
			FastCacheWrite::createChannelCache();
			FastCacheWrite::createCategoryCache();
			CommonDao::updateEnv();
			CategoryDao::updateEnv();
			break;
		case "Category":
			CategoryDao::updateEnv();
			break;
		case "MetaData":
			MetaDao::updateEnv();
			break;
		case "HomePage":
			HomepageDao::updateDataEnv();
			break;
		case "HomePageNews":
			HomepageDao::updateNews();
			break;
		case "HomePageMerchants":
			HomepageDao::updateMerchants();
			break;
		case "HomePageCounts":
			HomepageDao::updateCounts();
			break;
		
		case "ChannelPage":
			ChannelDao::updateEnv();
			break;
		 //added by thomas
		case "CarMainData":
			CarChannelDao::updateMainEnv();
			break;
		case "CarOtherData":
			CarChannelDao::updateOtherEnv();
			break;
		case "SiteMap":
			$oSiteMap = new SiteMapDao();
			$oSiteMap->createSiteMap();
			break;
//		case "SearchKey":
//			if (trim($_REQUEST['sk']) != '') {
//				$val = trim($_REQUEST['sk']);
//				$val = str_replace('，', ',', $val);
//				CommonDao::createSearchkey($val);
//			}
//			break;
		case "StaticHtml":
			$arr = array('company/', 'partner/', 'user/', '');
			$content = CommonDao::getSearchKey();
			$oStaticHtml = new StaticHtmlDao();
			$oStaticHtml->createStaticHtml($arr, $content);
			break;
		case "HotKeyword":
			set_time_limit(0);
			HotKeywordsDao::createHtml();
			break;
		case "NewestProducts":
			//更新产品
			NewProductDao::syncNewProduct();
			break;
		case "PriceDownProducts":
			//更新降价产品
			DepreciateDao::syncDepreciate();
			break;
		case "OlympicGames2008":
			//更新OlympicGames2008
			//创建Controller层对象
			$action = new OlympicAction();
			$action->setTrackingDisabled(true);
			$action->setDisplayDisabled(true);
			$action->execute('HomePage');
			$adsContent = $action->getSmartyData();
			$destFile = __SETTING_FULLPATH ."html/olympicgames2008.html";
			file_put_contents($destFile, $adsContent);
			break;
		case "UpdateSpaceCategory":
			$reqURL = SpaceDao::getCategoryUrl();
			//echo $reqURL;
			$curl = CURL :: getInstance();
			$curl->setTimeout(100);
			$curl->setHeaderEnable(false);
			$info = $curl->get($reqURL);
			$localFile = SpaceDao::getCategoryPath();
			$localCategoryPath = dirname($localFile)."/";
			if(!is_dir($localCategoryPath)) {
					mkdir($localCategoryPath, 0777);
			}
		
			$dataLength = strlen(trim($info['data']));
			if(substr($info['http_code'],0,1) ==2 && !empty($info['data']) && $dataLength > 30) {
				file_put_contents($localFile, $info['data']);
				if(filesize($localFile) > 60) {
					echo "Success create  ".$localFile."\r\n";
					SpaceDao::splitCategoryList();
			        FileDistribute::commit();
				}
			}
			else {
				echo "Fail to Create File".$localFile;
			}
	 
			break;
		case "clearSmartyCache" :
			$compile_dir  = __FILE_FULLPATH."templates_c/";
			foreach (glob($compile_dir . "*.tpl.php") as $filename) {
			   unlink($filename);
			}
			break;
		case "AutoChannelPriceCategory":
			AutoChannelDao::generatePriceRangeCategory();
			break;
// delete by Fan Xu(2008-12-23)
//		case "createCategoryCache":
//			FastCacheWrite::createCategoryCache();
//			break;
		case "createMerchantCache":
			if($_REQUEST['createMerchantCache_date'] != '') {
				$date = date("Y-m-d", strtotime($_REQUEST['createMerchantCache_date']));
				$sql = "SELECT M.MerchantID from Merchant M where M.LastChangeDate > '$date'";
				$tmp = DBQuery::instance()->executeQuery($sql);
				$needupdatemerchantid = Utilities::convertSimpleArray($tmp, "MerchantID");
				if($needupdatemerchantid) {
					FastCacheWrite::createMerchantCache($needupdatemerchantid);
					echo "Merchants: " . implode(',', $needupdatemerchantid), " updated\n";
				}
				else {
					echo 'None of merchant needs update', "\n";
				}
			}
			else {
				FastCacheWrite::createMerchantCache();
			}
			break;
		case "createCommendMerchant":
			CommendMerchantDao::syncCommendMerchant();
			break;
		}	
		echo "use time:".(Utilities :: getMicrotime()-$startTime)."<br/>\n";
		echo " EndTime:".date("Y-m-d H:i:s")."<br/>\n<br/>\n";
	}
	FileDistribute::commit();
}
?>	
<html>
<head>
<title>SyncFrontEnd</title>
</head>
<body>
<form name="form1" action="" method="POST">
<div>
	<b>更新缓存数据</b>
	<br><br>
</div>
<div>
	<input type="checkbox" name="syncId[]" value="CommonData">设置共通部份环境(cache)
	<br><br>
	
	<input type="checkbox" name="syncId[]" value="clearSmartyCache" />清除Smarty template cache
	<br><br>
	<br>
	<input type="submit" name="submit1" value="  更 新  ">
</div>
</form>
</body>
</html>