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
						 Seo Standard Management
					  </td>
				   </tr>
				</table>
				<br>
				<form name="record_list" action="{$SCRIPT_NAME}" method="POST">
				   <input type="hidden" name="action" value="">
				   <input type="hidden" name="id" value="">
				   <table width="100%" border="0" cellpadding="0" cellspacing="1" class="border">
					  <tr>
						 <td valign="top">
							<table width="100%" border="0" class="block" cellspacing="0" cellpadding="0">
							   <tr>
								  <td valign="top">
									 <table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr>
										   <td align="center"><input class="buttonhead" type="button" value="new standard" onClick="user_set('record_list','modify',0)"></td>
										   <td>&nbsp;</td>
										   <td>&nbsp;</td>
										   <td>&nbsp;</td>
										   <td>&nbsp;</td>
										   <td>&nbsp;</td>
										</tr>
										<tr> 
										   <td class="listheader" width="10%">ID</td>
										   <td class="listheader" width="10%">Template Name</td>
										   <td class="listheader" width="50%">Description</td>
										   <td class="listheader" width="10%">Editor</td>
										   <td class="listheader" width="10%">&nbsp;</td>
										   <td class="listheader" width="10%">&nbsp;</td>
										</tr>
										{section name=index loop=$standardlist}
										<tr> 
										   <td class="{$standardlist[index].ZEBRA}">{$standardlist[index].ID}</td>
										   <td class="{$standardlist[index].ZEBRA}">{$standardlist[index].Name}</td>
										   <td class="{$standardlist[index].ZEBRA}">{$standardlist[index].Description}</td>
										   <td class="{$standardlist[index].ZEBRA}">{$standardlist[index].Editor}</td>
										   <td class="{$standardlist[index].ZEBRA}"><input type="button" class="buttonhead" value="Modify" onClick="user_set('record_list','modify',{$standardlist[index].ID})"></td>
										   <td class="{$standardlist[index].ZEBRA}"><input type="button" class="buttonhead" value="Delete" onClick="confirm_delete('record_list','delete',{$standardlist[index].ID},'{$standardlist[index].Name}','seo standard')"></td>
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
