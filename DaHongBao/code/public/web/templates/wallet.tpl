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
               <td class="infoMessage">�����ڵ�½��Email�� {EMAIL}.  ���� <a class="infoLink" onclick="top.MyClose=false;" href="{LINK_ROOT}register.php">����</a> �л�������.</td>
            </tr>
            <tr>
               <td class="infoMessage">���Ҫ���������˻������� <a class="infoLink" onclick="top.MyClose=false;" href="{LINK_ROOT}register.php?action=modify">����</a>.</td>
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
                                 <td width="30%" class="merchantTitleText">��������</td>
                                 <td width="10%" class="merchantTitleText">��������</td>
                                 <td width="40%" class="merchantTitleText">�Ż����ݽ���</td>
                                 <td class="merchantTitleText">��������</td>
                                 <td>&nbsp;</td>
                                 <td>&nbsp;</td>
                                 <td>&nbsp;</td>
                              </tr>
                              <tr>
                                 <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}?order=MerchantName" class="merchantTitleLink">�̼�����</a></td>
                                 <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}?order=Amount"  class="merchantTitleLink">�������</a></td>
                                 <td class="merchantTitleLink">&nbsp;</td>
                                 <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}?order=ExpireDate" class="merchantTitleLink">����ʱ��</a></td>
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
                                 <td><a onclick="top.MyClose=false;" href="{LINK_ROOT}redir.php?p={COUPON_ID}" class="descriptLink">{DESCRIPT}</a><font class="restrLink">&nbsp;(<a onclick="top.MyClose=false;" href="" class="restrLink">�鿴���÷�Χ</a>)</font>{IS_NEW}</td>
                                 <td class="amountandexpire">{EXPIRE}</td>
                                 <td class="savesend"><a class="savesend" onclick="top.MyClose=false;" href="{LINK_ROOT}save_coupon.php?p={COUPON_ID}&remove=yes">Remove&nbsp;coupon</a></td>
                                 <td>&nbsp;</td>
                                 <td class="savesend"><a class="savesend" onclick="top.MyClose=false;" href="javascript:window.open('/send_friend.php?p={COUPON_ID}','sendfriend6','width=415,height=515,resizable=yes,scrollbars=yes');void(0);">�͸�����</a></td>
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
