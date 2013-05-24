{include file="simple_head_merchant.tpl"}
{include file="head_merchant.tpl"}
<div id="main">
<div id="left" class="fillBg">
	<div class="categorymenu">
		<ul><li><h2>&nbsp;&nbsp;优惠频道分类</h2></li></ul>
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
	{$RESOURCE_INCLUDE}
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			{include file="firefox.tpl"}
			<!--end adv -->
		<div class="merchantinfo">
				  {if $merchantLogo}<a {if $merchantURL}href="{$merchantURL}"{/if}><IMG  src="{$merchantLogo}" alt="{$merchantName}优惠券、折扣购物券"  border=0 class="f" width="200"></a>{/if}		
				  <div class="merchantinforight txt3E3E42"><h1>{$merchantName}</h1><BR>{$merchantDescript}
				  {if $merchantURL}<p class="b">直接访问商家主页: <a href="{$merchantURL}" target="_blank"><span class="blue">{$merchantName}</span></a> </p>{/if}
				  </div>
			</div>
			<!--end merchantinfo -->
			<div id="merchantcoupon">
				{if $couponlist}
				<div>查看&打印{$merchantName}最新优惠券, 折扣活动, 促销信息</div>
				<div class="middletitle"><h2>{$merchantName} 的优惠券</h2></div>
				{section name=index loop=$couponlist}
				<div class="couponlist">
					<div class="f couponimg">{if $couponlist[index].image}{if $couponlist[index].url}<A href="{$couponlist[index].url}" target="_blank"><IMG src="{$couponlist[index].image}" alt="{$couponlist[index].title}" width="{$couponlist[index].imageX}" height="{$couponlist[index].imageY}" ></A>{else}<IMG src="{$couponlist[index].image}" alt="{$merchantName}优惠券、折扣购物券" width="{$couponlist[index].imageX}" height="{$couponlist[index].imageY}" >{/if}{else}<IMG src="/images/dahongbao.gif" alt="{$couponlist[index].title}" >{/if}</div>
					<div class="right seeit">{if $couponlist[index].url}<a href="{$couponlist[index].url}"  target="_blank"><img src="/images/blue_but.gif" alt="查看此优惠" /></a>{else}{/if}</div>
					<dl>
						<dt>{if $couponlist[index].url}<a href="{$couponlist[index].url}" class="blue" target="_blank">{$couponlist[index].title}</a>{else}{$couponlist[index].title}{/if} </dt>
						<dd>{$couponlist[index].detail}</dd>                                                                  
						<dd>开始时间：{$couponlist[index].start}  &nbsp;结束时间：{$couponlist[index].end}</dd>
						<dd class="couponlistbottom"><a href="{$couponlist[index].saveUrl}" class="addtofav blue">加入收藏</a><a href="JavaScript:window.open('/send_friend.php?p={$couponlist[index].couponID}&c=','sendfriend{$couponlist[index].couponID}','width=415,height=550,resizable=0,scrollbars=yes');void(0);" class="comtofriend blue">推荐朋友</a><!--<a href="#" onclick="javascript: return false;" class="comments">发表评论</a>--></dd>
					</dl>
				</div>
				{/section}
				{else}
				<div><font color="#aa0000">{$merchantName}新的优惠活动即将开始,近请关注</font></div>
				{/if}
				{if $specWord}
				<br />
				<p>{$specWord}</p>
				{/if}
			</div>
			{include file="foot_merchant.tpl"}
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
