{include file="simple_head_noTracking.tpl"}
{include file="head.tpl"}
<script language="JavaScript" src="/jscript/category.js"></script>
<div id="main">
	<div class="local" style="padding-left:15px;">{$navigation_path}</div>	
	<div class="search_main">
	{if $smarterList}
		<div class="search_right">
			<h3>Smarter������Ʒ</h3>
			<ul>
			{section name=index loop=$smarterList}
				<li>			
					<a href="{$smarterList.index.URL}"><img src="{$smarterList.index.ImageURL}" alt="{$smarterList.index.Title}"/></a>
					<span><a href="{$smarterList.index.URL}">{$smarterList.index.Title} </a><br/><a href="{$smarterList.index.URL}" class="r_price">{$smarterList.index.Price}</a></span>
				</li>
			{/section}
			</ul>
		</div>
		<!--end search_right -->
	{/if}
		<div class="search_left">
		{if $couponList}
			<div class="b"><span class="blue">"{$seKey}"</span>����Ż�ȯ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�������ˣ�<select onchange="trygosearch(this.value)">{foreach from=$cityarray key=k item=foo}<option value="{$k}" {if $k==$nowcityid}selected{/if}>{$foo}</option>{/foreach}</select></div>
			{section name=index loop=$couponList}
			<div class="couponlist2 searchcoupon">
				<div class="f couponimg">{if $couponList[index].image}<a href="{$couponList[index].couponURL}"><img src="{$couponList[index].image}" width="{$couponList[index].imageX}" height="{$couponList[index].imageY}" alt="{$couponList[index].couponTitle}"></a>{else}<a href="{$couponList[index].couponURL}"><img src="/images/dahongbao.gif" width="100" height="90" alt="{$couponList[index].couponTitle}"></a>{/if}</div>
				<dl>
					<dt><a href="{$couponList[index].couponURL}" class="blue">{$couponList[index].couponTitle}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{if $couponList[index].merchantURL}<a href="{$couponList[index].merchantURL}" class="reddark">����{$couponList[index].merchantName}</a>{else}{$couponList[index].merchantName}{/if}</dt>
					<dt>����ʱ�䣺{$couponList[index].start} ����ʱ�䣺{$couponList[index].end} </dt>
					<dd>������{if $couponList[index].City==""}����{else}{$couponList[index].City}{/if} </dd>
					<dd class="couponlistbottom"><a href="{$couponList[index].saveUrl}" class="addtofav blue">�����ղ�</a><a href="JavaScript:window.open('/send_friend.php?p={$couponList[index].couponID}&c=','sendfriend{$couponList[index].couponID}','width=415,height=550,resizable=0,scrollbars=yes');void(0);" class="comtofriend blue">�Ƽ�����</a></dd>
				</dl>
			</div>
			{/section}
			{if $pageStr}
			<div class="pagelist">
			{$pageStr}
			</div>
			{/if}
		{else}
			<div class="b">û��{if $seKey}<span class="blue">"{$seKey}" </span>{else}������{/if}��������Ϣ </div>
			<div class="searchlist">
			��ʾ:
			  <ul>
			   <li>��������ȷ���̼�����</li>
			   <li>ͨ��Ŀ¼Ѱ������Ҫ���Ż�ȯ��Ϣ</li>
			  </ul>
		{/if}
		<br />
		<br />
		{if $ads}
		<script language="javascript">
		{literal}
		function _ss(w,id) {
		  window.status = w;
		  return true;
		}
		function _cs() {
		  window.status = "";
		}
		{/literal}
		</script>
		<div>
		<table align="left" cellpadding="0" cellspacing="0" border="0">
		<tr><td colspan="{$adsCount}">
		<div class="sponsr" style="font-size:13px;">
		<h3><img src="{$adsImageUrl}" border="0"  height="15" /></h3>
		</div>
		</td></tr>
		<tr>
		{section name=outer loop=$ads}
		<td>
		<div class="sponsr" style="font-size:13px; width:140px; height:140px; padding-top:10px;">
		<p onMouseOver="return _ss('{$ads[outer].visible_url}')" onMouseOut="_cs()">
		<a href='{$ads[outer].url}' class="green b" target="_blank">{$ads[outer].LINE1}</a>
		<a href='{$ads[outer].url}' style="text-decoration:none;" target="_blank">{$ads[outer].LINE2}</a>
		<a href='{$ads[outer].url}' style="text-decoration:none;" target="_blank">{$ads[outer].LINE3}</a>
		<a href='{$ads[outer].url}' class="greyls" target="_blank">{$ads[outer].SiteUrl}</a></p>
		</div>
		</td>	
		{/section}</tr>
		</table>
		{/if}
		</div>
		<!--end search_left -->
	</div>
	<!--end search_main -->
</div>
<!--end main -->
{include file="foot_search.tpl"}
<!--end footer -->
<!--
   make_stat(-1,-1,-1,1);
   afp_stat();
//-->
</script>

</body>
</html>
