{assign var="topNavSelected" value="4"}
{include file="inc/top.inc.tpl"}
<!--nav���� ��ʼ-->
<div id="nav_discount">
	<ul>			
	</ul>
</div>
{*** search���� ��ʼ ***}
<div id="search">
	<ul><form method="GET" name="searchform" action="/search.php" target="_parent" onsubmit="if(searchform.searchText.value=='' || searchform.searchText.value=='������ؼ���') return false;">
		<li class="input1"><input name="searchText" type="text" class="input" style="{if $searchtextformer && $searchtextformer != '������ؼ���'}color:#000;{else}color:#CCC;{/if}" value="{if $searchtextformer}{$searchtextformer}{else}������ؼ���{/if}" {literal}onblur="if(this.value=='') { this.value='������ؼ���'; this.style.color='#CCC'; }else{ this.style.color='#000'; }"{/literal} onfocus="if(this.value=='������ؼ���') this.value=''; this.style.color='#000';"/><INPUT TYPE="hidden" name="needjump" value="true"></li>			
		<li class="search_but"><input type="image" src="/images/button_search.gif"/></li></form>
		{if $page.HotKeywords}
		<li class="hot">���Źؼ��֣�</li>
		<li class="hotmessage">{$page.HotKeywords}</li>
		{/if}
	</ul>
</div>
{*** search���� ���� ***}
