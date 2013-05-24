{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			<!--end adv -->
		<div>
		<table>
		   <tr>
      <td width="1%"><img src="/images/bgim.gif" width="30" height="30"></td>
      <td><table border=0 cellpadding=2 cellspacing=2 width=600>
<tr>
<td><b><font size="5">关于大红包购物商城</font></b></td>
</tr>

<tr>
<td><img src="http://www.dahongbao.com/add/press_hr.gif" width=600 height=1 border=0 alt=""></td>
</tr>

<tr>
<td><img src="http://www.dahongbao.com/add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>


<tr>
<td>
<font face="arial" size="-1" color=black>欢迎光临<B>大红包购物商城</B>！无数的美国顾客在上网购物的时候都忘不了用Coupon，大家非常喜欢这样轻松直接的省钱方式，我们的<a href="http://www.couponmountain.com">CouponMountain</a> 因此在美国成为了最为流行的网站之一，您不仅可以找到您喜欢的商店和品牌，同时能够享受到优惠和省钱的多多乐趣。<br><br><a href="http://www.couponmountain.com">CouponMountain</a> 来到中国，宗旨始终如一：让大家每次网上购物省钱更加便捷。<br><br>希望您会喜欢<B>大红包购物商城</B>，同时也很高兴听到您宝贵的意见和建议，帮助我们做得更加出色。真诚期望听到您的声音！<br><br>
</font></td>
</tr>
</table>
</td>
</tr>
</table>
		</div>
			{include file="foot.tpl"}
			<!--end footer -->
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->
<!--
   make_stat({$category_cur},{$merchant_cur},{$coupon_cur},1);
   afp_stat();
//-->
</script>
</div>
<!--end main -->
</body>
</html>
