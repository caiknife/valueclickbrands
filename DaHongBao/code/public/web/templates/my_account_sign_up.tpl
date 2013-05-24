<script language="JavaScript">
<!--
   function checkRegInfo(typ){
      if (typ == "chap"){
         if (document.form_register.txFirst.value == "" ||
             document.form_register.txLast.value == ""  ||
             document.form_register.txEmail.value == "" ||
             document.form_register.txPass.value != document.form_register.txPass_.value){
            alert("请填写完整！！")
         }
         else {
            if (check_mail(document.form_register.txEmail))
               document.form_register.submit();
         }
      }
      else{
         if (document.form_register.txFirst.value == "" ||
             document.form_register.txLast.value == ""  ||
             document.form_register.txEmail.value == "" ||
             document.form_register.txPass.value == ""  ||
             document.form_register.txPass_.value == "" ||
             document.form_register.txPass.value != document.form_register.txPass_.value){
            alert("请填写完整！！")
         }
         else {
            if (check_mail(document.form_register.txEmail))
               document.form_register.submit();
         }
      }
   }
//-->
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
      <td>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td><img src="{LINK_ROOT}images/my_Account_Sign_Up.gif"></td>
            </tr>
            <tr>
               <td><img height="5" width="5" src="{LINK_ROOT}images/bgim.gif"></td>
            </tr>
            <tr>
               <td class="introduction">{INTRODUCTION}</td>
            </tr>
            <tr>
               <td><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
            </tr>
            <tr>
               <td>
                  <FORM NAME="form_register" METHOD="POST" ACTION="{SCRIPT_NAME}">
                  <input type="hidden" name="action" value="register">
                  <table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="{LINK_ROOT}images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482" valign="top">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td rowspan="2" bgcolor="#FCE482"><img width="15" height="10" src="{LINK_ROOT}images/bgim.gif"></td>
                                 <td class="loginTableHead">&nbsp;</td>
                                 <td rowspan="2" bgcolor="#FCE482"><img width="25" height="10" src="{LINK_ROOT}images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                                          <td colspan="2"><img width="5" height="5" src="{LINK_ROOT}images/bgim.gif"></td>
                                       </tr>
<!-- no forum                          <tr>
                                          <td class="introduction">Forum Display Name:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txForum"></td>
                                       </tr>
-->                                       
                                       <tr>
                                          <td class="introduction">*姓:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txFirst"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">*名:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txLast"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">*Email:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txEmail"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">*密　　码:&nbsp;</td>
                                          <td><input type="password" class="loginText" style="width:150px" name="txPass"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">*确认密码:&nbsp;</td>
                                          <td><input type="password" class="loginText" style="width:150px" name="txPass_"></td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="{LINK_ROOT}images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td><img width="15" height="10" src="{LINK_ROOT}images/bgim.gif"></td>
                                 <td width="100%" align="right"><a onclick="top.MyClose=false;" href="JavaScript:checkRegInfo('reg');"><img border="0" src="{LINK_ROOT}images/Register_but.gif"></a></td>
                                 <td><img width="25" height="10" src="{LINK_ROOT}images/bgim.gif"></td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="{LINK_ROOT}images/bgim.gif"></td>
                     </tr>
                  </table>
                  </form>
               </td>
            </tr>
         </TABLE>
      </td>
   </tr>
</table>