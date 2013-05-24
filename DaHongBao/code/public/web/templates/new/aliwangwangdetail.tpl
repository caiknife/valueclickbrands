<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>淘宝旺旺</title>
<link href="/css/aliwangwang.css" rel="stylesheet" type="text/css" />
<link href="/css/print.css" rel="stylesheet" type="text/css" media="print"/>
</head>
{literal}
<script language="javascript">
var pwidth = "";
var pheight = "";
function printme(){
	var image = $("#imagewillprint");
	image.width(pwidth);
	image.height(pheight);
    window.print();
}
</script>
{/literal}
<body scroll=no>
<div id="main">
<div id="main_box">
	<!--begin head-->
	{include file="new/head_aliwangwang.htm"}
	<!--end head-->	
	<!--begin detail-->
	<div id="detail">
		<div class="title">
			<h2>{$coupondetail.Descript}</h2>
			{if $coupondetail.CouponType==9}
				{$coupondetail.StartDate|date_format:"%Y-%m-%d"} 星期{$coupondetail.StartWeek} 至 {$coupondetail.ExpireDate|date_format:"%Y-%m-%d"} 星期{$coupondetail.EndWeek}
			{else}
				过期时间：{if $coupondetail.couponStatus==1}永久有效{else}{$coupondetail.ExpireDate|date_format:"%Y-%m-%d"}{/if}
			{/if}
		</div>		
		{if $coupondetail.CouponType==9}
			{if $coupondetail.Detail}
			<div class="detail_text">
				<p>{$coupondetail.Detail}</p>
				{if $coupondetail.ImageDownload==1}
				<img src="{$coupondetail.ImageURL}" id="imagewillprint" onLoad="smallimage()"/>
				{/if}
			</div>
				
			{else}
				<div class="detail_text">
					<div class="nosearch">该折扣信息无详细内容!</div>	
				</div>
			{/if}
		{else}
			<div class="detail_img" id="div1"><img src="{$coupondetail.ImageURL}" id="imagewillprint" onLoad="smallimage()"/>
			<p>{$coupondetail.Detail}</p>
			</div>
		{/if}
	</div>	
	<div id="sidebar">
		<ul>
			<li id="smallimage" style="display:none"><img src="/images/aliwangwang/button_smallphoto.gif" alt="缩小图片" onclick="javascript:smallimage()" style="cursor: pointer;"/></li>
			<li id="bigimage" style="display:none"><img src="/images/aliwangwang/button_photo.gif" alt="放大图片" onclick="javascript:bigimage()" style="cursor: pointer;"/></li>
			{if $coupondetail.CouponType==9}
			{else}
			<li><img src="/images/aliwangwang/button_print.gif" alt="打印预览" onclick="javascript:printme()" style="cursor: pointer;"/></li>
			{/if}
			<li><img src="/images/aliwangwang/button_return.gif" alt="返回上页" onclick="javascript:history.back();" style="cursor: pointer;"/></li>
		</ul>
	</div>
	<!--end sidebar-->
</div>	
</div>
{literal}
<script>
function bigimage(){
	var image = $("#imagewillprint");
	image.width(pwidth);
	image.height(pheight);
	$("#bigimage").attr("style","display:none"); 
	$("#smallimage").attr("style","display:inline"); 
}
function smallimage(){
	var image = $("#imagewillprint");
	$("#smallimage").attr("style","display:none"); 
	if(image.width()>width){
		image.height(width/image.width()*image.height());
		image.width(width);
		$("#bigimage").attr("style","display:inline"); 
	}
	if(image.height()>height){
		image.width(height/image.height()*image.width());
		image.height(height);
		$("#bigimage").attr("style","display:inline");
	}
}
</script>
{/literal}
</body>
</html>