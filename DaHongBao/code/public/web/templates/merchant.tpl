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
			<div class="local"><a class="navigationLink" onclick="top.MyClose=false;"  href="http://www.dahongbao.com/">首页</a>&nbsp;&gt;&nbsp;<a href="/" class="b n2y">当当优惠券、折扣券、购物券</a></div>	
			<!--end local -->
			<div style="margin:10px auto; padding-bottom:10px;border-bottom:#aca899 1px solid;"><IMG height=13 src="images/cm_merch_arr.gif" width=10 border=0> <A href="http://www.dahongbao.com/firefox.html" title="Firefox免费下载"><span class="red nud b">Firefox</span></A> <A onclick=top.MyClose=false; href="http://www.dahongbao.com/firefox.html">Firefox浏览器免费下载!</A> </FONT></div>
			<!--end adv -->
			<div class="merchantinfo">
				  <a href="/"><IMG  src="images/Logo.gif" alt="当当优惠券、折扣购物券"  border=0 class="f"></a>		
				  <div class="merchantinforight grey"><p>当当网上书店成立于1999年11月,是全球最大的中文网上书店,其管理团队拥有多年的图书出版、零售、信息技术及市场营销经验。面向全世界中文读者提供20多万种中文图书及超过1万种的音像商品,每天为成千上万的网上消费者提供方便、快捷的服务,大红包购物商城提供在线当当优惠券、当当购物券,给网上购物者带来极大的方便和实惠。大红包网站每天定时更新,确保各类当当电子优惠券确实有效,保证客户从中得到最大的折扣。</p>
				  <p> 购物时使用当当电子优惠券简单易操作；无需登陆，只需根据目录或者通过字母列表直接访问当当、浏览点击当当优惠券、购物券在线购物。大红包购物商城同时能通过当当优惠券列表发布当当零售商最新公布的奖励优惠信息，帮助您留意每天销售最热门的产品。关注最新的当当优惠购物券，关注大红包购物商城。点击热门商家在线菜单，给您带来意想不到的惊奇。 </p>
				  <p class="b">直接访问商家主页: <a href="/" ><span class="blue">当当</span></a> </p>
				  </div>
			</div>
			<!--end merchantinfo -->
			<div id="merchantcoupon">
				<div>点击查看当当优惠券, 其他折扣, 热门信息</div>
				<div class="middletitle"><h2>当当 的优惠券</h2></div>
				
				
				<div class="couponlist">
					<div class="f couponimg"><A href="http://www.dahongbao.com/dhc/index.html"><IMG src="images/8401.jpg" alt="DHC优惠券、折扣购物券" ></A></div>
					<div class="right seeit"><a href="/"><img src="images/blue_but.gif" alt="查看此优惠" /></a></div>
					<dl>
						<dt><a href="/" class="blue">情人节"情侣金猪"在线大放送!购物200元即赠!</a> </dt>
						<dd>活动期间,凡  购物满250元的用户即获得情侣金猪一只, 凡购物满400元的用户即可获得情侣金猪一对可活动期间,凡  购物满250元的用户即获得情侣金猪一只, 凡购物满400元的用户即可获得情侣金猪一对可活动期间,凡  购物满250元的用户即获得情侣金猪一只, 凡购物满400元的用户即可获得情侣金猪一对可</dd>                                                                  
						<dd>发布时间：2007-2-2  结束时间:2007-5-2</dd>
						<dd class="couponlistbottom"><a href="/" class="addtofav">加入收藏</a><a href="/" class="comtofriend">推荐朋友</a><a href="/"  class="comments">发表评论</a></dd>
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