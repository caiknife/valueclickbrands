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

	function goadd(id){
		//alert(tid)
		window.location.href="topicadd.php?id="+id;

	}
</script>
{/literal}
<BODY>
<TABLE border=1>
<TR><TD><INPUT TYPE="button" VALUE="���Ӱ��" onclick="javascript:goadd({$smarty.get.id});"></TD></TR>
<TR>
	<TD>���ID</TD><TD>�����ѯ����</TD><TD>����Ż�ȯ����</TD><TD>��ѯid</TD><TD>�Ż�ȯid</TD><TD>�����ؼ���</TD>
</TR>
{section name=loop loop=$topicarray}
<TR>
	<TD>{$topicarray[loop].id}</TD><TD>{$topicarray[loop].contenttitle}</TD><TD>{$topicarray[loop].coupontitle}</TD><TD>{$topicarray[loop].contentid}</TD><TD>{$topicarray[loop].couponid}</TD><TD><a href="topicdelete.php?id={$topicarray[loop].id}">���ɾ��</a>&nbsp;&nbsp;<a href="topicdetailedit.php?id={$topicarray[loop].id}">����޸�</a>&nbsp;&nbsp;</TD>
</TR>
{/section}
</TABLE>
</BODY>
</HTML>
