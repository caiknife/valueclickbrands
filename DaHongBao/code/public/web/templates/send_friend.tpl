<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<title>���������Ż�ȯ�����ߺ���</title>
<link rel="stylesheet" href="{LINK_ROOT}css/main.css" type="text/css">
<script type="text/javascript" src="{LINK_ROOT}jscript/email.js"></script>
</head>
<body bgcolor="#FFFFFF">
<script type="text/javascript">
<!--
   function checkMailInfo(){
   	  var friend1 = document.getElementById("friend1");
   	  var friend2 = document.getElementById("friend2");
   	  var friend3 = document.getElementById("friend3");
   	  var name = document.getElementById("name");
   	  var email = document.getElementById("email");
   	  var content = document.getElementById("comments");
      if (friend1.value == "" && 
           friend2.value == "" &&
           friend3.value == ""){
         alert("����д����Email��ַ����")
		 friend1.focus();
      }
	  else if(name.value    == "" &&
           email.value   == "") {
		   alert("����д������Ϣ����")
		   email.focus();
	  }
      else {
         Result = true; 
         if (friend1.value != ""){
            if (!check_mail(friend1)){
               Result = false;
			   return Result;
			}
         }
         if (friend2.value != ""){
            if (!check_mail(friend2)){
               Result = false;
			   return Result;
			}
         }
         if (friend3.value != ""){
            if (!check_mail(friend3)){
               Result = false;
			   return Result;
			}
         }
         if (!check_mail(email)){
            Result = false;
			return Result;
		 }
		 if (content.value.length > 255){
		 	alert("����������255���ַ���!");
			content.focus()
			return false;
		 }
         if (Result)
            window.document.forms['form_tell_fried'].submit();
      }
   }
-->
</script>
<center>
<table border="0" cellpadding="1" height="100%" cellspacing="0" class="BorderTable">
 <tr>
  <td width="100%">
   <table width="100%" height="100%" border="0" cellpadding="8" cellspacing="0" class="BackGround">
    <tr>
	 <td bgcolor="#ffffff">
	  <table width="100%">
	   <tr>
	    <td><B class="tellLabelBlack">&nbsp;&nbsp;{MERCHANT_NAME}</B></td>
	   </tr>
	   <tr>
	    <td>
	     <UL>
	      <FONT CLASS="tellLabelBlack">
	      <B>{CDESC}</B><BR>
          �Ż�ȯ���: <FONT CLASS="tellLabel"><B>{CCODE}</B></FONT>&nbsp;&nbsp;<BR>
          ����ʱ�䣺<FONT CLASS="tellLabel"><B>{CDATE}</B></FONT><br>
          ���÷�Χ: <FONT CLASS="tellLabel"><B>{CRESTR}</B></FONT><BR>
          </FONT>
	     </UL>
	    </td>
	   </tr>
	  </table>
	 </td>
	</tr>
	<tr>
	 <td align="center">
	  <table border="0" cellspaceing="0" cellpadding="2">
	   <form name="form_tell_fried" method="POST" action="{LINK_ROOT}send_friend.php">
       <input TYPE="HIDDEN" NAME="cp" VALUE="{CID}">
	   <tr>
	    <th COLSPAN="2" class="tellLabelBlack">������Ż�Email������</th>
	   </tr>
	   <tr>
	    <TD ALIGN="right" VALIGN="top" class="tellLabelBlack">���� 1:</TD>
        <TD CLASS="monospace"><INPUT TYPE="text" id="friend1" NAME="friend1" SIZE="30" style="width:250px" CLASS="txSubscribe"><font color="#FF0000">&nbsp;*</font></TD>
       </tr>
	   <tr>
	    <TD ALIGN="right" VALIGN="top" class="tellLabelBlack">���� 2:</TD>
        <TD CLASS="monospace"><INPUT TYPE="text" id="friend2" NAME="friend2" SIZE="30" style="width:250px" CLASS="txSubscribe"></TD>
       </tr>
	   <tr>
	    <TD ALIGN="right" VALIGN="top" class="tellLabelBlack">���� 3:</TD>
        <TD CLASS="monospace"><INPUT TYPE="text" id="friend3" NAME="friend3" SIZE="30" style="width:250px" CLASS="txSubscribe"></TD>
       </tr>
	   <tr>
	    <TD COLSPAN="2">&nbsp;</TD>
       </TR>
	   <tr valign="top">
	    <TD ALIGN="right" VALIGN="top" class="tellLabelBlack">������˵:</TD>
        <td class="monospace"><textarea name="comments" id="comments" rows="4" cols="52" style="width:250px" wrap="virtal" CLASS="txSubscribe">{SEND_FRIEND_TEXT}</textarea></td>
	   </tr>
	   <TR>
	    <TD COLSPAN="2">&nbsp;</TD>
       </TR>
	   <tr>
	    <TD ALIGN="right" VALIGN="top" class="tellLabelBlack">��������:</TD>
        <TD CLASS="monospace"><INPUT TYPE="text" id="name" NAME="name" SIZE="30" style="width:250px" CLASS="txSubscribe"></TD>
       </tr>
       <tr>
        <TD ALIGN="right" VALIGN="top" class="tellLabelBlack">���Email:</TD>
        <TD CLASS="monospace"><INPUT TYPE="text" id="email" NAME="email" SIZE="30" style="width:250px" CLASS="txSubscribe"><font color="#FF0000">&nbsp;*</font></TD>
       </tr>
       <TR>
        <TD COLSPAN="2">&nbsp;</TD>
       </TR>
       <TR>
        <TD ALIGN="center" COLSPAN="2">
         <INPUT TYPE="hidden" NAME="posted" VALUE="yes">
         <INPUT TYPE="hidden" NAME="p" VALUE="{CID}">
         <INPUT TYPE="button" onClick="JavaScript:checkMailInfo();" name="senddeal" class="normalButton" VALUE="����">&nbsp;&nbsp;
         <INPUT TYPE="button" class="normalButton" value="�ر�" onClick="self.close();">
        </TD>
       </TR></form>
	  </table>
	 </td>
	</tr>
   </table>
  </td>
 </tr>
</table>
</center>
</BODY>
</HTML>