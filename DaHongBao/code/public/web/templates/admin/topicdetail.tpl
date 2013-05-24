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
<TR><TD><INPUT TYPE="button" VALUE="增加板块" onclick="javascript:goadd({$smarty.get.id});"></TD></TR>
<TR>
	<TD>板块ID</TD><TD>板块咨询标题</TD><TD>板块优惠券标题</TD><TD>咨询id</TD><TD>优惠券id</TD><TD>板块广告关键字</TD>
</TR>
{section name=loop loop=$topicarray}
<TR>
	<TD>{$topicarray[loop].id}</TD><TD>{$topicarray[loop].contenttitle}</TD><TD>{$topicarray[loop].coupontitle}</TD><TD>{$topicarray[loop].contentid}</TD><TD>{$topicarray[loop].couponid}</TD><TD><a href="topicdelete.php?id={$topicarray[loop].id}">板块删除</a>&nbsp;&nbsp;<a href="topicdetailedit.php?id={$topicarray[loop].id}">板块修改</a>&nbsp;&nbsp;</TD>
</TR>
{/section}
</TABLE>
</BODY>
</HTML>
