{include file="simple_head_category.tpl"}
{include file="head_category.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
		<ul><li><h2>&nbsp;&nbsp;�Ż�Ƶ������</h2></li></ul>
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
</div>
{literal}
<script language="JavaScript" src="/jscript/category.js"></script>

{/literal}
<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			{$pageString}
			{include file="firefox.tpl"}
			<!--end adv -->
		<div>
			{if $couponList}
			<span class="black txt13 b">�̼Ҽ����Żݻ�б�</span>
			<table class="listbox">
				<tr>
				<td width="25%" class="title">�̼�����</td>
				<td width="35%" class="title">�Ż����ݽ���</td>
				<td width="8%" class="title">�Ż����</td>
				<td width="17%" class="title" style="padding-bottom: 9px;">����&nbsp;<select onchange="trygo(this.value)">{foreach from=$cityarray key=k item=foo}<option value="{$k}" {if $k==$nowcityid}selected{/if}>{$foo}</option>{/foreach}</select></td>
				<td width="13%" class="title" style="text-align:right;">����ʱ��</td>
				</tr>
				{section name=index loop=$couponList}
				{if $couponList[index].isAbled}
				<tr>
				  {if $couponList[index].merName}
				  <td class="over">{if $couponList[index].merUrl}<a href="{$couponList[index].merUrl}" class="b">{$couponList[index].merName}</a>{else}{$couponList[index].merName}{/if}</td>
				  {else}
				  <td class="over"></td>
				  {/if}
				  <td class="over">
				  {if $couponList[index].couponUrl}<a href="{$couponList[index].couponUrl}" target="_blank">{$couponList[index].couponTitle}</a>&nbsp;{if $couponList[index].Hasmap==1}<img src="/images/map.gif" alt="�е�ͼ">{/if}{else}{$couponList[index].couponTitle}{/if}
				  </td>
				  <td>{if $couponList[index].CouponType==1}<img src="/images/w.gif" alt="�����Ż�ȯ">{elseif $couponList[index].CouponType==2}<img src="/images/z.gif" alt="�ۿ�ȯ">{else $couponList[index].CouponType==3}<img src="/images/q.gif" alt="����ȯ">{/if}</td>
				   <td class="over">{$couponList[index].City}</td>
				  <td class="over" style="text-align:right;">{$couponList[index].couponStatus}</td>
			    </tr>
				{/if}
				{/section}
			</table>
			{/if}
			{$pageString}
		</div>
			{include file="foot_category.tpl"}
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
