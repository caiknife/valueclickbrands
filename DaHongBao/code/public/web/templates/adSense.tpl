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
										  <strong>ȫ�淢����վ����Ǳ��! ��������Google Adsense!</strong></font>
										  <p><font class="introduction">Google AdSense��һ�����ټ��ķ����������ø��ֹ�ģ����վ������Ϊ���ǵ���վչʾ����վ������ص�Google��沢��ȡ���롣</font></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                 </table></td>
                              </tr>
                            </table></td>
                          </tr>
  </table><br>
	<font style="FONT-WEIGHT: bold; FONT-SIZE: 16pt; COLOR: #214279; FONT-FAMILY: Arial">�������Google Adsense</font>
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
							Google Adsense for content - ������չʾ�Ĺ�����û���������վ�������������أ���������վ�������������û��ĸ��Ժ���������������ڿ����ڳ�ʵ��ҳ��ͬʱ��͸����ҳΪ���������Ч�档
                              <P>Google Adsense for search - ��վ�����̻��������� Google Adsense ��������ṩ Google ������������վ�������ܣ���ͨ�����������ҳ��չʾ Google �����������롣
							  <P>�ܹ�������������ҳ��չʾ�� Google ���ȿ����ǰ�ÿ�ε������ (CPC) ���ѵĹ�棬Ҳ�����ǰ�ÿǧ��չʾ���� (CPM) ���ѵĹ�档
							  <P>Ū����׬���ٵ���ð취������ע��Google Adsense��������ҳ�Ͽ�ʼչʾ��档������֧���κη��á��е��κ����񣬶��Ҽ���˼ƻ��ǳ����١��򵥡�
							  <P>����Google Adsense�������ύ������� Adsense ���͸����ĵ����ʼ�������ȷ���Լ��ĵ����ʼ���ַ��Google Adsense ������������룬���� 2 �� 3 ����ͨ�������ʼ�������ϵ�������������õ���׼������Ե�¼���Լ��� Adsense �ʻ�����ʼʹ�ô˼ƻ��� 
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
