<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>淘宝旺旺</title>
<link href="/css/aliwangwang.css" rel="stylesheet" type="text/css" />
</head>

<body scroll=no>
<div id="main">
<div id="main_box">
	<!--begin head-->
	{include file="new/head_aliwangwang.htm"}
	<!--end head-->	
	<!--begin detail-->
	<div id="detail">
		<div class="title_send">
			<h2>{$coupondetail.Descript}</h2>			
			{if $coupondetail.CouponType==9}折扣信息{else}优惠券{/if}编号：{$coupondetail.Coupon_}  过期时间：<strong>{if $coupondetail.couponStatus==1}永久有效{else}{$coupondetail.ExpireDate}{/if}</strong>  适用范围：<strong>{$coupondetail.City}</strong>
		</div>				
		<!--文字开始-->
		<div class="detail_send">
			<h3>把这个{if $coupondetail.CouponType==9}折扣信息{else}优惠券{/if}Email给好友</h3>	
			<ul>
				<FORM METHOD=POST ACTION="" id="inviteform">
				<li class="li_left">好友1 邮箱：</li>
				<li class="li_right"><input id="username1" name="username1" type="text" class="input required" /> <strong>*</strong></li>
				<li class="li_left">好友2 邮箱：</li>
				<li class="li_right"><input name="username2" type="text" class="input" /></li>
				<li class="li_left1">对他们说：</li>
				<li class="li_right1"><textarea name="telldetail" id="detail2" cols="" rows="" class="textarea" readonly>阿里旺旺有免费优惠券下载软件啦！我已下载了“{$coupondetail.Descript}”，你也来试试看吧！</textarea>
				</li>
				<li class="li_left">您的名字：</li>
				<li class="li_right"><input id="yourname" name="sendauthor" type="text" class="input" /> <strong>*</strong></li>
				<li class="li_left">您的Mail：</li>
				<li class="li_right"><input id="yourmail" name="" type="text" class="input" /></li>
			</ul>
		</div>
		<!--文字结束-->
	</div>	
	<div id="sidebar">
		<ul>
			<li><input type="image" src="/images/aliwangwang/button_send.gif" alt="立即发送"/></li>
			<li><img src="/images/aliwangwang/button_return.gif" alt="返回上页" onclick="javascript:history.back();" style="cursor: pointer;"/></li></FORM>
		</ul>
	</div>
	<!--end sidebar-->
</div>	
</div>
{literal}
<script language="JavaScript" src="/jscript/jquery.validate.pack.js"></script>
<script>
$("#inviteform").submit(function(){
	if(jQuery.trim($("#username1").attr('value'))==""){
		alert("请输入好友1 邮箱");
		$("#username1").focus();
		return false;
	}
	if(jQuery.trim($("#yourname").attr('value'))==""){
		alert("请填写您的名字");
		$("#yourname").focus();
		return false;
	}
	return true;
});
</script>
{/literal}
</body>
</html>