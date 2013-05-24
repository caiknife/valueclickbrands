<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
      <td>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td><img src="{LINK_ROOT}images/my_Account.gif"></td>
            </tr>
           <tr>
             <td align="left">
                  <table width="70%" border="0" cellspacing="0" cellpadding="0">
                   <tr><td align="right">
                      <a onclick="top.MyClose=false;" href="{LINK_ROOT}register.php?action=modify" class="loginLink">更改Email和密码</a></td>
                   </tr>
                   </table>
             </td>
           </tr>

            <tr>
               <td><img height="5" width="5" src="{LINK_ROOT}images/bgim.gif"></td>
            </tr>
            <tr>
               <td class="introduction">{INTRODUCTION}</td>
            </tr>
            <tr>
               <td><img src="{LINK_ROOT}images/bgim.gif" width="10" height="10"></td>
            </tr>
            <tr>
               <td>
                  <FORM NAME="form_notify" METHOD="POST" ACTION="{SCRIPT_NAME}">
                  <input type="hidden" name="action" value="save">
                  <input type="hidden" name="mymerchantlist" value="">
                  <table width="70%" border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td><hr size="1" width="100%" color="#000000"></td>
                     </tr>
                     <tr>
                        <td><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td class="introduction">
                           <b>订阅大红包新闻?</b><br>
                           <input type="radio" name="weeklyNews" value="1" {WEEKLY_YES}>是<br>
                           <input type="radio" name="weeklyNews" value="0" {WEEKLY_NO}>否<br>
                           <br>
                        </td>
                     </tr>
                     <tr>
                        <td><hr size="1" width="100%" color="#000000"></td>
                     </tr>
                     <tr>
                        <td><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td class="introduction">
                           <b>这些是排名前{TOP_MERCHANT_COUNT}位的商家。选择一个，查看其最新优惠活动信息</b><br><br>
                           <table width="100%">
                              <!-- BEGIN DYNAMIC BLOCK: top_merchant_list -->
                              <tr>
                                 <td width="40%" class="introduction" bgcolor="{SELCOLOR1}">
                                    <input type="checkbox" name="TopMerchnat[]" value="{TOPMERCH1VAL}" {TOPMERCH1}>&nbsp;{TOPMERCH1NAME}
                                 </td>
                                 <td width="10%"><img src="{LINK_ROOT}images/bgim.gif"></td>
                                 <td width="40%" class="introduction" bgcolor="{SELCOLOR2}">
                                    <input type="checkbox" name="TopMerchnat[]" value="{TOPMERCH2VAL}" {TOPMERCH2}>&nbsp;{TOPMERCH2NAME}
                                 </td>
                                 <td width="10%"><img src="{LINK_ROOT}images/bgim.gif"></td>
                              </tr>
                              <!-- END DYNAMIC BLOCK: top_merchant_list -->
                           </table>
                           <br><br>
                           <b>创建您个人喜欢的商家列表</b><br>
                           按住 Ctrl 多选<br><br>
                           <table width="100%" cellpadding="5">
                              <tr>
                                 <td width="35%" class="introduction" bgcolor="#EEEEEE">
                                    <b>所有的商家</b><br>
                                    <select size="5" style="width:150px" multiple name="allMerchant">
                                    {ALL_MERCHANT}
                                    </select>
                                 </td>
                                 <td width="1%"><img src="{LINK_ROOT}images/bgim.gif"></td>
                                 <td valign="middle">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                                          <td><a onclick="top.MyClose=false;" href="JavaScript:addm('form_notify','myMerchant','allMerchant');void(0);"><img border="0" src="{LINK_ROOT}images/my_Add_to_List.gif"></a></td>
                                       </tr>
                                       <tr>
                                          <td><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
                                       </tr>
                                       <tr>
                                          <td><a onclick="top.MyClose=false;" href="JavaScript:removem('form_notify','myMerchant');void(0);"><img border="0" src="{LINK_ROOT}images/my_Remove_from_List.gif"></a></td>
                                       </tr>
                                    </table>
                                 </td>
                                 <td width="1%"><img src="{LINK_ROOT}images/bgim.gif"></td>
                                 <td width="35%" class="introduction" bgcolor="#FCE482">
                                    <b>我的商家列表</b><br>
                                    <select size="5" style="width:150px" multiple name="myMerchant">
                                    {MY_MERCHANT}
                                    </select>
                                 </td>
                              </tr>
                           </table>
                           <br>
                        </td>
                     </tr>
                     <tr>
                        <td><hr size="1" width="100%" color="#000000"></td>
                     </tr>
                     <tr>
                        <td><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td class="introduction">
                           <b>这些商家以怎样的方式Email将优惠活动通知你？</b><br>
                           <input type="radio" name="howOften" value="1" {OFTEN_ASAP}>每当优惠活动开始的时候接收通知<br>
                           <input type="radio" name="howOften" value="2" {OFTEN_DAILY}>每天通知<br>
                           <input type="radio" name="howOften" value="3" {OFTEN_WEEKLY}>每周通知<br>
                           <input type="radio" name="howOften" value="0" {OFTEN_NEVER}>不接收任何通知<br>
                           <br>
                        </td>
                     </tr>
                     <tr>
                        <td><hr size="1" width="100%" color="#000000"></td>
                     </tr>
                     <tr>
                        <td><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td class="introduction">
                           <input type="checkbox" name="alertExpire" value="1" {ALERT_EXPIRE}>
                           <b>在活动结束前3天内</b> 通知我优惠快要过期了.<br>
                           <br>
                        </td>
                     </tr>
                     <tr>
                        <td><hr size="1" width="100%" color="#000000"></td>
                     </tr>
                     <tr>
                        <td><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td align="right">
                           <a onclick="top.MyClose=false;" href="JavaScript:list2str('form_notify','myMerchant','mymerchantlist');document.form_notify.submit();void(0);"><img border="0" src="{LINK_ROOT}images/Update_List_but.gif"></a>
                        </td>
                     </tr>
                  </table>
                  </form>
               </td>
            </tr>
         </TABLE>
      </td>
   </tr>
</table>
