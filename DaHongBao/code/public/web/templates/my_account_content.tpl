{include file="simple_head_noTracking.tpl"}
{include file="head.tpl"}

<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
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
			<script language="JavaScript" src="/jscript/register.js"></script>
			
			<!--end footer -->

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td width="1%"><img src="/images/bgim.gif" width="30" height="30"></td>
      <td>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td><img src="/images/my_Account.gif"></td>
            </tr>
            <tr>
               <td><img height="5" width="5" src="/images/bgim.gif"></td>
            </tr>
            <tr>
               <td class="introduction">{$INTRODUCTION}&nbsp;&nbsp;<a href="/register.php?action=log" class="loggedIn">更改用户</a></td>
            </tr>
            <tr>
               <td>
                  <table width="75%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td align="right"><!--<a href="/register.php?action=modify" class="loginLink">更改Email和密码</a>--></td>
                     </tr>
                     <tr>
                        <td><img width="10" height="10" src="/images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td width="5%"><img height="33" src="/images/my_Saved_Coupons.gif"></td>
                                 <td class="accountHead"><b>{$COUPON_COUNT} 张 优惠券</b> 已保存</td>
                                 <td align="right"><!--<a href="/How_to_Add_Coupons.html" class="loginLinkLarge">怎么样添加优惠券</a>--></td>
                                 <td width="1%"><img height="33" width="5" src="/images/bgim.gif"></td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     {if $nothing_yet1}
                     <tr>
                        <td>
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td colspan="2" width="1%"><img height="10" width="10" src="/images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td width="1%"><img height="10" width="10" src="/images/bgim.gif"></td>
                                 <td class="nothingYet">
                                    <b>怎么样添加优惠券</b><br>
                                    <b>1.</b> 找到您想要的优惠券<br>
                                    <b>2.</b> 点击优惠活动的细节<br>
                                    <b>3.</b> 点击“保存这个优惠”<br>
                                    <b>4.</b> 这样，在您的账户中会自动保存您刚才选择的那个优惠券<br>
                                    <br>
                                    进入 <a href="/" class="loginLinkLarge">大红包的首页</a> 。
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
					 {else}
                     <tr>
                        <td>
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td colspan="7" bgcolor="#FEF5D2"><img height="10" src="/images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td bgcolor="#FEF5D2" width="30%" class="walletCouponListHead">&nbsp;&nbsp;商家名字</td>
                                 <td bgcolor="#FEF5D2" width="1%"><img width="3" src="/images/bgim.gif"></td>
                                 <td bgcolor="#FEF5D2" width="10%" class="walletCouponListHead">&nbsp;&nbsp;打折情况</td>
                                 <td bgcolor="#FEF5D2" width="1%"><img width="3" src="/images/bgim.gif"></td>
                                 <td bgcolor="#FEF5D2" width="40%" class="walletCouponListHead" valign="bottom"><font color="#000000">优惠内容介绍</font></td>
                                 <td bgcolor="#FEF5D2" width="1%"><img width="3" src="/images/bgim.gif"></td>
                                 <td bgcolor="#FEF5D2" class="walletCouponListHead">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;过期时间</td>
                              </tr>
                              <tr>
                                 <td colspan="7" bgcolor="#FEF5D2"><img height="10" src="/images/bgim.gif"></td>
                              </tr>
                              {section name=outer loop=$couponList}
                              <tr>
                                 <td colspan="7"><img height="10" src="/images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td>&nbsp;{if $couponList[outer].merchantURL}<a href="{$couponList[outer].merchantURL}" class="blue">{$couponList[outer].merchantName}</a>{else}{$couponList[outer].merchantName}{/if}</td>
                                 <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                 <td class="amountandexpire" align="center">{$couponList[outer].Amount}</td>
                                 <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                 <td><a target="_blank" href="{$couponList[outer].couponURL}" class="blue">{$couponList[outer].couponTitle}</a></td>
                                 <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                 <td class="amountandexpire" align="center">{$couponList[outer].status}</td>
                              </tr>
                              <tr>
                                 <td colspan="7"><img height="10" src="/images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td colspan="7"><hr size="1px" color="#999999"></td>
                              </tr>
                              {/section}
                           </table>
                        </td>
                     </tr>
                    {/if}
                     <!--<tr>
                        <td><img height="30" src="/images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td width="5%"><img height="33" src="/images/my_Subscriptions.gif"></td>
                                 <td class="accountHead"><b>SUBSCRIPTION_COUNT 条 订阅信息</b></td>
                                 <td align="right"><a href="/notify_me.php" class="loginLinkLarge">更改订阅信息</a></td>
                                 <td width="1%"><img height="33" width="5" src="/images/bgim.gif"></td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td colspan="2" width="1%"><img height="10" width="10" src="/images/bgim.gif"></td>
                              </tr>
                              
                              <tr>
                                 <td width="1%"><img height="10" width="10" src="/images/bgim.gif"></td>
                                 <td class="nothingYet">
                                    <b>怎么样设置订阅信息</b><br>
                                    <b>1.</b> 进入 <a href="/notify_me.php" class="loginLinkLarge">更改订阅信息</a> 页面.<br>
                                    <b>2.</b> 按照页面上的提示完成设置<br>
                                    <b>3.</b> 点击“更新”，您新的订阅信息即保存完毕<br>
                                 </td>
                              </tr>
                             
                              <tr>
                                 <td width="1%"><img height="10" width="10" src="/images/bgim.gif"></td>
                                 <td class="hasSubscription">
                                    <b>1. 您目前在大红包制定的订阅新闻如下：</b><br>
                                    
                                    &nbsp;&nbsp;&nbsp;MERCHANT_NAME<br>
                                    
                                    &nbsp;&nbsp;&nbsp;<b>SUBSCRIPT_METHOD</b>
                                 </td>
                              </tr>
                             
                              <tr>
                                 <td width="1%"><img height="10" width="10" src="/images/bgim.gif"></td>
                                 <td class="hasSubscription">
                                    <br><br>
                                    <b>2.</b> 在优惠活动结束前三天内，我们会Email通知你。</b><br><br><br>
                                 </td>
                              </tr>
                              
                           </table>
                        </td>
                     </tr>-->
                  </table>
               </td>
            </tr>
         </TABLE>
      </td>
   </tr>
</table>


		 {include file="foot.tpl"}
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