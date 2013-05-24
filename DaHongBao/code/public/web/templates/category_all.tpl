{include file="simple_head_category.tpl"}
{include file="head_category.tpl"}
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
{literal}
<script language="JavaScript" src="/jscript/category.js"></script>

{/literal}
<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			{$pageString}
			{include file="firefox.tpl"}
			<!--end adv -->
		<div>
			{if $couponList}
			<span class="black txt13 b">商家及其优惠活动列表</span>
			<table class="listbox">
				<tr>
				<td width="25%" class="title">商家名称</td>
				<td width="35%" class="title">优惠内容介绍</td>
				<td width="8%" class="title">优惠类别</td>
				<td width="17%" class="title" style="padding-bottom: 9px;">地区&nbsp;<select onchange="trygo(this.value)">{foreach from=$cityarray key=k item=foo}<option value="{$k}" {if $k==$nowcityid}selected{/if}>{$foo}</option>{/foreach}</select></td>
				<td width="13%" class="title" style="text-align:right;">过期时间</td>
				</tr>
				{section name=index loop=$couponList}
				{if $couponList[index].isAbled}
				<tr>
				  {if $couponList[index].merName}
				  <td class="over">{if $couponList[index].merUrl}<a href="{$couponList[index].merUrl}" class="b">{$couponList[index].merName}</a>{else}{$couponList[index].merName}{/if}</td>
				  {else}
				  <td class="over"></td>
				  {/if}
				  <td class="over">
				  {if $couponList[index].couponUrl}<a href="{$couponList[index].couponUrl}" target="_blank">{$couponList[index].couponTitle}</a>&nbsp;{if $couponList[index].Hasmap==1}<img src="/images/map.gif" alt="有地图">{/if}{else}{$couponList[index].couponTitle}{/if}
				  </td>
				  <td>{if $couponList[index].CouponType==1}<img src="/images/w.gif" alt="网络优惠券">{elseif $couponList[index].CouponType==2}<img src="/images/z.gif" alt="折扣券">{else $couponList[index].CouponType==3}<img src="/images/q.gif" alt="抵用券">{/if}</td>
				   <td class="over">{$couponList[index].City}</td>
				  <td class="over" style="text-align:right;">{$couponList[index].couponStatus}</td>
			    </tr>
				{/if}
				{/section}
			</table>
			{/if}
			{$pageString}
		</div>
			{include file="foot_category.tpl"}
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
