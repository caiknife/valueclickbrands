{include file="simple_head.tpl"}
{include file="head.tpl"}
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
	{$newCoupon}
	{$hotCoupon}
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
					<div class="fourpageinfo">
				<IMG  src="/images/book_im_001.gif" alt="所有商家"  class="f">		
				<div class="fourpageinforight txt3E3E42" style="margin-left:116px; height:77px; border-bottom:#8b8b8b 1px solid;">
					<p style="margin-left:15px;"><span class="black b">1000多家商家提供优惠信息,省钱尽在大红包</span>
					点击下面任何一个商家名称，即可了解此商家最近的优惠活动情况。 
商家名称后面的数字代表此商家提供的优惠活动个数. 购物愉快！  </p>
				</div>
  	  	  </div>
			<!--end fourpageinfo -->
		<div class="allmerchantlist">
			<div class="moremerchant">
				<a href="/other_merchants.html" class="reddark" style="float:right">更多商家</a>
			</div>
			{if $MerchantList}
			<div class="allmerchantlistbox">
				<span class="b">特色商家</span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantList}
						<li>{if $MerchantList[index].MerURL}<a href="{$MerchantList[index].MerURL}">{$MerchantList[index].MerInfo}</a>{else}{$MerchantList[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			<div class="moremerchant">
				<a href="/other_merchants.html" class="reddark" style="float:right">更多商家</a>
			</div>
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
