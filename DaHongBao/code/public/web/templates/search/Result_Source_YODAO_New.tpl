<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>{$searchText}�����Ż�ȯ,������Ϣ,�����</title>
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
<!--ͷ����ʼ-->
{include file="new/head_searchallbaidu.htm"}
<!--ͷ������-->
<!--�������ݿ�ʼ-->
{if $getprodcntFlag == "yes"}
<!--productcnt:{$ProductCnt}--> 
<!--sponsorlinkcnt:{$AdsCnt}-->
{/if}
<div id="content">
	{*googleͷ����濪ʼ*}
	{if $adsResult.googleAds[0]}
	<div class="ad adYodao2">
		{foreach name=adsGoogle item=ads from=$adsResult.googleAds[0]}
			<div>
			<p class="ad_title"><a href="{$ads.url}" target="_blank">{$ads.LINE1}</a></p>
			<p class="ad_text">{$ads.LINE2}</p>
			<p class="ad_url"><a href="{$ads.url}" target="_blank">{$ads.SiteUrl}</a> </p>
			</div>
		{/foreach}
	</div>
	{/if}
	{if $adsResult.baiduAds[0]}
	<div class="ad adfill">
		{section name=ads loop=$adsResult.baiduAds[0] start=0 max=6}
		<div class="{if !$smarty.section.ads.last}ad_borderB {/if} bg_img_{$smarty.section.ads.iteration}" onmouseout="glb_baidu_cs(this)" onmouseover="return glb_baidu_ss('{$adsResult.baiduAds[0][ads].url}', this)">
			<a href="{$adsResult.baiduAds[0][ads].url}" target="_blank">
			<span class="ad_title">{$adsResult.baiduAds[0][ads].title}</span>
			<span class="ad_text">{$adsResult.baiduAds[0][ads].abstract}</span>
			<span class="ad_url">{$adsResult.baiduAds[0][ads].site}</span>
			</a>
			</a>
		</div>
		{/section}
	</div>
	{/if}
	{*googleͷ��������*}
	
	<!--ͷ����ʼ-->
	<div class="channelTab">
	<ul>
		<li><strong>ȫ��</strong><span class="libring">({$countall})</span></li>
		<li>
		{if $searchlistcount.1>0}
		<a href="/se-{$searchTextFormat}-1-1/" title="�Ż�ȯ">�Ż�ȯ({$searchlistcount.1})</a>
		{else}
		�Ż�ȯ(0)
		{/if}
		</li>
		<li>
		{if $searchlistcount.2>0}
		<a href="/se-{$searchTextFormat}-1-2/" title="�ۿ���Ϣ">�ۿ���Ϣ({$searchlistcount.2})</a>
		{else}
		�ۿ���Ϣ(0)
		{/if}
		</li>
		<li>
		{if $searchlistcount.113>0}
		<a href="/se-{$searchTextFormat}-1-113/" title="�����ؼ�">�����ؼ�({$searchlistcount.113})</a>
		{else}
		�����ؼ�(0)
		{/if}
		</li>
		<li>
		{if $searchlistcount.99 > 0}
		<a href="/se-{$searchTextFormat}-1-99/" title="����">���ζȼ�({$searchlistcount.99})</a>
		{else}
		���ζȼ�(0)
		{/if}
		</li>
		<li>
		{if $searchlistcount.3>0}
		<a href="/se-{$searchTextFormat}-1-3/" title="����">����({$searchlistcount.3})</a>
		{else}
		����(0)
		{/if}
		</li>
	</ul>
	</div>
	<!--ͷ������-->
	{if $countall <= 0}
	<div class="search_box" style="padding-left:20px;">
	<ul>
		<li class="lititle">�Բ�����ʱû��"{$searchText}"��ص���Ϣ��</li>
	</ul>
	</div>
	{/if}
	<div class="clr"></div>
	{if $adsResult.baiduAds[0][6]}
	<div class="ad adfill">
		{section name=adsBaiduBottom loop=$adsResult.baiduAds[0] start=6 max=12}
		<div class="{if !$smarty.section.adsBaiduBottom.last}ad_borderB {/if}bg_img_{$smarty.section.adsBaiduBottom.iteration+6}" onmouseout="glb_baidu_cs(this)" onmouseover="return glb_baidu_ss('{$adsResult.baiduAds[0][adsBaiduBottom].url}', this)">
			<a href="{$adsResult.baiduAds[0][adsBaiduBottom].url}" target="_blank">
			<span class="ad_title">{$adsResult.baiduAds[0][adsBaiduBottom].title}</span>
			<span class="ad_text">{$adsResult.baiduAds[0][adsBaiduBottom].abstract}</span>
			<span class="ad_url">{$adsResult.baiduAds[0][adsBaiduBottom].site}</span>
			</a>
		</div>
		{/section}
	</div>
	{/if}
	{*google�ײ�������*}
	
</div>
<!--�������ݽ���-->
			
{include file="new/foot.htm"}