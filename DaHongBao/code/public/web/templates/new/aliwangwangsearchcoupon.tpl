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
	var url = "{url f=aliUrl switch=search q=$searchTextOri type=$nowsearchcategory pageid=PG}";
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
	<div class="margin2"></div>
	<!--begin left-->

	{if $hasresult==1}
	<div id="left_searchcoupon">
		{section name=loop loop=$searchlist start=0 max=3}
		<div class="search_coupon">
			<div class="search_couponimg"><img src="{$searchlist[loop].ImageURL}" alt="img" /></div>
			<div class="search_coupontext">
				<ul>
					<li class="coupon_title"><a href="{url f=aliUrl switch=detail id=$searchlist[loop].Coupon_}" title="{$searchlist[loop].OriDescript}">{$searchlist[loop].Descript}</a></li>
					<li class="coupon_text">����ʱ�䣺{if $searchlist[loop].couponStatus==1}������Ч{else}{$searchlist[loop].couponStatus|date_format:"%Y-%m-%d"}{/if}</li>
					<li class="coupon_text">�������{$searchlist[loop].categoryname}</li>
					<li class="coupon_button"><a href="{url f=aliUrl switch=detail id=$searchlist[loop].Coupon_}"><img src="/images/aliwangwang/button_discount.gif" alt="��ϸ" /></a> <a href="{url f=aliUrl switch=tuijian id=$searchlist[loop].Coupon_}"><img src="/images/aliwangwang/button_commend.gif" alt="�Ƽ�" /></a></li>
				</ul>
			</div>
		</div>
		{/section}
	</div>
	
	<div id="right_searchcoupon">
		{section name=loop loop=$searchlist start=3 max=3}
		<div class="search_coupon">
			<div class="search_couponimg"><img src="{$searchlist[loop].ImageURL}" alt="img" /></div>
			<div class="search_coupontext">
				<ul>
					<li class="coupon_title"><a href="{url f=aliUrl switch=detail id=$searchlist[loop].Coupon_}" title="{$searchlist[loop].OriDescript}">{$searchlist[loop].Descript}</a></li>
					<li class="coupon_text">����ʱ�䣺{$searchlist[loop].ExpireDate|date_format:"%Y-%m-%d"}</li>
					<li class="coupon_text">�������{$searchlist[loop].categoryname}</li>
					<li class="coupon_button"><a href="{url f=aliUrl switch=detail id=$searchlist[loop].Coupon_}"><img src="/images/aliwangwang/button_discount.gif" alt="��ϸ" /></a> <a href="{url f=aliUrl switch=tuijian id=$searchlist[loop].Coupon_}"><img src="/images/aliwangwang/button_commend.gif" alt="�Ƽ�" /></a></li>
				</ul>
			</div>
		</div>
		{/section}
	</div>
	{else}
	<div class="nosearch">û���������!</div>	
	{/if}
	<div class="clr"></div>
	<!--begin page-->
	{if $hasresult==1}
	<div class="page3">
		<ul>
			{if $pageid>1}
			<li><a href="{url f=aliUrl switch=search q=$searchTextOri type=$nowsearchcategory pageid=$pageid-1}">��һҳ</a></li>
			{/if}
			<li>��ǰ{$pageid}ҳ����{$pageCount}ҳ</li>				
			{if $pageid<$pageCount}
			<li><a href='{url f=aliUrl switch=search q=$searchTextOri type=$nowsearchcategory pageid=$pageid+1}'>��һҳ</a></li>
			{/if}
			<li><input type="text" class="input" maxlength="4" id="pagejump" value="{$pageid}"/></li>
			<li><a href="javascript:goPage();">ȷ��</a></li>
		</ul>
	</div>
	{/if}
	<!--end page-->
</div>	
</div>
</body>
</html>
