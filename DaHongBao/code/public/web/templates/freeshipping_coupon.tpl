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
				<IMG  src="images/freeshipping.jpg" alt="����ͻ�"   border=0 class="f">		
				<div class="fourpageinforight txt3E3E42">
					<p><span class="b black">����ͻ�</span>-��ӭ�����������ͻ����������ﹺ�������������ܡ���ѡ�����Ȥ��
���ǣ��������ʾ����ѻͨ������һ�������������ƻ�������Ҫһ���Ż�ȯ��ţ����ҹ����ʱ��ע�⡣</p>
					<p>ף��������죡 </p>
				</div>
				<div class="cl"></div>
  	  	  </div>
		  
			<!--end fourpageinfo -->
		<div>
			<table class="listbox">
				<tr>
				<td width="32%" class="title">�̼�����</td>
				<td width="40%" class="title">�Ż����ݽ���</td>
				<td width="13%" class="title">����</td>
				<td width="13%" class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=index loop=$couponFreeList}
				<tr>
				  <td>{if $couponFreeList[index].merchantURL}<a href="{$couponFreeList[index].merchantURL}" class="b">{$couponFreeList[index].merchantName}</a>{else}{$couponFreeList[index].merchantName}{/if}</td>
				  <td>{if $couponFreeList[index].couponURL}<a href="{$couponFreeList[index].couponURL}" target="_blank">{$couponFreeList[index].couponTitle}</a>{else}{$couponFreeList[index].couponTitle}{/if}</td>
				  <td>{$couponFreeList[index].City}</td>
				  <td style="text-align:right;">{$couponFreeList[index].status}</td>
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
