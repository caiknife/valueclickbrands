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
		<span class="reddark righttitle" style="width: 143px;"><strong>�����Ż��Ƽ�</strong></span>
		<ul>
		{section name=index loop=$newCouponlist}
		<li><a href="{$newCouponlist[index].couponURL}" class="blue" target="_blank">{$newCouponlist[index].couponTitle}</a></li>
		{/section}
 		</ul>
		<br/>
		<span class="block textr"><a href="/new_coupon.html" class="reddark">����</a></span>
	</div>
</div>
<div id="middle">
	<div class="link_title">
	<a href="/">��ҳ</a>><a href="/exchange.html">�������</a>
	</div>
	{$EXCHANGE_INCLUDE}

<div id="footerbox">
	<div class="footercontent1" style="margin-left:20px;">
        <div class="footer"  id="footerprint" >
				<div class="copyright">
					<B>�����������������Ѿ���չ</b>
					<br/>
					a) �뽫�����վ����\logo\���ӵ�ַ\��ϵ�˵���Ϣ����: <a href="mailto:smile_chen@mezimedia.com">smile_chen@mezimedia.com</a>, ��������վ����Ա��˺��ٸ�������<BR>&nbsp;&nbsp;&nbsp;&nbsp;(��վGOOGLE��PRֵ���ڵ���4,�ɽ�����ҳ���ӣ�PRС��4���ɽ�����ҳ����)
					<br/>
					b) ������ʾ��˳�����ύ���Ⱥ�˳��Ϊ׼
					</div>
				<div class="contactus" >
					<div class="right" style="margin-top:10px;"><img src="images/shca_cc.gif" class="f" /><span class="block f" style="line-height:30px; padding-left:5px;">��ICP��12034406��</span></div>
					<p><A href="/About_CouponMountain.html">���ڴ���</A>  | <A href="/Contact_Us.html">��ϵ����</A> | ȥ�������ҿ����Ż�ȯ�� <a href="http://www.couponmountain.com">����</a> <a href="http://www.couponmountain.co.uk">Ӣ��</a> <a href="http://www.waribikiya.com">�ձ�</a> <a href="http://www.savingsmountain.com">����</a> <a href="http://www.couponmountain.de">�¹�</a> <a href="http://www.bonpromo.com">����</a></p>

					<p>��Ȩ�� &copy; 2007 <a href="http://www.mezimedia.com/" target="_blank">Mezi Media</a> ����</p>
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