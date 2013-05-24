<html>
<head>
<title>{$PAGE_TITLE} - Administration Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<link href="{$LINK_ROOT}css/admin.css" rel=stylesheet type=text/css>
<meta content=EN http-equiv=Content-Language>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<script language="JavaScript" src="{$LINK_ROOT}jscript/admin_main.js"></script>
</head>

<body bgcolor="White">
   <center>
      <table height="100%" width="80%" border="1" cellpadding="0" cellspacing="1" class="border">
         <tr>
            <td>
               <table width="100%" border="0">
                  <tr>
                     <td align="center">
                        <a href="{$LINK_ROOT}index.php">Main page</a>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
            <td valign="top" align="center" height="95%">
				<table width="100%" border="0">
				   <tr>
					  <td align="center" class="header">
						 Coupon Management
					  </td>
				   </tr>
				</table>
				<br>
				<form name="record_list" action="{$SCRIPT_NAME}" method="POST" target="_blank">
				   <input type="hidden" name="action" value="">
				   <input type="hidden" name="keylist" value="{$KEY_LIST}">
				   <input type="hidden" name="id" value="">
				   <input type="hidden" name="page" value="{$CUR_PAGE}">
				   <input type="hidden" name="merchant" value="{$merchantid}">
				   <input type="hidden" name="searchname" value="">
				   <table width="100%" border="0" cellpadding="0" cellspacing="1" class="border">
					  <tr>
						 <td class="control" align="left">
						 &nbsp;<input type="text" name="searchcoupon" value="" width="50">&nbsp;<input type="button" name="submitsearch" value="查找Coupon" onClick="JavaScript:window.document.record_list.page.value=1;window.document.record_list.searchname.value=window.document.record_list.searchcoupon.value;window.document.record_list.submit();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Show coupons for Merchant with name starts:&nbsp;&nbsp;&nbsp;
							<select name="filter" onChange="JavaScript:window.document.record_list.submit();">
							   <option value="...">ALL
							   {$FILTER_LIST}
							</select><br><br>
						 </td>
					  </tr>
					  <tr>
						 <td valign="top">
							<table width="100%" border="0" class="block" cellspacing="0" cellpadding="0">
							   <tr>
								  <td valign="top">
									 <table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr>
										   <td align="center" width="4%">&nbsp;</td>
										   <td align="center" width="20%"><input class="buttonhead" type="button" value="new Coupon" onClick="javascript:window.open('coupon.php?act=add')"></td>
										  <!-- <td width="20%">&nbsp;</td>-->
				<!--
										   <td>&nbsp;</td>
				//-->
										   <td width="18%">&nbsp;</td>
										   <td width="6%">&nbsp;</td>
										   <td width="6%">&nbsp;</td>
										   <td width="6%">&nbsp;</td>
										   <td width="5%">&nbsp;</td>
										   <td align="center" width="5%"><input class="buttonhead" type="button" value="set FreeShipping" onClick="Javascript:user_set('record_list','set_freeShipping');"></td>
										   <td align="center" width="4%"><input class="buttonhead" type="button" value="set Featured" onClick="Javascript:user_set('record_list','set_featured');"></td>
										   <td align="center" width="4%"><input class="buttonhead" type="button" value="set Active" onClick="Javascript:user_set('record_list','set_active');"></td>
										   <td align="center" width="4%"><input class="buttonhead" type="button" value="Delete" onClick="Javascript:mass_delete('record_list','set_delete','coupons');"></td>
										   <td width="4%">&nbsp;</td>
										   <td width="4%">&nbsp;</td>
										</tr>
										<tr> 
										   <td class="listheader">ID</td>
										  <!-- <td class="listheader"><a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=Code">Coupon&nbsp;Code</a></td>-->
										   <td class="listheader" ><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=Descript">-->Coupon&nbsp;Description<!--</a>--></td>
										   <td class="listheader" ><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=MerchantName">-->Merchant<!--</a>--></td>
										   
				<!--
										   <td class="listheader" width="30%">Categories</td>
				//-->
										   <td class="listheader"><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=StartDate">-->&nbsp;Start&nbsp;Date&nbsp;<!--</a>--></td>
										   <td class="listheader"><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=ExpireDate">-->&nbsp;Expire&nbsp;Date&nbsp;<!--</a>--></td>
										   <td class="listheader"><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=AddDate">-->&nbsp;Add&nbsp;Date&nbsp;<!--</a>--></td>
										   <td class="listheader"><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=Amount">-->Amounts<!--</a>--></td>
										   <td class="listheader"><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isFreeShipping">-->FreeShipping<!--</a>--></td>
										   <td class="listheader"><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isFeatured">-->Featured<!--</a>--></td>
										   <td class="listheader" ><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isActive">-->Active<!--</a>--></td>
										   <td class="listheader" >&nbsp;</td>
										   <td class="listheader"><!--<a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isFree">-->Free<!--</a>--></td>
										   <td class="listheader">&nbsp;</td>
										   <td class="listheader">&nbsp;</td>
										</tr>
										{section name=index loop=$couponlist}
										<tr> 
										   <td class="{$couponlist[index].ZEBRA}"><font color="#FF0000">{$couponlist[index].ID}</font></td>
										   <!--<td class="{$couponlist[index].ZEBRA}">{$couponlist[index].COUPON}</td>-->
										   <td class="{$couponlist[index].ZEBRA}">{$couponlist[index].DESCRIPT}</td>
										   <td class="{$couponlist[index].ZEBRA}"><a href="{$LINK_ROOT}edit/merchant.php?action=modify&id={$couponlist[index].MERCHANTID}">{$couponlist[index].MERCHANT}</a></td>
				<!--
										   <td class="{$couponlist[index].ZEBRA}">{$couponlist[index].CATEGORY}</td>
				//-->
										   <td class="{$couponlist[index].ZEBRA}">{$couponlist[index].STARTDATE}</td>
										   <td class="{$couponlist[index].ZEBRA}">{$couponlist[index].EXPIREDATE}</td>
										   <td class="{$couponlist[index].ZEBRA}">{$couponlist[index].ADDDATE}</td>
										   <td class="{$couponlist[index].ZEBRA}">{$couponlist[index].AMOUNT}</td>
										   <td class="{$couponlist[index].ZEBRA}"><input type="checkbox" name="FreeShipping[]" value="{$couponlist[index].ID}" {$couponlist[index].IS_FREESHIPPING}></td>
										   <td class="{$couponlist[index].ZEBRA}"><input type="checkbox" name="Featured[]" value="{$couponlist[index].ID}" {$couponlist[index].IS_FEATURED}></td>
										   <td class="{$couponlist[index].ZEBRA}"><input type="checkbox" name="Active[]" value="{$couponlist[index].ID}" {$couponlist[index].IS_ACTIVE}></td>
										   <td class="{$couponlist[index].ZEBRA}"><input type="checkbox" name="Delete[]" value="{$couponlist[index].ID}"></td>
										   <td class="{$couponlist[index].ZEBRA}"><input type="checkbox" name="FREE[]" value="{$couponlist[index].ID}" {$couponlist[index].IS_FREE} disabled="disabled"></td>
										   <td class="{$couponlist[index].ZEBRA}"><input type="button" class="buttonhead" value="Modify" onClick="user_set('record_list','modify',{$couponlist[index].ID})"></td>
										   <td class="{$couponlist[index].ZEBRA}"><input type="button" class="buttonhead" value="Delete" onClick="confirm_delete('record_list','delete',{$couponlist[index].ID},'{$couponlist[index].COUPON}','coupon')"></td>
										</tr>
										{/section}
									 </table>
								  </td>
							   </tr>
							</table>
						 </td>
					  </tr>
					  <tr>
						 <td><b>Total: {$TOTAL} ({$PAGE_TOTAL})</b></td>
					  </tr>
					  <tr>
						 <td><hr width="80%"></td>
					  </tr>
					  <tr>
						 <td class="control" align="center">
							Pages:&nbsp;&nbsp;&nbsp;
							<a href="JavaScript:gopage('record_list','page_list',{$CUR_PAGE}-1)">prev</a>&nbsp;&nbsp;
							<select name="page_list" onChange="JavaScript:gopage('record_list','page_list',this.options[this.selectedIndex].value);">
							   {$PAGE_LIST}
							</select>&nbsp;&nbsp;
							<a href="JavaScript:gopage('record_list','page_list',{$CUR_PAGE}+1)">next</a>
						 </td>
					  </tr>
				   </table>
				</form>
			</td>
         </tr>
         <tr>
            <td>
               <table width="100%" border="0">
                  <tr>
                     <td align="center">
                        <a href="{$LINK_ROOT}index.php">Main page</a>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </center>
</body>
</html>

