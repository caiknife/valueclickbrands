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
	function kg(tid){
		//alert(tid)
		window.location.href="topicindex.php?value="+tid;

	}
</script>
{/literal}
<BODY><FORM METHOD=POST ACTION=""><INPUT TYPE="hidden" name="act" value="add">
<TABLE border=1>
<TR>
<TD>ר������:<INPUT TYPE="text" NAME="c[title]"></TD>
</TR>
<TR>
<TD>ר��ؼ���(SEO):<INPUT TYPE="text" NAME="c[keywords]"></TD>
</TR>
<TR>
<TD>ר������(SEO):<INPUT TYPE="text" NAME="c[description]"></TD>
</TR>
<TR>
<TD>ר��ģ��:<INPUT TYPE="text" NAME="c[templates]"></TD>
</TR>

<TR>
<TD>ר����:<TEXTAREA NAME="c[topicdetail]" ROWS="20" COLS="50"></TEXTAREA></TD>
</TR>

<TR>
<TD><INPUT TYPE="button" value="����" onclick="window.location.href='topicindex.php'"><INPUT TYPE="submit" value="ȷ���޸�"></TD>
</TR>
</table></FORM>
</BODY>
</HTML>
