{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			<!--end adv -->
		<div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                  <td colspan="2">
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                           <td width="*"><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td colspan="2"><img src="/images/bgim.gif" width="5" height="5"></td>
   </tr>
   <tr>
      <td width="1%"><img src="/images/bgim.gif" width="30" height="30"></td>
      <td>	<div align="center">	  <br>
                      <table width="96%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#BCBCBC">
                          <tr>
                            <td bgcolor="#F0EFEF"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr>
                               <td width="89%" valign="top">
								<table width="100%" border="0" cellpadding="1" cellspacing="1">
                                    <tr>
                                      <td height="84"><table width="100%"  border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
                                        <tr>
                                          <td height="82" valign="top">
										  <font class="infoPageHead">
										  <strong>全面发挥网站创收潜力! 马上申请Google Adsense!</strong></font>
										  <p><font class="introduction">Google AdSense是一个快速简便的方法，可以让各种规模的网站发布商为他们的网站展示与网站内容相关的Google广告并获取收入。</font></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                 </table></td>
                              </tr>
                            </table></td>
                          </tr>
  </table><br>
	<font style="FONT-WEIGHT: bold; FONT-SIZE: 16pt; COLOR: #214279; FONT-FAMILY: Arial">免费申请Google Adsense</font>
	<br>
	<br>
	<script type="text/javascript"><!--
google_ad_client = "pub-5441366578519312";
google_ad_width = 120;
google_ad_height = 240;
google_ad_format = "120x240_as_rimg";
google_cpa_choice = "CAAQw6SdzgEaCLO58nz4npJlKJPM93M";
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
                        <p>
                        <table width="96%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="84%" valign="top" class="introduction">
							Google Adsense for content - 由于所展示的广告与用户在您的网站上浏览的内容相关，或与您网站内容所吸引的用户的个性和兴致相符，您终于可以在充实网页的同时，透过网页为你带来经济效益。
                              <P>Google Adsense for search - 网站发布商还可以利用 Google Adsense 向访问者提供 Google 网络搜索和网站搜索功能，并通过在搜索结果页上展示 Google 广告来获得收入。
							  <P>能够在您的内容网页上展示的 Google 广告既可以是按每次点击费用 (CPC) 付费的广告，也可以是按每千次展示费用 (CPM) 付费的广告。
							  <P>弄清能赚多少的最好办法是立即注册Google Adsense，并在网页上开始展示广告。您无需支付任何费用、承担任何义务，而且加入此计划非常快速、简单。
							  <P>填完Google Adsense申请表格并提交后，请查阅 Adsense 发送给您的电子邮件，在您确认自己的电子邮件地址后，Google Adsense 将审核您的申请，并在 2 到 3 天内通过电子邮件与您联系。如果您的申请得到批准，则可以登录到自己的 Adsense 帐户，开始使用此计划。 
						    </td></tr>
                        </table></div></td>
   </tr>
</table></td>
                           <td width="1%"><img width="10" height="10" src="/images/bgim.gif"></td>
                        </tr>
                     </table>
		</table>
		</div>
			{include file="foot.tpl"}
			<!--end footer -->
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->
<!--
   make_stat({$category_cur},{$merchant_cur},{$coupon_cur},1);
   afp_stat();
//-->
</script>
</div>
<!--end main -->
</body>
</html>
