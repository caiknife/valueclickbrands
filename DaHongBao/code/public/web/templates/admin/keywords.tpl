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
		document.form3.doone.value=value;
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

<FORM name="form3" METHOD=POST ACTION=""><INPUT TYPE="hidden" name="doone">
<table>
	<TR><TD>categoryname</TD><TD></TD><td>keywords</td><td>do</td></tr>
	{section name=loop loop=$categorylist}
	<TR><TD>{$categorylist[loop].Name}</TD><TD></TD><td><INPUT TYPE="text" NAME="key_{$categorylist[loop].Category_}" value="{$categorylist[loop].Keywords}"></td><td><INPUT TYPE="button" value="do" onclick="goone({$categorylist[loop].Category_})"></td></tr>
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
