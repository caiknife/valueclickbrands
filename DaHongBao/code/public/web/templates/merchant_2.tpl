{include file="simple_head_merchant_1.tpl"}
{include file="head_merchant.tpl"}
<div id="main">
<div id="left" class="fillBg">
	<div class="categorymenu">
		<h2>&nbsp;&nbsp;�Ż�Ƶ������</h2>
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
	{$RESOURCE_INCLUDE}
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			{include file="firefox.tpl"}
			<!--end adv -->
		<div class="merchantinfo">
				  {if $merchantLogo}<a {if $merchantURL}href="{$merchantURL}"{/if}><IMG  src="{$merchantLogo}" alt="{$merchantName}�Ż�ȯ���ۿ۹���ȯ"  border=0 class="f" width="200"></a>{/if}		
				  <div class="merchantinforight txt3E3E42"><h1>{$merchantName}</h1><BR>{$merchantDescript}
				  {if $merchantURL}<p class="b">ֱ�ӷ����̼���ҳ: <a href="{$merchantURL}" target="_blank"><span class="blue">{$merchantName}</span></a> </p>{/if}
				  </div>
			</div>
			<!--end merchantinfo -->
			<div id="merchantcoupon">
				{if $couponlist}
				
				<div class="middletitle"><h2>{$merchantName}�����Ż�ȯ</h2></div>
				{$spinfo}
				<div class="middletitle"><h2>{$merchantName}�����Ż�ȯ, ���Ϣ</h2></div>
				{section name=index loop=$couponlist}
				<div class="couponlist">
					<div class="f couponimg">{if $couponlist[index].image}{if $couponlist[index].url}<A href="{$couponlist[index].url}" target="_blank"><IMG src="{$couponlist[index].image}" alt="{$couponlist[index].title}" width="{$couponlist[index].imageX}" height="{$couponlist[index].imageY}" ></A>{else}<IMG src="{$couponlist[index].image}" alt="{$merchantName}�Ż�ȯ���ۿ۹���ȯ" width="{$couponlist[index].imageX}" height="{$couponlist[index].imageY}" >{/if}{else}<IMG src="/images/dahongbao.gif" alt="{$couponlist[index].title}" >{/if}</div>
					<div class="right seeit">{if $couponlist[index].url}<a href="{$couponlist[index].url}"  target="_blank"><img src="/images/blue_but.gif" alt="�鿴���Ż�" /></a>{else}{/if}</div>
					<dl>
						<dt>{if $couponlist[index].url}<a href="{$couponlist[index].url}" class="blue" target="_blank">{$couponlist[index].title}</a>{else}{$couponlist[index].title}{/if} </dt>
						<dd>{$couponlist[index].detail}</dd>                                                                  
						<dd>��ʼʱ�䣺{$couponlist[index].start}  &nbsp;����ʱ�䣺{$couponlist[index].end}</dd>
						<dd class="couponlistbottom"><a href="{$couponlist[index].saveUrl}" class="addtofav blue">�����ղ�</a><a href="JavaScript:window.open('/send_friend.php?p={$couponlist[index].couponID}&c=','sendfriend{$couponlist[index].couponID}','width=415,height=550,resizable=0,scrollbars=yes');void(0);" class="comtofriend blue">�Ƽ�����</a><!--<a href="#" onclick="javascript: return false;" class="comments">��������</a>--></dd>
					</dl>
				</div>
				{/section}
				{else}
				<div><font color="#aa0000">{$merchantName}�µ��Żݻ������ʼ,�����ע</font></div>
				{/if}
				{if $specWord}
				<br />
				<p>{$specWord}</p>
				{/if}
				
			</div>
			{literal}
			<script>
				function gocity(value){
					window.location.href="/merchant_d.php?id=899&mercityid="+value;
				}
				function goarea(value){
					var city = form1.city.value;
					window.location.href="/merchant_d.php?id=899&mercityid="+city+"&areaid="+value;
				}
			</script>
			{/literal}
			<!--begin shop_address--->
				<div class="shop_address"><FORM METHOD=POST ACTION="" NAME='form1'>
					<div class="shop_addresstitle">��������̵�ַ:{if $showaddressfilter==1}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<SELECT NAME="city" onchange="gocity(this.value)"><option value="0" {if $mercityid=="0"}selected{/if}>ȫ��</option><option value="21" {if $mercityid==21}selected{/if}>�Ϻ�</option><option value="10" {if $mercityid==10}selected{/if}>����</option></SELECT>&nbsp;&nbsp;&nbsp;&nbsp;{if $showareafilter==1}<SELECT NAME="area" onchange="goarea(this.value)"><option value="">ȫ������</option>{section name=loop loop=$areaarray}<option value="{$areaarray[loop].Area}" {if $areaid==$areaarray[loop].Area}selected{/if}>{$areaarray[loop].AreaName}</option>{/section}</SELECT>{/if}{/if}</div></FORM>
					<div class="shop_addresstext">
						<ul>
							<li>
								<ul>
									<li class="lileft libg">����</li>
									<li class="limiddle libg">��ַ</li>
									<li class="liright libg">�绰</li>									
								</ul>
							</li>
							<li class="border">
								<ul>
									<li class="lileft libg">����</li>
									<li class="limiddle libg">��ַ</li>
									<li class="liright libg">�绰</li>									
								</ul>
							</li>
							{section name=loop loop=$addressarray}
							<li {if $smarty.section.loop.iteration%2==0}class="border"{/if}>
								<ul>
									<li class="lileft">{$addressarray[loop].Name}</li>
									<li class="limiddle">{$addressarray[loop].Address}</li>
									<li class="liright">{$addressarray[loop].Tel}</li>									
								</ul>
							</li>
							{/section}
						</ul>
					</div>
				</div>
				<!--end shop_address-->
			{include file="foot_merchant.tpl"}
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