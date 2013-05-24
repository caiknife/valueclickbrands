{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<!--end top10coupon -->		
	<div class="categorymenu">
		<ul><li><h2>&nbsp;&nbsp;优惠频道分类</h2></li></ul>
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
	<!--end categorymenu -->
	{$newCoupon}
	{$hotCoupon}
	<!--end hotmerchant -->
</div>
<!--end left -->



<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local"><a class="navigationLink" onclick="top.MyClose=false;"  href="http://www.dahongbao.com/">首页</a>&nbsp;&gt;&nbsp;最热门的折扣优惠券</div>	
			<!--end local -->
			<div class="fourpageinfo">
				<IMG  src="images/mostpopularcoupons.jpg" alt="最热门的折扣优惠券" width="179" height="104"  border=0 class="f">		
				<div class="fourpageinforight grey">
					<p><span class="black b">最热门的折扣优惠券</span>
					每天会有五万多用户通过我们大红包参与各种各样的优惠活动。为了使您的工作更方便快捷，大红包每24小时对所有用户的参与情况进行统计，并且在这个页面内给您罗列了目前排名前30位的优惠活动。</p>
				</div>
				<div class="cl"></div>
	  	  	</div>
			<!--end fourpageinfo -->
		<div id="hotcouponlist">
			
			<table class="listbox">
				<tr>
				<td class="title">今天</td>
				<td class="title">&nbsp;</td>
				<td class="title">昨天</td>
				<td class="title">商家</td>
				<td class="title">打折情况</td>
				<td class="title">优惠内容</td>
				<td class="title" style="text-align:right;">过期时间</td>
				</tr>
				{section name=outer loop=$hotcoupons}
				<tr>
				  <td>1</td>
				  <td>--</td>
				  <td>1</td>
				  <td><a href="/" class="b">东方CJ</a></td>
				  <td>86折</td>
				  <td><a href="/frame.php?p=17348" target="_blank">电视购物顾客注册即得20元购物积分！</a></td>
				  <td style="text-align:right;">进行中</td>
			    </tr>
				{/section}
			</table>

				
		</div>
			<!--end hotcouponlist -->

		{include file="foot.tpl"}
			
		
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->


</div>
<!--end main -->
</body>
</html>