<html>
<head>
<title>{$PAGE_TITLE} - Administration Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<link href="{$LINK_ROOT}css/admin.css" rel=stylesheet type=text/css>
<meta content=EN http-equiv=Content-Language>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<script language="JavaScript" src="{$LINK_ROOT}jscript/admin_main.js"></script>
<script language="JavaScript">
{literal}
function setTemplate() {
	if(window.document.forms["record_edit"].merTemplate.value > 0) {
		window.document.forms["record_edit"].vHeadline.disabled = true;
		//window.document.forms["record_edit"].vDescript.disabled = true;
		window.document.forms["record_edit"].vMetaTitle.disabled = true;
		window.document.forms["record_edit"].vMetaKeyWords.disabled = true;
		window.document.forms["record_edit"].vMetaDescriptions.disabled = true;
	} else {
		window.document.forms["record_edit"].vHeadline.disabled = false;
		//window.document.forms["record_edit"].vDescript.disabled = false;
		window.document.forms["record_edit"].vMetaTitle.disabled = false;
		window.document.forms["record_edit"].vMetaKeyWords.disabled = false;
		window.document.forms["record_edit"].vMetaDescriptions.disabled = false;
	}
}
{/literal}
</script>
</head>

<body bgcolor="White" onLoad="javascript:setTemplate();">
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
						 Merchant Editor
					  </td>
				   </tr>
				</table>
				<br>
				<form name="record_edit" action="{$SCRIPT_NAME}" method="POST" enctype="multipart/form-data" >
				<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
				<input type="hidden" name="action" value="">
				<input type="hidden" name="merchantCategories" value="">
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
						<table border="0" cellspacing="0" cellpadding="0">
						   <tr>
							  <td class="maintext" align="right"><FONT color="red">Previous Editor&nbsp;&nbsp;-&nbsp;&nbsp;</FONT></td>
							  <td>
								 {$CUSTOMER_EDIT_OLD}
								 <input type="hidden" name="NewEditor" value="{$CUSTOMER_EDIT}" >
							  </td>
						   </tr>
						</table>
						</td>
					  </tr>
					  <tr>
						 <td>
							<table>
							<tr>
							   <td class="maintextrequire" align="right">Merchant</td>
							   <td><input type="text" class="control" style="width : 250px" name="vName" size="20" value="{$MERCHANT}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">URL</td>
							   <td><input type="text" class="control" style="width : 250px" name="vURL" size="20" value="{$URL}"></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right">Merchant URL name</td>
							   <td><input type="text" class="control" style="width : 250px" name="vNameURL" size="20" value="{$MERCHANTURL}"><br><center><font color=red>(only a-zA-Z0-9 allowed)</font></center><br></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right">Rating</td>
							   <td><input type="text" class="control" style="width : 40px" name="vRating" size="4" value="{$RATING}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Seo Template</td>
							   <td><select name="merTemplate" class="control" style="width : 100px" onChange="javascript:setTemplate();">
								       <OPTION VALUE='0' {if $hasNoTemplate}selected="selected"{/if}>不使用模板</OPTION>
									   {section name=index loop=$merTemplateList}
									   	   <OPTION VALUE='{$merTemplateList[index].Standard_}' {if $merTemplateID == $merTemplateList[index].Standard_}selected="selected"{/if}>{$merTemplateList[index].GroupTag}</OPTION>
									   {/section}
								   </select>
							   </td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right">Headline</td>
							   <td><input type="text" class="control" style="width : 250px" name="vHeadline" size="20" value="{$HEADLINE}">
								  </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Merchant Union</td>
							   <td><select name="merUnion" class="control" style="width : 100px">
									   {section name=ind loop=$merUnionList}
									   	   <OPTION VALUE='{$merUnionList[ind].Union_}' {if $merUnionID == $merUnionList[ind].Union_}selected="selected"{/if}>{$merUnionList[ind].Name}</OPTION>
									   {/section}
								   </select>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Sitemap Priority</td>
							   <td>
								  <select name="vSitemapPriority" class="control" style="width : 50px">
									 {$SITEMAPPRIORITY_LIST}
								  </select>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right" valign="top">Logo</td>
							   <td class="imagestatus">
								  <input type="file" class="buttonedit" style="width : 250px" name="vLogo"><br>
								  Status: <b class="{$PRESENT_LOGO}">{$PRESENT_LOGO}</b>&nbsp;&nbsp;&nbsp;<a onClick="JavaScript:window.open('{$LINK_ROOT}edit/view_image.php?merchant={$ID}&field=Logo','view{$ID}Logo','width=600, height=400, resizable=yes, scrollbars=yes');return false;" href="{$SCRIPT_NAME}">view</a>&nbsp;&nbsp;&nbsp;Delete:&nbsp;
								  <input type="checkbox" class="control" name="deleteLogo" value="1">
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right" valign="top">Old Logo</td>
							   <td class="imagestatus">
								  <input type="file" class="buttonedit" style="width : 250px" name="vOldLogo"><br>
								  Status: <b class="{$PRESENT_OLDLOGO}">{$PRESENT_OLDLOGO}</b>&nbsp;&nbsp;&nbsp;<a onClick="JavaScript:window.open('{$LINK_ROOT}edit/view_image.php?merchant={$ID}&field=OldLogo','view{$ID}OldLogo','width=600, height=400, resizable=yes, scrollbars=yes');return false;" href="{$SCRIPT_NAME}">view</a>&nbsp;&nbsp;&nbsp;Delete:&nbsp;
								  <input type="checkbox" class="control" name="deleteOldLogo" value="1">
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Description</td>
							   <td><textarea class="control" style="width : 250px" name="vDescript" rows="5">{$DESCRIPT}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Name1</td>
							   <td><input class="control" name="vName1" VALUE='{$NAME1}'></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Name2</td>
							   <td><input class="control" name="vName2" VALUE='{$NAME2}'></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Name3</td>
							   <td><input class="control" name="vName3" VALUE='{$NAME3}'></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Name4</td>
							   <td><input class="control" name="vName4" VALUE='{$NAME4}'></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Name5</td>
							   <td><input class="control" name="vName5" VALUE='{$NAME5}'></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Name6</td>
							   <td><input class="control" name="vName6" VALUE='{$NAME6}'></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Bold</td>
							   <td><input type="checkbox" class="control" name="vBold" size="10" value="1" {$IS_BOLD}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Premium</td>
							   <td><input type="checkbox" class="control" name="vPremium" size="10" value="1" {$IS_PREMIUM}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Popup</td>
							   <td><input type="checkbox" class="control" name="vPopup" size="10" value="1" {$IS_POPUP}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Popdown</td>
							   <td><input type="checkbox" class="control" name="vPopdown" size="10" value="1" {$IS_POPDOWN}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Frame</td>
							   <td><input type="checkbox" class="control" name="vFrame" size="10" value="1" {$IS_FRAME}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Top</td>
							   <td><input type="checkbox" class="control" name="vTop" size="10" value="1" {$IS_TOP}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Active</td>
							   <td><input type="checkbox" class="control" name="vActive" size="10" value="1" {$IS_ACTIVE} ></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">AdSense Code</td>
							   <td><input type="checkbox" class="control" name="vAdSenseCode" size="10" value="1" {$IS_ADSENSE_CODE}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">NotFree</td>
							   <td><input type="checkbox" class="control" name="vNotFree" size="10" value="1" {$IS_NOTFREE}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Keywords</td>
							   <td><textarea class="control" style="width : 250px" name="vKeyword" rows="5">{$KEYWORD}</textarea></td>
							</tr>
							
				<!--
							<tr>
							   <td class="maintext" align="right">Type of URL</td>
							   <td>
								  <select class="control" name="vURLType">
									 <option value="0">Select type of Tracking URL
									 {$TYPESELECT}
								  </select>
							   </td>
							</tr>
				//-->
							<tr>
							   <td colspan="2"><hr></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">'WHERE TO ENTER<br> COUPON' text</td>
							   <td><textarea class="control" style="width : 250px" name="vEnterCode" rows="5">{$ENTERCODETEXT}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right" valign="top">'WHERE TO ENTER<br> COUPON' image</td>
							   <td class="imagestatus">
								  <input type="file" class="buttonedit" style="width : 250px" name="vEnterImage"><br>
								  Status: <b class="{$PRESENT_ENTER_IMAGE}">{$PRESENT_ENTER_IMAGE}</b>&nbsp;&nbsp;&nbsp;<a onClick="JavaScript:window.open('{$LINK_ROOT}edit/view_image.php?merchant={$ID}&field=EnterCodeImage','view{$ID}EnterCodeImage','width=600, height=400, resizable=yes, scrollbars=yes');return false;" href="{$SCRIPT_NAME}">view</a>&nbsp;&nbsp;Delete:&nbsp;
								  <input type="checkbox" class="control" name="deleteEnterImage" value="1">
							   </td>
							</tr>
							<tr>
							   <td colspan="2"><hr></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Categories</td>
							   <td>
								  <!--<table width="80%" border="0" cellspacing="0" cellpadding="0">
									 <tr>
										<td class="maintext" align="center">Stored in:<td>
										<td class="maintext" align="center">All:<td>
									 </tr>
									 <tr>
										<td align="center">
										   <select name="merchantCategory" class="control" style="width:130px" size="5">
											  {$MERCHANT_CATEGORY}
										   </select>
										<td>
										<td align="center">
										   <select name="allCategory" class="control" style="width:130px" size="5">
											  {$ALL_CATEGORY}
										   </select>
										<td>
									 </tr>
									 <tr>
										<td class="maintext" align="center">
										   <input type="button" class="buttonedit" style="width:100px" name="btAdd" value="Remove" onClick="JavaScript:remove('record_edit','merchantCategory')">
										<td>
										<td class="maintext" align="center">
										   <input type="button" class="buttonedit" style="width:100px" name="btAdd" value="&nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;" onClick="JavaScript:add('record_edit','merchantCategory','allCategory')">
										<td>
									 </tr>
								  </table>-->
							   </td>
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
							   <td colspan="2" class="maintext" align="center">Phrase <SPAN class="maintextrequire">&#123;Merchant Name&#125; </SPAN> automatic replace with Name Of Merchant</td>
							</tr>
							<tr>
							   <td colspan="2" ><hr></td>
							</tr>
							<tr>
							   <td colspan="2" class="maintext" align="center">MERCHANT DEALS</td>
							</tr>
							<tr>
							   <td colspan="2">
								  <table border="1" cellspacing="2" cellpadding="2">
									 <tr>
										<td class="imagestatus">
										   <center>
											  <B>Image 1</B>&nbsp;
											  <select class="control" name="vImage1Type">
												 <option value="0">None
												 <option value="2" {$IS_LINK1}>Remote
												 <option value="1" {$IS_IMAGE1}>Local
											  </select>
										   </center>
										   <input type="text" class="buttonedit" style="width : 150px" name="vImage1i" size="20" value="{$REMOTE_IMAGE1}"><br>
										   <input type="file" class="buttonedit" style="width : 150px" name="vImage1" size="20"><br>
										   Status: <b class="{$PRESENT_IMAGE1}">{$PRESENT_IMAGE1}</b>&nbsp;
										   <a onClick="JavaScript:window.open('{$LINK_ROOT}edit/view_image.php?merchant={$ID}&field=Image1','view{$ID}Image1','width=600, height=400, resizable=yes, scrollbars=yes');return false;" href="{$SCRIPT_NAME}">view</a><br>
										   Delete:&nbsp;<input type="checkbox" class="control" name="deleteImage1" value="1"><br>
										   URL:&nbsp;<input type="type" class="buttonedit" style="width : 116px" name="vImage1URL" size="20" value="{$IMAGE1URL}"><br>
										</td>
										<td class="imagestatus">
										   <center>
											  <B>Image 2</B>&nbsp;
											  <select class="control" name="vImage2Type">
												 <option value="0">None
												 <option value="2" {$IS_LINK2}>Remote
												 <option value="1" {$IS_IMAGE2}>Local
											  </select>
										   </center>
										   <input type="text" class="buttonedit" style="width : 150px" name="vImage2i" size="20" value="{$REMOTE_IMAGE2}"><br>
										   <input type="file" class="buttonedit" style="width : 150px" name="vImage2" size="20"><br>
										   Status: <b class="{$PRESENT_IMAGE2}">{$PRESENT_IMAGE2}</b>&nbsp;
										   <a onClick="JavaScript:window.open('{$LINK_ROOT}edit/view_image.php?merchant={$ID}&field=Image2','view{$ID}Image2','width=600, height=400, resizable=yes, scrollbars=yes');return false;" href="{$SCRIPT_NAME}">view</a><br>
										   Delete:&nbsp;<input type="checkbox" class="control" name="deleteImage2" value="1"><br>
										   URL:&nbsp;<input type="type" class="buttonedit" style="width : 116px" name="vImage2URL" size="20" value="{$IMAGE2URL}"><br>
										</td>
										<td class="imagestatus">
										   <center>
											  <B>Image 3</B>&nbsp;
											  <select class="control" name="vImage3Type">
												 <option value="0">None
												 <option value="2" {$IS_LINK3}>Remote
												 <option value="1" {$IS_IMAGE3}>Local
											  </select>
										   </center>
										   <input type="text" class="buttonedit" style="width : 150px" name="vImage3i" size="20" value="{$REMOTE_IMAGE3}"><br>
										   <input type="file" class="buttonedit" style="width : 150px" name="vImage3" size="20"><br>
										   Status: <b class="{$PRESENT_IMAGE3}">{$PRESENT_IMAGE3}</b>&nbsp;
										   <a onClick="JavaScript:window.open('{$LINK_ROOT}edit/view_image.php?merchant={$ID}&field=Image3','view{$ID}Image3','width=600, height=400, resizable=yes, scrollbars=yes');return false;" href="{$SCRIPT_NAME}">view</a><br>
										   Delete:&nbsp;<input type="checkbox" class="control" name="deleteImage3" value="1"><br>
										   URL:&nbsp;<input type="type" class="buttonedit" style="width : 116px" name="vImage3URL" size="20" value="{$IMAGE3URL}"><br>
										</td>
									 </tr>
								  </table>
							   </td>
							</tr>
							<tr>
							   <td colspan="2" align="center"><hr></td>
							</tr>
							<tr>
							   <td colspan="2" class="maintext" align="center">CM3 fields</td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Shipping policy</td>
							   <td><textarea class="control" style="width : 250px" name="vShippingPolicy" rows="5">{$SHIPPINGPOLICY}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Shipping policy URL</td>
							   <td><input type="text" class="control" style="width : 250px" name="vShippingPolicyURL" size="4" value="{$SHIPPINGPOLICYURL}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Tax policy</td>
							   <td><textarea class="control" style="width : 250px" name="vTaxPolicy" rows="5">{$TAXPOLICY}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Tax Policy URL</td>
							   <td><input type="text" class="control" style="width : 250px" name="vTaxPolicyURL" size="4" value="{$TAXPOLICYURL}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Payment policy</td>
							   <td><textarea class="control" style="width : 250px" name="vPaymentPolicy" rows="5">{$PAYMENTPOLICY}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Payment Policy URL</td>
							   <td><input type="text" class="control" style="width : 250px" name="vPaymentPolicyURL" size="4" value="{$PAYMENTPOLICYURL}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Return policy</td>
							   <td><textarea class="control" style="width : 250px" name="vReturnPolicy" rows="5">{$RETURNPOLICY}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Return Policy URL</td>
							   <td><input type="text" class="control" style="width : 250px" name="vReturnPolicyURL" size="4" value="{$RETURNPOLICYURL}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Privacy policy</td>
							   <td><input type="text" class="control" style="width : 250px" name="vPrivacyPolicy" size="4" value="{$PRIVACYPOLICY}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Customer Service Phone</td>
							   <td><input type="text" class="control" style="width : 250px" name="vCSPhone" size="4" value="{$CSPHONE}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Customer Service Email</td>
							   <td><input type="text" class="control" style="width : 250px" name="vCSEmail" size="4" value="{$CSEMAIL}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Online gift certificates</td>
							   <td><textarea class="control" style="width : 250px" name="vCertificate" rows="5">{$CERTIFICATE}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Online gift certificates URL</td>
							   <td><input type="text" class="control" style="width : 250px" name="vCertificateURL" size="4" value="{$CERTIFICATEURL}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Ordering a catalog</td>
							   <td><textarea class="control" style="width : 250px" name="vCatalog" rows="5">{$CATALOG}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Store Review</td>
							   <td><textarea class="control" style="width : 250px" name="vStoreReview" rows="5">{$STOREREVIEW}</textarea></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Merchant Short Description</td>
							   <td><input type="text" class="control" style="width : 250px" name="vShortDescr" size="20" value="{$SHORTDESCR}"></td>
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
					  <input type="button" class="ctlbutton" value="Save" onClick="JavaScript: user_set('record_edit','save','{$ID}')">
					  
					  <input type="button" class="ctlbutton" value="Cancel" onClick="JavaScript:user_set('record_edit','cancel','{$ID}')" >
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
