{include file="simple_head_noTracking.tpl"}
<link href="/css/stprint.css" rel="stylesheet" type="text/css" media="print">
{include file="head_coupon.tpl"}
<div id="main">
<div id="left" class="fillBg">
	<div class="categorymenu1" id="newcoupon" >
		<h3>&nbsp;&nbsp;���µ����Ż�ȯ</h3>
		<ul>
		{section name=index loop=$newCouponlist}
		<li><a href="{$newCouponlist[index].couponURL}" target="_blank" class="blue">{$newCouponlist[index].couponTitle}</a></li>
		{/section}
 		</ul>
		<br/>
		<span class="block textr"><a href="/new_coupon.html" class="reddark">���������Ż�</a></span>
	</div>
	<div class="cl"></div>
	<div class="categorymenu">
		<ul><li><h2>&nbsp;&nbsp;�Ż�Ƶ������</h2></li></ul>
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
	<div class="googleleft" style="padding-bottom:25px;">
		<h2>&nbsp;&nbsp;�Ż�ȯʹ��С��ʾ</h2>
		<ul>
		{if $couponRow.MerchantURL}<li>&nbsp;- ���̼������Ż�ȯ&nbsp;<a href="{$couponRow.MerchantURL}" class="reddarkthick">{$merCouponCount}</a></li>{/if}
		<li>&nbsp;- ѡȡ����Ҫ�ĵ����Ż�ȯ</li>
		<li>&nbsp;- �鿴���<a href="{$categoryURL}" class="blue">{$categoryName}</a>�Ż�ȯ</li>
		<li>&nbsp;- �˽�����̼�,�Ż�ȯ��Ϣ</li>
		</ul>
	</div>
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="logo_p"><img src="/images/cm_top_logo_p.gif"/></div>
			<div class="local">{$navigation_path}</div>	
			<div class="couponinfo">
			<div>
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
					  <td colspan="2" class="txt3E3E42"><div style="width:590px;"><h1>{$couponRow.Descript}<B><font color="#FF0000">(�ѹ���)</font></b></h1></div></td>
					</tr>
				{if $couponRow.ImageDownload}
					<tr>
						<td colspan="2">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td colspan="2"><img src="{$couponRow.ImageURL}" alt="{$couponRow.Descript}" width="{$couponRow.imageX}" height="{$couponRow.imagey}"/></td>
								</tr>
								<tr>
									<td height="35"><a href="/account.php?action=save&p={$couponRow.Coupon_}" class="addtofav blue">�����ղ�</a><a href="JavaScript:window.open('/send_friend.php?p={$couponRow.Coupon_}&c=','sendfriend{$couponRow.Coupon_}','width=415,height=550,resizable=0,scrollbars=yes');void(0);" class="comtofriend blue">�Ƽ�����</a></td>
					    <td><a href="#" onClick="javascript:window.print();return false;" ><img src="/images/print.gif" border="0" /></a></td>
								</tr>
							</table>
						</td>
					</tr>
				 {/if}
					<tr>
						<td colspan="2">
						<strong>�̼ҽ���</strong> {if $couponRow.MerchantURL}<a href="{$couponRow.MerchantURL}" class="blue">����鿴{$couponRow.MerchantName}�����Ż���Ϣ</a>{else}{$couponRow.MerchantName}{/if}                        
						<p>{if $couponRow.Detail}{$couponRow.Detail}{else}������ϸ��Ϣ{/if}</p><!--<p>����ʱ��:{$couponRow.AddDate}</p>--><p>��ʼʱ��:{$couponRow.Start}&nbsp;����ʱ��:{$couponRow.End}</p><p>{if $couponRow.City!=""}����:{$couponRow.City}{/if}</p><p>{if $couponRow.Address!=""}��ַ:{$couponRow.Address}{/if}</p><p>{if $couponRow.Phone!=""}�绰:{$couponRow.Phone}{/if}</p></td>
					</tr>
					
					<TR><TD colspan="2">
						{if $map==1}
						<img src="/images/map/{$smarty.get.cid}.gif" class="couponmap">
						{/if}
					</td></tr>
					
					<!--<tr>
					  <td><strong>��������</strong> </td>
				      <td><div align="right"><a href="#" onclick="javascript:return false;">��ʾ��������</a></div></td>
				  </tr>-->
					<!--<tr>
					  <td colspan="2">
					    <textarea name="textfield"  style="width:99%;" rows="6"></textarea>
					  </td>
				  </tr>
					<tr>
					  <td colspan="2" style="padding:5px 0;">
					      <input type="submit" name="Submit" value="�ύ" style="width:80px; height:22px;" />
					  </td>
				  </tr>-->
			  </table>
			</div>
			</div>
			{if $hotCoupon}
			<div id="xgpro_box">
			<div id="xgprotit">&nbsp;�����Ż�ȯ</div>
				<div style="float:left;width:{$widthAll}px; border-left:1px solid #EAEAEA;border-top:1px solid #EAEAEA;border-bottom:1px solid #EAEAEA; text-align:center;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr style="height:25px;">
							{section name=loop loop=$hotCoupon}
							<td width="{$widthPercent}%" class="td_main">
								<div id="div_blank_1"></div>
								<div style="height:100px"><a href="{$hotCoupon[loop].url}"><img src="{$hotCoupon[loop].image}" width="{$hotCoupon[loop].imageX}" height="{$hotCoupon[loop].imageY}" title="{$hotCoupon[loop].title}"/></a></div>
								<div id="div_name">
									<a href="{$hotCoupon[loop].url}" class="green" target="_blank">
									<b>{$hotCoupon[loop].title}</b></a>
								</div>
							</td>
							{/section}
						</tr>
					</table>
				</div>
				<div class="cl"></div>
			</div>
			{/if}
			{include file="foot_coupon.tpl"}
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