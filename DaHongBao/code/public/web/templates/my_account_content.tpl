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
               <td class="introduction">{$INTRODUCTION}&nbsp;&nbsp;<a href="/register.php?action=log" class="loggedIn">�����û�</a></td>
            </tr>
            <tr>
               <td>
                  <table width="75%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td align="right"><!--<a href="/register.php?action=modify" class="loginLink">����Email������</a>--></td>
                     </tr>
                     <tr>
                        <td><img width="10" height="10" src="/images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td width="5%"><img height="33" src="/images/my_Saved_Coupons.gif"></td>
                                 <td class="accountHead"><b>{$COUPON_COUNT} �� �Ż�ȯ</b> �ѱ���</td>
                                 <td align="right"><!--<a href="/How_to_Add_Coupons.html" class="loginLinkLarge">��ô������Ż�ȯ</a>--></td>
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
                                    <b>��ô������Ż�ȯ</b><br>
                                    <b>1.</b> �ҵ�����Ҫ���Ż�ȯ<br>
                                    <b>2.</b> ����Żݻ��ϸ��<br>
                                    <b>3.</b> �������������Żݡ�<br>
                                    <b>4.</b> �������������˻��л��Զ��������ղ�ѡ����Ǹ��Ż�ȯ<br>
                                    <br>
                                    ���� <a href="/" class="loginLinkLarge">��������ҳ</a> ��
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
                                 <td bgcolor="#FEF5D2" width="30%" class="walletCouponListHead">&nbsp;&nbsp;�̼�����</td>
                                 <td bgcolor="#FEF5D2" width="1%"><img width="3" src="/images/bgim.gif"></td>
                                 <td bgcolor="#FEF5D2" width="10%" class="walletCouponListHead">&nbsp;&nbsp;�������</td>
                                 <td bgcolor="#FEF5D2" width="1%"><img width="3" src="/images/bgim.gif"></td>
                                 <td bgcolor="#FEF5D2" width="40%" class="walletCouponListHead" valign="bottom"><font color="#000000">�Ż����ݽ���</font></td>
                                 <td bgcolor="#FEF5D2" width="1%"><img width="3" src="/images/bgim.gif"></td>
                                 <td bgcolor="#FEF5D2" class="walletCouponListHead">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����ʱ��</td>
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
                                 <td class="accountHead"><b>SUBSCRIPTION_COUNT �� ������Ϣ</b></td>
                                 <td align="right"><a href="/notify_me.php" class="loginLinkLarge">���Ķ�����Ϣ</a></td>
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
                                    <b>��ô�����ö�����Ϣ</b><br>
                                    <b>1.</b> ���� <a href="/notify_me.php" class="loginLinkLarge">���Ķ�����Ϣ</a> ҳ��.<br>
                                    <b>2.</b> ����ҳ���ϵ���ʾ�������<br>
                                    <b>3.</b> ��������¡������µĶ�����Ϣ���������<br>
                                 </td>
                              </tr>
                             
                              <tr>
                                 <td width="1%"><img height="10" width="10" src="/images/bgim.gif"></td>
                                 <td class="hasSubscription">
                                    <b>1. ��Ŀǰ�ڴ����ƶ��Ķ����������£�</b><br>
                                    
                                    &nbsp;&nbsp;&nbsp;MERCHANT_NAME<br>
                                    
                                    &nbsp;&nbsp;&nbsp;<b>SUBSCRIPT_METHOD</b>
                                 </td>
                              </tr>
                             
                              <tr>
                                 <td width="1%"><img height="10" width="10" src="/images/bgim.gif"></td>
                                 <td class="hasSubscription">
                                    <br><br>
                                    <b>2.</b> ���Żݻ����ǰ�����ڣ����ǻ�Email֪ͨ�㡣</b><br><br><br>
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