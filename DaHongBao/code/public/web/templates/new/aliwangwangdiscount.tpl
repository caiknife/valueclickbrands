<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>淘宝旺旺</title>
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
		<!--折扣信息开始-->
		<h2><a href="#">热门折扣</a> </h2>
		<div class="discount">
			<ul>
				{section name=loop loop=$hotdiscountlist}
				<li class="li_title"><a href="{url f=aliUrl switch=detail id=$hotdiscountlist[loop].Coupon_}" title="{$hotdiscountlist[loop].OriDescript}">{$hotdiscountlist[loop].Descript}</a></li>
				<li class="li_time"><a href="{url f=aliUrl switch=detail id=$hotdiscountlist[loop].Coupon_}">[{$hotdiscountlist[loop].StartDate|date_format:"%m月%d日"}-{$hotdiscountlist[loop].ExpireDate|date_format:"%m月%d日"}]</a></li>
				{/section}
			</ul>
		</div>
		<!--折扣信息结束-->
	</div>
	<!--end left-->
	<!--begin right-->
	<div id="right_discount">
		<!--最新折扣开始-->
		<h2>{if $type=='startdate'}<a href="{url f=aliUrl switch=discount}">最新折扣</a> | 最近生效{else}最新折扣 | <a href="{url f=aliUrl switch=discount type=startdate}">最近生效</a>{/if}</h2>

		<div class="discount">
			{section name=loop loop=$discountlist}
			<div class="discount_box">
				<ul>
					<li class="li_title"><a href="{url f=aliUrl switch=detail id=$discountlist[loop].Coupon_}" title="{$discountlist[loop].OriDescript}">{$discountlist[loop].Descript}</a></li>
					<li class="li_time">活动时间：{$discountlist[loop].StartDate} 至 {$discountlist[loop].ExpireDate}</li>						
				</ul>
				<div class="li_button"><a href="{url f=aliUrl switch=detail id=$discountlist[loop].Coupon_}"><img src="/images/aliwangwang/button_discount.gif" alt="详细" /></a> <a href="{url f=aliUrl switch=tuijian id=$discountlist[loop].Coupon_}"><img src="/images/aliwangwang/button_commend.gif" alt="推荐" /></a></div>		
			</div>
			{/section}
		</div>
		<!--最新折扣结束-->
		<div class="clr"></div>
		<!--begin page-->
		<div class="page1">
			<ul>
				{if $pageid>1}
				<li><a href="{url f=aliUrl switch=discount type=$type pageid=$pageid-1}">上一页</a></li>
				{/if}
				<li>当前{$pageid}页，共{$page}页</li>		
				{if $pageid<$page}
				<li><a href="{url f=aliUrl switch=discount type=$type pageid=$pageid+1}">下一页</a></li>
				{/if}
				<li><input type="text" class="input" maxlength="4" id="pagejump" value="{$pageid}"/></li>
				<li><a href="javascript:goPage();">确定</a></li>
			</ul>
		</div>
		<!--end page-->
	</div>
	<!--end head-->
</div>	
</div>
</body>
</html>
