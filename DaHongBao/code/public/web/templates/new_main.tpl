{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	<!--Start Resource-->
	{RESOURCE_INCLUDE}
	<!--End Resource-->
	<!--end top10coupon -->		
	<div class="categorymenu">
		<ul>	
		  <li><a href="category.html" class="cmenu">�鼮</a></li>
		  <li><a href="#" class="cmenu">����</a></li>
		  <li><a href="#" class="cmenu">����</a></li>
		  <li><a href="#" class="cmenu">����</a></li>
		  <li><a href="#" class="cmenu">�ҵ�</a></li>
		  <li><a href="#" class="cmenu">Ӱ��</a></li>
		  <li><a href="#" class="cmenu">����</a></li>
		  <li><a href="#" class="cmenu">����</a></li>
		  <li><a href="#" class="cmenu">��Ϸ</a></li>
		  <li><a href="#" class="cmenu">���</a></li>
		  <li><a href="#" class="cmenu">����</a></li>
		  <li><a href="#" class="cmenu">��Ʒ</a></li>
		  <li><a href="#" class="cmenu">����</a></li>
		  <li><a href="#" class="cmenu">���</a></li>
		  <li><a href="#" class="cmenu">ͨѶ</a></li>
		  <li><a href="#" class="cmenu">����</a></li>
		  <li><a href="#" class="cmenu">��ʳ</a></li>
		</ul>
	</div>
	<!--end categorymenu -->
	<div class="account">
		<h2>���ġ��Ԥ�桱�ʼ�</h2>
		�������佫��ʱ�յ��Ԥ�棬���и��ָ������Ż�ȯ��
		<input name="searchText" type="text"  class="searchbox" value="�����ʼ���ַ" maxlength="50" style="margin-left:0;"><input type="button" value="����" class="searchbotton" style="margin:0; height:22px;"/>
	</div>	
	<!--end account -->	
</div>
<!--end left -->

<div id="right" class="fillBg">
	<div style="margin-top:35px;"><img src="images/ad-right.gif" style="height:80px; width:165px;"/></div>
	<!--end events -->
	<!--Start-->
	{NEWCOUPON_INCLUDE}
	<!--End-->
	<!--Start Hotcoupon-->
	<!--Start-->
	{HOTCOUPON_INCLUDE}
	<!--End-->
	<!--end hotmerchant -->
	<div class="advright"><img src="images/140-600.jpg" /></div>
	<!--end adv -->
</div>
<!--end right -->

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			{NAVIGATION_PATH}
			<!--end local -->
			{MAIN_CONTENT}
			<!--end comcoupon -->						
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->
{include file="foot.tpl"}