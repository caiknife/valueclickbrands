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
<BODY><FORM METHOD=POST ACTION="" enctype="multipart/form-data"><INPUT TYPE="hidden" name="act" value="change">
<TABLE border=1>
<TR>
<TD>ר��ID:{$topicrow.id}</TD>
</TR>
<TR>
<TD>ר������:<INPUT TYPE="text" NAME="c[title]" value="{$topicrow.title}"></TD>
</TR>
<TR>
<TD>ר��ؼ���(SEO):<INPUT TYPE="text" NAME="c[keywords]" value="{$topicrow.keywords}"></TD>
</TR>
<TR>
<TD>ר������(SEO):<INPUT TYPE="text" NAME="c[description]" value="{$topicrow.description}"></TD>
</TR>
<TR>
<TD>ר����ؼ���(ad):<INPUT TYPE="text" NAME="c[adwords]" value="{$topicrow.adwords}"></TD>
</TR>
<TR>
<TD>ר���ͼ:<INPUT TYPE="file" NAME="image1"></TD>
</TR>

<TR>
<TD>ר�����ģ��ͼƬ1:<INPUT TYPE="file" NAME="image2"></TD>
</TR>

<TR>
<TD>ר�����ģ��ͼƬ2:<INPUT TYPE="file" NAME="image3"></TD>
</TR>

<TR>
<TD>ר�����ģ��ͼƬ3:<INPUT TYPE="file" NAME="image4"></TD>
</TR>
<TR>
<TD>ר�����ģ��ͼƬ4:<INPUT TYPE="file" NAME="image5"></TD>
</TR>

<TR>
<TD>ר�����ģ��ͼƬ5:<INPUT TYPE="file" NAME="image6"></TD>
</TR>
<TR>
<TD>ר�����ģ��ͼƬ6:<INPUT TYPE="file" NAME="image7"></TD>
</TR>
<TR>
<TD>ר����̳���飨for 2��ģ�棩:<INPUT TYPE="text" NAME="c[bbsdetail]" value="{$topicrow.bbsdetail}"></TD>
</TR>
<TR>
<TD>ר����̳����url��for 2��ģ�棩:<INPUT TYPE="text" NAME="c[bbsurl]" value="{$topicrow.bbsurl}"></TD>
</TR>
<TR>
<TD>ר����̳����id��for 2��ģ�棩:<INPUT TYPE="text" NAME="c[bbsid]" value="{$topicrow.bbsid}"></TD>
</TR>


<TR>
<TD>ר��ģ��:<INPUT TYPE="text" NAME="c[templates]" value="{$topicrow.templates}"></TD>
</TR>

<TR>
<TD>ר����:<TEXTAREA NAME="c[topicdetail]" ROWS="20" COLS="50">{$topicrow.topicdetail}</TEXTAREA></TD>
</TR>

<TR>
<TD><INPUT TYPE="button" value="����" onclick="window.location.href='topicindex.php'"><INPUT TYPE="submit" value="ȷ���޸�"></TD>
</TR>
</table></FORM>
</BODY>
</HTML>
