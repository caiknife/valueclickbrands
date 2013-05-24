<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
      <td>
         <form name="form_notify" method="POST" action="{SCRIPT_NAME}">
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td class="infoPageHead">Saved coupons</td>
            </tr>
            <tr>
               <td><img src="{LINK_ROOT}images/bgim.gif" width="10" height="10"></td>
            </tr>
            <tr>
               <td class="infoMessage">您现在登陆的Email： {EMAIL}.  单击 <a class="infoLink" onclick="top.MyClose=false;" href="{LINK_ROOT}register.php">这里</a> 切换成其他.</td>
            </tr>
            <tr>
               <td class="infoMessage">如果要更改您的账户，单击 <a class="infoLink" onclick="top.MyClose=false;" href="{LINK_ROOT}register.php?action=modify">这里</a>.</td>
            </tr>
            <tr>
               <td valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td rowspan="6" width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="30" height="100"></td>
                     </tr>
                     <tr>
                        <td><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
                     </tr>
                     <tr>
                        <td>
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td width="30%" class="merchantTitleText">重新排列</td>
                                 <td width="10%" class="merchantTitleText">重新排列</td>
                                 <td width="40%" class="merchantTitleText">优惠内容介绍</td>
                                 <td class="merchantTitleText">重新排列</td>
                                 <td>&nbsp;</td>
                                 <td>&nbsp;</td>
                                 <td>&nbsp;</td>
                              </tr>
                              <tr>
                                 <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}?order=MerchantName" class="merchantTitleLink">商家名字</a></td>
                                 <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}?order=Amount"  class="merchantTitleLink">打折情况</a></td>
                                 <td class="merchantTitleLink">&nbsp;</td>
                                 <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}?order=ExpireDate" class="merchantTitleLink">过期时间</a></td>
                                 <td>&nbsp;</td>
                                 <td>&nbsp;</td>
                                 <td>&nbsp;</td>
                              </tr>
                              <tr>
                                 <td colspan="7"><img src="{LINK_ROOT}images/bgim.gif" width="5" height="5"></td>
                              </tr>
                              <!-- BEGIN DYNAMIC BLOCK: coupon_list -->
                              <tr>
                                 <td colspan="7"><img src="{LINK_ROOT}images/hor_grey_{LINE_TYPE}.gif" width="570" height="1"></td>
                              </tr>
                              <tr>
                                 <td colspan="7"><img src="{LINK_ROOT}images/bgim.gif" width="15" height="15"></td>
                              </tr>
                              <tr>
                                 <td><a onclick="top.MyClose=false;" href="{LINK_ROOT}{RAWMERCHANT}/{RAWMERCHANT}_coupon.htm" class="merchantLink">{MERCHANT}</a></td>
                                 <td class="amountandexpire">{AMOUNT}</td>
                                 <td><a onclick="top.MyClose=false;" href="{LINK_ROOT}redir.php?p={COUPON_ID}" class="descriptLink">{DESCRIPT}</a><font class="restrLink">&nbsp;(<a onclick="top.MyClose=false;" href="" class="restrLink">查看适用范围</a>)</font>{IS_NEW}</td>
                                 <td class="amountandexpire">{EXPIRE}</td>
                                 <td class="savesend"><a class="savesend" onclick="top.MyClose=false;" href="{LINK_ROOT}save_coupon.php?p={COUPON_ID}&remove=yes">Remove&nbsp;coupon</a></td>
                                 <td>&nbsp;</td>
                                 <td class="savesend"><a class="savesend" onclick="top.MyClose=false;" href="javascript:window.open('/send_friend.php?p={COUPON_ID}','sendfriend6','width=415,height=515,resizable=yes,scrollbars=yes');void(0);">送给好友</a></td>
                              </tr>
                              <tr>
                                 <td colspan="7"><img src="{LINK_ROOT}images/bgim.gif" width="10" height="10"></td>
                              </tr>
                              <!-- END DYNAMIC BLOCK: coupon_list -->
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
                     </tr>
                  </table>
               </td>
            </tr>
         </table>
         </form>
      </td>
   </tr>
</table>
