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
	function del(value){
		//alert(tid)
		window.location.href="seo_tag.php?act=del&value="+value;
	}
</script>
{/literal}
<BODY>
<TABLE border=1>
<TR>
	<TD>seo keywords</TD><TD>url</TD><TD>num</TD><TD>²Ù×÷</TD>
</TR>
{section name=loop loop=$seoarray}
<TR>
	<TD>{$seoarray[loop].key}</TD><TD>{$seoarray[loop].url}</TD><TD>{$seoarray[loop].num}</TD><TD><a href="javascript:del('{$seoarray[loop].key}')">É¾³ý</a></TD>
</TR>
{/section}
<FORM METHOD=POST ACTION="">
<TR>

	<TD><INPUT TYPE="text" NAME="key"></TD><TD><INPUT TYPE="text" NAME="url"></TD><TD><INPUT TYPE="text" NAME="num"></TD>
	

</TR>
<TR>

	<TD><INPUT TYPE="hidden" name="act" value="add"><INPUT TYPE="submit" value="Ôö¼Ó"></TD>
	

</TR>
</FORM>
</TABLE>
</BODY>
</HTML>
