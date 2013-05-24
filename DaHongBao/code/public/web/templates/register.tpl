{include file="simple_head_noTracking.tpl"}
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
			<script language="JavaScript" src="/jscript/register.js"></script>
			
			<!--end footer -->

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
               <td><img src="/images/my_Account_Login.gif"></td>
            </tr>
            <tr>
               <td><img height="5" width="5" src="/images/bgim.gif"></td>
            </tr>
            <tr>
               <td class="introduction">赶快在大红包注册免费账户吧，好多优惠等着您！<br>有了账户之后，您就可以：<br>    1，及时收到大红包为您更新的最新优惠活动信息。<br>    2，将您喜爱的商家添加到您自己的商家列表<br><br>注意：您在大红包设置的任何信息都会被安全的保存，更不会被公开。</td>
            </tr>
            <tr>
               <td><img src="/images/bgim.gif" width="30" height="30"></td>
            </tr>
            <tr>
               <td>
                  <FORM NAME="form_login" METHOD="POST" ACTION="">
                  <input type="hidden" name="action" value="login">
				  <input type="hidden" name="p" value="{$addCouponID}">
                  <table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="/images/bgim.gif"></td>
                        <td bgcolor="#FFFFFF"><img width="5" height="5" src="/images/bgim.gif"></td>
                        <td bgcolor="#FEF5D2"><img width="10" height="10" src="/images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482" width="49%" valign="top">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td rowspan="2" bgcolor="#FCE482"><img width="15" height="10" src="/images/bgim.gif"></td>
                                 <td class="loginTableHead">{if $relogin}密码错误，请重新登陆{else}已经是会员了？{/if}</td>
                                 <td rowspan="2" bgcolor="#FCE482"><img width="15" height="10" src="/images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                                          <td colspan="2"><img width="5" height="5" src="/images/bgim.gif"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">Email:&nbsp;</td>
                                          <td><input type="text" class="loginText" style="width:150px" name="txEmail"></td>
                                       </tr>
                                       <tr>
                                          <td class="introduction">密码:&nbsp;</td>
                                          <td><input type="password" class="loginText" style="width:150px" name="txPass"></td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                           </table>
                        </td>
                        <td bgcolor="#FFFFFF"><img width="5" height="5" src="/images/bgim.gif"></td>
                        <td bgcolor="#FEF5D2" width="49%" valign="top">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td rowspan="2"><img width="15" height="10" src="/images/bgim.gif"></td>
                                 <td class="loginTableHead">我要注册</td>
                                 <td rowspan="2"><img width="15" height="10" src="/images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td class="loginTableBody">注册新用户能让您购物更方便，更省钱</td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="/images/bgim.gif"></td>
                        <td bgcolor="#FFFFFF"><img width="5" height="5" src="/images/bgim.gif"></td>
                        <td bgcolor="#FEF5D2"><img width="10" height="10" src="/images/bgim.gif"></td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td><img width="15" height="10" src="/images/bgim.gif"></td>
                                 <td width="50%" valign="bottom" align="left"><!--<a onclick="top.MyClose=false;" href="/register.php?action=forgot1" class="loginLink">忘记密码了？</a>--></td>
                                 <td width="50%" align="right"><a onclick="top.MyClose=false;" href="JavaScript:window.document.form_login.submit();"><img border="0" src="/images/Log_in_but.gif"></a></td>
                                 <td><img width="15" height="10" src="/images/bgim.gif"></td>
                              </tr>
                           </table>
                        </td>
                        <td bgcolor="#FFFFFF"><img width="5" height="5" src="/images/bgim.gif"></td>
                        <td bgcolor="#FEF5D2">
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                 <td><img width="15" height="10" src="/images/bgim.gif"></td>
                                 <td width="100%" align="right"><a onclick="top.MyClose=false;" href="/register.php?action=new&p={$addCouponID}"><img border="0" src="/images/Register_Here_but.gif"></a></td>
                                 <td><img width="15" height="10" src="/images/bgim.gif"></td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td bgcolor="#FCE482"><img width="10" height="10" src="/images/bgim.gif"></td>
                        <td bgcolor="#FFFFFF"><img width="5" height="5" src="/images/bgim.gif"></td>
                        <td bgcolor="#FEF5D2"><img width="10" height="10" src="/images/bgim.gif"></td>
                     </tr>
                  </table>
                  </form>
               </td>
            </tr>
         </TABLE>


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