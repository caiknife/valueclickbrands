<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
      <td>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td>
                  <FORM NAME="form_login" METHOD="POST" ACTION="{SCRIPT_NAME}">
                     <INPUT TYPE="HIDDEN" NAME="action" VALUE="login">
                     <TABLE width="100%" cellspacing="5" cellpadding="0" border="0">
                        <TR>
                           <TD HEIGHT="20" class="PartHead">&nbsp;登录</TD>
                        </TR>
                        <TR>
                           <TD>
                              <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="5">
                                 <tr>
                                    <td align=center valign=middle>
                                       <TABLE  WIDTH="50%">
                                          <TR>
                                             <TD class="InfoCSP" colspan="2">&nbsp;</TD>
                                          </TR>
                                          <TR>
                                             <TD class="InfoCSP">E-mail</TD>
                                             <TD class="InfoCSP">
                                                <INPUT TYPE="TEXT" class="fieldText" style="width:200px" NAME="txEmail" SIZE="20">
                                             </TD>
                                          </TR>
                                          <TR>
                                             <TD class="InfoCSP">密码</TD>
                                             <TD class="InfoCSP">
                                                <INPUT TYPE="PASSWORD" class="fieldText" style="width:200px" NAME="txPass" SIZE="20">
                                             </TD>
                                          </TR>
                                          <TR>
                                             <TD class="InfoCSP" colspan="2">&nbsp;</TD>
                                          </TR>
                                          <TR>
                                             <TD class="InfoCSP" colspan="2" align="center">
                                                <INPUT TYPE="SUBMIT" NAME="btnRegister" VALUE="登录" class="normalButton">
                                             </TD>
                                          </TR>
                                          <TR>
                                             <TD class="InfoCSP" colspan="2">&nbsp;</TD>
                                          </TR>
                                          <TR>
                                             <TD class="InfoCSP" colspan="2">
                                                忘记密码了？ 单击 <A class="InfoCSP" onclick="top.MyClose=false;" href="{SCRIPT_NAME}?action=forgot1">这里</A> 获得帮助
                                             </TD>
                                          </TR>
                                          <TR>
                                             <TD class="InfoCSP" colspan="2">
                                                第一次来大红包？ 单击 <A class="InfoCSP" onclick="top.MyClose=false;" href="{SCRIPT_NAME}?action=new">这里</A> 注册新用户
                                             </TD>
                                          </TR>
                                          <TR>
                                             <TD colspan="2">&nbsp;</TD>
                                          </TR>
                                       </TABLE>
                                    </td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                     </table>
                  </FORM>
               </td>
            </tr>
         </TABLE>
      </td>
   </tr>
</table>