<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>商家在线折扣信息,在线折扣券</title>
<META NAME="description" CONTENT="大红包劲爆特价频道为您提供数以万计的热门商家的折扣信息，足不出户就能享受优惠惊喜">
<META NAME="keywords" CONTENT="在线折扣券">
<meta name="copyright" content="www.dahongbao.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="verify-v1" content="QVIPb4M3AU1SQ7HQONfC2lrlH7RkeUn+PL1dLv8l5fs=" />
<link href="/css/onlinediscount.css" rel="stylesheet" type="text/css"/>
<link href="/css/prodlist.css" rel="stylesheet" type="text/css"/>
<script src="/jscript/prototype.js"></script>
</head>
<body>
<div id="head" class="main_box">
{*** 头部开始 ***}
{include file="inc/top_onlinediscount.inc.tpl"}
{*** 头部结束 ***}
</div>
{*** 主体内容开始 ***}
<div id="content">
	{*-- category_title 导航开始 ***}
	<div id="category_title">
		<span class="bring">当前位置:</span> <a href="/">首页</a> &gt; 
		{if $breadCrums} 
			{section name=breadName loop=$breadCrums}
				<a href="{$breadCrums[breadName].navigationUrl}">{$breadCrums[breadName].navigationName}</a> &gt;
			{/section}
		{/if}
		<h1>{if $page}<a href="{$currentCategory.navigationUrl}">{$currentCategory.navigationName}</a>{/if}</h1>
	</div>
	{*** category_title 导航结束 ***}
	
	{*** category 具体内容开始 ***}
	<div id="category">
		{*** main 栏目页主体内容开始 ***}
		<div id="main">
			{*** ad 广告开始 ***}
			<div id="ad"></div>
			<div id="content_ad1"></div>
			{*** ad 广告结束 ***}

			{*** main 栏目页具体内容开始 ***}
			<div id="main_min">
				{*** main_title 头部开始 ***}
				<div class="main_title">
					<ul>
						<li class="liname">名称  </li>
						<li class="select">{$sortstring}</li>
					</ul>
				</div>
				{*** main_title 头部结束 ***}
				
				{section name=loop loop=$listProds}
				{*** main_box 具体内容块开始 ***}
				<div class="main_box">
					{*** main_left 左边开始 ***}
					<div class="main_left">
						<div class="topleft">
						<h3><a href="{$listProds[loop].DetailURL}" target=_blank>{$listProds[loop].Name}</a></h3>
						</div>

						<div class="main_leftimg">
						<a href="{$listProds[loop].DetailURL}" target=_blank>
							<img src="{$listProds[loop].ImageURL}" alt="{$listProds[loop].Name}" />
						</a>
						</div>

						<p>{$listProds[loop].Brief}</p>
						{if $listProds[loop].tagname neq ""}
						<div class="message">标签：{$listProds[loop].tagname}</div>
						{/if}
						<div class="message">
							<ul>
							<li>商家：{$listProds[loop].MerchantName} &nbsp; 收录日期：{$listProds[loop].LoginDate} </li>						
							<li class="view"><a href="{$listProds[loop].DetailURL}">查看详细</a></li>
						</div>
					</div>
					{*** main_left 左边结束 ***}
					
					{*** main_right 右边开始 ***}
					<div class="main_right">
						&nbsp; 原价：<span class="original_price">￥{$listProds[loop].FullPrice}</span><BR>
						&nbsp; 特价：<span class="discount_price">￥{$listProds[loop].Price}</span>
						{*** main_img 图片开始 ***}
						<div class="main_img1">
							<a href="{$listProds[loop].OfferURL}" target="_blank"><img src="/images/ico_buy.gif" alt="去购买" /></a>						
						</div>
						{*** main_img 图片结束 ***}
						
						{*** main_time 时间开始 ***}
						<div class="main_time">
						{$listProds[loop].couponStatus}
						</div>
						{*** main_time 时间结束 ***}
					</div>
					{*** main_left 右边结束 ***}
				</div>
				{*** main_box 具体内容块结束 ***}
				{/section}
				
				{$pagination}
			</div>
			{*** main 栏目页具体内容结束 ***}

		
			{***  广告结束 ***}
			<div id="content_ad2"></div>
		</div>
		{*** main 栏目页主体内容结束 ***}

		{*** sidebar 栏目页左边内容开始 ***}
		<div id="sidebar">			
			{if $categoryMerchantList}
			{*** sidebox 热门商家开始 ***}			
			<div class="sidebox">
				<div class="title"><h2>热门商家</h2></div>										
				<ul>
					{section name=loop loop=$categoryMerchantList start=0 max=15}
					<li>·<a href="{$categoryMerchantList[loop].MerURL}" target="_blank">{$categoryMerchantList[loop].Name}</a></li>
					{/section}
					<li style="text-align:right;padding-right: 10px;height: 16px;overflow: hidden;"><a href="/other_merchants.html" target="_blank">>>查看全部商家</a></li>
				</ul>
			</div>			
			{*** sidebox 热门商家结束 ***}
			{/if}
			
			{*** sidebox 热门商家结束 ***}
			<div class="sidebox">
				<div class="title"><h2>热门优惠券</h2></div>
				<ul>
					{section name=loop loop=$page.HotCoupon start=0 max=10}
					<li>·{if $page.HotCoupon[loop].Merchant_>0}【<a href="/Me-{$page.HotCoupon[loop].NameURL}--Mi-{$page.HotCoupon[loop].Merchant_}.html">{if $page.HotCoupon[loop].name1 == ""}{$page.HotCoupon[loop].name}{else}{$page.HotCoupon[loop].name1}{/if}</a>】{/if}<a href="/{if $page.HotCoupon[loop].NameURL}{$page.HotCoupon[loop].NameURL}{else}merchant{/if}/coupon-{$page.HotCoupon[loop].Coupon_}/">{$page.HotCoupon[loop].Descript}</a></li>
					{/section}
				</ul>
			</div>
			{*** sidebox 热门优惠券结束 ***}
			
			{*** sidebox 论坛相关开始 ***}
			<div class="sidebox">
				<div class="title"><h2>论坛热贴</h2></div>
				<ul>
					{section name=loop loop=$page.HotBBS start=0 max=10}
					<li>·<a href="/bbs/read.php?tid={$page.HotBBS[loop].tid}">{$page.HotBBS[loop].subject}</a></li>
					{/section}
				</ul>
			</div>
			{*** sidebox 论坛相关结束 ***}
		</div>
		{*** sidebar 栏目页左边内容结束 ***}
	</div>
	{*** category 具体内容结束 ***}

	{if $RESOURCE_INCLUDE!=""}
		{$RESOURCE_INCLUDE}
	{/if}
</div>
{*** 主体内容结束 ***}
{literal}
<script type="text/javascript">
	{/literal}
	smarter_adhost = "{$smarty.const.__ADHOST}";
	
	
	condition = "cat-{$catid}";
{literal}
smarter_ad = [{
		DocID: 'ad',
		PageKey: 'dhb_ol_discount',
		SectionKey: 'top_banner',
		ConditionKey: condition
	},
	{
		DocID: 'content_ad2',
		PageKey: 'dhb_ol_discount',
		SectionKey: 'bottom_banner',
		ConditionKey: condition
	}
];

</script>
{/literal}
<script type="text/javascript" src="{$smarty.const.__ADHOST}js/load.js"></script>
			
{include file="new/foot.htm"}