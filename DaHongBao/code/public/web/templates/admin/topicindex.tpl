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
	function addnew(){
		window.location.href="addtopic.php";
	}
</script>
{/literal}
<BODY>
<TABLE border=1>
<TR><TD><INPUT TYPE="button" value="�����ר��" onclick="addnew();"></TD></TR>
<TR>
	<TD>ר��ID</TD><TD>ר������</TD><TD>�Ƿ��(1:�� 0:�ر�)</TD><TD>ר��ģ��</TD><TD>����</TD>
</TR>
{section name=loop loop=$topicarray}
<TR>
	<TD>{$topicarray[loop].id}</TD><TD>{$topicarray[loop].title}</TD><TD>{$topicarray[loop].isactive}</TD><TD>{$topicarray[loop].templates}</TD><TD><INPUT TYPE="button" value="ר�⿪��" onclick="kg({$topicarray[loop].id})">&nbsp;&nbsp;<a href="topicedit.php?id={$topicarray[loop].id}">ר���޸�</a>&nbsp;&nbsp;<a href="topicdetail.php?id={$topicarray[loop].id}">����ר��</a></TD>
</TR>
{/section}
</TABLE>
</BODY>
</HTML>
