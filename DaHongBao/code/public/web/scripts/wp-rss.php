<?php
	require_once("../etc/const.inc.php");
	require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
	require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
	$coupon = new Coupon();
	$array = $coupon->getRSSnewCouponList();
	header('Content-type: application/xml');

?>
<?php
	echo '<?xml version="1.0" encoding="gb2312"?>';
?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
	<title>大红包最新优惠券</title>
	<link>http://www.dahongbao.com</link>
	<language>en</language>
	<?
	//print_r($array);
		for($i=0;$i<10;$i++){
			?>
			<item>
			<?
			echo "<title>".$array[$i]['Descript']."</title>";
			?>
			<description><![CDATA[
			<?
			echo $array[$i]['Descript'];
			?>	
			]]></description>
			<link>
			<?
			echo "http://www.dahongbao.com/".$array[$i]['NameURL']."/coupon-".$array[$i]['Coupon_']."/";
			?>
			</link>
			</item>
			<?
		}

	?>
</channel>
</rss>