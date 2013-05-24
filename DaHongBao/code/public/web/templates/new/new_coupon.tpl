<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>最新信息 - 大红包购物商城提供各类电子优惠券</title>
<META NAME="description" CONTENT="大红包购物商城为您提供网络上最IN，最流行的优惠券，折扣券和特卖行动，包括书籍，数码产品，化妆品，鲜花等各类商品的折扣信息。">
<META NAME="keywords" CONTENT="">
<meta name="copyright" content="www.dahongbao.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="verify-v1" content="QVIPb4M3AU1SQ7HQONfC2lrlH7RkeUn+PL1dLv8l5fs=" />
<link href="../css/detail.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<!--头部开始-->
{include file="new/head.htm"}
<!--头部结束-->
<!--主体内容开始-->
<div id="content">
	<!--category_title 导航开始-->
	<div id="category_title">
		<span class="bring">当前位置:</span> <a href="/">首页</a> > <h1>最新优惠购物券</h1>
	</div>
	<!--category_title 导航结束-->		
	<!--sidebar 栏目页左边内容开始-->
	<div id="sidebar">
		<!--sidebox 热门优惠券开始-->
		<div class="sidebox">
			<h2>最新优惠券</h2>
			<ul>
				{section name=loop loop=$newsestcoupon start=0 max=10}
				<li>·{if $newsestcoupon[loop].Merchant_>0}【<a href="/Me-{$newsestcoupon[loop].NameURL}--Mi-{$newsestcoupon[loop].Merchant_}.html">{if $newsestcoupon[loop].name1 == ""}{$newsestcoupon[loop].name}{else}{$newsestcoupon[loop].name1}{/if}</a>】{/if}<a href="/{if $newsestcoupon[loop].NameURL}{$newsestcoupon[loop].NameURL}{else}merchant{/if}/coupon-{$newsestcoupon[loop].Coupon_}/">{$newsestcoupon[loop].Descript}</a></li>
				{/section}
			</ul>
		</div>
		<!--sidebox 热门优惠券结束-->
			
		<!--sidebox 热门优惠券开始-->
		<div class="sidebox">
			<h2>热门资讯</h2>
			<ul>
				{section name=loop loop=$hotinfo start=0 max=10}
				<li>·<a href="/news---Ca-{$NameURL}--Ci-{$hotinfo[loop].fid}--number-{$hotinfo[loop].tid}.html">{$hotinfo[loop].subject}</a></li>
				{/section}
			</ul>
		</div>
		<!--sidebox 热门优惠券结束-->
			
		<!--sidebox 热门优惠券开始-->
		<div class="sidebox">
			<h2>论坛热帖</h2>
			<ul>
				{section name=loop loop=$hotbbs start=0 max=10}
				<li>·<a href="/bbs/read.php?tid={$hotbbs[loop].tid}">{$hotbbs[loop].subject}</a></li>
				{/section}
			</ul>
		</div>
		<!--sidebox 热门优惠券结束-->
	</div>
	<!--sidebar 栏目页左边内容结束-->	
	
	<!--detail 具体内容开始-->
	<div id="detail">
		<div id="detail_title2"><h2>{$startDay1} 开始的优惠购物券</h2></div>
		<!--list_box 列表开始-->
		<div class="list_box">
			<ul>
				{section name=index loop=$couponTodayList}
				<li class="lileft">·<a href="{$couponTodayList[index].couponURL}" target="_blank">{$couponTodayList[index].couponTitle}</a></li>
				<li class="liright">{$couponTodayList[index].status}</li>
				{/section}
			</ul>
		</div>
		<!--list_box 列表结束-->			
	</div>

	<div id="detail">
		<div id="detail_title2"><h2>{$startDay2} 开始的优惠购物券</h2></div>
		<!--list_box 列表开始-->
		<div class="list_box">
			<ul>
				{section name=index loop=$couponBeforeList1}
				<li class="lileft">·<a href="{$couponBeforeList1[index].couponURL}" target="_blank">{$couponBeforeList1[index].couponTitle}</a></li>
				<li class="liright">{$couponBeforeList1[index].status}</li>
				{/section}
			</ul>
		</div>
		<!--list_box 列表结束-->			
	</div>

	<div id="detail">
		<div id="detail_title2"><h2>{$startDay3} 开始的优惠购物券</h2></div>
		<!--list_box 列表开始-->
		<div class="list_box">
			<ul>
				{section name=index loop=$couponBeforeList2}
				<li class="lileft">·<a href="{$couponBeforeList2[index].couponURL}" target="_blank">{$couponBeforeList2[index].couponTitle}</a></li>
				<li class="liright">{$couponBeforeList2[index].status}</li>
				{/section}
			</ul>
		</div>
		<!--list_box 列表结束-->			
	</div>
	<!--detail 具体内容结束-->	
</div>
<!--主体内容结束-->

{include file="new/foot.htm"}
