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
		window.location.href="topicindex.php?act=g&value="+tid;

	}
</script>
{/literal}
<BODY><FORM METHOD=POST ACTION="" enctype="multipart/form-data"><INPUT TYPE="hidden" name="act" value="change">
<TABLE border=1>
<TR>
<TD><INPUT TYPE="hidden" name="c[topicid]" value="{$smarty.get.id}"></TD>
</TR>
<TR>
<TD>�����ѯ����:<INPUT TYPE="text" NAME="c[contenttitle]"></TD>
</TR>
<TR>
<TD>����Ż�ȯ����:<INPUT TYPE="text" NAME="c[coupontitle]"></TD>
</TR>
<TR>
<TD>�����ѯ:<INPUT TYPE="text" NAME="c[contentid]"></TD>
</TR>
<TR>
<TD>����Ż�ȯ:<INPUT TYPE="text" NAME="c[couponid]"></TD>
</TR>

<TR>
<TD>���googleads:<INPUT TYPE="text" NAME="c[googlesearch]"></TD>
</TR>

<TR>
<TD>ͼƬ1:<INPUT TYPE="file" NAME="image1"></TD>
</TR>
<TR>
<TD>ͼƬ2:<INPUT TYPE="file" NAME="image2"></TD>
</TR>
<TR>
<TD>ͼƬ3:<INPUT TYPE="file" NAME="image3"></TD>
</TR>
<TR>
<TD>ͼƬ4:<INPUT TYPE="file" NAME="image4"></TD>
</TR>
<TR>
<TD>ͼƬ5:<INPUT TYPE="file" NAME="image5"></TD>
</TR>

<TR>
<TD><INPUT TYPE="button" value="����" onclick="window.location.href='topicindex.php'"><INPUT TYPE="submit" value="ȷ���޸�"></TD>
</TR>
</table></FORM>
</BODY>
</HTML>
