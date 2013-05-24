<html>
<head>
<title>{PAGE_TITLE}</title>
<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
<link href="{LINK_ROOT}css/main.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#6666CC" text="#000000" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
         <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td rowspan="4" width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="60"></td>
                  <td><img src="{LINK_ROOT}images/bgim.gif" width="21" height="21"></td>
                  <td rowspan="4" width="1%"><img src="{LINK_ROOT}images/bgim.gif" width="20" height="60"></td>
               </tr>
<!--
               <tr>
                  <td>
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                           <td><font class="pageNumber">{PAGE_NUM}.&nbsp;</font><font class="pageTitle">{PAGE_TITLE}</font></td>
                        </tr>
                        <tr>
                           <td><img src="{LINK_ROOT}images/bgim.gif" width="10" height="10"></td>
                        </tr>
                     </table>
                  </td>
               </tr>
//-->
               <tr>
                  <td bgcolor="#9999FF">{PAGE_CONTENT}</td>
               </tr>
               <tr>
                  <td>
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                           <td colspan="3"><img src="{LINK_ROOT}images/bgim.gif" width="18" height="18"></td>
                        </tr>
                        <tr>
                           <td width="1%"><a onclick="top.MyClose=false;" href="{PREV_PAGE}"><img border="0" src="{LINK_ROOT}images/prev_page.gif"></a></td>
                           <td align="center">
                              <table>
                                 <tr>
                                    <!-- BEGIN DYNAMIC BLOCK: page_list -->
                                    <td class="pageList{PG_SEL}"><a onclick="top.MyClose=false;" href="{PG_LINK}" class="pageList{PG_SEL}">{PG_NUM}</a></td>
                                    <!-- END DYNAMIC BLOCK: page_list -->
                                 </tr>
                              </table>
                           </td>
                           <td width="1%"><a onclick="top.MyClose=false;" href="{NEXT_PAGE}"><img border="0" src="{LINK_ROOT}images/next_page.gif"></a></td>
                        </tr>
                        <tr>
                           <td colspan="3"><img border="0" src="{LINK_ROOT}images/bgim.gif" width="18" height="18"></td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
</body>
</html>

