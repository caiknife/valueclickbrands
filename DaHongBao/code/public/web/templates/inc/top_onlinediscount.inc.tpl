{assign var="topNavSelected" value="3"}
{include file="inc/top.inc.tpl"}
<!--nav���� ��ʼ-->
<div id="nav_discount">
	<ul>			
	</ul>
</div>
<!--nav���� ����-->
{*** nav���� ��ʼ ***}
<div id="nav">
	<ul>
	{section name=loop loop=$categories}
	{if $categories[loop].child}
		<li class="home" onmouseover="document.getElementById('menu_{$categories[loop].CategoryID}').style.display = 'block';" onmouseout="document.getElementById('menu_{$categories[loop].CategoryID}').style.display = 'none';"><a href="{$categories[loop].URL}" {if $categories[loop].IsSelected=='YES'}class="newbring"{/if}>{$categories[loop].CategoryName}</a>
		<div class="homewrapper disn" id="menu_{$categories[loop].CategoryID}">
			<div class="topcow"><div class="arrowimg"></div></div>
			<div class="itemlist">
			<ul>
				{section name=childName loop=$categories[loop].child}
					<li onmouseover="this.className='menuMouseOver'" onmouseout="this.className='menuMouseOut'">
						<a href="{$categories[loop].child[childName].navigationUrl}">
						{$categories[loop].child[childName].CategoryName}
						</a>
					</li>
				{/section}
			</ul>
			<div class="clr"></div>
			</div>
		</div>
		</li>
	{elseif $categories[loop].IsSelected=='YES'}
		<li class="newbring">{$categories[loop].CategoryName}</li>
	{else}
		<li><a href="{$categories[loop].URL}" title="{$categories[loop].CategoryName}">{$categories[loop].CategoryName}</a></li>
	{/if}
	{/section}
	</ul>
</div>
{*** nav���� ���� ***}
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
