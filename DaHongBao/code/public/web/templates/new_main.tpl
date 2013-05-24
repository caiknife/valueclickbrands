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
		  <li><a href="category.html" class="cmenu">书籍</a></li>
		  <li><a href="#" class="cmenu">休闲</a></li>
		  <li><a href="#" class="cmenu">保健</a></li>
		  <li><a href="#" class="cmenu">动漫</a></li>
		  <li><a href="#" class="cmenu">家电</a></li>
		  <li><a href="#" class="cmenu">影视</a></li>
		  <li><a href="#" class="cmenu">数码</a></li>
		  <li><a href="#" class="cmenu">旅游</a></li>
		  <li><a href="#" class="cmenu">游戏</a></li>
		  <li><a href="#" class="cmenu">玩具</a></li>
		  <li><a href="#" class="cmenu">生活</a></li>
		  <li><a href="#" class="cmenu">礼品</a></li>
		  <li><a href="#" class="cmenu">美容</a></li>
		  <li><a href="#" class="cmenu">软件</a></li>
		  <li><a href="#" class="cmenu">通讯</a></li>
		  <li><a href="#" class="cmenu">音乐</a></li>
		  <li><a href="#" class="cmenu">饮食</a></li>
		</ul>
	</div>
	<!--end categorymenu -->
	<div class="account">
		<h2>订阅“活动预告”邮件</h2>
		您的信箱将及时收到活动预告，还有各种各样的优惠券！
		<input name="searchText" type="text"  class="searchbox" value="输入邮件地址" maxlength="50" style="margin-left:0;"><input type="button" value="订阅" class="searchbotton" style="margin:0; height:22px;"/>
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