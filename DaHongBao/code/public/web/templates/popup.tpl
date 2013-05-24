<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>电子优惠券折扣券－大红包购物商城</title>
<link href="{LINK_ROOT}css/main.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="{LINK_ROOT}jscript/restr.js"></script>
<script language="JavaScript">
<!--
   {COUPON_URL}
//-->
</script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0">
   <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#000000">
      <tr>
         <td bgcolor="#6363CE">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td width="5%"><img width="25" height="50" src="{LINK_ROOT}images/bgim.gif"></td>
                  <td valign="middle"><a onclick="top.MyClose=false;" href="JavaScript:opener.location.href = '{LINK_ROOT}';self.close();"><img border="0" src="{LINK_ROOT}images/frame_logo.gif"></a></td>
                  <td width="5%"><img width="15" height="50" src="{LINK_ROOT}images/bgim.gif"></td>
                  <td width="1%" valign="middle"><img width="1" src="{LINK_ROOT}images/pop_vert_space.gif"></td>
                  <td width="5%"><img width="15" height="50" src="{LINK_ROOT}images/bgim.gif"></td>
                  <td width="50%" valign="middle">
                     <a class="frameLink" onclick="top.MyClose=false;" href="javascript:show_restriction('{RESTR}');void(0);">{READ_RESTRICTION}</a><br>
                     <!-- BEGIN DYNAMIC BLOCK: this_is_coupon_3 -->
                     <a class="frameLink" onclick="top.MyClose=false;" href="javascript:window.open('{LINK_ROOT}{RAWMERCHANT}/Where_to_Enter_Coupon.html','whereEnter15','resizable=yes,scrollbars=yes');void(0);">查看在何处输入优惠券编号</a>
                     <!-- END DYNAMIC BLOCK: this_is_coupon_3 -->
                  </td>
                  <td><img src="{LINK_ROOT}images/bgim.gif" width="1" height="40"></td>
               </tr>
               <tr>
                  <td colspan="6" align="center"><img height="2" src="{LINK_ROOT}images/pop_hor_space.gif"></td>
               </tr>
               <tr>
                  <td colspan="6" align="center">
                     <form>
                     <select class="couponList" onChange="top.MyClose=false;window.location.href = '{LINK_ROOT}popup.php?p=' + this.options[this.selectedIndex].value + '&f=yes&op={COUP_ID}';">
                     {COUPONLIST}
                     </select>
                     </form>
                  </td>
                  <td><img src="{LINK_ROOT}images/bgim.gif" width="1" height="40"></td>
               </tr>
               <tr>
                  <td colspan="6" align="center">
                     <!-- BEGIN DYNAMIC BLOCK: this_is_coupon_2 -->
                     <table cellspacing="0" cellpadding="0" border="0" height="18">
                        <tr>
                           <td><img src="{LINK_ROOT}images/frame_arrow.gif"></td>
                           <td><img src="{LINK_ROOT}images/bgim.gif" width="10"></td>
                           <td><img src="{LINK_ROOT}images/frame_but_left.gif" height="18"></td>
                           <td background="{LINK_ROOT}images/frame_but_bg.gif" valign="middle">&nbsp;<a class="couponCodeButton1">优惠券编号</a><a class="couponCodeButton">{COUPON_CODE}</a></td>
                           <td><img src="{LINK_ROOT}images/frame_but_right.gif" height="18"></td>
                           <td><img src="{LINK_ROOT}images/bgim.gif" width="10"></td>
                           <td><img src="{LINK_ROOT}images/frame_arrow_1.gif"></td>
                        </tr>
                     </table>
                     <!-- END DYNAMIC BLOCK: this_is_coupon_2 -->
                  </td>
                  <td><img src="{LINK_ROOT}images/bgim.gif" width="1" height="40"></td>
               </tr>
               <tr>
                  <td colspan="7" bgcolor="#FFCE00" width="30%">
                     <form name="verifyCouponForm" method="GET" action="{LINK_ROOT}popup.php">
                     <input type="hidden" name="p" value="{COUP_ID}">
                     <input type="hidden" name="op" value="{COUP_ID}">
                     <input type="hidden" name="f" value="yes">
                     <input type="hidden" name="mv" value="yes">
                     <table>
                        <tr>
                           <td rowspan="3"><img src="{LINK_ROOT}images/bgim.gif" width="10" height="10"></td>
                           <td class="verify">
                              <!-- BEGIN DYNAMIC BLOCK: did_coupon_work -->
                              这个优惠券有效吗？
                              <!-- END DYNAMIC BLOCK: did_coupon_work -->
                              &nbsp;
                           </td>
                           <td class="verify" valign="middle">{VERIFY_STATUS}</td>
                        </tr>
                     </table>
                     </form>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
</body>
</html>