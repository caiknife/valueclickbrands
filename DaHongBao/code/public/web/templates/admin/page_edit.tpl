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
						 Include page Editor
					  </td>
				   </tr>
				   <tr>
					  <td align="center" class="comment">
						 <font color="red">You can't change name of pages and delete pages that mark as 'Special'</font>
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
							   <td class="maintextrequire" align="right">Name</td>
							   <td>
								  <input type="text" class="control" style="width : 250px" name="vName" size="20" value="{$PAGE}">
								  &nbsp;<font class="comment">(used as filename with '.htm' extension)</font>
							   </td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right">Title</td>
							   <td><input type="text" class="control" style="width : 400px" name="vTitle" size="20" value="{$TITLE}"></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right" valign="top">Content</td>
							   <td><textarea dataformatas="text" class="control" style="width : 400px" name="vContent" rows="20">{$CONTENT}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Special</td>
							   <td><input type="checkbox" class="control" name="vSpecial" size="10" value="1" {$IS_SPECIAL}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Static</td>
							   <td><input type="checkbox" class="control" name="vStatic" size="10" value="1" {$IS_STATIC}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Full</td>
							   <td><input type="checkbox" class="control" name="vFull" size="10" value="1" {$IS_FULL}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Script</td>
							   <td><input type="checkbox" class="control" name="vScript" size="10" value="1" {$IS_SCRIPT}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Sitemap</td>
							   <td><input type="checkbox" class="control" name="vSitemap" size="10" value="1" {$IS_SITEMAP}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Group page</td>
							   <td><input type="text" class="control" style="width : 200px" name="vGroup" size="20" value="{$GROUP}">&nbsp;<input type="text" class="control" style="width : 20px" name="vNumber" size="2" value="{$NUMBER}"></td>
							</tr>
							<tr>
							   <td colspan="2"><hr></td>
							</tr>
							<tr>
							   <td colspan="2" class="maintext" align="center">META TAGS</td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Meta Title</td>
							   <td><textarea class="control" style="width : 250px" name="vMetaTitle" rows="5">{$METATITLE}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Meta KeyWords</td>
							   <td><textarea class="control" style="width : 250px" name="vMetaKeyWords" rows="5">{$METAKEYWORDS}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Meta Descriptions</td>
							   <td><textarea class="control" style="width : 250px" name="vMetaDescriptions" rows="5">{$METADESCRIPTIONS}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Meta Frame</td>
							   <td><textarea class="control" style="width : 250px" name="vMetaFrame" rows="5">{$METAFRAME}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Hidden Words</td>
							   <td><textarea class="control" style="width : 250px" name="vHiddenWords" size="20" rows="20">{$HIDDENWORDS}</textarea></td>
							</tr>
							<tr>
							   <td colspan="2" class="maintext" align="center">Phrase <SPAN class="maintextrequire">&#126;&#124;NAME&#124;&#126; </SPAN> automatic replace with Name Of Merchant</td>
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
				   <td align="center"><hr width="80%">&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">Red - required data</font></td>
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
