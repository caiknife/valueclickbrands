<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>网站地图首页 - 大红包</title>
<meta name="copyright" content="www.dahongbao.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="verify-v1" content="QVIPb4M3AU1SQ7HQONfC2lrlH7RkeUn+PL1dLv8l5fs=" />
<link href="/css/st.css" rel="stylesheet" type="text/css" media="all">
<script language="JavaScript" src="/jscript/js.js"></script>
</head>
<body>
<link href="/css/stprint.css" rel="stylesheet" type="text/css" media="print">
{include file="head_coupon.tpl"}
</div><div id="main">
<div id="left" class="fillBg">
	<div class="categorymenu1" id="newcoupon" style="border-bottom: 1px solid #000;">
		<h3>&nbsp;&nbsp;最新电子优惠券</h3>
		<ul>
		{section name=index loop=$newCouponlist}
		<li><a href="{$newCouponlist[index].couponURL}" class="blue">{$newCouponlist[index].couponTitle}</a></li>
		{/section}
 		</ul>
		<br/>
		<span class="block textr"><a href="/new_coupon.html" class="reddark">更多最新优惠</a></span>
	</div>
</div>

<div id="middle">
	<!--begin middle_map-->
	<div class="middle_map">
		<a href="/">首页</a> > 网站地图
		<ul>
			{section name=loop loop=$categoryarray}
			<li class="liline"><a href="/sitemap/{$categoryarray[loop].NameURL}.html">{$categoryarray[loop].Name}</a></li>
			{/section}
		</ul>
		<!--begin footer-->
	<div class="footer"  id="footerprint">
		<div class="copyright">
			<br/>
			使用本站必须遵守大红包 <A href="/Privacy_Policy.html">隐私条例</A> 和<A href="/Terms_&amp;_Conditions.html">服务条款</A>					
		</div>
		<div class="contactus" >
			<div class="right" style="margin-top:10px;"><img src="/images/shca_cc.gif" class="f" /><span class="block f" style="line-height:30px; padding-left:5px;">沪ICP备12034406号</span>
			</div>
		<p><A href="/About_CouponMountain.html">关于大红包</A>  | <A href="/Contact_Us.html">联系我们</A>  | <A href="/sitemap/index.html">网站地图</A>  | 大红包推荐您去其他国家看看： <a href="http://www.couponmountain.com">美国</a> <a href="http://www.couponmountain.co.uk">英国</a> <a href="http://www.waribikiya.com">日本</a> <a href="http://www.savingsmountain.com">澳洲</a> <a href="http://www.couponmountain.de">德国</a> <a href="http://www.bonpromo.com">法国</a></p>

		<p>版权归 &copy; 2007 <a href="http://www.mezimedia.com/" target="_blank">Mezi Media</a> 所有</p>
		</div>			
	</div>
	<!--end footer -->
	</div>
	<!--end middle_map-->
	
</div>
<!--end middle -->
<!--
   make_stat(-1,-1,-1,1);
   afp_stat();
//-->
</script>

</div>
<!--end main -->
</body>
</html>
