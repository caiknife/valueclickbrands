{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
	<div class="local" style="padding-left:15px;">{$navigation_path}</div>	
	{section name=index loop=$couponList}
	{if $couponList[index].0.merchantName}
	<div class="search_dangdang">
		<h2><a class="reddark2" href="{$couponList[index].0.merchantURL}">{$couponList[index].0.merchantName}优惠信息</a></h2>
		<ul>
		{section name=n loop=$couponList[index]}
			<li>
				<div class="couponlist3 dangdanglist">
					<div class="f couponimg">{if $couponList[index][n].image}<a href="{$couponList[index][n].couponURL}"><img src="{$couponList[index][n].image}" width="{$couponList[index][n].imageX}" height="{$couponList[index][n].imageY}" alt="{$couponList[index][n].couponTitle}"></a>{/if}</div>
					<dl>
						<dt><a href="{$couponList[index][n].couponURL}" class="blue">{$couponList[index][n].couponTitle}</a> </dt>
						<dd>发布时间：{$couponList[index][n].start} 结束时间：{$couponList[index][n].end} </dd>
						<dd class="couponlistbottom"><a href="{$couponList[index][n].saveUrl}" class="addtofav blue">加入收藏</a><a href="JavaScript:window.open('/send_friend.php?p={$couponList[index][n].couponID}&c=','sendfriend{$couponList[index][n].couponID}','width=415,height=550,resizable=0,scrollbars=yes');void(0);" class="comtofriend blue">推荐朋友</a></dd>
					</dl>
				</div>
			</li>
		{/section}
		</ul>
	</div>
	{/if}
	{/section}
	<!--end search_dangdang -->
</div>
<!--end main -->
{include file="foot.tpl"}
<!--end footer -->
<!--
   make_stat(-1,-1,-1,1);
   afp_stat();
//-->
</script>

</body>
</html>
