<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
      <td>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td><img src="{LINK_ROOT}images/Looking_For.gif"></td>
            </tr>
            <tr>
               <td><img src="{LINK_ROOT}images/bgim.gif" width="30" height="30"></td>
            </tr>
            <tr>
               <td>
                  <form name="form_looking_for" method="POST" action="{SCRIPT_NAME}">
                     <table width="60%" border="0" cellspacing="0" cellpadding="1" bgcolor="#FCE482">
                        <tr>
                           <td colspan="4"><img height="15" width="15" src="{LINK_ROOT}images/bgim.gif"></td>
                        </tr>
                        <tr>
                           <td rowspan="7"><img width="15" src="{LINK_ROOT}images/bgim.gif"></td>
                           <td class="introduction">姓:</td>
                           <td align="left"><input type="text" class="fieldText" style="width:200px" size="20" name="firstname"></td>
                           <td rowspan="7"><img width="15" src="{LINK_ROOT}images/bgim.gif"></td>
                        </tr>
                        <tr>
                           <td class="introduction">名:</td>
                           <td align="left"><input type="text" class="fieldText" style="width:200px" size="20" name="lastname"></td>
                        </tr>
                        <tr>
                           <td class="introduction">Email地址:</td>
                           <td align="left"><input type="text" class="fieldText" style="width:200px" size="20" name="email"></td>
                        </tr>
                        <tr>
                           <td class="introduction">Email地址重复:</td>
                           <td align="left"><input type="text" class="fieldText" style="width:200px" size="20" name="email1"></td>
                        </tr>
                        <tr>
                           <td class="introduction" valign="top">查找产品还是查找商家</td>
                           <td align="left"><textarea rows="3" class="fieldText" style="width:250px" size="30" name="productmerchant"></textarea></td>
                        </tr>
                        <tr>
                           <td class="introduction" valign="top">说明</td>
                           <td align="left"><textarea rows="5" class="fieldText" style="width:250px" size="30" name="comments"></textarea></td>
                        </tr>
                        <tr>
                           <td colspan="2"><img src="{LINK_ROOT}images/bgim.gif" width="30" height="20"></td>
                        </tr>
                        <tr>
                           <td colspan="2" align="right">
                              <input type="hidden" name="posted" value="yes">
                              <a onclick="top.MyClose=false;" href="JavaScript:document.form_looking_for.reset();void(0);"><img border="0" src="{LINK_ROOT}images/Reset_but.gif"></a>
                              &nbsp;&nbsp;&nbsp;
                              <a onclick="top.MyClose=false;" href="JavaScript:if (check_mail(window.document.form_looking_for.email)){ window.document.form_looking_for.submit(); }void(0);"><img border="0" src="{LINK_ROOT}images/Submit_but.gif"></a>
                           </td>
                        </tr>
                        <tr>
                           <td colspan="4"><img height="15" width="15" src="{LINK_ROOT}images/bgim.gif"></td>
                        </tr>
                     </table>
                  </form>
               </td>
            </tr>
         </table>
      </td>
   </tr>
</table>