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
<table border=1>
<TR><TD>�Ż�ȯ����</TD><TD>��ǩ</TD><TD>������Ϣ</TD><TD>���ͼƬ</TD><TD>����</TD></tr>
{section name=loop loop=$getuseraddcouponlist}
	<TR><TD>{$getuseraddcouponlist[loop].CouponName}</tD><TD>{$getuseraddcouponlist[loop].TagName}</TD><TD><a href="useraddcoupondetail.php?id={$getuseraddcouponlist[loop].ID}">{$getuseraddcouponlist[loop].Description}</a></tD><TD><img src="http://images.dahongbao.com/images/useradd/{$getuseraddcouponlist[loop].Picurl}"></TD><TD><a href="useraddcoupon.php?act=change&id={$getuseraddcouponlist[loop].ID}">�༭</a>&nbsp;&nbsp;<a href="useraddcoupon.php?act=delete&id={$getuseraddcouponlist[loop].ID}">ɾ��</a></tD></tr>

{/section}
</table>
</BODY>
</HTML>
