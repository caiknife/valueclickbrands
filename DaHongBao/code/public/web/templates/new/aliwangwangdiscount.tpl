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
	var url = "{url f=aliUrl switch=discount type=$type pageid=PG}";
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
	<div id="left_discount">
		<!--�ۿ���Ϣ��ʼ-->
		<h2><a href="#">�����ۿ�</a> </h2>
		<div class="discount">
			<ul>
				{section name=loop loop=$hotdiscountlist}
				<li class="li_title"><a href="{url f=aliUrl switch=detail id=$hotdiscountlist[loop].Coupon_}" title="{$hotdiscountlist[loop].OriDescript}">{$hotdiscountlist[loop].Descript}</a></li>
				<li class="li_time"><a href="{url f=aliUrl switch=detail id=$hotdiscountlist[loop].Coupon_}">[{$hotdiscountlist[loop].StartDate|date_format:"%m��%d��"}-{$hotdiscountlist[loop].ExpireDate|date_format:"%m��%d��"}]</a></li>
				{/section}
			</ul>
		</div>
		<!--�ۿ���Ϣ����-->
	</div>
	<!--end left-->
	<!--begin right-->
	<div id="right_discount">
		<!--�����ۿۿ�ʼ-->
		<h2>{if $type=='startdate'}<a href="{url f=aliUrl switch=discount}">�����ۿ�</a> | �����Ч{else}�����ۿ� | <a href="{url f=aliUrl switch=discount type=startdate}">�����Ч</a>{/if}</h2>

		<div class="discount">
			{section name=loop loop=$discountlist}
			<div class="discount_box">
				<ul>
					<li class="li_title"><a href="{url f=aliUrl switch=detail id=$discountlist[loop].Coupon_}" title="{$discountlist[loop].OriDescript}">{$discountlist[loop].Descript}</a></li>
					<li class="li_time">�ʱ�䣺{$discountlist[loop].StartDate} �� {$discountlist[loop].ExpireDate}</li>						
				</ul>
				<div class="li_button"><a href="{url f=aliUrl switch=detail id=$discountlist[loop].Coupon_}"><img src="/images/aliwangwang/button_discount.gif" alt="��ϸ" /></a> <a href="{url f=aliUrl switch=tuijian id=$discountlist[loop].Coupon_}"><img src="/images/aliwangwang/button_commend.gif" alt="�Ƽ�" /></a></div>		
			</div>
			{/section}
		</div>
		<!--�����ۿ۽���-->
		<div class="clr"></div>
		<!--begin page-->
		<div class="page1">
			<ul>
				{if $pageid>1}
				<li><a href="{url f=aliUrl switch=discount type=$type pageid=$pageid-1}">��һҳ</a></li>
				{/if}
				<li>��ǰ{$pageid}ҳ����{$page}ҳ</li>		
				{if $pageid<$page}
				<li><a href="{url f=aliUrl switch=discount type=$type pageid=$pageid+1}">��һҳ</a></li>
				{/if}
				<li><input type="text" class="input" maxlength="4" id="pagejump" value="{$pageid}"/></li>
				<li><a href="javascript:goPage();">ȷ��</a></li>
			</ul>
		</div>
		<!--end page-->
	</div>
	<!--end head-->
</div>	
</div>
</body>
</html>
