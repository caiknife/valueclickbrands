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
						 Merchant Management
					  </td>
				   </tr>
				</table>
				<br>
				<form name="record_list" action="{$SCRIPT_NAME}" method="POST">
				   <input type="hidden" name="action" value="">
				   <input type="hidden" name="keylist" value="{$KEY_LIST}">
				   <input type="hidden" name="id" value="">
				   <input type="hidden" name="page" value="{$CUR_PAGE}">
				   <input type="hidden" name="searchname" value="">
				   <table width="100%" border="0" cellpadding="0" cellspacing="1" class="border">
					  <tr>
						 <td class="control" align="left">
						 	&nbsp;<input type="text" name="searchmer" value="" width="50">&nbsp;<input type="button" name="submitsearch" value="查找商家" onClick="JavaScript:window.document.record_list.page.value=1;window.document.record_list.searchname.value=window.document.record_list.searchmer.value;window.document.record_list.submit();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Show Merchant with name starts:&nbsp;&nbsp;&nbsp;
							<select name="filter" onChange="JavaScript:window.document.record_list.page.value=1;window.document.record_list.submit();">
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
										   <td align="center"><input class="buttonhead" type="button" value="new Merchant" onClick="user_set('record_list','modify',0)"></td>
										   <td>&nbsp;</td>
										   <td>&nbsp;</td>
<!--										   <td align="center"><input class="buttonhead" type="button" value="set Bold" onClick="Javascript:user_set('record_list','set_bold');"></td>
										   <td align="center"><input class="buttonhead" type="button" value="set Premium" onClick="Javascript:user_set('record_list','set_premium');"></td>-->
										    <td align="center"><input class="buttonhead" type="button" value="set NotFree" onClick="Javascript:user_set('record_list','set_free');"></td>
										   <td align="center">&nbsp;<!--<input class="buttonhead" type="button" value="set Active" onClick="Javascript:user_set('record_list','set_active');">--></td>
<!--										   <td align="center"><input class="buttonhead" type="button" value="set Popup" onClick="Javascript:user_set('record_list','set_popup');"></td>-->
				<!--
										   <td align="center"><input class="buttonhead" type="button" value="set Popdown" onClick="Javascript:user_set('record_list','set_popdown');"></td>
										   <td align="center"><input class="buttonhead" type="button" value="set Frame" onClick="Javascript:user_set('record_list','set_frame');"></td>
										   <td align="center"><input class="buttonhead" type="button" value="set Top" onClick="Javascript:user_set('record_list','set_top');"></td>
				-->
										   <!--<td align="center"><input class="buttonhead" type="button" value="AdSense Code" onClick="Javascript:user_set('record_list','set_AdSenseCode');"></td>-->
				
										   <td align="center"><input class="buttonhead" type="button" value="Delete" onClick="Javascript:mass_delete('record_list','set_delete','merchants');"></td>
										   <td>&nbsp;</td>
										   <td>&nbsp;</td>
										   <td>&nbsp;</td>
										</tr>
										<tr> 
										   <td class="listheader"><!--<a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=NameURL">-->Merchant<!--</a>--></td>
										   <td class="listheader"><!--<a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=Coupons"-->Coupons<!--</a>--></td>
										   <td class="listheader"><!--<a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=Rating">-->Rating<!--</a>--></td>
										   <!--<td class="listheader"><a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=isBold">Bold</a></td>
										   <td class="listheader"><a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=isPremium">Premium</a></td>-->
										   <td class="listheader"><!--<a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=isFree">-->MerchantType<!--</a>--></td>
										   <td class="listheader"><!--<a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=isActive">-->Active<!--</a>--></td>
										  <!-- <td class="listheader"><a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=isPopup">Popup</a></td>-->
				<!--
										   <td class="listheader"><a class="sortlink" href="{$LINK_ROOT }edit/merchant.php?action=order&order=isPopdown">Popdown</a></td>
										   <td class="listheader"><a class="sortlink" href="{$LINK_ROOT }edit/merchant.php?action=order&order=isFrame">Frame</a></td>
										   <td class="listheader"><a class="sortlink" href="{$LINK_ROOT }edit/merchant.php?action=order&order=isTop">Top</a></td>
				-->
										  <!-- <td class="listheader"><a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=isAdSenseCode">AdSense Code</a></td>-->
										   <td class="listheader">&nbsp;</td>
										   <td class="listheader"><!--<a class="sortlink" href="{$LINK_ROOT}edit/merchant.php?action=order&order=SitemapPriority">-->Sitmap<!--</a>--></td>
										   <td class="listheader">&nbsp;</td>
										   <td class="listheader">&nbsp;</td>
										</tr>
										{section name=index loop=$merchantlist}
										<tr> 
										   <td class="{$merchantlist[index].ZEBRA}">{$merchantlist[index].MERCHANT}</td>
										   <td class="{$merchantlist[index].ZEBRA}"><a href="{$LINK_ROOT}edit/coupon.php?merchant={$merchantlist[index].ID}">{$merchantlist[index].COUPONS}</a></td>
										   <td class="{$merchantlist[index].ZEBRA}">{$merchantlist[index].RATING}</td>
<!--										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Bold[]" value="{$merchantlist[index].ID}" {$IS_BOLD}></td>
										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Premium[]" value="{$merchantlist[index].ID}" {$IS_PREMIUM}></td>-->
										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Free[]" value="{$merchantlist[index].ID}" {$merchantlist[index].IS_FREE}></td>
										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Active[]" value="{$merchantlist[index].ID}" {$merchantlist[index].IS_ACTIVE} disabled="disabled"></td>
<!--										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Popup[]" value="{$merchantlist[index].ID}" {$IS_POPUP}></td>-->
				<!--
										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Popdown[]" value="{$merchantlist[index].ID}" {$IS_POPDOWN}></td>
										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Frame[]" value="{$merchantlist[index].ID}" {$IS_FRAME}></td>
										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Top[]" value="{$merchantlist[index].ID}" {$IS_TOP}></td>
				-->
<!--										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="AdSenseCode[]" value="{$merchantlist[index].ID}" {$merchantlist[index].IS_ADSENSE_CODE}></td>-->
										   <td class="{$merchantlist[index].ZEBRA}"><input type="checkbox" name="Delete[]" value="{$merchantlist[index].ID}"></td>
										   <td class="{$merchantlist[index].ZEBRA}">{$merchantlist[index].SITEMAP_PRIORITY}</td>
										   <td class="{$merchantlist[index].ZEBRA}"><input type="button" class="buttonhead" value="Modify" onClick="user_set('record_list','modify',{$merchantlist[index].ID})"></td>
										   <td class="{$merchantlist[index].ZEBRA}"><input type="button" class="buttonhead" value="Delete" onClick="confirm_delete('record_list','delete',{$merchantlist[index].ID},'{$merchantlist[index].MERCHANTJ}','merchant')"></td>
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
