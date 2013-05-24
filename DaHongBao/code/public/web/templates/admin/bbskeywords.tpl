<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
</HEAD>
{literal}
<script>
	function goone(value){
		//v=document.getElementById(value).value;
		//alert(v)
		document.form3.done.value=value;
		document.form3.target = "_blank";
		document.form3.submit();
	}
</script>
{/literal}

<BODY>
<a href="keywords.php">category</a>&nbsp;&nbsp;&nbsp;<a href="keywords.php?type=1">bbs</a>

<!-- <FORM name="form1" METHOD=GET ACTION="">
<TABLE border=1>
<TR>
<TD>Merchant ID:<INPUT TYPE="text" NAME="merchantid" value="{$smarty.get.merchantid}"></TD>
</TR>
<TR>
<TD><INPUT TYPE="submit" value="find"></TD>
</TR>
</table></FORM> -->

<FORM name="form3" METHOD=POST ACTION=""><INPUT TYPE="hidden" name="done">
<table>
	<TR><TD>bbscategory</TD><TD></TD><td>keywords</td><td>do</td></tr>
	{section name=loop loop=$categorylist}
	<TR><TD>{$categorylist[loop].fid}</TD><TD></TD><td><TEXTAREA NAME="key_{$categorylist[loop].fid}" ROWS="20" COLS="20">{$categorylist[loop].keywords}</TEXTAREA></td><td><INPUT TYPE="button" value="do" onclick="goone({$categorylist[loop].fid})"></td></tr>
	{/section}
</table>
</FORM>

<!-- <FORM name="form2" METHOD=POST ACTION="" target=_blank>
<TABLE border=1>
<TR>
<TD>merchantid</TD><TD>title</TD><TD>url</TD>
</TR>
<TR>
<TD><INPUT TYPE="text" NAME="merchantid"></TD><TD><INPUT TYPE="text" NAME="title"></TD><TD><INPUT TYPE="text" NAME="url"></TD>
</TR>
<TR>
<TD><INPUT TYPE="submit" name="submit" value="add"></TD>
</TR>
</table></FORM> -->

</BODY>
</HTML>
