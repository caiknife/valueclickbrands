<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>��վ��ͼ��ҳ - ����</title>
<meta name="copyright" content="www.dahongbao.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="verify-v1" content="QVIPb4M3AU1SQ7HQONfC2lrlH7RkeUn+PL1dLv8l5fs=" />
<link href="/css/st.css" rel="stylesheet" type="text/css" media="all">
<script language="JavaScript" src="/jscript/js.js"></script>
</head>
<body>
<link href="/css/stprint.css" rel="stylesheet" type="text/css" media="print">
{include file="head_coupon.tpl"}
</div><div id="main">
<div id="left" class="fillBg">
	<div class="categorymenu1" id="newcoupon" style="border-bottom: 1px solid #000;">
		<h3>&nbsp;&nbsp;���µ����Ż�ȯ</h3>
		<ul>
		{section name=index loop=$newCouponlist}
		<li><a href="{$newCouponlist[index].couponURL}" class="blue">{$newCouponlist[index].couponTitle}</a></li>
		{/section}
 		</ul>
		<br/>
		<span class="block textr"><a href="/new_coupon.html" class="reddark">���������Ż�</a></span>
	</div>
</div>

<div id="middle">
	<!--begin middle_map-->
	<div class="middle_map">
		<a href="/">��ҳ</a> > ��վ��ͼ
		<ul>
			{section name=loop loop=$categoryarray}
			<li class="liline"><a href="/sitemap/{$categoryarray[loop].NameURL}.html">{$categoryarray[loop].Name}</a></li>
			{/section}
		</ul>
		<!--begin footer-->
	<div class="footer"  id="footerprint">
		<div class="copyright">
			<br/>
			ʹ�ñ�վ�������ش��� <A href="/Privacy_Policy.html">��˽����</A> ��<A href="/Terms_&amp;_Conditions.html">��������</A>					
		</div>
		<div class="contactus" >
			<div class="right" style="margin-top:10px;"><img src="/images/shca_cc.gif" class="f" /><span class="block f" style="line-height:30px; padding-left:5px;">��ICP��12034406��</span>
			</div>
		<p><A href="/About_CouponMountain.html">���ڴ���</A>  | <A href="/Contact_Us.html">��ϵ����</A>  | <A href="/sitemap/index.html">��վ��ͼ</A>  | �����Ƽ���ȥ�������ҿ����� <a href="http://www.couponmountain.com">����</a> <a href="http://www.couponmountain.co.uk">Ӣ��</a> <a href="http://www.waribikiya.com">�ձ�</a> <a href="http://www.savingsmountain.com">����</a> <a href="http://www.couponmountain.de">�¹�</a> <a href="http://www.bonpromo.com">����</a></p>

		<p>��Ȩ�� &copy; 2007 <a href="http://www.mezimedia.com/" target="_blank">Mezi Media</a> ����</p>
		</div>			
	</div>
	<!--end footer -->
	</div>
	<!--end middle_map-->
	
</div>
<!--end middle -->
<!--
   make_stat(-1,-1,-1,1);
   afp_stat();
//-->
</script>

</div>
<!--end main -->
</body>
</html>
