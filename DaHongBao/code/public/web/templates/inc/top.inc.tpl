{if $topNavSelected<1 || $topNavSelected>5}{assign var="topNavSelected" value="1"}{/if}
<script language="javascript" type="text/javascript" src="/jscript/st.js"></script>
<!--head_top 开始-->
<div id="head_top">		
	<div id="logo">
		<a href="/"><img src="/images/logo.gif" alt="大红包优惠券" /></a>
	</div>		
	<!--head_topnav 开始-->
	<div id="head_topnav">
		<div class="head_nav">
			<div class="head_navimg"><img src="/images/top_nav/current_{$topNavSelected}.gif" id="id2"/></div>
			<ul>
				{section name=i loop=$__Top_Navigation}
				{if $smarty.section.i.rownum != $topNavSelected}
				<li {$__Top_Navigation[i].ClassStr}><a href="{$__Top_Navigation[i].URL}" title="{$__Top_Navigation[i].Name}" onmouseover="document.getElementById('id2').src='/images/top_nav/other_{$topNavSelected}_{$smarty.section.i.rownum}.gif'" onmouseout="document.getElementById('id2').src='/images/top_nav/current_{$topNavSelected}.gif'">{$__Top_Navigation[i].Name}</a></li>
				{else}
				<li {$__Top_Navigation[i].ClassStr}><a href="{$__Top_Navigation[i].URL}" title="{$__Top_Navigation[i].Name}"><strong>{$__Top_Navigation[i].Name}</strong></a></li>
				{/if}
				{/section}
			</ul>
		</div>
		<!--下面的注释为登陆以后的状态-->
		{include file="new/head_userinfo.htm"}	
	</div>
	<!--head_topnav 结束-->			
</div>
<!--head_top 结束-->	
