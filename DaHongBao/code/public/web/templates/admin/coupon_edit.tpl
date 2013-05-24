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
						 Coupon Editor
					  </td>
				   </tr>
				</table>
				<br>
				<form name="record_edit" action="{$SCRIPT_NAME}" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="action" value="">
				<input type="hidden" name="couponCategories" value="">
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
							<!--<tr>
							   <td class="maintext" align="right">Code</td>
							   <td><input type="text" class="control" style="width : 250px" name="vCode" size="20" value="{$CODE}"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right" valign="top">Upload unique Codes</td>
							   <td>
								  <input type="file" class="control" style="width : 250px" name="vCodeList"><br>
								  Total&nbsp;codes:{$CODECOUNT}&nbsp;Used&nbsp;codes:{$USEDCODECOUNT}&nbsp;&nbsp;&nbsp;<input type="checkbox" name="vCodeClear" value="1">&nbsp;Clear&nbsp;saved<br><br>
								  <input type="hidden" name="MAX_FILE_SIZE" value="8388608">
							   </td>
							</tr>-->
							<tr>
							   <td class="maintext" align="right">URL</td>
							   <td><input type="text" class="control" style="width : 250px" name="vURL" size="20" value="{$URL}"></td>
							</tr>
							<!--<tr>
							   <td colspan="2"><hr width="80%"></td>
							</tr>-->
							<!--<tr>
							   <td class="maintext" align="right" valign="top">Restriction</td>
							   <td>
								  <input type="text" class="control" style="width : 250px" name="vRestr" size="20" value="{$RESTR}"><br>
								  <select name="standardRestr" style="width : 200px" class="control" onChange="JavaScript:putvalue('record_edit','standardRestr','vRestr');">
									 <option value="" SELECTED>-- Select restriction --
									 {$STANDARDRESTR}
								  </select>
								  <a href="{$LINK_ROOT}edit/standard.php?ed=restr" class="control">Change</a>
							   </td>
							</tr>-->
<!--							<tr>
							   <td class="maintext" align="right" valign="top">Long restriction</td>
							   <td>
								  <textarea class="control" style="width : 250px" name="vLongRestr" rows="5">{$LONGRESTR}</textarea><br>
								  <select name="standardLongRestr" style="width : 200px" class="control" onChange="JavaScript:putvalue('record_edit','standardLongRestr','vLongRestr');">
									 <option value="" SELECTED>-- Select restriction --
									 {$STANDARDLONGRESTR}
								  </select>
								  <a href="{$LINK_ROOT}edit/standard.php?ed=longrestr" class="control">Change</a>
							   </td>
							</tr>-->
							<tr>
							   <td colspan="2"><hr width="80%"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right" valign="top">Amount</td>
							   <td>
								  <input type="text" class="control" style="width : 250px" name="vAmount" size="20" value="{$AMOUNT}"><br>
								  <select name="standardAmount" style="width : 200px" class="control" onChange="JavaScript:putvalue('record_edit','standardAmount','vAmount');">
									 <option value="" SELECTED>-- Select amount --
									 {$STANDARDAMOUNT}
								  </select>
								  <a href="{$LINK_ROOT}edit/standard.php?ed=amount" class="control">Change</a>
							   </td>
							</tr>
							<tr>
							   <td colspan="2"><hr width="80%"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right" valign="top">IsCoupon OR IsInfo</td>
							   <td>
								 <INPUT TYPE="radio" NAME="vCouponType" value="1"><INPUT TYPE="radio" NAME="vCouponType" value="0">
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right" valign="top">Start Date</td>
							   <td>
								  <input type="text" class="control" style="width : 80px" name="vStart" size="10" value="{$STARTDATE}">&nbsp;<font class="comment">(MM/DD/YYYY or N/A or O/G)</font>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right" valign="top">Expire Date</td>
							   <td>
								  <input type="text" class="control" style="width : 80px" name="vExpire" size="10" value="{$EXPIREDATE}">&nbsp;<font class="comment">(MM/DD/YYYY or N/A or O/G)</font>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Title</td>
							   <td>
								  <textarea class="control" style="width : 250px" name="vDescript" rows="5">{$DESCRIPT}</textarea><br>
								  <select name="standardDescript" style="width : 200px" class="control" onChange="JavaScript:putvalue('record_edit','standardDescript','vDescript');">
									 <option value="" SELECTED>-- Select Title --
									 {$STANDARDDESCRIPT}
								  </select>
								  <a href="{$LINK_ROOT}edit/standard.php?ed=descript" class="control">Change</a><br>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Detail</td>
							   <td>
								  <textarea class="control" style="width : 250px" name="vDetail" rows="5">{$Detail}</textarea><br>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">City</td>
							   <td>
								  <input type="text" class="control" style="width : 250px" name="vCity" value="{$City}" ><br>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Address</td>
							   <td>
								  <input type="text" class="control" style="width : 250px" name="vAddress" value="{$Address}" ><br>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Phone</td>
							   <td>
								  <input type="text" class="control" style="width : 250px" name="vPhone" value="{$Phone}" ><br>
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Image</td>
							   <td>
								  <input type="file" class="buttonedit" style="width : 250px" name="vImage"><br>
								  <input type="hidden" value="{$imageDownload}" name="hasImage" id="hasImage">
								  {if $imagePath}<img src="{$imagePath}" width="150"><br>{/if}
							   </td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Active</td>
							   <td><input type="checkbox" class="control" name="vActive" size="10" value="1" {$IS_ACTIVE}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Featured</td>
							   <td><input type="checkbox" class="control" name="vFeatured" size="10" value="1" {$IS_FEATURED}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">FreeShipping</td>
							   <td><input type="checkbox" class="control" name="vFreeShipping" size="10" value="1" {$IS_FREESHIPPING}></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">NotFree</td>
							   <td><input type="checkbox" class="control" name="vNotFree" size="10" value="1" {$IS_NOTFREE} disabled="disabled"></td>
							</tr>
							<tr>
							   <td class="maintextrequire" align="right">Merchant</td>
							   <td>
								  <select class="control" name="vMerchant">
									 {$MERCHANTLIST}
								  </select>
							   </td>
							</tr>
							<tr>
							   <td colspan="2"><hr width="80%"></td>
							</tr>
							<tr>
							   <td class="maintext" align="right">Categories</td>
							   <td>
								  <table width="80%" border="0" cellspacing="0" cellpadding="0">
									 <tr>
										<td class="maintext" align="center">Stored in:<td>
										<td class="maintext" align="center">All:<td>
									 </tr>
									 <tr>
										<td align="center">
										   <select name="couponCategory" class="control" style="width:130px" size="5">
											  {$COUPON_CATEGORY}
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
										   <input type="button" class="buttonedit" style="width:100px" name="btAdd" value="Remove" onClick="JavaScript:remove('record_edit','couponCategory')">
										<td>
										<td class="maintext" align="center">
										   <input type="button" class="buttonedit" style="width:100px" name="btAdd" value="&nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;" onClick="JavaScript:add('record_edit','couponCategory','allCategory')">
										<td>
									 </tr>
								  </table>
							   </td>
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
					  <input type="button" class="ctlbutton" value="Save" onClick="JavaScript:list2str('record_edit','couponCategory','couponCategories'); user_set('record_edit','save','{$ID}')">
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
