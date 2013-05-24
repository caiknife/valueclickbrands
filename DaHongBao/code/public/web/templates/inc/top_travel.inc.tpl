{assign var="topNavSelected" value="4"}
{include file="inc/top.inc.tpl"}
<!--nav导航 开始-->
<div id="nav_discount">
	<ul>			
	</ul>
</div>
{*** search搜索 开始 ***}
<div id="search">
	<ul><form method="GET" name="searchform" action="/search.php" target="_parent" onsubmit="if(searchform.searchText.value=='' || searchform.searchText.value=='请输入关键字') return false;">
		<li class="input1"><input name="searchText" type="text" class="input" style="{if $searchtextformer && $searchtextformer != '请输入关键字'}color:#000;{else}color:#CCC;{/if}" value="{if $searchtextformer}{$searchtextformer}{else}请输入关键字{/if}" {literal}onblur="if(this.value=='') { this.value='请输入关键字'; this.style.color='#CCC'; }else{ this.style.color='#000'; }"{/literal} onfocus="if(this.value=='请输入关键字') this.value=''; this.style.color='#000';"/><INPUT TYPE="hidden" name="needjump" value="true"></li>			
		<li class="search_but"><input type="image" src="/images/button_search.gif"/></li></form>
		{if $page.HotKeywords}
		<li class="hot">热门关键字：</li>
		<li class="hotmessage">{$page.HotKeywords}</li>
		{/if}
	</ul>
</div>
{*** search搜索 结束 ***}
