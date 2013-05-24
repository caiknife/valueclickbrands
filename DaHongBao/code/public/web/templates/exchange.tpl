{include file="simple_head.tpl"}
{include file="head.tpl"}
{literal}
<style>
.copyright a:link,.copyright a:visited,copyright a:hover{ color:#0328C1; text-decoration: underline;}
</style>
{/literal}
<div id="main">
<div id="left1" class="fillBg">	
	<div class="rightlist" id="newcoupon" >
		<span class="reddark righttitle" style="width: 143px;"><strong>热门优惠推荐</strong></span>
		<ul>
		{section name=index loop=$newCouponlist}
		<li><a href="{$newCouponlist[index].couponURL}" class="blue" target="_blank">{$newCouponlist[index].couponTitle}</a></li>
		{/section}
 		</ul>
		<br/>
		<span class="block textr"><a href="/new_coupon.html" class="reddark">更多</a></span>
	</div>
</div>
<div id="middle">
	<div class="link_title">
	<a href="/">首页</a>><a href="/exchange.html">合作伙伴</a>
	</div>
	{$EXCHANGE_INCLUDE}

<div id="footerbox">
	<div class="footercontent1" style="margin-left:20px;">
        <div class="footer"  id="footerprint" >
				<div class="copyright">
					<B>合作伙伴的连接申请已经开展</b>
					<br/>
					a) 请将你的网站名称\logo\链接地址\联系人等信息发至: <a href="mailto:smile_chen@mezimedia.com">smile_chen@mezimedia.com</a>, 经我们网站管理员审核后再更新上线<BR>&nbsp;&nbsp;&nbsp;&nbsp;(网站GOOGLE的PR值大于等于4,可交换首页链接，PR小于4，可交换内页链接)
					<br/>
					b) 链接显示的顺序以提交的先后顺序为准
					</div>
				<div class="contactus" >
					<div class="right" style="margin-top:10px;"><img src="images/shca_cc.gif" class="f" /><span class="block f" style="line-height:30px; padding-left:5px;">沪ICP备12034406号</span></div>
					<p><A href="/About_CouponMountain.html">关于大红包</A>  | <A href="/Contact_Us.html">联系我们</A> | 去其他国家看看优惠券： <a href="http://www.couponmountain.com">美国</a> <a href="http://www.couponmountain.co.uk">英国</a> <a href="http://www.waribikiya.com">日本</a> <a href="http://www.savingsmountain.com">澳洲</a> <a href="http://www.couponmountain.de">德国</a> <a href="http://www.bonpromo.com">法国</a></p>

					<p>版权归 &copy; 2007 <a href="http://www.mezimedia.com/" target="_blank">Mezi Media</a> 所有</p>
				</div>			
			</div>	</div>
</div>
<!--end footerbox -->
</div>
<!--
   make_stat({$category_cur},{$merchant_cur},{$coupon_cur},1);
   afp_stat();
//-->
</script>

</div>
<!--end main -->
</body>
</html>