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
	var url = "{url f=aliUrl switch=dingzhi pageid=PG}";
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
	<div id="left">
		<!--折扣信息开始-->
		<h2><a href="#">请选择您所感兴趣的类别</a></h2>
		<div class="custom">
			<ul><FORM METHOD=POST ACTION="">
				{section name=loop loop=$categorylist}
				<li class="li_input"><input id="c{$categorylist[loop].Category}" name="c{$categorylist[loop].Category}" type="checkbox" value="{$categorylist[loop].Category}" /></li>
				<li class="li_sort">{$categorylist[loop].Name}</li>
				{if $categorylist[loop].Descript}<li class="li_line">{$categorylist[loop].Descript}</li>{/if}
				{/section}
				<li class="li_button"><input type="image" src="/images/aliwangwang/button_save.gif" alt="保存定制" />&nbsp;&nbsp;<a href="{url f=aliUrl switch=dingzhi type=del}"><img src="/images/aliwangwang/button_cancel.gif" alt="取消定制" /></a></li>
				</FORM>
			</ul>
		</div>
		<!--折扣信息结束-->
	</div>
	<!--end left-->
	<!--begin right-->
	<div id="right">
		<!--begin right_box-->
		{section name=loop loop=$allMerchantInfo}
		<div class="right_box">			
			<div class="right_img"><a href="{url f=aliUrl switch=detail id=$allMerchantInfo[loop].Coupon_}" title="{$allMerchantInfo[loop].couponTitle}"><img src="{$allMerchantInfo[loop].ImageURL}" alt="" /></a></div>			
			<ul>
				<li class="lititle"><a href="{url f=aliUrl switch=detail id=$allMerchantInfo[loop].Coupon_}" title="{$allMerchantInfo[loop].couponTitle}">{$allMerchantInfo[loop].couponTitle}</a></li>
				<li>过期时间：{if $allMerchantInfo[loop].couponStatus==1}永久有效{else}{$allMerchantInfo[loop].couponStatus|date_format:"%m-%d"}{/if}</li>
				<li><a href="{url f=aliUrl switch=detail id=$allMerchantInfo[loop].Coupon_}">详细</a> <a href="{url f=aliUrl switch=tuijian id=$allMerchantInfo[loop].Coupon_}">推荐</a></li>
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
					<li><a href="{url f=aliUrl switch=dingzhi pageid=$pageid-1}">上一页</a></li>
					{else}
					<li><a href="{url f=aliUrl switch=dingzhi pageid=$pageid-1}">上一页</a></li>
					{/if}
				{/if}
				<li>当前{$pageid}页，共{$pageCount}页</li>
				{if $pageid<$pageCount}
					{if $smarty.get.cid}
					<li><a href="{url f=aliUrl switch=dingzhi pageid=$pageid+1}">下一页</a></li>
					{else}
					<li><a href="{url f=aliUrl switch=dingzhi pageid=$pageid+1}">下一页</a></li>
					{/if}
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
{literal}
<script>
{/literal}
var usercategory = "{$usercategory}";

{literal}
if(usercategory){
	var carray = usercategory.split(",");
	var i = 0;
	for(i=0;i<carray.length;i++){
		var c = carray[i];
		$("#c"+c).attr("checked","true");
	}
}

</script>
{/literal}
</body>
</html>