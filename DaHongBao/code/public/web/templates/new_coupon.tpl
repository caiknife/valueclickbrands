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
				<IMG  src="images/newcoupons.jpg" alt="���µ��Żݹ���ȯ" width="179" height="104"  border=0 class="f">		
				<div class="fourpageinforight txt3E3E42">
					<p><span class="black b">���µ��Żݹ���ȯ</span>
					���Ǵ�������ÿ�������ղ���ʱ�������ǵ��̼һ�������ϵ�����������µ��Żݻ��Ϣ�����ԣ��ǵ�ʱ������������˵���������µľ�ϲŶ�� </p>
				</div>
				<div class="cl"></div>
  	  	  </div>
			<!--end fourpageinfo -->
		<div>
			<span class="red txt16 b">{$startDay1} ��ʼ���Żݹ���ȯ</span>
			<table class="listbox">
				<tr>
				<td width="32%" class="title">�̼�����</td>
				<td width="40%" class="title">�Ż����ݽ���</td>
				<td width="13%" class="title">����</td>
				<td width="13%" class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=index loop=$couponTodayList}
				<tr>
				  <td>{if $couponTodayList[index].merchantURL}<a href="{$couponTodayList[index].merchantURL}" class="b">{$couponTodayList[index].merchantName}</a>{else}{$couponTodayList[index].merchantName}{/if}</td>
				  <td>{if $couponTodayList[index].couponURL}<a href="{$couponTodayList[index].couponURL}" target="_blank">{$couponTodayList[index].couponTitle}</a>{else}{$couponTodayList[index].couponTitle}{/if}</td>
				  <td>{$couponTodayList[index].City}</td>
				  <td style="text-align:right;">{$couponTodayList[index].status}</td>
			    </tr>
				{/section}
			</table>
			
			<span class="red txt16 b">{$startDay2} ��ʼ���Żݹ���ȯ</span>
			<table class="listbox">
				<tr>
				<td width="32%" class="title">�̼�����</td>
				<td width="40%" class="title">�Ż����ݽ���</td>
				<td width="13%" class="title">����</td>
				<td width="13%" class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=index loop=$couponBeforeList1}
				<tr>
				  <td>{if $couponBeforeList1[index].merchantURL}<a href="{$couponBeforeList1[index].merchantURL}" class="b">{$couponBeforeList1[index].merchantName}</a>{else}{$couponBeforeList1[index].merchantURL}{/if}</td>
				  <td>{if $couponBeforeList1[index].couponURL}<a href="{$couponBeforeList1[index].couponURL}" target="_blank">{$couponBeforeList1[index].couponTitle}</a>{else}{$couponBeforeList1[index].couponTitle}{/if}</td>
				  <td>{$couponBeforeList1[index].City}</td>
				  <td style="text-align:right;">{$couponBeforeList1[index].status}</td>
			    </tr>
				{/section}
			</table>
			
			<span class="red txt16 b">{$startDay3} ��ʼ���Żݹ���ȯ</span>
			<table class="listbox">
				<tr>
				<td width="32%" class="title">�̼�����</td>
				<td width="40%" class="title">�Ż����ݽ���</td>
				<td width="13%" class="title">����</td>
				<td width="13%" class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=index loop=$couponBeforeList2}
				<tr>
				  <td>{if $couponBeforeList2[index].merchantURL}<a href="{$couponBeforeList2[index].merchantURL}" class="b">{$couponBeforeList2[index].merchantName}</a>{else}{$couponBeforeList2[index].merchantURL}{/if}</td>
				  <td>{if $couponBeforeList2[index].couponURL}<a href="{$couponBeforeList2[index].couponURL}" target="_blank">{$couponBeforeList2[index].couponTitle}</a>{else}{$couponBeforeList2[index].couponTitle}{/if}</td>
				  <td>{$couponBeforeList2[index].City}</td>
				  <td style="text-align:right;">{$couponBeforeList2[index].status}</td>
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
