<!DOCTYPE >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>{$title}</title>
<META NAME="description" CONTENT="{$description}">
<META NAME="keywords" CONTENT="{$keywords}">
<meta name="copyright" content="www.dahongbao.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="verify-v1" content="QVIPb4M3AU1SQ7HQONfC2lrlH7RkeUn+PL1dLv8l5fs=" />
<META name="y_key" content="4e5d1ab55ff4129b" />
<link href="/css/st.css" rel="stylesheet" type="text/css">
<link href="css/homepage.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/jscript/js.js"></script>
{literal}
<style>
.notice a:link,.notice a:visited,.notice a:hover {
	color: #fff;
	text-decoration: underling;
}
{/literal}
</style>
</head>
<body>
<div id="header">
	<div id="head">
		<!--begin head_left-->
		<div id="head_left">
			<div class="logo"><a href="/"><img src="images/logo.gif" alt="大红包电子优惠券大全" width="310" height="68" border="0" /></a></div>			
		</div>
		<!--end head_left-->
		<!--begin head_right-->
		<div id="head_right">
			<div class="libook"><a href="/useraddcoupon.php">发布优惠券</a></div>
			<div class="libbs"><a href="/bbs/">&nbsp;&nbsp;BBS社区&nbsp;&nbsp;</a></div>
		</div>
		<!--end head_right-->
		
<div class="head_middle">{literal}
					  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="410" height="68">
                    <param name="movie" value="/images/hk_AD.swf" />
                    <param name="quality" value="high" />
                    <embed src="/images/hk_AD.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="410" height="68"></embed>
		      </object>{/literal}
		</div>
	</div>
	<div class="nav">
		<ul class="right">
			<li><a href="/hot_coupon.html" class="reddark" title="热门的优惠">热门的优惠</a></li>
			<li><a href="/new_coupon.html" class="reddark" title="最新优惠">最新优惠</a></li>
			<li><a href="/expire_coupon.html" class="reddark" title="快过期的优惠">快过期的优惠</a></li>
			<li><a href="/freeshipping_coupon.html" class="reddark" title="免费送货">免费送货</a></li>
		</ul>
		<form method="GET" name="searchform" action="/search.php" target="_parent" style="margin-top:3px;padding:0px">
		<input maxlength="50"  class="searchbox" type="text" name="searchText"><input type="image" src="/images/search.gif" class="searchbotton">
		</form>
		<!--城市切换开始-->
	<div style="line-height: 20px;padding-left: 10px;"> &nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;城市选择：<a href="/changecity.php?id=2" rel="nofollow">上海站</a>&nbsp;&nbsp;<a href="/changecity.php?id=1" rel="nofollow">北京站</a>&nbsp;&nbsp;<a href="/changecity.php?id=5" rel="nofollow">香港站</a>&nbsp;&nbsp;<a href="/changecity.php?id=99" rel="nofollow">全国</a>&nbsp;] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当前位置：{$cityname}</div>
	</div>
		</div>  
	</div>
		<!--城市切换结束-->
	</div>
</div>
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
		<ul><li><h2>&nbsp;&nbsp;优惠频道分类</h2></li></ul>
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
	<div class="categorymenu">
		<h2>&nbsp;&nbsp;RSS订阅</h2>
		<ul>
		<li>
			&nbsp;&nbsp;<a href="wp-rss.php" target='_blank'><img src="/images/rss.gif" /></a></li>	
		<li>&nbsp;&nbsp;<a href="ad.php?dahongbaoadurl=12" target='_blank'><img src="/images/addToGoogle.gif"></a></li>
		<li>&nbsp;&nbsp;<a href="ad.php?dahongbaoadurl=13" target='_blank'><img src="/images/addToYahoo.gif"></a></li>	
		<li>&nbsp;&nbsp;<a href="ad.php?dahongbaoadurl=14" target='_blank'><img src="/images/msnlive.gif"></a></li>		
		<li>&nbsp;&nbsp;<a href="ad.php?dahongbaoadurl=15" target='_blank'><img src="/images/zhuaxia.gif"></a></li>
		<li>&nbsp;&nbsp;<a href="ad.php?dahongbaoadurl=16" target='_blank'><img src="/images/bloglines.gif"></a></li>
		<li>&nbsp;&nbsp;<a href="ad.php?dahongbaoadurl=17" target='_blank'><img src="/images/rojo.gif"></a></li>
		</ul>
	</div>
	<div class="account"><FORM name=form2 METHOD=POST ACTION="">
		<h2>订阅“活动预告”邮件</h2>
		您的信箱将及时收到活动预告，还有各种各样的优惠券！
		<input name="email" type="text"  class="searchbox" value="输入邮件地址" maxlength="50" style="margin-left:0;"><INPUT TYPE="hidden" name="addemail" value="true"><input type="submit" value="订阅" class="searchbotton" style="margin:0; height:22px;"/>
		</FORM>
	</div>
	
	<div class="advright">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ad.php?dahongbaoadurl=8" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=11757&u=&e=" width="120" height="60"  border="0"></a></div>
	<div class="advright">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ad.php?dahongbaoadurl=11" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=8230&u=&e=" width="120" height="120"  border="0"></a></div>
	<!-- <div class="advright">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ad.php?dahongbaoadurl=10" target=_blank><img src="http://images.bol.com.cn/bol/images/dp/200402/banner/bollinktech/0205_88_120x360.gif" width="120" height="360" border="0"></a><img src="http://track.linktech.cn/?m=bolcps&a=A100003846&l=00017" width="1" height="1" border="0" style="display:none"></div> -->
	<div class="advright"><iframe align=center width=120 height=240 src=http://eacs.eqifa.com/cctv/120x240x4.asp?deal_id=79 frameborder=no border=0 marginwidth=0 marginheight=0  scrolling=no></iframe></div>
	<div class="advright">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ad.php?dahongbaoadurl=9" target=_blank><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=9721&u=&e=" width="120" height="120"  border="0"></a><img src="http://track.linktech.cn/?m=yoee&a=A100003846&l=00038" width="1" height="1" border="0" style="display:none"></div>
	<!-- <div class="advright"><iframe  marginwidth="0" marginheight="0" hspace="0" frameborder="0" scrolling="no"  src="http://eavs02.eqifa.com/EQIFAEAVS_EC/eavsentry_ex.aspx?eqifa_ad_channel=25750&eqifa_user_id=45149&eqifa_action_id=853&eqifa_ad_id=4949&e1=bc75562cf09056f44ab8d33b4ad8c7e6&eu_id=" width="150" height="150"></iframe></div> -->
	<div class="advright">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ad.php?dahongbaoadurl=6" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=8211&u=&e=" width="120" height="60"  border="0"></a></div>
	<div class="advright">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ad.php?dahongbaoadurl=5" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=8709&u=&e=" width="120" height="60"  border="0"></a></div>
	<div class="advright">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ad.php?dahongbaoadurl=18" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=12190&u=&e=" width="120" height="60"  border="0"></a></div>
</div>
<div id="right" class="fillBg">
	<div style="margin-top:35px;"><A href="http://click.linktech.cn/?m=wall&a=A100003846&l=99999&l_type2=0&tu=http%3A%2F%2Fwww.menglu.com%2Fhuodong%2F070620ca%2F070620ca.htm" target="_blank"><img src="/images/61.gif" alt="免费领取梦露激情内衣10元购物卡" style="width:165px;"/></A></div>
	{$newCoupon}
	{$hotCoupon}
	<div class="advright"><a href="http://www.dahongbao.com/track/scripts/redir.php?bt=Y291cG9u&ci=68095&mi=832" target=_blank><img src="/images/dell.gif" border=0></a></div>
	<!-- <div class="advright"><a href="http://www.chanet.com.cn/click.cgi?a=109&d=11772&u=&e=" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=11772&u=&e=" width="120" height="120"  border="0"></a></div> -->
	<div class="advright"><a href="http://www.chanet.com.cn/click.cgi?a=109&d=46&u=&e=" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=46&u=&e=" width="120" height="240"  border="0"></a></div>
	<div class="advright"><a href="/ad.php?dahongbaoadurl=1" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=8975&u=&e=" width="120" height="120"  border="0"></a></div>
	<div class="advright"><a href="/ad.php?dahongbaoadurl=2" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=8487&u=&e=" width="150" height="250"  border="0"></a></div>
	<div class="advright"><a href="/ad.php?dahongbaoadurl=3" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=9037&u=&e=" width="120" height="120"  border="0"></a></div>
	<div class="advright"><a href="/ad.php?dahongbaoadurl=4" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=9002&u=&e=" width="120" height="360"  border="0"></a></div>
	<div class="advright"><a href="/ad.php?dahongbaoadurl=7" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=109&d=10821&u=&e=" width="120" height="120"  border="0"></a></div>
	<div class="advright"><a href="/ad.php?dahongbaoadurl=19" target="_blank"><img src="http://www.shishangqiyi.com/LinkTech/pic/120X120.gif" width="120" height="120" border="0"></a></div>
</div>
<div id="middle">
	<div class="mcontent" style="display:table;">
		<div class="middlecontent">
			<div class="local"><a class="navigationLink" onclick="top.MyClose=false;"  href="/">首页</a> > 优惠券折扣券-大红包购物商城提供各种电子优惠券、折扣券</div>	
			<!--end local -->
			<div class="suggest" >
			  <p>大红包 --- 知名的电子优惠券交换聚集地。超过<font color="#aa0000">{$totalCoupon}</font>条优惠券信息，无需注册，便可通过目录或者我们的搜索功能找到您想要的优惠信息。</p>
			  <p style="margin-top:10px;">大红包今天为您提供了<font color="#aa0000">{$todayCoupon}</font>个省钱的机会! </p>
			</div>
			<!--end suggest -->
			<!--<div class="reader"><a href="/"><img src="images/g.gif" alt="订阅到google" /></a><a href="/"><img src="images/y.gif" alt="订阅到yahoo" /></a><a href="/"><img src="images/live.gif" alt="订阅到live" /></a><a href="/"><img src="images/zx.gif" alt="订阅到抓虾" /></a></div>-->
			<!--end reader -->
		{$loadFromDB}
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->
<div id="footerbox">
	<div class="footercontent">
        {include file="foot_homepage.tpl"}
	</div>
</div>
<!--end footerbox -->
<!--
   make_stat({$category_cur},{$merchant_cur},{$coupon_cur},1);
   afp_stat();
//-->
</script>

</div>
<!--end main -->
</body>
</html>
