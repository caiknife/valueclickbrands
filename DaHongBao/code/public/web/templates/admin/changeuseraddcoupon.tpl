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
<table border=1><FORM METHOD=POST ACTION="">
<TR><TD>
<INPUT TYPE="hidden" name=act value="ok"><INPUT TYPE="hidden" name="id" value="{$couponrow.ID}">优惠券名字:<INPUT TYPE="text" NAME="c[CouponName]" value="{$couponrow.CouponName}"></TD></tr>
<TR><TD>上传优惠券图片:<img src="http://images.dahongbao.com/images/useradd/{$couponrow.Picurl}"></TD></tr>
<TR><TD>优惠券描述:<INPUT TYPE="text" NAME="c[Description]" value="{$couponrow.Description}"></TD></tr>
<TR><TD>优惠券的标签：<INPUT TYPE="text" NAME="c[TagName]" value="{$couponrow.TagName}"></TD></tr>
<TR><TD>优惠券的类别：<SELECT NAME="c[Category_]">{section name=loop loop=$categoryList}<option value="{$categoryList[loop].Category_}" {if $categoryList[loop].Category_==$couponrow.Category_}selected{/if}>{$categoryList[loop].Name}</option>{/section}</SELECT></TD></tr>
<TR><TD>开始时间:<INPUT TYPE="text" NAME="c[Start]" value="{$couponrow.Start}"></TD></tr>
<TR><TD>结束时间:<INPUT TYPE="text" NAME="c[End]" value="{$couponrow.End}"></TD></tr>
<TR><TD>城市:<SELECT NAME="c[CityID]">{section name=loop loop=$citylist}<option value="{$citylist[loop].CityID}" {if $citylist[loop].CityID==$couponrow.CityID}selected{/if}>{$citylist[loop].CityName}</option>{/section}</SELECT></TD></tr>
<TR><TD>商家名称:<INPUT TYPE="text" NAME="c[MerName]" value="{$couponrow.MerName}"></TD></tr>
<TR><TD>优惠券添加人:<INPUT TYPE="text" NAME="c[AddUser]" value="{$couponrow.AddUser}"></TD></tr>

<TR><TD><INPUT TYPE="submit" value="确认并发布" onclick="window.location.href='useraddcoupon.php?act=ok&id={$couponrow.ID}'">&nbsp;&nbsp;<INPUT TYPE="submit" value="删除" onclick="window.location.href='useraddcoupon.php?act=delete&id={$couponrow.ID}'"></TD></tr></FORM>
</table>
</BODY>
</HTML>
