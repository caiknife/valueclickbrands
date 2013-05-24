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
						<IMG  src="/images/mostpopularcoupons.jpg" alt="最热门的折扣优惠券" width="179" height="104"  border=0 class="f">		
						<div class="fourpageinforight txt3E3E42">
							<p><span class="black b">最热门的折扣优惠券</span>
							每天会有五万多用户通过我们大红包参与各种各样的优惠活动。为了使您的工作更方便快捷，大红包每24小时对所有用户的参与情况进行统计，并且在这个页面内给您罗列了目前排名前30位的优惠活动。</p>
						</div>
						<div class="cl"></div>
					</div>
			<!--end adv -->
		<div id="hotcouponlist">
			<table class="listbox">
				<tr>
				<td width="7%" class="title">今天</td>
				<td width="32%" class="title">商家名称</td>
				<td width="35%" class="title">优惠内容介绍</td>
				<td width="13%" class="title">地区</td>
				<td width="13%" class="title" style="text-align:right;">过期时间</td>
				</tr>
				{section name=index loop=$couponList}
				<tr>
				  <td>{$smarty.section.index.rownum}</td>
				  <td>{if $couponList[index].merchantURL}<a href="{$couponList[index].merchantURL}" class="b">{$couponList[index].merchantName}</a>{else}{$couponList[index].merchantName}{/if}</td>
				  <td>{if $couponList[index].couponURL}<a href="{$couponList[index].couponURL}" target="_blank">{$couponList[index].couponTitle}</a>{else}{$couponList[index].couponTitle}{/if}</td>
				  <td>{$couponList[index].City}</td>
				  <td style="text-align:right;">{$couponList[index].status}</td>
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
