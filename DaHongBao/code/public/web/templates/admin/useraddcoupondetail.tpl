<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> New Document </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
</HEAD>

<BODY>
<table>
<FORM METHOD=POST ACTION="">


	<TR><TD>商家名称:<INPUT TYPE="text" NAME="" value="{$getuseraddcoupon.MerName}"><INPUT TYPE="hidden" name="id" value="{$getuseraddcoupon.ID}"></tD></tr>
	<TR><TD>优惠券描述:<INPUT TYPE="text" NAME="" value="{$getuseraddcoupon.Description}"></td></tr>
	<TR><TD>开始时间:<INPUT TYPE="text" NAME="" value="{$getuseraddcoupon.Start}"></td></tr>
	<TR><TD>结束时间:<INPUT TYPE="text" NAME="" value="{$getuseraddcoupon.End}"></td></tr>
	<TR><TD>图片:<img src="http://dahongbaodev.sh.mezimedia.com/images/useradd/{$getuseraddcoupon.Picurl}"></td></tr>
	<TR><TD>网站url:<INPUT TYPE="text" NAME="" value="{$getuseraddcoupon.Url}"></td></tr>
	<TR><TD>地址:<INPUT TYPE="text" NAME="" value="{$getuseraddcoupon.Address}"></td></tr>
	<TR><TD><INPUT TYPE="submit" value="删除" name="submit">&nbsp;&nbsp;<INPUT TYPE="submit" value="确认通过" name="submit"></td></tr>
</FORM>

</table>
</BODY>
</HTML>
