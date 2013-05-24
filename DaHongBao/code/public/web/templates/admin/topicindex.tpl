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
<TR><TD><INPUT TYPE="button" value="添加新专题" onclick="addnew();"></TD></TR>
<TR>
	<TD>专题ID</TD><TD>专题名称</TD><TD>是否打开(1:打开 0:关闭)</TD><TD>专题模版</TD><TD>操作</TD>
</TR>
{section name=loop loop=$topicarray}
<TR>
	<TD>{$topicarray[loop].id}</TD><TD>{$topicarray[loop].title}</TD><TD>{$topicarray[loop].isactive}</TD><TD>{$topicarray[loop].templates}</TD><TD><INPUT TYPE="button" value="专题开关" onclick="kg({$topicarray[loop].id})">&nbsp;&nbsp;<a href="topicedit.php?id={$topicarray[loop].id}">专题修改</a>&nbsp;&nbsp;<a href="topicdetail.php?id={$topicarray[loop].id}">进入专题</a></TD>
</TR>
{/section}
</TABLE>
</BODY>
</HTML>
