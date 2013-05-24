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
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
						<div class="fourpageinfo">
				<IMG  src="images/freeshipping.jpg" alt="免费送货"   border=0 class="f">		
				<div class="fourpageinforight txt3E3E42">
					<p><span class="b black">免费送货</span>-欢迎进入大红包免费送货场。在这里购物会让您充分享受“免费”的乐趣。
但是，这里的显示的免费活动通常会有一个购买数量限制或者是需要一个优惠券编号，请大家购物的时候注意。</p>
					<p>祝您购物愉快！ </p>
				</div>
				<div class="cl"></div>
  	  	  </div>
		  
			<!--end fourpageinfo -->
		<div>
			<table class="listbox">
				<tr>
				<td width="32%" class="title">商家名称</td>
				<td width="40%" class="title">优惠内容介绍</td>
				<td width="13%" class="title">地区</td>
				<td width="13%" class="title" style="text-align:right;">过期时间</td>
				</tr>
				{section name=index loop=$couponFreeList}
				<tr>
				  <td>{if $couponFreeList[index].merchantURL}<a href="{$couponFreeList[index].merchantURL}" class="b">{$couponFreeList[index].merchantName}</a>{else}{$couponFreeList[index].merchantName}{/if}</td>
				  <td>{if $couponFreeList[index].couponURL}<a href="{$couponFreeList[index].couponURL}" target="_blank">{$couponFreeList[index].couponTitle}</a>{else}{$couponFreeList[index].couponTitle}{/if}</td>
				  <td>{$couponFreeList[index].City}</td>
				  <td style="text-align:right;">{$couponFreeList[index].status}</td>
			    </tr>
				{/section}
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
