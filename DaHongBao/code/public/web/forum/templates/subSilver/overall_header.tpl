<html>
<head>
<title>{PAGE_TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Language" content="EN">
{KEYWORDS}
{DESCRIPTION_H}
<meta name="copyright" content="www.couponmountain.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<link href="/css/main.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/jscript/js.js"></script>
<script language="JavaScript">
<!--
   isLoadedPage = false;
   function init(){
      MM_preloadImages('/images/category_background.gif','/images/category_background_active.gif','/images/category_arrow.gif');
      isLoadedPage = true;
   }
//-->
</script>
<base target="_top">
<!-- link rel="stylesheet" href="templates/subSilver/{T_HEAD_STYLESHEET}" type="text/css" -->
<style type="text/css">
<!--
/*
  The original subSilver Theme for phpBB version 2+
  Created by subBlue design
  http://www.subBlue.com

  NOTE: These CSS definitions are stored within the main page body so that you can use the phpBB2
  theme administration centre. When you have finalised your style you could cut the final CSS code
  and place it in an external file, deleting this section to save bandwidth.
*/

/* General page style. The scroll bar colours only visible in IE5.5+ */
body { 
   background-color: {T_BODY_BGCOLOR};
   scrollbar-face-color: {T_TR_COLOR2};
   scrollbar-highlight-color: {T_TD_COLOR2};
   scrollbar-shadow-color: {T_TR_COLOR2};
   scrollbar-3dlight-color: {T_TR_COLOR3};
   scrollbar-arrow-color:  {T_BODY_LINK};
   scrollbar-track-color: {T_TR_COLOR1};
   scrollbar-darkshadow-color: {T_TH_COLOR1};
}

/* General font families for common tags */
font,th,td,p { font-family: {T_FONTFACE1} }
a:link,a:active,a:visited { color : {T_BODY_LINK}; }
a:hover     { text-decoration: underline; color : {T_BODY_HLINK}; }
hr { height: 0px; border: solid {T_TR_COLOR3} 0px; border-top-width: 1px;}

/* This is the border line & background colour round the entire page */
.bodyline   { background-color: {T_TD_COLOR2}; border: 1px {T_TH_COLOR1} solid; }

/* This is the outline round the main forum tables */
.forumline  { background-color: {T_TD_COLOR2}; border: 2px {T_TH_COLOR2} solid; }

/* Main table cell colours and backgrounds */
td.row1  { background-color: {T_TR_COLOR1}; }
td.row2  { background-color: {T_TR_COLOR2}; }
td.row3  { background-color: {T_TR_COLOR3}; }

/*
  This is for the table cell above the Topics, Post & Last posts on the index.php page
  By default this is the fading out gradiated silver background.
  However, you could replace this with a bitmap specific for each forum
*/
td.rowpic {
      background-color: {T_TD_COLOR2};
      background-image: url(templates/subSilver/images/{T_TH_CLASS3});
      background-repeat: repeat-y;
}

/* Header cells - the blue and silver gradient backgrounds */
th {
   color: {T_FONTCOLOR3}; font-size: {T_FONTSIZE2}px; font-weight : bold; 
   background-color: {T_BODY_LINK}; height: 25px;
   background-image: url(templates/subSilver/images/{T_TH_CLASS2});
}

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
         background-image: url(templates/subSilver/images/{T_TH_CLASS1});
         background-color:{T_TR_COLOR3}; border: {T_TH_COLOR3}; border-style: solid; height: 28px;
}

/*
  Setting additional nice inner borders for the main table cells.
  The names indicate which sides the border will be on.
  Don't worry if you don't understand this, just ignore it :-)
*/
td.cat,td.catHead,td.catBottom {
   height: 29px;
   border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
   font-weight: bold; border: {T_TD_COLOR2}; border-style: solid; height: 28px;
}
td.row3Right,td.spaceRow {
   background-color: {T_TR_COLOR3}; border: {T_TH_COLOR3}; border-style: solid;
}

th.thHead,td.catHead { font-size: {T_FONTSIZE3}px; border-width: 1px 1px 0px 1px; }
th.thSides,td.catSides,td.spaceRow   { border-width: 0px 1px 0px 1px; }
th.thRight,td.catRight,td.row3Right  { border-width: 0px 1px 0px 0px; }
th.thLeft,td.catLeft   { border-width: 0px 0px 0px 1px; }
th.thBottom,td.catBottom  { border-width: 0px 1px 1px 1px; }
th.thTop  { border-width: 1px 0px 0px 0px; }
th.thCornerL { border-width: 1px 0px 0px 1px; }
th.thCornerR { border-width: 1px 1px 0px 0px; }

/* The largest text used in the index page title and toptic title etc. */
.maintitle  {
   font-weight: bold; font-size: 22px; font-family: "{T_FONTFACE2}",{T_FONTFACE1};
   text-decoration: none; line-height : 120%; color : {T_BODY_TEXT};
}

/* General text */
.gen { font-size : {T_FONTSIZE3}px; }
.genmed { font-size : {T_FONTSIZE2}px; }
.gensmall { font-size : {T_FONTSIZE1}px; }
.gen,.genmed,.gensmall { color : {T_BODY_TEXT}; }
a.gen,a.genmed,a.gensmall { color: {T_BODY_LINK}; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover  { color: {T_BODY_HLINK}; text-decoration: underline; }

/* The register, login, search etc links at the top of the page */
.mainmenu      { font-size : {T_FONTSIZE2}px; color : {T_BODY_TEXT} }
a.mainmenu     { text-decoration: none; color : {T_BODY_LINK};  }
a.mainmenu:hover{ text-decoration: underline; color : {T_BODY_HLINK}; }

/* Forum category titles */
.cattitle      { font-weight: bold; font-size: {T_FONTSIZE3}px ; letter-spacing: 1px; color : {T_BODY_LINK}}
a.cattitle     { text-decoration: none; color : {T_BODY_LINK}; }
a.cattitle:hover{ text-decoration: underline; }

/* Forum title: Text and link to the forums used in: index.php */
.forumlink     { font-weight: bold; font-size: {T_FONTSIZE3}px; color : {T_BODY_LINK}; }
a.forumlink    { text-decoration: none; color : {T_BODY_LINK}; }
a.forumlink:hover{ text-decoration: underline; color : {T_BODY_HLINK}; }

/* Used for the navigation text, (Page 1,2,3 etc) and the navigation bar when in a forum */
.nav        { font-weight: bold; font-size: {T_FONTSIZE2}px; color : {T_BODY_TEXT};}
a.nav       { text-decoration: none; color : {T_BODY_LINK}; }
a.nav:hover    { text-decoration: underline; }

/* titles for the topics: could specify viewed link colour too */
.topictitle,h1,h2 { font-weight: bold; font-size: {T_FONTSIZE2}px; color : {T_BODY_TEXT}; }
a.topictitle:link   { text-decoration: none; color : {T_BODY_LINK}; }
a.topictitle:visited { text-decoration: none; color : {T_BODY_VLINK}; }
a.topictitle:hover   { text-decoration: underline; color : {T_BODY_HLINK}; }

/* Name of poster in viewmsg.php and viewtopic.php and other places */
.name       { font-size : {T_FONTSIZE2}px; color : {T_BODY_TEXT};}

/* Location, number of posts, post date etc */
.postdetails      { font-size : {T_FONTSIZE1}px; color : {T_BODY_TEXT}; }

/* The content of the posts (body of text) */
.postbody { font-size : {T_FONTSIZE3}px; line-height: 18px}
a.postlink:link   { text-decoration: none; color : {T_BODY_LINK} }
a.postlink:visited { text-decoration: none; color : {T_BODY_VLINK}; }
a.postlink:hover { text-decoration: underline; color : {T_BODY_HLINK}}

/* Quote & Code blocks */
.code { 
   font-family: {T_FONTFACE3}; font-size: {T_FONTSIZE2}px; color: {T_FONTCOLOR2};
   background-color: {T_TD_COLOR1}; border: {T_TR_COLOR3}; border-style: solid;
   border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.quote {
   font-family: {T_FONTFACE1}; font-size: {T_FONTSIZE2}px; color: {T_FONTCOLOR1}; line-height: 125%;
   background-color: {T_TD_COLOR1}; border: {T_TR_COLOR3}; border-style: solid;
   border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

/* Copyright and bottom info */
.copyright     { font-size: {T_FONTSIZE1}px; font-family: {T_FONTFACE1}; color: {T_FONTCOLOR1}; letter-spacing: -1px;}
a.copyright    { color: {T_FONTCOLOR1}; text-decoration: none;}
a.copyright:hover { color: {T_BODY_TEXT}; text-decoration: underline;}

/* Form elements */
input,textarea, select {
   color : {T_BODY_TEXT};
   font: normal {T_FONTSIZE2}px {T_FONTFACE1};
   border-color : {T_BODY_TEXT};
}

/* The text input fields background colour */
input.post, textarea.post, select {
   background-color : {T_TD_COLOR2};
}

input { text-indent : 2px; }

/* The buttons used for bbCode styling in message post */
input.button {
   background-color : {T_TR_COLOR1};
   color : {T_BODY_TEXT};
   font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1};
}

/* The main submit button option */
input.mainoption {
   background-color : {T_TD_COLOR1};
   font-weight : bold;
}

/* None-bold submit button */
input.liteoption {
   background-color : {T_TD_COLOR1};
   font-weight : normal;
}

/* This is the line in the posting page which shows the rollover
  help line. This is actually a text box, but if set to be the same
  colour as the background no one will know ;)
*/
.helpline { background-color: {T_TR_COLOR2}; border-style: none; }

/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
@import url("templates/subSilver/formIE.css"); 
-->
</style>
<!-- BEGIN switch_enable_pm_popup -->
<script language="Javascript" type="text/javascript">
<!--
   if ( {PRIVATE_MESSAGE_NEW_FLAG} )
   {
      window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
   }
//-->
</script>
<!-- END switch_enable_pm_popup -->
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" onLoad="init();">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
         <td bgcolor="#6666CC">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td width="1%" align="center" valign="middle"><a onclick="top.MyClose=false;" href="/"><img border="0" src="/images/logo.gif"></a></td>
                  <td width="100%" allign="right">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                           <td valign="middle" align="right">
                              <table width="350" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                    <td align="right" valign="middle">
                                       <table border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                             <td><a onclick="top.MyClose=false;" href="/all_merchant.html"><img border="0" src="/images/smwCM.gif"></a></td>
                                             <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                             <td><img src="/images/point_arrow.gif"></td>
                                             <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                             <td><a onclick="top.MyClose=false;" href="/all_merchant.html"><img border="0" src="/images/cm.gif"></a></td>
                                             <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                             <td><img src="/images/point_arrow.gif"></td>
                                             <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                             <td><a onclick="top.MyClose=false;" href="/all_merchant.html"><img border="0" src="/images/sfo.gif"></a></td>
                                             <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                             <td><img src="/images/point_arrow.gif"></td>
                                             <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                             <td><a onclick="top.MyClose=false;" href="/all_merchant.html"><img border="0" src="/images/ss.gif"></a></td>
                                             <td><img width="10" height="5" src="/images/bgim.gif"></td>
                                          </tr>
                                       </table>
                                    </td>
                                 </tr>
                              </table>
                           </td>
                           <td align="left">
                              <table border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                    <td width="1%"><img src="/images/top_vpoint.gif" width="1" height="70"></td>
                                    <td width="1%"><img src="/images/bgim.gif" width="10" height="70"></td>
                                    <td valign="middle">
                                       <a class="booklink" onclick="top.MyClose=false;" href="JavaScript:add_bookmark();">Bookmark&nbsp;CouponMountain</a><br>
                                       <a class="booklink" onclick="top.MyClose=false;" href="JavaScript:window.open('/Take_a_Tour.html','take_tour','width=510,height=580,scrolbars=yes,resizable=yes');void(0);">First&nbsp;time&nbsp;here?&nbsp;Take&nbsp;a&nbsp;tour</a>
                                    </td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td bgcolor="#000000"><img src="/images/bgim.gif" width="153" height="1"></td>
                  <td colspan="2" bgcolor="#000000"><img src="/images/bgim.gif" width="1" height="1"></td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
         <td bgcolor="#FFCC00" align="right">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td width="*"><img src="/images/bgim.gif" width="1" height="1"></td>
                  <td width="1" bgcolor="#A3A2A2"><img src="/images/bgim.gif" width="1" height="1"></td>
                  <td width="1%" bgcolor="{HOTCACTIVE}">&nbsp;<a onclick="top.MyClose=false;" href="/hot_coupon.html" class="couponlink">Most&nbsp;Popular&nbsp;Coupons</a>&nbsp;</td>
                  <td width="1" bgcolor="#A3A2A2"><img src="/images/bgim.gif" width="1" height="1"></td>
                  <td width="1%" bgcolor="{NEWCACTIVE}">&nbsp;<a onclick="top.MyClose=false;" href="/new_coupon.html" class="couponlink">New&nbsp;Coupons</a>&nbsp;</td>
                  <td width="1" bgcolor="#A3A2A2"><img src="/images/bgim.gif" width="1" height="1"></td>
                  <td width="1%" bgcolor="{EXPCACTIVE}">&nbsp;<a onclick="top.MyClose=false;" href="/expire_coupon.html" class="couponlink">Expiring&nbsp;Coupons</a>&nbsp;</td>
                  <td width="1" bgcolor="#A3A2A2"><img src="/images/bgim.gif" width="1" height="1"></td>
                  <td width="1%" bgcolor="{FRSCACTIVE}">&nbsp;<a class="couponlink" onclick="top.MyClose=false;" href="/freeshipping_coupon.html" class="couponlink">Free&nbsp;Shipping</a>&nbsp;</td>
                  <td width="1" bgcolor="#A3A2A2"><img src="/images/bgim.gif" width="1" height="1"></td>
               </tr>
            </table>
         </td>
      </tr>
      <tr>
         <td bgcolor="#A3A2A2"><img width="1" height="1" src="/images/bgim.gif"></td>
      </tr>
   </table>
   <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
         <td width="10%" valign="top">
            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td valign="top">
                     <table width="153" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFCC00">
                        <tr>
                           <td bgcolor="#FFDF5E">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                    <td width="1%"><img width="5" height="3" src="/images/bgim.gif"></td>
                                    <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                 </tr>
                                 <tr>
                                    <td width="1%"><img width="5" height="5" src="/images/bgim.gif"></td>
                                    <td align="center" class="display"><b>Display savings for:</b></td>
                                 </tr>
                                 <tr>
                                    <td width="1%"><img width="5" height="3" src="/images/bgim.gif"></td>
                                    <td><img width="5" height="5" src="/images/bgim.gif"></td>
                                 </tr>
                                 <tr>
                                    <td width="1%"><img width="5" height="5" src="/images/bgim.gif"></td>
                                    <td>
                                       <select name="merchantList" style="width:143px" class="pulldown" onChange="JavaScript:top.location.href = '/' + this.options[this.selectedIndex].text.replace(/ \([0-9]*\)$/g,'').replace(/ /g,'_') + '/index.html'">
                                          <option value="0">Select Merchant</option>
                                          <OPTION VALUE="370" class="nullcoupon">Amazon (0)</OPTION><OPTION VALUE="372" class="nullcoupon">Dell (0)</OPTION><OPTION VALUE="368">m1 (1)</OPTION><OPTION VALUE="369">m2 (1)</OPTION>
                                       </select>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td width="1%"><img width="5" height="3" src="/images/bgim.gif"></td>
                                    <td><img width="5" height="10" src="/images/bgim.gif"></td>
                                 </tr>
                                 <tr>
                                    <td width="1%"><img width="5" height="5" src="/images/bgim.gif"></td>
                                    <td align="center"><a onclick="top.MyClose=false;" href="/all_merchant.html" class="display">See&nbsp;All&nbsp;Merchants</a></td>
                                 </tr>
                                 <tr>
                                    <td width="1%"><img width="5" height="3" src="/images/bgim.gif"></td>
                                    <td><img width="5" height="10" src="/images/bgim.gif"></td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td><img src="/images/left_top_spacer.gif" height="2" width="152"></td>
                        </tr>
                        <tr>
                           <td align="left">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                 <tr>
                                    <td colspan="3"><img width="152" height="10" src="/images/bgim.gif"></td>
                                 </tr>

                                 <tr>
                                    <td width="10px" height="18px" id="cat1_51"  background="/images/category_background.gif"><div style="width:10px;height=18px;" onMouseOver="mouseover('/',51);void(0);" onMouseOut="mouseout('/',51,'off');void(0);" onMouseUp="mouseclick('/','/test1.html',51);void(0);"><img border="0" width="5" height="18" src="/images/bgim.gif" alt="test1"></div></td>
                                    <td valign="bottom" width="125px" height="18px" id="cat2_51" background="/images/category_background.gif" class="leftCategory"><div style="width:125px;height=16px;" onMouseOver="mouseover('/',51);void(0);" onMouseOut="mouseout('/',51,'off');void(0);" onMouseUp="mouseclick('/','/test1.html',51);void(0);"><a onclick="top.MyClose=false;" href="/test1.html" class="leftCategory">test1</a></div></td>
                                    <td width="17px" height="18px" id="cat3_51" background="/images/category_background.gif"><div style="width:17px;height=18px;" onMouseOver="mouseover('/',51);void(0);" onMouseOut="mouseout('/',51,'off');void(0);" onMouseUp="mouseclick('/','/test1.html',51);void(0);"><img name="cat_arrow_51" border="0" width="17" height="18" src="/images/category_background.gif" alt="test1"></div></td>
                                 </tr>

                                 <tr>
                                    <td width="10px" height="18px" id="cat1_52"  background="/images/category_background.gif"><div style="width:10px;height=18px;" onMouseOver="mouseover('/',52);void(0);" onMouseOut="mouseout('/',52,'off');void(0);" onMouseUp="mouseclick('/','/test2.html',52);void(0);"><img border="0" width="5" height="18" src="/images/bgim.gif" alt="test2"></div></td>
                                    <td valign="bottom" width="125px" height="18px" id="cat2_52" background="/images/category_background.gif" class="leftCategory"><div style="width:125px;height=16px;" onMouseOver="mouseover('/',52);void(0);" onMouseOut="mouseout('/',52,'off');void(0);" onMouseUp="mouseclick('/','/test2.html',52);void(0);"><a onclick="top.MyClose=false;" href="/test2.html" class="leftCategory">test2</a></div></td>
                                    <td width="17px" height="18px" id="cat3_52" background="/images/category_background.gif"><div style="width:17px;height=18px;" onMouseOver="mouseover('/',52);void(0);" onMouseOut="mouseout('/',52,'off');void(0);" onMouseUp="mouseclick('/','/test2.html',52);void(0);"><img name="cat_arrow_52" border="0" width="17" height="18" src="/images/category_background.gif" alt="test2"></div></td>
                                 </tr>

                                 <tr>
                                    <td colspan="3"><img width="152" height="10" src="/images/bgim.gif"></td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td bgcolor="#000000"><img src="/images/bgim.gif" width="152" height="1"></td>
                        </tr>
                        <tr>
                           <td bgcolor="#FFDF5E">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td><img src="http://www.couponmountain.com/images/bgim.gif" width="152" height="10"></td>
 </tr>
 <tr>
  <td><img src="http://www.couponmountain.com/images/my_coupon.gif" width="152" height="11"></td>
 </tr>
 <tr>
  <td><img src="http://www.couponmountain.com/images/bgim.gif" width="152" height="5"></td>
 </tr>
 <tr>
  <td>
   <a onclick="top.MyClose=false;" href="http://www.couponmountain.com/account.php"><img border="0" src="http://www.couponmountain.com/images/button_saved_coupons.gif" width="152" height="19"></a>
  </td>
 </tr>
 <tr>
  <td><img src="http://www.couponmountain.com/images/bgim.gif" width="152" height="10"></td>
 </tr>
 <tr>
  <td>
   <a onclick="top.MyClose=false;" href="http://www.couponmountain.com/notify_me.php"><img border="0" src="http://www.couponmountain.com/images/button_notify_me.gif" width="152" height="19"></a>
  </td>
 </tr>
 <tr>
  <td><img src="http://www.couponmountain.com/images/bgim.gif" width="152" height="15"></td>
 </tr>
</table>

                           </td>
                        </tr>
                        <tr>
                           <td bgcolor="#000000"><img src="/images/bgim.gif" width="152" height="1"></td>
                        </tr>
                        {INCLUDE1}
                        <tr>
                           <td bgcolor="#FEF5D2">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td colspan="2"><img src="http://www.couponmountain.com/images/bgim.gif" width="152" height="10"></td>
 </tr>
 <tr>
  <td colspan="2"><img src="http://www.couponmountain.com/images/resources.gif" width="152" height="10"></td>
 </tr>
 <tr>
  <td colspan="2"><img src="http://www.couponmountain.com/images/bgim.gif" width="152" height="5"></td>
 </tr>
 <tr>
  <td rowspan="3"><img src="http://www.couponmountain.com/images/bgim.gif" width="10" height="10"></td>
  <td class="leftmenu">
   <a class="leftmenu" onclick="top.MyClose=false;" href="http://www.couponmountain.com/About_Online_Coupons.html">About Online Coupons</a>
  </td>
 </tr>
 <tr>
 <td class="leftmenu">
   <a class="leftmenu" onclick="top.MyClose=false;" href="http://www.couponmountain.com/Where_to_Enter_Coupon.html">Where to Enter Coupon</a>
  </td>
 </tr>
 <tr>
  <td class="leftmenu">
   <a class="leftmenu" onclick="top.MyClose=false;" href="http://www.couponmountain.com/FAQ.html">FAQ</a>
  </td>
 </tr>
 <tr>
  <td colspan="2"><img src="http://www.couponmountain.com/images/bgim.gif" width="152" height="15"></td>
 </tr>
</table>

                           </td>
                        </tr>
                        <tr>
                           <td bgcolor="#000000"><img src="/images/bgim.gif" width="152" height="1"></td>
                        </tr>
                        {INCLUDE2}
                     </table>
                  </td>
                  <td width="0%" bgcolor="#000000"><img src="/images/bgim.gif" width="1" height="1"></td>
               </tr>
            </table>
         </td>
         <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td colspan="2"><img src="/images/bgim.gif" width="20" height="10"></td>
               </tr>
               <tr>
                  <td width="1%"><img src="/images/bgim.gif" width="20" height="1"></td>
                  <td >{NAVIGATION_PATH}</td>
               </tr>
               <tr>
                  <td colspan="2">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                           <td width="*">
<a name="top"></a>

<table width="100%" cellspacing="0" cellpadding="10" border="0" align="center"> 
   <tr> 
      <td class="bodyline"><table width="100%" cellspacing="0" cellpadding="0" border="0">
         <tr> 
            <td><a onclick="top.MyClose=false;" href="{U_INDEX}"><img src="templates/subSilver/images/logo_phpBB.gif" border="0" alt="{L_INDEX}" vspace="1" /></a></td>
            <td align="center" width="100%" valign="middle"><span class="maintitle">{SITENAME}</span><br /><span class="gen">{SITE_DESCRIPTION}<br />&nbsp; </span> 
            <table cellspacing="0" cellpadding="2" border="0">
               <tr> 
                  <td align="center" valign="top" nowrap="nowrap"><span class="mainmenu">&nbsp;<a onclick="top.MyClose=false;" href="{U_FAQ}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_faq.gif" width="12" height="13" border="0" alt="{L_FAQ}" hspace="3" />{L_FAQ}</a></span><span class="mainmenu">&nbsp; &nbsp;<a onclick="top.MyClose=false;" href="{U_SEARCH}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_search.gif" width="12" height="13" border="0" alt="{L_SEARCH}" hspace="3" />{L_SEARCH}</a>&nbsp; &nbsp;<a onclick="top.MyClose=false;" href="{U_MEMBERLIST}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_members.gif" width="12" height="13" border="0" alt="{L_MEMBERLIST}" hspace="3" />{L_MEMBERLIST}</a>&nbsp; &nbsp;<a onclick="top.MyClose=false;" href="{U_GROUP_CP}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_groups.gif" width="12" height="13" border="0" alt="{L_USERGROUPS}" hspace="3" />{L_USERGROUPS}</a>&nbsp; 
                  <!-- BEGIN switch_user_logged_out -->
                  &nbsp;<a onclick="top.MyClose=false;" href="{U_REGISTER}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_register.gif" width="12" height="13" border="0" alt="{L_REGISTER}" hspace="3" />{L_REGISTER}</a></span>&nbsp;
                  <!-- END switch_user_logged_out -->
                  </td>
               </tr>
               <tr>
                  <td height="25" align="center" valign="top" nowrap="nowrap"><span class="mainmenu">&nbsp;<a onclick="top.MyClose=false;" href="{U_PROFILE}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_profile.gif" width="12" height="13" border="0" alt="{L_PROFILE}" hspace="3" />{L_PROFILE}</a>&nbsp; &nbsp;<a onclick="top.MyClose=false;" href="{U_PRIVATEMSGS}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_message.gif" width="12" height="13" border="0" alt="{PRIVATE_MESSAGE_INFO}" hspace="3" />{PRIVATE_MESSAGE_INFO}</a>&nbsp; &nbsp;<a onclick="top.MyClose=false;" href="{U_LOGIN_LOGOUT}" class="mainmenu"><img src="templates/subSilver/images/icon_mini_login.gif" width="12" height="13" border="0" alt="{L_LOGIN_LOGOUT}" hspace="3" />{L_LOGIN_LOGOUT}</a>&nbsp;</span></td>
               </tr>
            </table></td>
         </tr>
      </table>

      <br />
