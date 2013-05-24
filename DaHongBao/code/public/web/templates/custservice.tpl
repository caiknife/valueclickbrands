<html>
<head>
<title>Customer Service</title>
<script language="JavaScript" src="{LINK_ROOT}jscript/js.js"></script>
<script language="JavaScript">
<!--
   top.MyClose=false;
   function checkRegInfo(){
         if (document.form_send.requiredname.value == "" ||
             document.form_send.requiredcomments.value == "" )
            alert("Please fill all fields!")
         else {
            if (check_mail(document.form_send.requiredemail))
               document.form_send.submit();
         }
   }
//-->
</script>
</head>

<body bgcolor=white>

<table border=0 cellpadding=0 cellspacing=0 width=612>
<tr>
<td><img src="{LINK_ROOT}images/bgim.gif" width="15" height="0" border=0 alt=""></td>
<td>


<table border=0 cellpadding=2 cellspacing=2 width=600>
<tr>
<td><img src="{LINK_ROOT}add/custserv_title.gif" width="275" height="23" border="0" alt=""></td>
</tr>

<tr>
<td><img src="{LINK_ROOT}add/press_hr.gif" width=600 height=1 border=0 alt=""></td>
</tr>

<tr>
<td><img src="{LINK_ROOT}add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>


<tr valign=top>
<td>
    <table border=0 cellpadding=2 cellspacing=2 width=600>
    <tr>
    <td><font face="arial" size="-1" color=black>Welcome to our online customer service center. While we have attempted to answer the most common shopping questions below, please feel free to email your questions and feedback to us so we can make CouponMountain your favorite website at which to save money shopping online.</font></td>
    <td rowspan=2>
        <table border=0 cellpadding=2 cellspacing=0 width=200 bgcolor=9393BC>
        <tr>
        <td align=center>
            <table border=0 cellspacing=0 cellpadding=2 bgcolor=E9E9ED width="95%">
            <tr valign=middle>
            <td bgcolor=9393BC><img src="{LINK_ROOT}add/custserv_merchhelpcenter.gif" alt="" width="197" height="31" border="0"></td>
            </tr>
            <tr>
            <td><font face="arial, verdana" size="-1">Please select a merchant below to:</font></td>
            </tr>
            <tr>
            <td align=center>
                <table border=0 cellpadding=2 cellspacing=0 width="95%">
                <tr valign=top>
                <td width="1%">&#183; </td>
                <td><font face="arial, verdana" size="-1">Find where to enter a coupon code</font></td>
                </tr>
                <tr valign=top>
                <td width="1%">&#183; </td>
                <td><font face="arial, verdana" size="-1">Locate customer service contact information</font></td>
                </tr>
                <tr valign=top>
                <td width="1%">&#183; </td>
                <td><font face="arial, verdana" size="-1">Research shipping, tax and return policies </font></td>
                </tr>
                </table>
            </td>
            </tr>
            <tr>
            <td align=center><FORM NAME="main">
              <select name="merchantList" style="width:143px" class="pulldown" onChange="JavaScript:top.location.href = '{LINK_ROOT}' + this.options[this.selectedIndex].text.replace(/ \([0-9]*\)$/g,'').replace(/ /g,'_') + '/index.html'">
                 <option value="0">Select Merchant</option>
                 {MERCHANT_LIST}
              </select>
            </FORM>
            </td>
            </tr>
            <tr>
            <td><img src="{LINK_ROOT}add/sp.gif" width=1 height=5 border=0 alt=""></td>
            </tr>
            </table>

        </td>
        </tr>
        </table>
    </td>
    </tr>
    <tr>
    <td><font face="arial" size="-1" color=black><img src="{LINK_ROOT}add/custserv_usingcoupons.gif" alt="" width="200" height="23" border="0"><br>CouponMountain works directly with the most popular online merchants to provide our users with unique online coupons, promotions and discounts. These offers can and do change frequently, and occasionally a coupon may not work. Here are some suggestions for using online coupons that will ensure that you have a pleasant shopping experience with CouponMountain.</font></td>
    </tr>
    </table>
</td>
</tr>


<tr>
<td><font face="arial" size="-1" color=black><b>Where do I enter an online coupon?</b><br>
Please use our merchant help center above to find details on where to enter a coupon code for a particular merchant.</font></td>
</tr>

<tr>
<td><font face="arial" size="-1" color=black><b>What should I do if I don't see a credit in my shopping basket?</b><br>
If you have found that a discount does not show up in your shopping basket before you make your order final, please check the following:</font></td>
</tr>

<tr align=center>
<td>
    <table border=0 cellpadding=1 cellspacing=1 width=550>
    <tr valign=top>
    <td width="1%">&#183;</td>
    <td><font face="arial, verdana" size="-1">Has the coupon code been properly entered? Try entering the code once more.</font></td>
    </tr>
    <tr valign=top>
    <td width="1%">&#183;</td>
    <td><font face="arial, verdana" size="-1">The restrictions for the coupon code have not been met. Check the fine print or restrictions to make sure your purchase should be eligible for a discount with the coupon code.</font></td>
    </tr>
    <tr valign=top>
    <td width="1%">&#183;</td>
    <td><font face="arial, verdana" size="-1">The coupon has been withdrawn by the merchant or is no longer valid. Some merchants do not indicate how long their coupon codes are valid for, so if the coupon code does not work, it is safe to assume that the coupon code is no longer valid.</font></td>
    </tr>
    <tr valign=top>
    <td width="1%">&#183;</td>
    <td><font face="arial, verdana" size="-1">The merchant website may not be functioning properly.</font></td>
    </tr>
    </table>
</td>
</tr>

<tr>
<td><font face="arial" size="-1" color=black>Never finalize an order if you don't see a credit from applying a coupon code. If you have already made an order without the discount, we recommend you call the merchant, using the customer service contact information provided in our </font><font face="arial" size="-1" color=5E5E90><b>merchant help center</b></font><font face="arial" size="-1" color=black> or on the merchants site, and give them the coupon code. If they cannot provide you with a discount, you can always cancel the order at no cost. Please <a onclick="top.MyClose=false;" href="mailto:cmfeedback@mezimedia.com">email us</a> if you find a coupon on our site that does not work, and we will remove it from our site immediately.</font></td>
</tr>

<tr>
<td><img src="{LINK_ROOT}add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>

<tr>
<td><font face="arial" size="-1" color=black><img src="{LINK_ROOT}add/custserv_aboutorder.gif" alt="" width="150" height="23" border="0"><br>For questions about an order you placed, please contact the merchant directly with your order number. Individual stores in CouponMountain are responsible for their own fulfillment, shipping, security and other policies. CouponMountain does not take any credit card or order information.</font></td>
</tr>

<tr>
<td><font face="arial" size="-1" color=black>To obtain the customer service email address or phone number of an individual store at CouponMountain, please use our </font><font face="arial" size="-1" color=5E5E90><b>merchant help center</b></font><font face="arial" size="-1" color=black> to find details on their customer service policies. Please note that some merchants do not provide a telephone number for customer service on their website order to reduce costs.</font></td>
</tr>

<tr>
<td><font face="arial" size="-1" color=black>If you have an experience with a merchant (either good or bad) that you'd like to tell us about, we'd like to hear it. Please <a onclick="top.MyClose=false;" href="mailto:cmfeedback@mezimedia.com">email us</a> with the name of the merchant and the problem or service you experienced.</font></td>
</tr>

<tr>
<td><img src="{LINK_ROOT}add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>

<tr>
<td><font face="arial" size="-1" color=black><img src="{LINK_ROOT}add/custserv_returns.gif" alt="" width="65" height="23" border="0"><br>Did you order something that needs to be returned? Or do you want to learn about merchant return policies before you buy? Please use our </font><font face="arial" size="-1" color=5E5E90><b>merchant help center</b></font><font face="arial" size="-1" color=black> above to find details on the particular merchants return policies.</font></td>
</tr>

<tr>
<td><img src="{LINK_ROOT}add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>

<tr>
<td><font face="arial" size="-1" color=black><img src="{LINK_ROOT}add/custserv_ordersoutsideus.gif" alt="" width="215" height="23" border="0"><br>Many merchants accept orders from outside the U.S. Please check with the individual merchants to learn more about their policies and shipping for orders that are sent outside the fifty United States and the District of Columbia ("U.S.").</font></td>
</tr>

<tr>
<td><img src="{LINK_ROOT}add/sp.gif" width=1 height=5 border=0 alt=""></td>
</tr>

<tr>
<td><font face="arial" size="-1" color=black><img src="{LINK_ROOT}add/custserv_yourcomments.gif" alt="" width="215" height="23" border="0"><br>For placed orders, please contact the merchant with your order number. For all other comments and/or inquiries, please fill out the form below or <a onclick="top.MyClose=false;" href="mailto:cmfeedback@mezimedia.com">email us</a> directly.</font></td>
</tr>

<tr align=center>
<td>
<FORM NAME="form_send" METHOD="POST" ACTION="{LINK_ROOT}send_form.php">
<input type='hidden' name="for_brows" value="{LINK_ROOT}custservice.html">
    <table border=0 cellpadding=3 cellspacing=3 width="80%">
    <tr>
    <td width="1%"><font face="arial" size="-1" color=black>Name</font></td>
    <td><input type="text" name="requiredname"></td>
    </tr>
    <tr>
    <td><font face="arial" size="-1" color=black>Email</font></td>
    <td><input type="text" name="requiredemail"></td>
    </tr>
    <tr>
    <td><font face="arial" size="-1" color=black>Comments</font></td>
    <td><textarea name="requiredcomments"></textarea></td>
    </tr>
    <tr>
    <td colspan=2 align=center><input type="reset" value="Clear">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Submit" onclick="checkRegInfo()"></td>
    </tr>
    </table>
</FORM>
</td>
</tr>

</table>


</td>
</tr>
</table>
</body>
</html>