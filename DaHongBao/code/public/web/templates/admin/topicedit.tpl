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
<TD>专题ID:{$topicrow.id}</TD>
</TR>
<TR>
<TD>专题名称:<INPUT TYPE="text" NAME="c[title]" value="{$topicrow.title}"></TD>
</TR>
<TR>
<TD>专题关键字(SEO):<INPUT TYPE="text" NAME="c[keywords]" value="{$topicrow.keywords}"></TD>
</TR>
<TR>
<TD>专题描述(SEO):<INPUT TYPE="text" NAME="c[description]" value="{$topicrow.description}"></TD>
</TR>
<TR>
<TD>专题广告关键字(ad):<INPUT TYPE="text" NAME="c[adwords]" value="{$topicrow.adwords}"></TD>
</TR>
<TR>
<TD>专题大图:<INPUT TYPE="file" NAME="image1"></TD>
</TR>

<TR>
<TD>专题二号模板图片1:<INPUT TYPE="file" NAME="image2"></TD>
</TR>

<TR>
<TD>专题二号模板图片2:<INPUT TYPE="file" NAME="image3"></TD>
</TR>

<TR>
<TD>专题二号模板图片3:<INPUT TYPE="file" NAME="image4"></TD>
</TR>
<TR>
<TD>专题二号模板图片4:<INPUT TYPE="file" NAME="image5"></TD>
</TR>

<TR>
<TD>专题二号模板图片5:<INPUT TYPE="file" NAME="image6"></TD>
</TR>
<TR>
<TD>专题二号模板图片6:<INPUT TYPE="file" NAME="image7"></TD>
</TR>
<TR>
<TD>专题论坛活动简介（for 2号模版）:<INPUT TYPE="text" NAME="c[bbsdetail]" value="{$topicrow.bbsdetail}"></TD>
</TR>
<TR>
<TD>专题论坛活动简介url（for 2号模版）:<INPUT TYPE="text" NAME="c[bbsurl]" value="{$topicrow.bbsurl}"></TD>
</TR>
<TR>
<TD>专题论坛主题id（for 2号模版）:<INPUT TYPE="text" NAME="c[bbsid]" value="{$topicrow.bbsid}"></TD>
</TR>


<TR>
<TD>专题模版:<INPUT TYPE="text" NAME="c[templates]" value="{$topicrow.templates}"></TD>
</TR>

<TR>
<TD>专题简介:<TEXTAREA NAME="c[topicdetail]" ROWS="20" COLS="50">{$topicrow.topicdetail}</TEXTAREA></TD>
</TR>

<TR>
<TD><INPUT TYPE="button" value="返回" onclick="window.location.href='topicindex.php'"><INPUT TYPE="submit" value="确认修改"></TD>
</TR>
</table></FORM>
</BODY>
</HTML>
