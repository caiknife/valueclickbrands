<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�Ա�����</title>
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
			{if $coupondetail.CouponType==9}�ۿ���Ϣ{else}�Ż�ȯ{/if}��ţ�{$coupondetail.Coupon_}  ����ʱ�䣺<strong>{if $coupondetail.couponStatus==1}������Ч{else}{$coupondetail.ExpireDate}{/if}</strong>  ���÷�Χ��<strong>{$coupondetail.City}</strong>
		</div>				
		<!--���ֿ�ʼ-->
		<div class="detail_send">
			<h3>�����{if $coupondetail.CouponType==9}�ۿ���Ϣ{else}�Ż�ȯ{/if}Email������</h3>	
			<ul>
				<FORM METHOD=POST ACTION="" id="inviteform">
				<li class="li_left">����1 ���䣺</li>
				<li class="li_right"><input id="username1" name="username1" type="text" class="input required" /> <strong>*</strong></li>
				<li class="li_left">����2 ���䣺</li>
				<li class="li_right"><input name="username2" type="text" class="input" /></li>
				<li class="li_left1">������˵��</li>
				<li class="li_right1"><textarea name="telldetail" id="detail2" cols="" rows="" class="textarea" readonly>��������������Ż�ȯ��������������������ˡ�{$coupondetail.Descript}������Ҳ�����Կ��ɣ�</textarea>
				</li>
				<li class="li_left">�������֣�</li>
				<li class="li_right"><input id="yourname" name="sendauthor" type="text" class="input" /> <strong>*</strong></li>
				<li class="li_left">����Mail��</li>
				<li class="li_right"><input id="yourmail" name="" type="text" class="input" /></li>
			</ul>
		</div>
		<!--���ֽ���-->
	</div>	
	<div id="sidebar">
		<ul>
			<li><input type="image" src="/images/aliwangwang/button_send.gif" alt="��������"/></li>
			<li><img src="/images/aliwangwang/button_return.gif" alt="������ҳ" onclick="javascript:history.back();" style="cursor: pointer;"/></li></FORM>
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
		alert("���������1 ����");
		$("#username1").focus();
		return false;
	}
	if(jQuery.trim($("#yourname").attr('value'))==""){
		alert("����д��������");
		$("#yourname").focus();
		return false;
	}
	return true;
});
</script>
{/literal}
</body>
</html>