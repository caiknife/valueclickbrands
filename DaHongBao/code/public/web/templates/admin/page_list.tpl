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
						 Include pages Management
					  </td>
				   </tr>
				<!--
				   <tr>
					  <td align="center" class="comment">
						 <font color="red">You can't change name of pages and delete pages that mark as 'Special'</font>
					  </td>
				   </tr>
				//-->
				</table>
				<br>
				<form name="record_list" action="{$SCRIPT_NAME}" method="POST">
				   <input type="hidden" name="action" value="">
				   <input type="hidden" name="keylist" value="{$KEY_LIST}">
				   <input type="hidden" name="id" value="">
				   <input type="hidden" name="page" value="{$CUR_PAGE}">
				   <table width="100%" border="0" cellpadding="0" cellspacing="1" class="border">
					  <tr>
						 <td valign="top">
							<table width="100%" border="0" class="block" cellspacing="0" cellpadding="0">
							   <tr>
								  <td valign="top">
									 <table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr>
										   <td align="center"><input class="buttonhead" type="button" value="new Page" onclick="user_set('record_list','modify',0)"></td>
										   <td align="center"><input class="buttonhead" type="button" value="set Special" onClick="Javascript:user_set('record_list','set_special');"></td>
										   <td align="center"><input class="buttonhead" type="button" value="set Static" onClick="Javascript:user_set('record_list','set_static');"></td>
										   <td align="center"><input class="buttonhead" type="button" value="set Full" onClick="Javascript:user_set('record_list','set_full');"></td>
										   <td align="center"><input class="buttonhead" type="button" value="set Script" onClick="Javascript:user_set('record_list','set_script');"></td>
										   <td align="center"><input class="buttonhead" type="button" value="set Sitemap" onClick="Javascript:user_set('record_list','set_sitemap');"></td>
										   <td align="center">&nbsp;</td>
										   <td align="center"><input class="buttonhead" type="button" value="Delete" onClick="Javascript:mass_delete('record_list','set_delete','pages');"></td>
										   <td>&nbsp;</td>
										   <td>&nbsp;</td>
										</tr>
										<tr> 
										   <td class="listheader"><a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=Name">Page Name</a></td>
										   <td class="listheader" width="10%"><a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isSpecial">Special</a></td>
										   <td class="listheader" width="10%"><a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isStatic">Static</a></td>
										   <td class="listheader" width="10%"><a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isFull">isFull</a></td>
										   <td class="listheader" width="10%"><a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isScript">isScript</a></td>
										   <td class="listheader" width="10%"><a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=isSitemap">isSitemap</a></td>
										   <td class="listheader" width="10%"><a class="sortlink" href="{$SCRIPT_NAME}?action=order&order=GroupName">Group</a></td>
										   <td class="listheader" width="10%">&nbsp;</td>
										   <td class="listheader" width="10%">&nbsp;</td>
										   <td class="listheader" width="10%">&nbsp;</td>
										</tr>
										{section name=index loop=$pagelist}
										<tr> 
										   <td class="{$pagelist[index].ZEBRA}">{$pagelist[index].PAGE}</td>
										   <td class="{$pagelist[index].ZEBRA}"><input type="checkbox" name="Special[]" value="{$pagelist[index].ID}" {$pagelist[index].IS_SPECIAL}></td>
										   <td class="{$pagelist[index].ZEBRA}"><input type="checkbox" name="Static[]" value="{$pagelist[index].ID}" {$pagelist[index].IS_STATIC}></td>
										   <td class="{$pagelist[index].ZEBRA}"><input type="checkbox" name="Full[]" value="{$pagelist[index].ID}" {$pagelist[index].IS_FULL}></td>
										   <td class="{$pagelist[index].ZEBRA}"><input type="checkbox" name="Script[]" value="{$pagelist[index].ID}" {$pagelist[index].IS_SCRIPT}></td>
										   <td class="{$pagelist[index].ZEBRA}"><input type="checkbox" name="Sitemap[]" value="{$pagelist[index].ID}" {$pagelist[index].IS_SITEMAP}></td>
										   <td class="{$pagelist[index].ZEBRA}">{$pagelist[index].GROUP}</td>
										   <td class="{$pagelist[index].ZEBRA}"><input type="checkbox" name="Delete[]" value="{$pagelist[index].ID}"></td>
										   <td class="{$pagelist[index].ZEBRA}"><input type="button" class="buttonhead" value="Modify" onclick="user_set('record_list','modify',{$pagelist[index].ID})"></td>
										   <td class="{$pagelist[index].ZEBRA}"><input type="button" class="buttonhead" value="Delete" onclick="confirm_delete('record_list','delete',{$pagelist[index].ID},'{$pagelist[index].PAGE}','page')"></td>
										</tr>
										{/section}
									 </table>
								  </td>
							   </tr>
							</table>
						 </td>
					  </tr>
					  <tr>
						 <td colspan="10"><b>Total: {$TOTAL} ({$PAGE_TOTAL})</b></td>
					  </tr>
					  <tr>
						 <td colspan="10"><hr width="80%"></td>
					  </tr>
					  <tr>
						 <td colspan="10" class="control" align="center">
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
