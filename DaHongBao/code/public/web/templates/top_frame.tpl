<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<link href="{LINK_ROOT}css/main.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="{LINK_ROOT}jscript/restr.js"></script>
<script language="JavaScript">
<!--
{COUPON_URL}
//-->
</script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#000000">
   <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#000000">
      <tr>
         <td bgcolor="#6363CE">
            <form>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td width="1%"><img border="0" width="15" height="20" src="{LINK_ROOT}images/bgim.gif"></td>
                  <td width="10%" valign="middle"><a target="_top" onclick="top.MyClose=false;" href="JavaScript:top.frames[0].document.verifyCouponForm.myact.value='yes';top.location.href='{LINK_ROOT}';void(0);"><img border="0" src="{LINK_ROOT}images/frame_logo.gif"></a></td>
                  <td width="1%"><img border="0" width="15" height="20" src="{LINK_ROOT}images/bgim.gif"></td>
                  <td width="1%"><img border="0" src="{LINK_ROOT}images/frame_space.gif"></td>
                  <td width="1%"><img border="0" width="15" height="20" src="{LINK_ROOT}images/bgim.gif"></td>
                  <td>
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                           <td>
                              <table border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                    <td>
                                       <select class="couponList" onChange="window.location.href = '{LINK_ROOT}top_frame.php?p=' + this.options[this.selectedIndex].value + '&f=yes&op={COUP_ID}'">
                                       {COUPONLIST}
                                       </select>
                                    </td>
                                    <td>
                                       <img width="5" height="5" src="{LINK_ROOT}images/bgim.gif">
                                    </td>
                                    <td>
                                       <!-- BEGIN DYNAMIC BLOCK: this_is_coupon_1 -->
                                       <img src="{LINK_ROOT}images/frame_arrow.gif">
                                       <!-- END DYNAMIC BLOCK: this_is_coupon_1 -->
                                    </td>
                                    <td>
                                       <img width="5" height="5" src="{LINK_ROOT}images/bgim.gif">
                                    </td>
                                    <td>
                                       <!-- BEGIN DYNAMIC BLOCK: this_is_coupon_2 -->
                                       <table cellspacing="0" cellpadding="0" border="0" height="18">
                                          <tr>
                                             <td><img src="{LINK_ROOT}images/frame_but_left.gif" height="18"></td>
                                             <td background="{LINK_ROOT}images/frame_but_bg.gif" valign="middle">&nbsp;<a class="couponCodeButton1">Code:</a><a class="couponCodeButton">{COUPON_CODE}</a></td>
                                             <td><img src="{LINK_ROOT}images/frame_but_right.gif" height="18"></td>
                                          </tr>
                                       </table>
                                       <!-- END DYNAMIC BLOCK: this_is_coupon_2 -->
                                    </td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td>
                              <table border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                    <td><a class="frameLink" onclick="top.MyClose=false;" href="javascript:show_restriction('{RESTR}');void(0);">{READ_RESTRICTION}</a></td>
                                    <td><img border="0" width="10" height="20" src="{LINK_ROOT}images/bgim.gif"></td>
                                    <td valign="middle">{SPACER_IMAGE}</td>
                                    <td><img border="0" width="10" height="20" src="{LINK_ROOT}images/bgim.gif"></td>
                                    <td>
                                       <!-- BEGIN DYNAMIC BLOCK: this_is_coupon_3 -->
                                       <a class="frameLink" onclick="top.MyClose=false;" href="javascript:window.open('{LINK_ROOT}{RAWMERCHANT}/Where_to_Enter_Coupon.html','whereEnter15','resizable=yes,scrollbars=yes');void(0);">察看在何处使用优惠券编号</a>
                                       <!-- END DYNAMIC BLOCK: this_is_coupon_3 -->
                                    </td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                     </table>
                  <td>
                  <td width="1%"><img border="0" width="15" height="20" src="{LINK_ROOT}images/bgim.gif"></td>
               </tr>
            </table>
            </form>
         </td>
         <td bgcolor="#FFCE00" width="30%">
            <form name="verifyCouponForm" method="GET" action="{LINK_ROOT}top_frame.php">
            <input type="hidden" name="p" value="{COUP_ID}">
            <input type="hidden" name="op" value="{COUP_ID}">
            <input type="hidden" name="myact" value="">
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
               </tr>
               <tr>
                  <td class="verify" valign="middle">{VERIFY_STATUS}</td>
               </tr>
               <tr>
                  <td><img src="{LINK_ROOT}images/bgim.gif" width="200" height="1"></td>
               </tr>
            </table>
            </form>
         </td>
      </tr>
   </table>
</body>
</html>