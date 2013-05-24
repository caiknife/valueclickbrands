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
						 Seo Standard Editor
					  </td>
				   </tr>
				   
				</table>
				<br>
				<form name="record_edit" action="{$SCRIPT_NAME}" method="POST">
				<input type="hidden" name="action" value="">
				<input type="hidden" name="id" value="{$ID}">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				   <td class="error">
					  {$ERROR_MESSAGE}
				   </td>
				</tr>
				<tr>
				   <td>&nbsp;</td>
				</tr>
				<tr>
				   <td align="center">
					  <table border="0" cellspacing="2" cellpadding="3" bordercolor="#5898B0">
					  <tr>
						 <td>
							<table>
							<tr>
							   <td class="maintextrequire" align="right">Standard Name</td>
							   <td>
								  <input type="text" class="control" style="width : 250px" name="vName" size="20" value="{$Name}">
								  &nbsp;
							   </td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right">Template Description</td>
							   <td><textarea dataformatas="text" class="control" style="width : 400px" name="vDescription" rows="5">{$Description}</textarea></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right" valign="top">Meta Title</td>
							   <td><textarea dataformatas="text" class="control" style="width : 400px" name="vMetaTitle" rows="5">{$MetaTitle}</textarea></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right" valign="top">Meta Description</td>
							   <td><textarea dataformatas="text" class="control" style="width : 400px" name="vMetaDescription" rows="15">{$MetaDescription}</textarea></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right" valign="top">Meta Keywords</td>
							   <td><textarea dataformatas="text" class="control" style="width : 400px" name="vMetaKeywords" rows="5">{$MetaKeywords}</textarea></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right" valign="top">Navigation</td>
							   <td><textarea dataformatas="text" class="control" style="width : 400px" name="vNavigation" rows="5">{$Navigation}</textarea></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right" valign="top">Merchant Description</td>
							   <td><textarea dataformatas="text" class="control" style="width : 400px" name="vMerDescription" rows="15">{$MerDescription}</textarea></td>
							</tr>
							<tr>
							   <td colspan="2"><hr></td>
							</tr>
							</table>
						 </td>
					  </tr>
					  </table>
				   </td>
				</tr>
				<tr>
				   <td>&nbsp;</td>
				</tr>
				<tr>
				   <td align="center">
					  <input type="button" class="ctlbutton" value="Save" onClick="JavaScript:user_set('record_edit','save','{$ID}')">
					  <input type="button" class="ctlbutton" onClick="JavaScript:user_set('record_edit','cancel','{$ID}')" value="Cancel">
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
