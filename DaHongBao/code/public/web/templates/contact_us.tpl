{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			<!--end adv -->
		<div>
		<table border=0 cellpadding=2 cellspacing=2 width=600>
<tr>
<td><h2>��ϵ����</h2><td>
</tr>

<tr>
<td><img src="http://www.couponmountain.com/add/press_hr.gif" width=600 height=1 border=0 alt=""></td>
</tr>

<tr>
<td><img src="http://www.couponmountain.com/add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>


<tr>
<td><b><font size="5">��վ�û�����</font></b><p><br>
<font face="arial" size="-1" color=black>�κ����ʡ���������顢�������⣬��������ǣ�<a href="mailto:dahongbao@mezimedia.com">dahongbao@mezimedia.com</a></font></td>
</tr>

<tr>
<td><img src="http://www.couponmountain.com/add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>

<tr>
<td><b><font size="5">�̼Һ�����չ</font></b><p><br>
<font face="arial" size="-1" color=black>ϣ��ͨ���ۿۺ��Żݴ��������˿ͣ�ʵ�־޶�������������������ǣ�<a href="mailto:dahongbao@mezimedia.com">dahongbao@mezimedia.com</a></font></td>
</tr>

<tr>
<td><img src="http://www.couponmountain.com/add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>

<tr>
<td><b><font size="5">ý�幫��</font></b><p><br>
<font face="arial" size="-1" color=black>ý�壬��������ߣ��������ҹ�ͨ���� ��������ǣ�<a href="mailto:dahongbao@mezimedia.com">dahongbao@mezimedia.com</a></font></td>
</tr>
</table>
		</div>
			{include file="foot.tpl"}
			<!--end footer -->
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->
<!--
   make_stat({$category_cur},{$merchant_cur},{$coupon_cur},1);
   afp_stat();
//-->
</script>

</div>
<!--end main -->
</body>
</html>
