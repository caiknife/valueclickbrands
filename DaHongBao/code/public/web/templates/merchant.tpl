{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<!--end top10coupon -->		
	<div class="categorymenu">
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
	<!--end categorymenu -->
	<!--end hotmerchant -->
</div>
<!--end left -->

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local"><a class="navigationLink" onclick="top.MyClose=false;"  href="http://www.dahongbao.com/">��ҳ</a>&nbsp;&gt;&nbsp;<a href="/" class="b n2y">�����Ż�ȯ���ۿ�ȯ������ȯ</a></div>	
			<!--end local -->
			<div style="margin:10px auto; padding-bottom:10px;border-bottom:#aca899 1px solid;"><IMG height=13 src="images/cm_merch_arr.gif" width=10 border=0> <A href="http://www.dahongbao.com/firefox.html" title="Firefox�������"><span class="red nud b">Firefox</span></A> <A onclick=top.MyClose=false; href="http://www.dahongbao.com/firefox.html">Firefox������������!</A> </FONT></div>
			<!--end adv -->
			<div class="merchantinfo">
				  <a href="/"><IMG  src="images/Logo.gif" alt="�����Ż�ȯ���ۿ۹���ȯ"  border=0 class="f"></a>		
				  <div class="merchantinforight grey"><p>����������������1999��11��,��ȫ�����������������,������Ŷ�ӵ�ж����ͼ����桢���ۡ���Ϣ�������г�Ӫ�����顣����ȫ�������Ķ����ṩ20����������ͼ�鼰����1���ֵ�������Ʒ,ÿ��Ϊ��ǧ����������������ṩ���㡢��ݵķ���,���������̳��ṩ���ߵ����Ż�ȯ����������ȯ,�����Ϲ����ߴ�������ķ����ʵ�ݡ�������վÿ�춨ʱ����,ȷ�����൱�������Ż�ȯȷʵ��Ч,��֤�ͻ����еõ������ۿۡ�</p>
				  <p> ����ʱʹ�õ��������Ż�ȯ���ײ����������½��ֻ�����Ŀ¼����ͨ����ĸ�б�ֱ�ӷ��ʵ����������������Ż�ȯ������ȯ���߹�����������̳�ͬʱ��ͨ�������Ż�ȯ�б����������������¹����Ľ����Ż���Ϣ������������ÿ�����������ŵĲ�Ʒ����ע���µĵ����Żݹ���ȯ����ע���������̳ǡ���������̼����߲˵��������������벻���ľ��档 </p>
				  <p class="b">ֱ�ӷ����̼���ҳ: <a href="/" ><span class="blue">����</span></a> </p>
				  </div>
			</div>
			<!--end merchantinfo -->
			<div id="merchantcoupon">
				<div>����鿴�����Ż�ȯ, �����ۿ�, ������Ϣ</div>
				<div class="middletitle"><h2>���� ���Ż�ȯ</h2></div>
				
				
				<div class="couponlist">
					<div class="f couponimg"><A href="http://www.dahongbao.com/dhc/index.html"><IMG src="images/8401.jpg" alt="DHC�Ż�ȯ���ۿ۹���ȯ" ></A></div>
					<div class="right seeit"><a href="/"><img src="images/blue_but.gif" alt="�鿴���Ż�" /></a></div>
					<dl>
						<dt><a href="/" class="blue">���˽�"���½���"���ߴ����!����200Ԫ����!</a> </dt>
						<dd>��ڼ�,��  ������250Ԫ���û���������½���һֻ, ��������400Ԫ���û����ɻ�����½���һ�Կɻ�ڼ�,��  ������250Ԫ���û���������½���һֻ, ��������400Ԫ���û����ɻ�����½���һ�Կɻ�ڼ�,��  ������250Ԫ���û���������½���һֻ, ��������400Ԫ���û����ɻ�����½���һ�Կ�</dd>                                                                  
						<dd>����ʱ�䣺2007-2-2  ����ʱ��:2007-5-2</dd>
						<dd class="couponlistbottom"><a href="/" class="addtofav">�����ղ�</a><a href="/" class="comtofriend">�Ƽ�����</a><a href="/"  class="comments">��������</a></dd>
					</dl>
				</div>
				
				
			</div>
			<!--end commerchant -->
			{$adsence_code}
			<!--end googldad -->

			{include file="foot.tpl"}
			<!--end footer -->
		
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