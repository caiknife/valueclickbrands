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
               <td class="introduction">�Ͽ��ڴ���ע������˻��ɣ��ö��Żݵ�������<br>�����˻�֮�����Ϳ��ԣ�<br>    1����ʱ�յ�����Ϊ�����µ������Żݻ��Ϣ��<br>    2������ϲ�����̼���ӵ����Լ����̼��б�<br><br>ע�⣺���ڴ������õ��κ���Ϣ���ᱻ��ȫ�ı��棬�����ᱻ������</td>
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
                                 <td class="loginTableHead">{if $relogin}������������µ�½{else}�Ѿ��ǻ�Ա�ˣ�{/if}</td>
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
                                          <td class="introduction">����:&nbsp;</td>
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
                                 <td class="loginTableHead">��Ҫע��</td>
                                 <td rowspan="2"><img width="15" height="10" src="/images/bgim.gif"></td>
                              </tr>
                              <tr>
                                 <td class="loginTableBody">ע�����û���������������㣬��ʡǮ</td>
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
                                 <td width="50%" valign="bottom" align="left"><!--<a onclick="top.MyClose=false;" href="/register.php?action=forgot1" class="loginLink">���������ˣ�</a>--></td>
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