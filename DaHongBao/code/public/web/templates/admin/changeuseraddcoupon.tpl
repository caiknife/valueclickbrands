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
<INPUT TYPE="hidden" name=act value="ok"><INPUT TYPE="hidden" name="id" value="{$couponrow.ID}">�Ż�ȯ����:<INPUT TYPE="text" NAME="c[CouponName]" value="{$couponrow.CouponName}"></TD></tr>
<TR><TD>�ϴ��Ż�ȯͼƬ:<img src="http://images.dahongbao.com/images/useradd/{$couponrow.Picurl}"></TD></tr>
<TR><TD>�Ż�ȯ����:<INPUT TYPE="text" NAME="c[Description]" value="{$couponrow.Description}"></TD></tr>
<TR><TD>�Ż�ȯ�ı�ǩ��<INPUT TYPE="text" NAME="c[TagName]" value="{$couponrow.TagName}"></TD></tr>
<TR><TD>�Ż�ȯ�����<SELECT NAME="c[Category_]">{section name=loop loop=$categoryList}<option value="{$categoryList[loop].Category_}" {if $categoryList[loop].Category_==$couponrow.Category_}selected{/if}>{$categoryList[loop].Name}</option>{/section}</SELECT></TD></tr>
<TR><TD>��ʼʱ��:<INPUT TYPE="text" NAME="c[Start]" value="{$couponrow.Start}"></TD></tr>
<TR><TD>����ʱ��:<INPUT TYPE="text" NAME="c[End]" value="{$couponrow.End}"></TD></tr>
<TR><TD>����:<SELECT NAME="c[CityID]">{section name=loop loop=$citylist}<option value="{$citylist[loop].CityID}" {if $citylist[loop].CityID==$couponrow.CityID}selected{/if}>{$citylist[loop].CityName}</option>{/section}</SELECT></TD></tr>
<TR><TD>�̼�����:<INPUT TYPE="text" NAME="c[MerName]" value="{$couponrow.MerName}"></TD></tr>
<TR><TD>�Ż�ȯ�����:<INPUT TYPE="text" NAME="c[AddUser]" value="{$couponrow.AddUser}"></TD></tr>

<TR><TD><INPUT TYPE="submit" value="ȷ�ϲ�����" onclick="window.location.href='useraddcoupon.php?act=ok&id={$couponrow.ID}'">&nbsp;&nbsp;<INPUT TYPE="submit" value="ɾ��" onclick="window.location.href='useraddcoupon.php?act=delete&id={$couponrow.ID}'"></TD></tr></FORM>
</table>
</BODY>
</HTML>
