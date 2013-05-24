{include file="simple_head.tpl"}
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
			<script language="JavaScript" src="../jscript/register.js"></script>
			
			<!--end footer -->

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td width="1%"><img src="/images/bgim.gif" width="30" height="30"></td>
      <td>
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td><img src="/images/my_Account_Sign_Up.gif"></td>
            </tr>
            <tr>
               <td><img height="5" width="5" src="/images/bgim.gif"></td>
            </tr>
            <tr>
               <td class="introduction"></td>
            </tr>
            <tr>
               <td><img src="/images/bgim.gif" width="30" height="30"></td>
            </tr>
            <tr>
               <td>
                  <FORM NAME="form_register" METHOD="POST" ACTION="/register.php">
                  <input type="hidden" name="action" value="register">
                  <table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="/images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482" valign="top">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td rowspan="2" bgcolor="#FCE482"><img width="15" height="10" src="/images/bgim.gif"></td>
                                 <td class="loginTableHead">&nbsp;</td>
                                 <td rowspan="2" bgcolor="#FCE482"><img width="25" height="10" src="/images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                                          <td colspan="2"><img width="5" height="5" src="/images/bgim.gif"></td>
                                       </tr>
<!-- no forum                          <tr>
                                          <td class="introduction">Forum Display Name:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txForum"></td>
                                       </tr>
-->                                       
                                       <tr>
                                          <td class="introduction">*–’:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txFirst"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">*√˚:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txLast"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">*Email:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txEmail"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">*√‹°°°°¬Î:&nbsp;</td>
                                          <td><input type="password" class="loginText" style="width:150px" name="txPass"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">*»∑»œ√‹¬Î:&nbsp;</td>
                                          <td><input type="password" class="loginText" style="width:150px" name="txPass_"></td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="/images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td><img width="15" height="10" src="/images/bgim.gif"></td>
                                 <td width="100%" align="right"><a onclick="top.MyClose=false;" href="JavaScript:checkRegInfo('reg');"><img border="0" src="/images/Register_but.gif"></a></td>
                                 <td><img width="25" height="10" src="/images/bgim.gif"></td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="/images/bgim.gif"></td>
                     </tr>
                  </table>
                  </form>
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