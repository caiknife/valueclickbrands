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
	<div class="margin"></div>
	<!--begin left-->
	{if $hasresult==1}
	<div id="left_searchdiscount">
		<!--�ۿ���Ϣ��ʼ-->		
		<div class="discount">
			{section name=loop loop=$searchlist start=0 max=6}
			<div class="discount_box">
				<ul>
					<li class="li_title"><a href="{url f=aliUrl switch=detail id=$searchlist[loop].Coupon_}">{$searchlist[loop].Descript}</a></li>
					<li class="li_time">{$searchlist[loop].StartDate} �� {$searchlist[loop].ExpireDate}</li>						
				</ul>
				<div class="li_button"><a href="{url f=aliUrl switch=detail id=$searchlist[loop].Coupon_}"><img src="/images/aliwangwang/button_discount.gif" alt="��ϸ" /></a> <a href="{url f=aliUrl switch=tuijian id=$searchlist[loop].Coupon_}"><img src="/images/aliwangwang/button_commend.gif" alt="�Ƽ�" /></a></div>		
			</div>
			{/section}
		</div>
		<!--�ۿ���Ϣ����-->
	</div>
	<!--end left-->
	<!--begin right-->
	<div id="right_searchdiscount">
		<!--�����ۿۿ�ʼ-->		
		<div class="discount">			
			{section name=loop loop=$searchlist start=6 max=6}
			<div class="discount_box">
				<ul>
					<li class="li_title"><a href="{url f=aliUrl switch=detail id=$searchlist[loop].Coupon_}">{$searchlist[loop].Descript}</a></li>
					<li class="li_time">{$searchlist[loop].StartDate} �� {$searchlist[loop].ExpireDate}</li>						
				</ul>
				<div class="li_button"><a href="{url f=aliUrl switch=detail id=$searchlist[loop].Coupon_}"><img src="/images/aliwangwang/button_discount.gif" alt="��ϸ" /></a> <a href="{url f=aliUrl switch=tuijian id=$searchlist[loop].Coupon_}"><img src="/images/aliwangwang/button_commend.gif" alt="�Ƽ�" /></a></div>		
			</div>
			{/section}
		</div>
		<!--�����ۿ۽���-->		
	</div>
	{else}
	<div class="nosearch">û���������!</div>	
	{/if}
	<div class="clr"></div>
	{if $hasresult==1}
	<!--begin page-->
	<div class="page2">
		<ul>
			{if $pageid>1}
			<li><a href="{url f=aliUrl switch=search q=$searchTextOri type=$nowsearchcategory pageid=$pageid-1}">��һҳ</a></li>
			{/if}
			<li>��ǰ{$pageid}ҳ����{$pageCount}ҳ</li>		
			{if $pageid<$pageCount}
			<li><a href="{url f=aliUrl switch=search q=$searchTextOri type=$nowsearchcategory pageid=$pageid+1}">��һҳ</a></li>
			{/if}
			<li><input type="text" class="input" maxlength="4" id="pagejump" value="{$pageid}"/></li>
			<li><a href="javascript:goPage();">ȷ��</a></li>
		</ul>
	</div>
	<!--end page-->
	{/if}
</div>	
</div>
</body>
</html>
