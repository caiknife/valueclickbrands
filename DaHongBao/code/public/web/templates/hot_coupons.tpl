{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<!--end top10coupon -->		
	<div class="categorymenu">
		<ul><li><h2>&nbsp;&nbsp;�Ż�Ƶ������</h2></li></ul>
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
	<!--end categorymenu -->
	{$newCoupon}
	{$hotCoupon}
	<!--end hotmerchant -->
</div>
<!--end left -->



<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local"><a class="navigationLink" onclick="top.MyClose=false;"  href="http://www.dahongbao.com/">��ҳ</a>&nbsp;&gt;&nbsp;�����ŵ��ۿ��Ż�ȯ</div>	
			<!--end local -->
			<div class="fourpageinfo">
				<IMG  src="images/mostpopularcoupons.jpg" alt="�����ŵ��ۿ��Ż�ȯ" width="179" height="104"  border=0 class="f">		
				<div class="fourpageinforight grey">
					<p><span class="black b">�����ŵ��ۿ��Ż�ȯ</span>
					ÿ�����������û�ͨ�����Ǵ���������ָ������Żݻ��Ϊ��ʹ���Ĺ����������ݣ�����ÿ24Сʱ�������û��Ĳ����������ͳ�ƣ����������ҳ���ڸ���������Ŀǰ����ǰ30λ���Żݻ��</p>
				</div>
				<div class="cl"></div>
	  	  	</div>
			<!--end fourpageinfo -->
		<div id="hotcouponlist">
			
			<table class="listbox">
				<tr>
				<td class="title">����</td>
				<td class="title">&nbsp;</td>
				<td class="title">����</td>
				<td class="title">�̼�</td>
				<td class="title">�������</td>
				<td class="title">�Ż�����</td>
				<td class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=outer loop=$hotcoupons}
				<tr>
				  <td>1</td>
				  <td>--</td>
				  <td>1</td>
				  <td><a href="/" class="b">����CJ</a></td>
				  <td>86��</td>
				  <td><a href="/frame.php?p=17348" target="_blank">���ӹ���˿�ע�ἴ��20Ԫ������֣�</a></td>
				  <td style="text-align:right;">������</td>
			    </tr>
				{/section}
			</table>

				
		</div>
			<!--end hotcouponlist -->

		{include file="foot.tpl"}
			
		
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->


</div>
<!--end main -->
</body>
</html>