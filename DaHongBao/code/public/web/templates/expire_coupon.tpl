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
				<IMG  src="images/expiringcoupons.jpg" alt="���µ��Żݹ���ȯ" width="179" height="104"  border=0 class="f">		
				<div class="fourpageinforight txt3E3E42">
					<p><span class="b black">����ڵ��Ż�ȯ���ۿ�ȯ</span>-�����������ܻ�ӭ���Żݻ��ֻ����һ����ʱ��ξ��С� ����������ҳ���������㲻Ҫ������ᡣ���������Ǹ��������˽����콫Ҫ�������Żݻ�������������Ҫ�ģ�Ҫ������Ŷ�� </p>
				</div>
				<div class="cl"></div>
  	  	  </div>
		  
			<!--end fourpageinfo -->
		<div>
			<span class="red txt16 b">���� ���ڵ��Ż�ȯ���ۿ�ȯ</span>
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
			<span class="red txt16 b">���� ���ڵ��Ż�ȯ���ۿ�ȯ</span>
			<table class="listbox">
				<tr>
				<td width="32%" class="title">�̼�����</td>
				<td width="40%" class="title">�Ż����ݽ���</td>
				<td width="13%" class="title">����</td>
				<td width="13%" class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=index loop=$couponTomorrowList}
				<tr>
				  <td>{if $couponTomorrowList[index].merchantURL}<a href="{$couponTomorrowList[index].merchantURL}" class="b">{$couponTomorrowList[index].merchantName}</a>{else}{$couponTomorrowList[index].merchantName}{/if}</td>
				  <td>{if $couponTomorrowList[index].couponURL}<a href="{$couponTomorrowList[index].couponURL}" target="_blank">{$couponTomorrowList[index].couponTitle}</a>{else}{$couponTomorrowList[index].couponTitle}{/if}</td>
				  <td>{$couponTomorrowList[index].City}</td>
				  <td style="text-align:right;">{$couponTomorrowList[index].status}</td>
			    </tr>
				{/section}
			</table>
			<span class="red txt16 b">���� ���ڵ��Ż�ȯ���ۿ�ȯ</span>
			<table class="listbox">
				<tr>
				<td width="32%" class="title">�̼�����</td>
				<td width="40%" class="title">�Ż����ݽ���</td>
				<td width="13%" class="title">����</td>
				<td width="13%" class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=index loop=$couponAfterTomorrowList}
				<tr>
				  <td>{if $couponAfterTomorrowList[index].merchantURL}<a href="{$couponAfterTomorrowList[index].merchantURL}" class="b">{$couponAfterTomorrowList[index].merchantName}</a>{else}{$couponAfterTomorrowList[index].merchantName}{/if}</td>
				  <td>{if $couponAfterTomorrowList[index].couponURL}<a href="{$couponAfterTomorrowList[index].couponURL}" target="_blank">{$couponAfterTomorrowList[index].couponTitle}</a>{else}{$couponAfterTomorrowList[index].couponTitle}{/if}</td>
				  <td>{$couponAfterTomorrowList[index].City}</td>
				  <td style="text-align:right;">{$couponAfterTomorrowList[index].status}</td>
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
