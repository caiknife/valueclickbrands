<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�̼������ۿ���Ϣ,�����ۿ�ȯ</title>
<META NAME="description" CONTENT="���������ؼ�Ƶ��Ϊ���ṩ������Ƶ������̼ҵ��ۿ���Ϣ���㲻�������������Żݾ�ϲ">
<META NAME="keywords" CONTENT="�����ۿ�ȯ">
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
{*** ͷ����ʼ ***}
{include file="inc/top_onlinediscount.inc.tpl"}
{*** ͷ������ ***}
</div>
{*** �������ݿ�ʼ ***}
<div id="content">
	{*-- category_title ������ʼ ***}
	<div id="category_title">
		<span class="bring">��ǰλ��:</span> <a href="/">��ҳ</a> &gt; 
		{if $breadCrums} 
			{section name=breadName loop=$breadCrums}
				<a href="{$breadCrums[breadName].navigationUrl}">{$breadCrums[breadName].navigationName}</a> &gt;
			{/section}
		{/if}
		<h1>{if $page}<a href="{$currentCategory.navigationUrl}">{$currentCategory.navigationName}</a>{/if}</h1>
	</div>
	{*** category_title �������� ***}
	
	{*** category �������ݿ�ʼ ***}
	<div id="category">
		{*** main ��Ŀҳ�������ݿ�ʼ ***}
		<div id="main">
			{*** ad ��濪ʼ ***}
			<div id="ad"></div>
			<div id="content_ad1"></div>
			{*** ad ������ ***}

			{*** main ��Ŀҳ�������ݿ�ʼ ***}
			<div id="main_min">
				{*** main_title ͷ����ʼ ***}
				<div class="main_title">
					<ul>
						<li class="liname">����  </li>
						<li class="select">{$sortstring}</li>
					</ul>
				</div>
				{*** main_title ͷ������ ***}
				
				{section name=loop loop=$listProds}
				{*** main_box �������ݿ鿪ʼ ***}
				<div class="main_box">
					{*** main_left ��߿�ʼ ***}
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
						<div class="message">��ǩ��{$listProds[loop].tagname}</div>
						{/if}
						<div class="message">
							<ul>
							<li>�̼ң�{$listProds[loop].MerchantName} &nbsp; ��¼���ڣ�{$listProds[loop].LoginDate} </li>						
							<li class="view"><a href="{$listProds[loop].DetailURL}">�鿴��ϸ</a></li>
						</div>
					</div>
					{*** main_left ��߽��� ***}
					
					{*** main_right �ұ߿�ʼ ***}
					<div class="main_right">
						&nbsp; ԭ�ۣ�<span class="original_price">��{$listProds[loop].FullPrice}</span><BR>
						&nbsp; �ؼۣ�<span class="discount_price">��{$listProds[loop].Price}</span>
						{*** main_img ͼƬ��ʼ ***}
						<div class="main_img1">
							<a href="{$listProds[loop].OfferURL}" target="_blank"><img src="/images/ico_buy.gif" alt="ȥ����" /></a>						
						</div>
						{*** main_img ͼƬ���� ***}
						
						{*** main_time ʱ�俪ʼ ***}
						<div class="main_time">
						{$listProds[loop].couponStatus}
						</div>
						{*** main_time ʱ����� ***}
					</div>
					{*** main_left �ұ߽��� ***}
				</div>
				{*** main_box �������ݿ���� ***}
				{/section}
				
				{$pagination}
			</div>
			{*** main ��Ŀҳ�������ݽ��� ***}

		
			{***  ������ ***}
			<div id="content_ad2"></div>
		</div>
		{*** main ��Ŀҳ�������ݽ��� ***}

		{*** sidebar ��Ŀҳ������ݿ�ʼ ***}
		<div id="sidebar">			
			{if $categoryMerchantList}
			{*** sidebox �����̼ҿ�ʼ ***}			
			<div class="sidebox">
				<div class="title"><h2>�����̼�</h2></div>										
				<ul>
					{section name=loop loop=$categoryMerchantList start=0 max=15}
					<li>��<a href="{$categoryMerchantList[loop].MerURL}" target="_blank">{$categoryMerchantList[loop].Name}</a></li>
					{/section}
					<li style="text-align:right;padding-right: 10px;height: 16px;overflow: hidden;"><a href="/other_merchants.html" target="_blank">>>�鿴ȫ���̼�</a></li>
				</ul>
			</div>			
			{*** sidebox �����̼ҽ��� ***}
			{/if}
			
			{*** sidebox �����̼ҽ��� ***}
			<div class="sidebox">
				<div class="title"><h2>�����Ż�ȯ</h2></div>
				<ul>
					{section name=loop loop=$page.HotCoupon start=0 max=10}
					<li>��{if $page.HotCoupon[loop].Merchant_>0}��<a href="/Me-{$page.HotCoupon[loop].NameURL}--Mi-{$page.HotCoupon[loop].Merchant_}.html">{if $page.HotCoupon[loop].name1 == ""}{$page.HotCoupon[loop].name}{else}{$page.HotCoupon[loop].name1}{/if}</a>��{/if}<a href="/{if $page.HotCoupon[loop].NameURL}{$page.HotCoupon[loop].NameURL}{else}merchant{/if}/coupon-{$page.HotCoupon[loop].Coupon_}/">{$page.HotCoupon[loop].Descript}</a></li>
					{/section}
				</ul>
			</div>
			{*** sidebox �����Ż�ȯ���� ***}
			
			{*** sidebox ��̳��ؿ�ʼ ***}
			<div class="sidebox">
				<div class="title"><h2>��̳����</h2></div>
				<ul>
					{section name=loop loop=$page.HotBBS start=0 max=10}
					<li>��<a href="/bbs/read.php?tid={$page.HotBBS[loop].tid}">{$page.HotBBS[loop].subject}</a></li>
					{/section}
				</ul>
			</div>
			{*** sidebox ��̳��ؽ��� ***}
		</div>
		{*** sidebar ��Ŀҳ������ݽ��� ***}
	</div>
	{*** category �������ݽ��� ***}

	{if $RESOURCE_INCLUDE!=""}
		{$RESOURCE_INCLUDE}
	{/if}
</div>
{*** �������ݽ��� ***}
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