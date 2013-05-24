<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>{$detailInfo.ProductName}</title>
<META NAME="description" CONTENT="{$detailInfo.Brief}">
<META NAME="keywords" CONTENT="{$detailInfo.ProductName}">
<link href="/css/onlinediscount.css" rel="stylesheet" type="text/css"/>
<link href="/css/icoupon1.css" rel="stylesheet" type="text/css" media="all" />
<link href="/css/icoupon2.css" rel="stylesheet" type="text/css" media="all" />
<link href="/css/detail.css" rel="stylesheet" type="text/css" media="all" />
</head>

<script>
var glbLogin = {$islogon};
{literal}
	function checkgo(){
		if(document.form.content.value=='')
		{
			alert('请输入内容');
			document.form.content.focus();
			return false;
		}else{
			document.form.submit();
		}

	}

function vote(id){
	
	lo = location.href.split("#"); 
	window.location=lo[0]+"#notlogon";
	if(glbLogin==1){
		document.form.icon[1].checked="true";
		document.form.content.value="这条优惠券对我很有帮助!"
		document.form.content.select();
	}else{
		
	}
}
	
	function page(value){
			var page = value;
			var tid = document.hiddenall.couponwindid.value;
			var pageall = document.hiddenall.couponwindpage.value;
			var url = '/async_getreply.php'
			var pars = 'id=' + page +'&tid='+tid+'&pageall='+pageall;
			var myAjax = new Ajax.Request(
			url,
			{
			method: 'get',
			parameters: pars,
			onComplete: showResponse1
			});
		}
		function showResponse1(originalRequest)
		{
			var returnstr = originalRequest.responseText;
			var all = returnstr.split("^^%%!!@@##");
			var reply = all[0];
			var replyitem = reply.split("!#%$#@");
			var repstr = "";
			for(var i=0;i<replyitem.length-1;i++){
				reprow = replyitem[i].split("***$$$###");
				repstr += "<li class='libg'><span class='bring'>"+reprow[0]+"</span> 在 "+reprow[1]+" 发表评论";
				if(reprow[4]==2){
					repstr += "<span class='detail_bring'>进行举报</span>";
				}else if(reprow[4]==1){
					repstr += "<span class='detail_bring1'>表示支持</span>";
				}else{
					repstr += "发表评论";
				}
				
				repstr += ": </li><li class='libg1'>"+reprow[2]+"</li>";

			}
			//alert(repstr);
		
			document.getElementById('replycontent').innerHTML=repstr;
			document.getElementById('pagestr').innerHTML=all[1];
			//document.getElementById(b).innerHTML=parseInt(document.getElementById(b).innerHTML)+1;
		}

		function go(){
			if(this.content.value==''){alert('请输入您想发表的内容');return false;}else{this.submit();}
		}

		function goprint(){
			var src = document.getElementById('couponpicsrc').src;
			window.open("../../print.php?url="+src);
		}
		function goprinti(value){
			//var src = document.getElementById('couponpicsrc').src;
			window.open("../../print.php?id="+value);
		}
</script>
<script src="/jscript/prototype.js"></script>
{/literal}
<body>
<div id="head" class="main_box">
{*** 头部开始 ***}
{include file="inc/top_onlinediscount.inc.tpl"}
{*** 头部结束 ***}
{*** 主体内容开始 ***}
<div id="content">
	{*** category_title 导航开始 ***}
	<div id="category_title">
		<span class="bring">当前位置:</span> <a href="/">首页</a> &gt; 
		{section name=breadName loop=$breadCrums}
				<a href="{$breadCrums[breadName].navigationUrl}">{$breadCrums[breadName].navigationName}</a> &gt;
		{/section}
		<h1>{$detailInfo.ProductName}</h1>
	</div>
	{*** category_title 导航结束 ***}		
	<div class="detail_ads">
	{*** detail 具体内容开始 ***}
	<div id="detail" style="overflow: hidden;">
		<div id="detail_title"><h2>{$detailInfo.ProductName}</h2></div>
		<!--google 广告开始-->
		{if $adsResult.googleAds[0]}
		<div class="favBox favBg">
			<div class="title"></div>
			<ul>
				{foreach item=ads from=$adsResult.googleAds[0]}
					<li>
						<p class="tit"><a href="{$ads.url}" class="favTit"  rel="nofollow" target="_blank">{$ads.LINE1}</a></p>
						<p class="Des">{$ads.LINE2} </p>   <span class="www"><a href="{$ads.url}" class="favWww"  rel="nofollow" target="_blank">{$ads.SiteUrl}</a>  </span> 
					</li>
				{/foreach}
			</ul> 
		</div>
		{/if}
        {if $adsResult.baiduAds[0]}
		<div class="favBox favBg newFavBg">
			<div class="title"></div>
			<ul>
				{foreach item=ads from=$adsResult.baiduAds[0]}
					<li>
						<p class="tit"><a href="{$ads.url}" class="favTit"  rel="nofollow" target="_blank">{$ads.title}</a></p>
						<p class="Des">{$ads.abstract} </p>   <span class="www"><a href="{$ads.url}" class="favWww"  rel="nofollow" target="_blank">{$ads.site}</a>  </span> 
					</li>
				{/foreach}
			</ul> 
		</div>
		{/if}
		<!--google 广告结束-->
		<div style="padding: 10px;background: #fff;">
				{if $detailInfo.HasImage == "YES"}
					<div class="detail_proimg"><a href="{$detailInfo.OfferURL}" target="_blank"><img id="couponpicsrc" src="{$detailInfo.ImageURL}" alt="{$detailInfo.ProductName}" border=0/></a></div>
					<a href="{$detailInfo.OfferURL}" target="_blank"><img src="/images/ico_buy.gif" border=0/></a>
				{/if}

				<p>{if $detailInfo.Description}{$detailInfo.Description}{else}暂无详细信息{/if}</p>

		</div>
		{*** detail_box 产品具体内容开始 ***}
		<div class="detail_box">
			{*** detail_left 内容开始 ***}
			<div class="detail_left">
				{if $detailInfo.tagname neq ""}
				<div class="message">标签：{$detailInfo.tagname}</div>
				{/if}
			 	<div class="message">
					{if $detailInfo.name1 neq ""}
					<div class="more"><a href="/Ca-{$NameURL}--Ci-{$cid}.html">>>{$page.CurrentCategoryName}{$categoryName}</a></div>
					{/if}
					类别：<a href="{$currentCategory.navigationUrl}"><span class="red">{$currentCategory.navigationName}</span></a>
				</div>				
				<div class="message">							
					商家：{$detailInfo.MerchantName}
					&nbsp; 收录时间：{$detailInfo.LoginDate}						
				</div>
				{if $attributes} 
				<div class="message">店铺：团购网所属{if $attributes.SHOP}{$attributes.SHOP}{/if} {if $attributes.SHOPTEL}联系电话：{$attributes.SHOPTEL} {/if}{if $attributes.SHOPDESCRIPTION}<a href="javascript:void(0);" onclick="document.getElementById('shop_description').style.display='block';">查看店铺介绍</a>{/if}</div>
				{/if}
				<FORM METHOD=POST NAME="hiddenall" ACTION="">
					<INPUT TYPE="hidden" NAME="couponwindid" value="{$detailInfo.tid}">
					<INPUT TYPE="hidden" NAME="couponwindpage" value="{$pageAll}">
				</FORM>
			</div>
			{*** detail_left 内容结束 ***}
			
			{*** detail_right 投票开始 ***}
			<div class="detail_right">
				<div class="main_img">	
					<a href="{$detailInfo.OfferURL}" target="_blank"><img src="/images/ico_buy.gif" border=0/></a>
				</div>
			</div>

			<div class="price_box">
				&nbsp; 原价：<span class="original_price">￥{$detailInfo.FullPrice}</span><BR>
				&nbsp; 特价：<span class="discount_price">￥{$detailInfo.Price}</span>
			</div>
			
			{*** detail_right 投票结束 ***}			
		</div>
		{*** detail_box 产品具体内容结束 ***}	

		<div class="clr"></div>
		{if $attributes.SHOPDESCRIPTION}<div class="message disn" id="shop_description" style="width:750px;"><p style="text-indent:2em;">{$attributes.SHOPDESCRIPTION}</p></div>{/if}
		{*** detail_text 相关评论开始 ***}	
		{*** detail_text 相关评论结束 ***}
	</div>
	{*** detail 具体内容结束 ***}	
	 <!--google 广告开始-->
	{if $adsResult.googleAds[1]}
	<div class="favBox company_end_ads">
		<div class="foottitle"></div>
		<ul>
			{foreach item=ads from=$adsResult.googleAds[1]}
				<li>
					<p class="tit"><a href="{$ads.url}" class="favTit"  rel="nofollow" target="_blank">{$ads.LINE1}</a></p>
					<p class="Des">{$ads.LINE2} </p>   <span class="www"><a href="{$ads.url}" class="favWww"  rel="nofollow" target="_blank">{$ads.SiteUrl}</a>  </span> 
				</li>
			{/foreach}
		</ul> 
	</div>
	{/if}
    {if $adsResult.baiduAds[1]}
	<div class="favBox company_end_ads newFavBg{if $adsResult.googleAds[1]} newTopBg{/if}">
		<div class="foottitle"></div>
		<ul>
			{foreach item=ads from=$adsResult.baiduAds[1]}
				<li>
					<p class="tit"><a href="{$ads.url}" class="favTit"  rel="nofollow" target="_blank">{$ads.title}</a></p>
					<p class="Des">{$ads.abstract} </p>   <span class="www"><a href="{$ads.url}" class="favWww"  rel="nofollow" target="_blank">{$ads.site}</a>  </span> 
				</li>
			{/foreach}
		</ul> 
	</div>
	{/if}
	<!--google 广告结束-->
	</div>
	{*** sidebar 栏目页左边内容开始 ***}
	<div id="sidebar">			
		{*** sidebox 热门商家开始 ***}			
		{if $categoryMerchantList}
		<div class="sidebox">
			<div class="title"><h2>热门商家</h2></div>										
			<ul>
				{section name=loop loop=$categoryMerchantList start=0 max=15}
				<li>·<a href="{$categoryMerchantList[loop].MerURL}" target="_blank">{$categoryMerchantList[loop].Name}</a></li>
				{/section}
				<li style="text-align:right;padding-right: 10px;height: 16px;overflow: hidden;"><a href="/other_merchants.html" target="_blank">>>查看全部商家</a></li>
			</ul>
		</div>			
		{/if}
		{*** sidebox 热门商家结束 ***}
		
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
			<div class="title"><h2>论坛热贴</h2>
			</div>
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
{*** 主体内容结束 ***}
{if $tui==1}
<script>
	vote();
</script>
{/if}
{include file="new/foot.htm"}