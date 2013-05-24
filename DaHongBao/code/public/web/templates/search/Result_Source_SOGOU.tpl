<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>{$searchText}电子优惠券,打折信息,促销活动</title>
<META NAME="description" CONTENT="{$description}">
<META NAME="keywords" CONTENT="{$keywords}">
<meta name="copyright" content="www.dahongbao.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="verify-v1" content="QVIPb4M3AU1SQ7HQONfC2lrlH7RkeUn+PL1dLv8l5fs=" />
<link href="../css/search_sogou_allbd.css" rel="stylesheet" type="text/css" media="all" />
<script src="/jscript/prototype.js"></script>
<style>
	{literal}
	.li_text a:hover {
		text-decoration:none;
	}
	.litext a:hover {
		text-decoration:none;
	}
	
	{/literal}
</style>
</head>
<body>
<!--头部开始-->
{include file="new/head_searchallbaidu.htm"}
<!--头部结束-->
<!--主体内容开始-->
{if $getprodcntFlag == "yes"}
<!--productcnt:{$ProductCnt}--> 
<!--sponsorlinkcnt:{$AdsCnt}-->
{/if}
<div id="content">
	{*google头部广告开始*}
	{if $adsResult.googleAds[0]}
	<div class="ad">
		{foreach name=adsGoogle item=ads from=$adsResult.googleAds[0]}
			<div class="{if !$smarty.foreach.adsGoogle.last}ad_borderB {/if}bg_img_{$smarty.foreach.adsGoogle.iteration}">
			<p class="ad_title"><a href="{$ads.url}" target="_blank">{$ads.LINE1}</a></p>
			<p class="ad_text">{$ads.LINE2}</p>
			<p class="ad_url"><a href="{$ads.url}" target="_blank">{$ads.SiteUrl}</a> </p>		
			</div>
		{/foreach}
	</div>
	{/if}
	{if $adsResult.baiduAds[0]}
	<div class="ad adfill">
		{foreach name=ads item=ads from=$adsResult.baiduAds[0]}
		<div class="{if !$smarty.foreach.ads.last}ad_borderB {/if} bg_img_{$smarty.foreach.adsGoogle.iteration+$smarty.foreach.ads.iteration}" onmouseout="glb_baidu_cs(this)" onmouseover="return glb_baidu_ss('{$ads.url}', this)">
			<a href="{$ads.url}" target="_blank">
			<span class="ad_title">{$ads.title}</span>
			<span class="ad_text">{$ads.abstract}</span>
			<span class="ad_url">{$ads.site}</span>
			</a>
		</div>
		{/foreach}
	</div>
	{/if}
	{*google头部广告结束*}
	
	<!--头部开始-->
	<div class="channelTab">
	<ul>
		<li><strong>全部</strong><span class="libring">({$countall})</span></li>
		<li>
		{if $searchlistcount.1>0}
		<a href="/se-{$searchTextFormat}-1-1/" title="优惠券">优惠券({$searchlistcount.1})</a>
		{else}
		优惠券(0)
		{/if}
		</li>
		<li>
		{if $searchlistcount.2>0}
		<a href="/se-{$searchTextFormat}-1-2/" title="折扣信息">折扣信息({$searchlistcount.2})</a>
		{else}
		折扣信息(0)
		{/if}
		</li>
		<li>
		{if $searchlistcount.113>0}
		<a href="/se-{$searchTextFormat}-1-113/" title="劲爆特价">劲爆特价({$searchlistcount.113})</a>
		{else}
		劲爆特价(0)
		{/if}
		</li>
		<li>
		{if $searchlistcount.99 > 0}
		<a href="/se-{$searchTextFormat}-1-99/" title="旅游">旅游度假({$searchlistcount.99})</a>
		{else}
		旅游度假(0)
		{/if}
		</li>
		<li>
		{if $searchlistcount.3>0}
		<a href="/se-{$searchTextFormat}-1-3/" title="社区">社区({$searchlistcount.3})</a>
		{else}
		社区(0)
		{/if}
		</li>
	</ul>
	</div>
	<!--头部结束-->
	{if $countall <= 0}
	<div class="search_box" style="padding-left:20px;">
	<ul>
		<li class="lititle">对不起，暂时没有"{$searchText}"相关的信息。</li>
	</ul>
	</div>
	{/if}
	<div class="clr"></div>
	
	{*google底部广告开始*}
	{if $adsResult.googleAds[1]}
	<div class="ad">
		{foreach name=adsBottom item=ads from=$adsResult.googleAds[1]}
		<div class="{if !$smarty.foreach.adsBottom.last}ad_borderB {/if}bg_img_{$smarty.foreach.adsBottom.index+1+6}">
			<p class="ad_title"><a href="{$ads.url}" target="_blank">{$ads.LINE1}</a></p>
			<p class="ad_text">{$ads.LINE2}</p>
			<p class="ad_url"><a href="{$ads.url}" target="_blank">{$ads.SiteUrl}</a> </p>
		</div>
		{/foreach}
	</div>
	{/if}
    {if $adsResult.baiduAds[1]}
	<div class="ad adfill">
		{foreach name=adsBaiduBottom item=ads from=$adsResult.baiduAds[1]}
		<div class="{if !$smarty.foreach.adsBaiduBottom.last}ad_borderB {/if}bg_img_{$smarty.foreach.adsBaiduBottom.index+1+6+$smarty.foreach.adsBottom.iteration}" onmouseout="glb_baidu_cs(this)" onmouseover="return glb_baidu_ss('{$ads.url}', this)">
			<a href="{$ads.url}" target="_blank">
			<span class="ad_title">{$ads.title}</span>
			<span class="ad_text">{$ads.abstract}</span>
			<span class="ad_url">{$ads.site}</span>
			</a>
		</div>
		{/foreach}
	</div>
	{/if}
	{*google底部广告结束*}
	
</div>
<!--主体内容结束-->
			
{include file="new/foot.htm"}