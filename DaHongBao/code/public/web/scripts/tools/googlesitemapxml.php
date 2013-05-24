<?php
/**
 * googleSitemap.php
 *-------------------------
 *
 * build google sitemap
 *
 * PHP versions 4
 *
 * LICENSE: This source file is from CouponMountain.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 4.0
 * @version    CVS: $Id: googlesitemapxml.php,v 1.1 2013/04/15 10:58:18 rock Exp $
 * @link       http://www.couponmountain.com/
 * @deprecated File deprecated in Release 1.0.0
 */

require_once("../../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/util/class.FileDistribute.php");
include_once("DB.php");


$oGS = new GoogleSitemap();
$oGS->doOpenfile();
$oGS->main();


class GoogleSitemap
{//{{{
	var $db;
	var $file;

	var $file1;


	function GoogleSitemap() {
		
		//$this->doConnectDB();
	}

	function __destruct() {
		@fwrite($this->file,"</urlset>\r\n");
		@fclose($this->file);

		@fwrite($this->file1,"</urlset>\r\n");
		@fclose($this->file1);
		@$this->db->disconnect();
	}

	function doOpenfile() {
		$src = __SETTING_FULLPATH."xml/";
		@mkdir($src);
		$fileName   = $src."sitemap.xml";
		$this->file = fopen($fileName, "w");

		$fileName   = $src."couponsitemap.xml";
		$this->file1 = fopen($fileName, "w");

		if(!$this->file) die("cannot open file.");
		if(!$this->file1) die("cannot open file.");
	}

	function doConnectDB() {
		$dsn = "mysql://".__DB_USER.":".__DB_PASS."@".__DB_HOST."/".__DB_BASE;
		$this->db =& DB::connect($dsn);
		$this->db->setFetchMode(DB_FETCHMODE_ASSOC);
		if (PEAR::isError($this->db)) {
			die($this->db->getMessage());
		}
	}

	function doWriteHead() {
		fwrite($this->file, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n");
		fwrite($this->file, "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n");

		fwrite($this->file, " <url>\r\n");
		fwrite($this->file, "  <loc>"."http://www.dahongbao.com"."</loc>\r\n");
		//fwrite($this->file, "  <lastmod>".date("Y-m-d")."</lastmod>\r\n");
		//fwrite($this->file, "  <changefreq>"."daily"."</changefreq>\r\n");
		//fwrite($this->file, "  <priority>"."1"."</priority>\r\n");
		fwrite($this->file, " </url>\r\n");



		fwrite($this->file1, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n");
		fwrite($this->file1, "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n");

		fwrite($this->file1, " <url>\r\n");
		fwrite($this->file1, "  <loc>"."http://www.dahongbao.com"."</loc>\r\n");
		fwrite($this->file1, " </url>\r\n");
	}


	function doWritePage() {
		//$sql = "SELECT Name, DATE_FORMAT(now(), '%Y-%m-%d') LastGenerate FROM Page WHERE isSitemap=1 ORDER BY Name";
		//$data = $this->db->getAll($sql);
		//if (PEAR::isError($data)) {
		//	die($data->getMessage());
		//}
		//if (sizeof($data) > 0) {
		//	foreach ($data as $v) {
		//		$url = trim("http://www.dahongbao.com/" . str_replace(" ", "_", $v["Name"].".html"));
		//		$lastmod = $v["LastGenerate"];
		//		$changefreq = "daily";
		//		$this->doWriteItem($url, $lastmod, $changefreq);
		//	}
		//}
		//$this->doWriteItem("http://www.dahongbao.com/all_merchant.html", "", "");
		//$this->doWriteItem("http://www.dahongbao.com/expire_coupon.html", "", "");
		//$this->doWriteItem("http://www.dahongbao.com/freeshipping_coupon.html", "", "");
		//$this->doWriteItem("http://www.dahongbao.com/hot_coupon.html", "", "");
		$this->doWriteItem("http://www.dahongbao.com/new_coupon.html", "", "");
	}


	function doWriteCategory() {
		$sql  = "SELECT Category_,NameURL, DATE_FORMAT(now(), '%Y-%m-%d') LastGenerate, SitemapPriority FROM Category WHERE isActive=1 ORDER BY NameURL";
		$data = DBQuery::instance()->executeQuery($sql);
		if (sizeof($data) > 0) {
			foreach ($data as $v) {
				$url = trim("http://www.dahongbao.com/Ca-".$v["NameURL"]."--Ci-".$v["Category_"].".html");
				$lastmod = $v["LastGenerate"];
				$changefreq = "daily";
				$priority = $v["SitemapPriority"];
				$this->doWriteItem($url, $lastmod, $changefreq, $priority);
			}
		}

		//$this->doWriteItem("http://www.dahongbao.com/firefox.html", "", "");
		//$this->doWriteItem("http://www.dahongbao.com/picasa.html", "", "");
	}


	function doWriteMerchant() {
		$sql  = "SELECT DISTINCT m.NameURL,m.Merchant_, DATE_FORMAT(now(), '%Y-%m-%d') LastGenerate, m.SitemapPriority FROM Merchant m WHERE m.isActive=1 AND m.Merchant_ <> 0 ORDER BY m.NameURL";
		$data = DBQuery::instance()->executeQuery($sql);
		if (sizeof($data) > 0) {
			foreach ($data as $v) {
				$NameURL = trim(str_replace(" ", "_", $v["NameURL"]));
				$lastmod = $v["LastGenerate"];
				$changefreq = "daily";
				$priority = $v["SitemapPriority"];
				$merchant_ = $v["Merchant_"];
	
				$url = "http://www.dahongbao.com/Me-".$NameURL."--Mi-".$merchant_.".html";
				$this->doWriteItem($url, $lastmod, $changefreq, $priority);
			}
		}
	}

	function doWriteCoupon() {
		$sql  = "SELECT DISTINCT m.NameURL,m.Merchant_, DATE_FORMAT(now(), '%Y-%m-%d') LastGenerate, m.SitemapPriority,c.Coupon_ FROM Merchant m inner join Coupon c on m.Merchant_=c.Merchant_ WHERE c.isActive=1 AND m.Merchant_ <> 0 ORDER BY m.NameURL";
		$data = DBQuery::instance()->executeQuery($sql);
		if (sizeof($data) > 0) {
			foreach ($data as $v) {
				$NameURL = trim(str_replace(" ", "_", $v["NameURL"]));
				$lastmod = $v["LastGenerate"];
				$changefreq = "daily";
				$priority = $v["SitemapPriority"];
				$merchant_ = $v["Merchant_"];
				$couponid = $v['Coupon_'];
	
				$url = "http://www.dahongbao.com/".$NameURL."/coupon-".$couponid."/";
				$this->doWriteItem1($url, $lastmod, $changefreq, $priority);
			}
		}
	}

	function doWriteItem($url, $lastmod, $changefreq, $priority=null) {
		$priority = empty($priority) ? 0.5 : $priority;
		fwrite($this->file, " <url>\r\n");
		fwrite($this->file, "  <loc>".htmlentities($url)."</loc>\r\n");
		//fwrite($this->file, "  <lastmod>".$lastmod."</lastmod>\r\n");
		//fwrite($this->file, "  <changefreq>".$changefreq."</changefreq>\r\n");
		//fwrite($this->file, "  <priority>".$priority."</priority>\r\n");
		fwrite($this->file, " </url>\r\n");
	}


	function doWriteItem1($url, $lastmod, $changefreq, $priority=null) {
		$priority = empty($priority) ? 0.5 : $priority;
		fwrite($this->file1, " <url>\r\n");
		fwrite($this->file1, "  <loc>".htmlentities($url)."</loc>\r\n");
		//fwrite($this->file, "  <lastmod>".$lastmod."</lastmod>\r\n");
		//fwrite($this->file, "  <changefreq>".$changefreq."</changefreq>\r\n");
		//fwrite($this->file, "  <priority>".$priority."</priority>\r\n");
		fwrite($this->file1, " </url>\r\n");
	}


	function main() {
		$this->doWriteHead();
		$this->doWritePage();
		$this->doWriteCategory();
		$this->doWriteMerchant();
		$this->doWriteCoupon();
//		$this->__destruct();
	}
	
}//}}}
//FileDistribute::syncFile(__INCLUDE_ROOT."couponsitemap.xml");
//FileDistribute::syncFile(__INCLUDE_ROOT."sitemap.xml");
FileDistribute::syncFile();

echo "\n\nDONE\n".getDateTime("Y-m-d H:i:s")."\n";
?>