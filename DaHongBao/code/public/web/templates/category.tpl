<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="1"></td>
      <td><img src="{LINK_ROOT}images/categories/header_category{CATEGORY_SELECT}.png"></td>
   </tr>
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="1"></td>
      <td><img src="{LINK_ROOT}images/bgim.gif" width="30" height="10"></td>
   </tr>
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="1"></td>
      <td>{MERCHANT_PROMO_INCLUDE}</td>
   </tr>
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="1"></td>
      <td><img src="{LINK_ROOT}images/bgim.gif" width="30" height="5"></td>
   </tr>
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="1"></td>
      <td>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td width="30%" class="merchantTitleText">重新排列</td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td width="10%" class="merchantTitleText">重新排列</td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td width="40%" class="merchantTitleText">优惠内容介绍</td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td align="right" class="merchantTitleText">重新排列</td>
            </tr>
            <tr>
               <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}_MerchantName_all{SEARCH_METHOD}.html" class="merchantTitleLink">商家名字</a></td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}_Amount_all{SEARCH_METHOD}.html"  class="merchantTitleLink">打折情况</a></td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td class="merchantTitleLink">&nbsp;</td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td align="right"><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}_ExpireDate_all{SEARCH_METHOD}.html" class="merchantTitleLink">过期时间</a></td>
            </tr>
            <tr>
               <td colspan="7"><img src="{LINK_ROOT}images/bgim.gif" width="5" height="5"></td>
            </tr>
            <!-- BEGIN DYNAMIC BLOCK: coupon_list -->
            <tr>
               <td colspan="7" background="{LINK_ROOT}images/hor_grey_{LINE_TYPE}.gif"><img src="{LINK_ROOT}images/bgim.gif" width="570" height="1"></td>
            </tr>
            <tr>
               <td colspan="7"><img src="{LINK_ROOT}images/bgim.gif" width="15" height="15"></td>
            </tr>
            <tr>
               <td><a onclick="top.MyClose=false;" href="{LINK_ROOT}{RAWMERCHANT}/index.html" class="merchantLink">{MERCHANT}</a></td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td class="amountandexpire">{AMOUNT}</td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td><a target="_blank" onclick="top.MyClose=false;" href="{LINK_ROOT}frame.php?p={COUPON_ID}&c={CATEGORY_SELECT}" class="descriptLink">{DESCRIPT}</a><font class="restrLink">&nbsp;<a onclick="top.MyClose=false;" href="JavaScript:show_restriction('{RESTR}');void(0);" class="restrLink">{SEE_RESTRICTION}</a></font>{IS_NEW}</td>
               <td width="1%"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
               <td align="right" nowrap class="amountandexpire">{EXPIRE}</td>
            </tr>
            <tr>
               <td colspan="7"><img src="{LINK_ROOT}images/bgim.gif" width="10" height="10"></td>
            </tr>
            <!-- END DYNAMIC BLOCK: coupon_list -->
         </table>
      </td>
   </tr>
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="1"></td>
      <td><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
   </tr>
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="1"></td>
      <td>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td width="20%"><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}{ORDER}_all{SEARCH_METHOD}.html" class="merchantLink">See&nbsp;All&nbsp;Coupons,&nbsp;Sales&nbsp;&amp;&nbsp;Promotions</a>&nbsp;&nbsp;</td>
               <td><a onclick="top.MyClose=false;" href="{SCRIPT_NAME}{ORDER}_all{SEARCH_METHOD}.html"><img src="{LINK_ROOT}images/arrow_right.gif" border="0"></a></td>
            </tr>
         </table>
      </td>
   </tr>
</table>
