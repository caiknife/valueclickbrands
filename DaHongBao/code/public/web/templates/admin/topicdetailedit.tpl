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
<TD>板块ID:<INPUT TYPE="hidden" name="c[id]" value="{$topicarray.0.id}"></TD>
</TR>
<TR>
<TD>板块咨询标题:<INPUT TYPE="text" NAME="c[contenttitle]" value="{$topicarray.0.contenttitle}"></TD>
</TR>
<TR>
<TD>板块优惠券标题:<INPUT TYPE="text" NAME="c[coupontitle]" value="{$topicarray.0.coupontitle}"></TD>
</TR>
<TR>
<TD>板块咨询:<INPUT TYPE="text" NAME="c[contentid]" value="{$topicarray.0.contentid}"></TD>
</TR>
<TR>
<TD>板块优惠券:<INPUT TYPE="text" NAME="c[couponid]" value="{$topicarray.0.couponid}"></TD>
</TR>

<TR>
<TD>板块googleads:<INPUT TYPE="text" NAME="c[googlesearch]" value="{$topicarray.0.googlesearch}"></TD>
</TR>

<TR>
<TD>图片1:<image src="/images/topic/{$smarty.get.id}/topic10.gif"><INPUT TYPE="file" NAME="image1"></TD>
</TR>
<TR>
<TD>图片2:<image src="/images/topic/{$smarty.get.id}/topic11.gif"><INPUT TYPE="file" NAME="image2"></TD>
</TR>
<TR>
<TD>图片3:<image src="/images/topic/{$smarty.get.id}/topic12.gif"><INPUT TYPE="file" NAME="image3"></TD>
</TR>
<TR>
<TD>图片4:<image src="/images/topic/{$smarty.get.id}/topic13.gif"><INPUT TYPE="file" NAME="image4"></TD>
</TR>
<TR>
<TD>图片5:<image src="/images/topic/{$smarty.get.id}/topic14.gif"><INPUT TYPE="file" NAME="image5"></TD>
</TR>

<TR>
<TD><INPUT TYPE="button" value="返回" onclick="window.location.href='topicindex.php'"><INPUT TYPE="submit" value="确认修改"></TD>
</TR>
</table></FORM>
</BODY>
</HTML>
