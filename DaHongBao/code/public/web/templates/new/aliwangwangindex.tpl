<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�Ա�����</title>
<link href="/css/aliwangwang.css" rel="stylesheet" type="text/css" />
</head>
{literal}
<script>
function goPage(){
	var pagejump = parseInt($("#pagejump").attr('value'));
	{/literal}
	var url = "{url f=aliUrl switch=coupon id=$smarty.get.cid pageid=PG}";
	{literal}
	if(isNaN(pagejump)){
		return;
	}else{
		url = url.replace("PG",pagejump);
		window.location.href=url;
	}
}
</script>
{/literal}

<body scroll=no>
<div id="main">
<div id="main_box">
	<!--begin head-->
	{include file="new/head_aliwangwang.htm"}
	<!--end head-->
	<!--begin nav-->
	<div class="nav">
		<ul>
			{if $cid==1}
			<li><strong>ȫ��</strong></li>
				{section name=loop loop=$categorylist}
				<li>| <a href="{url f=aliUrl switch=coupon id=$categorylist[loop].Category}">{$categorylist[loop].Name}</a></li>
				{/section}
			{else}
			<li><a href="{url f=aliUrl switch=coupon id=1}">ȫ��</a></li>
				{section name=loop loop=$categorylist}
					{if $cid == $categorylist[loop].Category}
					<li>| <strong>{$categorylist[loop].Name}</strong></li>
					{else}
					<li>| <a href="{url f=aliUrl switch=coupon id=$categorylist[loop].Category}">{$categorylist[loop].Name}</a></li>
					{/if}
				{/section}
			{/if}
		</ul>
	</div>
	<!--end nav-->
	<!--begin left-->
	{$hotcoupon_include}
	<!--end left-->
	<!--begin right-->
	<div id="right">
		{if $hascoupon==0}
			<BR>�㻹û�ж�����Ϣ��<a href="{url f=aliUrl switch=dingzhi}">���������ж���</a>

		{else}
		<!--begin right_box-->
		{section name=loop loop=$allCategoryCoupon}
		<div class="right_box">			
			<div class="right_img"><a href="{url f=aliUrl switch=detail id=$allCategoryCoupon[loop].Coupon_}" title="{$allCategoryCoupon[loop].Descript}"><img src="{$allCategoryCoupon[loop].ImageURL}" alt="" /></a></div>			
			<ul>
				<li class="lititle"><a href="{url f=aliUrl switch=detail id=$allCategoryCoupon[loop].Coupon_}" title="{$allCategoryCoupon[loop].couponTitle}">{$allCategoryCoupon[loop].couponTitle}</a></li>
				<li>����ʱ�䣺{if $allCategoryCoupon[loop].couponStatus==1}������Ч{else}{$allCategoryCoupon[loop].couponStatus|date_format:"%m-%d"}{/if}</li>
				<li><a href="{url f=aliUrl switch=detail id=$allCategoryCoupon[loop].Coupon_}">��ϸ</a> <a href="{url f=aliUrl switch=tuijian id=$allCategoryCoupon[loop].Coupon_}">�Ƽ�</a></li>
			</ul>
		</div>		
		{/section}
		<!--end right_box-->
		<div class="clr"></div>
		<!--begin page-->
		<div class="page">
			<ul>
				{if $pageid>1}
					{if $smarty.get.cid}
					<li><a href="{url f=aliUrl switch=coupon id=$smarty.get.cid pageid=$pageid-1}">��һҳ</a></li>
					{else}
					<li><a href="{url f=aliUrl switch=coupon pageid=$pageid-1}">��һҳ</a></li>
					{/if}
				{/if}
				<li>��ǰ{$pageid}ҳ����{$pageCount}ҳ</li>
				{if $pageid<$pageCount}
					{if $smarty.get.cid}
					<li><a href="{url f=aliUrl switch=coupon id=$smarty.get.cid pageid=$pageid+1}">��һҳ</a></li>
					{else}
					<li><a href="{url f=aliUrl switch=coupon pageid=$pageid+1}">��һҳ</a></li>
					{/if}
				{/if}
				<li><input type="text" class="input" maxlength="4" id="pagejump" value="{$pageid}"/></li>
				<li><a href="javascript:goPage();">ȷ��</a></li>
			</ul>
		</div>
		<!--end page-->
		{/if}
	</div>
	<!--end head-->
</div>	
</div>
</body>
</html>
