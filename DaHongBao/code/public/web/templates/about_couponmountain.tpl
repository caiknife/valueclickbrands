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
		<table>
		   <tr>
      <td width="1%"><img src="/images/bgim.gif" width="30" height="30"></td>
      <td><table border=0 cellpadding=2 cellspacing=2 width=600>
<tr>
<td><b><font size="5">���ڴ��������̳�</font></b></td>
</tr>

<tr>
<td><img src="http://www.dahongbao.com/add/press_hr.gif" width=600 height=1 border=0 alt=""></td>
</tr>

<tr>
<td><img src="http://www.dahongbao.com/add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>


<tr>
<td>
<font face="arial" size="-1" color=black>��ӭ����<B>���������̳�</B>�������������˿������������ʱ����������Coupon����ҷǳ�ϲ����������ֱ�ӵ�ʡǮ��ʽ�����ǵ�<a href="http://www.couponmountain.com">CouponMountain</a> �����������Ϊ����Ϊ���е���վ֮һ�������������ҵ���ϲ�����̵��Ʒ�ƣ�ͬʱ�ܹ����ܵ��Żݺ�ʡǮ�Ķ����Ȥ��<br><br><a href="http://www.couponmountain.com">CouponMountain</a> �����й�����ּʼ����һ���ô��ÿ�����Ϲ���ʡǮ���ӱ�ݡ�<br><br>ϣ������ϲ��<B>���������̳�</B>��ͬʱҲ�ܸ������������������ͽ��飬�����������ø��ӳ�ɫ�����������������������<br><br>
</font></td>
</tr>
</table>
</td>
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
