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
	function kg(){
		var idstring = "";
	//alert(document.getElementsByName("addtagname").length)
		var name = document.getElementById("addwhat").value;
		for(i=0;i<document.getElementsByName("addtagname").length;i++){
			if(document.getElementsByName("ifadd")[i].checked){
				var exname = document.getElementsByName("addtagname")[i].value;
				if(exname==''){
					document.getElementsByName("addtagname")[i].value=name;
				}else{
					document.getElementsByName("addtagname")[i].value=exname+","+name;
				}
				idstring += document.getElementsByName("addcouponid")[i].value;
				idstring +=",";
			}else{
				document.getElementsByName("addtagname")[i].value="";
			}
			
		}
		document.getElementById("couponidlist").value=idstring;
		
		//alert(idstring);

	}


	function goone(value){
		//v=document.getElementById(value).value;
		//alert(v)
		document.form2.doone.value=value;
		document.form2.target = "_blank";
		document.form2.submit();
	}
</script>
<style>
body {

	font-size: 12px;

}	
</style>
{/literal}

<BODY><!-- <FORM METHOD=POST ACTION=""><INPUT TYPE="hidden" name="act" value="addtag">
<TABLE border=1>
<TR>
<TD>TAG:<INPUT TYPE="text" NAME="tagname"></TD>
</TR>
<TR>
<TD><INPUT TYPE="submit" value="add"></TD>
</TR>
</table></FORM> -->


<FORM name="form1" METHOD=GET ACTION=""><INPUT TYPE="hidden" name="act" value="findcoupon">
<TABLE border=1>
<TR>
<TD>FIND Coupon:<INPUT TYPE="text" NAME="couponname"></TD>
</TR>
<TR>
<TD><INPUT TYPE="submit" name="submit" value="find">  <INPUT TYPE="submit" name="submit" value="top100"></TD>
</TR>
</table></FORM>

<FORM METHOD=POST ACTION="" name="form2" target=_blank><INPUT TYPE="hidden" name="act" value="addtag"><INPUT TYPE="hidden" name="nowurl" value="{$nowurl}"><INPUT TYPE="hidden" name="doone" value="">
<TABLE border=1>
<TR><TD></TD><TD></TD><TD>批量增加:<INPUT TYPE="text" name="addwhat" id="addwhat" value=""></TD></TR>
<TR>
<TD>couponid</TD><TD>name</TD><TD>tagname<INPUT TYPE="button" value="add all" onclick="kg()"><INPUT TYPE="hidden" NAME="couponidlist"></TD><TD>isselect</TD><TD>单独处理</TD>
</TR>
{section name=loop loop=$couponlist}
<TR>
<TD>{$couponlist[loop].Coupon_}<INPUT TYPE="hidden" name="addcouponid" value="{$couponlist[loop].Coupon_}"></TD><TD>{$couponlist[loop].Descript}</TD><TD><INPUT TYPE="text" NAME="addtagname" value="{$couponlist[loop].tagname}"></TD><TD><INPUT TYPE="checkbox" NAME="ifadd" checked></TD><TD><INPUT TYPE="text" NAME="{$couponlist[loop].Coupon_}" value="{$couponlist[loop].tagname}"><INPUT TYPE="button" value="do" onclick="goone({$couponlist[loop].Coupon_});"></TD>
</TR>
{/section}
<TR><TD></TD><TD></TD><TD><INPUT TYPE="submit" value="ADD!"></TD></TR>
</table></FORM>
</BODY>
</HTML>
