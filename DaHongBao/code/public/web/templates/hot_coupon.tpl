{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
		<ul><li><h2>&nbsp;&nbsp;�Ż�Ƶ������</h2></li></ul>
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
					<div class="fourpageinfo">
						<IMG  src="/images/mostpopularcoupons.jpg" alt="�����ŵ��ۿ��Ż�ȯ" width="179" height="104"  border=0 class="f">		
						<div class="fourpageinforight txt3E3E42">
							<p><span class="black b">�����ŵ��ۿ��Ż�ȯ</span>
							ÿ�����������û�ͨ�����Ǵ���������ָ������Żݻ��Ϊ��ʹ���Ĺ����������ݣ�����ÿ24Сʱ�������û��Ĳ����������ͳ�ƣ����������ҳ���ڸ���������Ŀǰ����ǰ30λ���Żݻ��</p>
						</div>
						<div class="cl"></div>
					</div>
			<!--end adv -->
		<div id="hotcouponlist">
			<table class="listbox">
				<tr>
				<td width="7%" class="title">����</td>
				<td width="32%" class="title">�̼�����</td>
				<td width="35%" class="title">�Ż����ݽ���</td>
				<td width="13%" class="title">����</td>
				<td width="13%" class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=index loop=$couponList}
				<tr>
				  <td>{$smarty.section.index.rownum}</td>
				  <td>{if $couponList[index].merchantURL}<a href="{$couponList[index].merchantURL}" class="b">{$couponList[index].merchantName}</a>{else}{$couponList[index].merchantName}{/if}</td>
				  <td>{if $couponList[index].couponURL}<a href="{$couponList[index].couponURL}" target="_blank">{$couponList[index].couponTitle}</a>{else}{$couponList[index].couponTitle}{/if}</td>
				  <td>{$couponList[index].City}</td>
				  <td style="text-align:right;">{$couponList[index].status}</td>
			    </tr>
				{/section}
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
