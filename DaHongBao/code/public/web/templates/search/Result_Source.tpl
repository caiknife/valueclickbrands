<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>{$searchText}�����Ż�ȯ,������Ϣ,�����</title>
<META NAME="description" CONTENT="{$description}">
<META NAME="keywords" CONTENT="{$keywords}">
<meta name="copyright" content="www.dahongbao.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="verify-v1" content="QVIPb4M3AU1SQ7HQONfC2lrlH7RkeUn+PL1dLv8l5fs=" />
<link href="../css/search_allbd.css" rel="stylesheet" type="text/css" media="all" />
<script src="/jscript/prototype.js"></script>
<style>
	{literal}
	.li_text a:hover {
		text-decoration:none;
	}
	.litext a:hover {
		text-decoration:none;
	}
	
	{/literal}
</style>
</head>
<body>
<!--ͷ����ʼ-->
{include file="new/head_searchallbaidu.htm"}
<!--ͷ������-->
<!--�������ݿ�ʼ-->
{if $getprodcntFlag == "yes"}
<!--productcnt:{$ProductCnt}--> 
<!--sponsorlinkcnt:{$AdsCnt}-->
{/if}
<div id="content" class="gg_content">
	{*googleͷ����濪ʼ*}
	{if $adsResult.googleAds[0] }
	<div class="topad topad_single">
		<div class="topad_title"><img src="/images/ico_bd.gif" /></div>
		{foreach item=ads from=$adsResult.googleAds[0]}
		<ul>
			<li class="li_title"><a href="{$ads.url}" target="_blank">{$ads.LINE1}</a></li>
			<li class="li_text">{$ads.LINE2}</li>
			<li class="li_url"><a href="{$ads.url}" target="_blank">{$ads.SiteUrl}</a> </li>		
		</ul>
		{/foreach}
	</div>
	{/if}
    {if $adsResult.baiduAds[0] }
	<div class="topad topad_single topad_single_bd">
		{foreach item=ads name=adsbaiName from=$adsResult.baiduAds[0]}
		<ul {if $smarty.foreach.adsbaiName.index == 0 && $adsResult.googleAds[0]}style="border-top:1px solid #E8E8E8;padding-top:5px;"{/if}>
			<li class="li_title"><a href="{$ads.url}" target="_blank">{$ads.title}</a></li>
			<li class="li_text">{$ads.abstract}</li>
			<li class="li_url"><a href="{$ads.url}" target="_blank">{$ads.site}</a> </li>
		</ul>
		{/foreach}
	</div>
	{/if}

	{*googleͷ��������*}
	<!--ͷ����ʼ-->
	<div id="content_top">
		<ul>
			<li>{if $smarty.get.ci==""}<strong>ȫ��</strong><span class="libring">({$countall})</span>{else}<a href="/se-{$smarty.get.searchText}-1-/">ȫ��({$countall})</a>{/if}</li>
			<li>{if $smarty.get.ci=="1"}
				<strong>�Ż�ȯ</strong><span class="libring">({$searchlistcount.1})</span>
				{else}
					{if $searchlistcount.1>0}
					<a href="/se-{$searchTextFormat}-1-1/" title="�Ż�ȯ">�Ż�ȯ({$searchlistcount.1})</a>
					{else}
					�Ż�ȯ(0)
					{/if}
				{/if}
			</li>
			<li>{if $smarty.get.ci=="2"}
				<strong>�ۿ���Ϣ</strong><span class="libring">({$searchlistcount.2})</span>
				{else}
					{if $searchlistcount.2>0}
					<a href="/se-{$searchTextFormat}-1-2/" title="�ۿ���Ϣ">�ۿ���Ϣ({$searchlistcount.2})</a>
					{else}
					�ۿ���Ϣ(0)
					{/if}
				{/if}
			</li>
			<li>{if $smarty.get.ci=="113"}
				<strong>�����ؼ�</strong><span class="libring">({$searchlistcount.113})</span>
				{else}
					{if $searchlistcount.113>0}
					<a href="/se-{$searchTextFormat}-1-113/" title="�����ؼ�">�����ؼ�({$searchlistcount.113})</a>
					{else}
					�����ؼ�(0)
					{/if}
				{/if}
			</li>					
			<li>
			{if $smarty.get.ci=="99"}
				<strong>���ζȼ�</strong><span class="libring">({$searchlistcount.99})</span>
				{else}
					{if $searchlistcount.99 > 0}
					<a href="/se-{$searchTextFormat}-1-99/" title="����">���ζȼ�({$searchlistcount.99})</a>
					{else}
					���ζȼ�(0)
					{/if}
				{/if}
			</li>
			<li>{if $smarty.get.ci=="3"}
				<strong>����</strong><span class="libring">({$searchlistcount.3})</span>
				{else}
					{if $searchlistcount.3>0}
					<a href="/se-{$searchTextFormat}-1-3/" title="����">����({$searchlistcount.3})</a>
					{else}
					����(0)
					{/if}
				{/if}
			</li>									
		</ul>
		<div class="content_topright">
			<div class="content_bring">���� <strong>{$countall}</strong> ������ <strong>{$searchTextRe}</strong> ���������</div>
		</div>
	</div>
	<!--ͷ������-->
	<div class="clr"></div>
	<!--������ݿ�ʼ-->
	<div id="content_left">


		{section name=loop loop=$newsearchlist}
		<!--�Żݾ�ʼ-->
		{if $newsearchlist[loop].Type==1}
			<div class="coupon_box" onmouseover="this.className='coupon_boxover'" onmouseout="this.className='coupon_box'">
				<!--�Żݾ����ݿ�ʼ-->
				<div class="coupon_left">
					<div class="coupon_boximg"><a href="{$newsearchlist[loop].LinkURL}" target="_blank"><img src="{$newsearchlist[loop].ImageURL}" alt="{$newsearchlist[loop].oriTitle}"  onerror="this.src='/images/dahongbao.gif'"/></a></div>
					<div class="coupon_boxtext">
						<ul>
							<li class="lititle">{if $newsearchlist[loop].MerchantName}{$newsearchlist[loop].MerchantName}{/if}<a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].Title}</a></li>		
							<li class="litext">{$newsearchlist[loop].Detail}</li>
							{if $newsearchlist[loop].tagname}<li class="litag">{$newsearchlist[loop].tagname}&nbsp;&nbsp;</li>{/if}
							<li class="lisustain"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].digest}��</a>֧��</li>
							<li class="lireview"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].replies}��</a>����</li>
							<li class="litime">����ʱ�䣺{$newsearchlist[loop].ExpireDate}</li>
						</ul>
					</div>
				</div>
				<!--�Żݾ����ݽ���-->
				<!--�Żݾ�logo��ʼ-->
				<div class="coupon_logo"><a href="/se-{$smarty.get.searchText}-1-1/" title="�����Ż�ȯ"><img src="../../images/newsearch/coupon_logo.gif" alt="�Ż�ȯ" /></a></div>
				<!--�Żݾ�logo����-->
			</div>
		{elseif $newsearchlist[loop].Type==2}
			<div class="coupon_box" onmouseover="this.className='coupon_boxover'" onmouseout="this.className='coupon_box'">
				<!--�ۿ���Ϣ���ݿ�ʼ-->
				<div class="coupon_left">
					{if $newsearchlist[loop].ImageDownload}
					<div class="discount_boximg"><a href="{$newsearchlist[loop].LinkURL}" target="_blank"><img src="{$newsearchlist[loop].ImageURL}" alt="{$newsearchlist[loop].oriTitle}" onerror="this.src='/images/dahongbao.gif'"/></a></div>
					{/if}
					<div class="{if $newsearchlist[loop].ImageDownload}discount_box{else}discount_box1{/if}">
						<ul>
							<li class="lititle"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].Title}</a></li>		
							<li class="litext">{$newsearchlist[loop].Detail}</li>
							{if $newsearchlist[loop].tagname}<li class="litag">{$newsearchlist[loop].tagname}&nbsp;&nbsp;</li>{/if}
							<li class="lisustain"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].digest}��</a>֧��</li>
							<li class="lireview"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].replies}��</a>����</li>
							<li class="litime">����ʱ�䣺{$newsearchlist[loop].ExpireDate}</li>
						</ul>
					</div>
				</div>
				<!--�ۿ���Ϣ���ݽ���-->
				<!--�ۿ���Ϣlogo��ʼ-->
				<div class="coupon_logo"><a href="/se-{$smarty.get.searchText}-1-2/" title="�����ۿ���Ϣ"><img src="../../images/newsearch/discount_logo.gif" alt="�ۿ���Ϣ" /></a></div>
				<!--�ۿ���Ϣlogo����-->
			</div>
		{elseif $newsearchlist[loop].Type==3}
			<div class="bbs_box" onmouseover="this.className='bbs_boxover'" onmouseout="this.className='bbs_box'">
			<!--�����������ݿ�ʼ-->
			<div class="bbs_boxleft">
				<ul>
					<li class="lititle"><a href="/bbs/read.php?tid={$newsearchlist[loop].tid}" target="_blank">{$newsearchlist[loop].Title}</a></li>
					<li class="litext">{$newsearchlist[loop].Detail}</li>
					<li class="liauthor">by <a href="/bbs/profile.php?action=show&uid={$newsearchlist[loop].authorid}" target="_blank">{$newsearchlist[loop].author}</a> ������</li>				
					<li class="litime">{$newsearchlist[loop].postdate}</li>
					<li class="libbspoint">{$newsearchlist[loop].hits} ����</li>
				</ul>
			</div>
			<!--�����������ݽ���-->
			<!--�ۿ���Ϣlogo��ʼ-->
			<div class="bbs_logo"><a href="/se-{$smarty.get.searchText}-1-3/" title="����"><img src="../../images/newsearch/bbs_logo.gif" alt="����������Ϣ" /></a></div>
			<!--�ۿ���Ϣlogo����-->
		</div>
		{elseif $newsearchlist[loop].Type==113 && $newsearchlist[loop].Name}
		<div class="coupon_box" onmouseover="this.className='coupon_boxover'" onmouseout="this.className='coupon_box'">
			<!--�ۿ���Ϣ���ݿ�ʼ-->
			<div class="coupon_left">
				{if $newsearchlist[loop].HasImage=="YES"}
				<div class="discount_boximg"><a href="{$newsearchlist[loop].DetailURL}" target="_blank"><img src="{$newsearchlist[loop].ImageURL}" alt="{$newsearchlist[loop].Name}" onerror="this.src='/images/dahongbao.gif'"/></a></div>
				{/if}
				<div class="{if $newsearchlist[loop].HasImage=='YES'}discount_box{else}discount_box1{/if}">
					<ul>
						<li class="lititle"><a href="{$newsearchlist[loop].DetailURL}" target="_blank">{$newsearchlist[loop].Name}</a></li>		
						<li class="litext">
							<div class="price_box">
								&nbsp; ԭ�ۣ�<span class="original_price">��{$newsearchlist[loop].FullPrice}</span>
								&nbsp; �ؼۣ�<span class="discount_price">��{$newsearchlist[loop].Price}</span>
							</div>
						</li>
						{if $newsearchlist[loop].tagname}<li class="litag">{$newsearchlist[loop].tagname}&nbsp;&nbsp;</li>{/if}
						<li>�̼ң�{$newsearchlist[loop].MerchantName} &nbsp; ��¼���ڣ�{$newsearchlist[loop].LoginDate}</li>
						<li class="lireview"><a href="{$newsearchlist[loop].DetailURL}" target="_blank">�鿴��ϸ</a></li>
					</ul>
				</div>
			</div>
			<!--�ۿ���Ϣ���ݽ���-->
			<!--�ۿ���Ϣlogo��ʼ-->
			<div class="coupon_logo"><a href="/se-{$smarty.get.searchText}-1-113/" title="���ྡྷ���ؼ�"><img src="../../images/newsearch/onlinediscount_logo.gif" alt="�����ؼ�" /></a></div>
			<!--�ۿ���Ϣlogo����-->
		</div>
		{elseif $newsearchlist[loop].Type == 99 && $newsearchlist[loop].Name}
		<div class="coupon_box" onmouseover="this.className='coupon_boxover'" onmouseout="this.className='coupon_box'">
			<!--�ۿ���Ϣ���ݿ�ʼ-->
			<div class="coupon_left">
				{if $newsearchlist[loop].TourID}
				<div class="discount_boximg"><a href="{$newsearchlist[loop].DetailURL}" target="_blank"><img src="{$newsearchlist[loop].TourPictureUrl}" alt="{$newsearchlist[loop].Name}" onerror="this.src='/images/travel/tour_default.gif'"/></a></div>
				{else}
				 <div class="discount_boximg"><a href="{$newsearchlist[loop].DetailURL}" target="_blank"><img src="{$newsearchlist[loop].ImageURL}" alt="{$newsearchlist[loop].Name}" /></a></div>
				{/if}
				<div class="discount_box">
					<ul>
						<li class="lititle"><a href="{$newsearchlist[loop].DetailURL}" target="_blank">{$newsearchlist[loop].Name}</a></li>		
						<li class="litext">
							{$newsearchlist[loop].Info|strip_tags}{$newsearchlist[loop].Description} 
						</li>
						<li>{if $newsearchlist[loop].MerchantName}�̼ң�{$newsearchlist[loop].MerchantName} &nbsp;{/if}��¼���ڣ�{$newsearchlist[loop].LoginDate}{$newsearchlist[loop].r_StartTime}</li>
						<li class="lireview"><a href="{$newsearchlist[loop].DetailURL}" target="_blank">�鿴��ϸ</a></li>
					</ul>
				</div>
			</div>
			<!--�ۿ���Ϣ���ݽ���-->
			<!--�ۿ���Ϣlogo��ʼ-->
			<div class="coupon_logo"><a href="/se-{$smarty.get.searchText}-1-99/" title="��������"><img src="../../images/travel/logo_travel.gif" alt="����" /></a></div>
			<!--�ۿ���Ϣlogo����-->
		</div>
		{/if}
		{/section}
		<!--�Żݾ����-->
		
		<!--��ҳ��ʼ-->
		{if $countall>0}
		{$pageString}
        {/if}
		{if $countall <= 3 && $hotCouponList}
			<div class="search_box">
			<ul>
				<li class="lititle" style="padding-bottom:5px;">{if $countall <= 0}�Բ�����ʱû��"{$searchText}"��ص���Ϣ��{/if}����������Ϊ���Ƽ�������<font style="color:#A92F2B;font-size:14px;font-weight: 600;">�Ż�ȯ</font></li>
			</ul>
			</div>
            {if $hotCouponList}
            {section name=loop loop=$hotCouponList}
            <div class="coupon_box hot_coupon_box" onmouseover="this.className='hot_coupon_boxover'" onmouseout="this.className='coupon_box hot_coupon_box'">
				<!--�Żݾ����ݿ�ʼ-->
				<div class="coupon_left">
					<div class="coupon_boximg"><a href="{$hotCouponList[loop].LinkURL}" target="_blank"><img src="{$hotCouponList[loop].ImageURL}" alt="{$hotCouponList[loop].oriTitle}"  onerror="this.src='/images/dahongbao.gif'"/></a></div>
					<div class="coupon_boxtext">
						<ul>
							<li class="lititle"><a href="{$hotCouponList[loop].LinkURL}" target="_blank">{$hotCouponList[loop].Descript}</a></li>
							<li class="litext">{$hotCouponList[loop].Detail}</li>
							<li class="litime" style="margin-left: 0px;">����ʱ�䣺{$hotCouponList[loop].ExpireDate}</li>
						</ul>
					</div>
				</div>
				<!--�Żݾ����ݽ���-->
				<!--�Żݾ�logo��ʼ-->
				<div class="coupon_logo"><a href="/" title="�����Ż�ȯ"><img src="../../images/newsearch/coupon_logo.gif" alt="�Ż�ȯ" /></a></div>
				<!--�Żݾ�logo����-->
			</div>
            {/section}
            {/if}
		{/if}
		<!--��ҳ����-->
	</div>
	<!--������ݽ���-->
	
	{*Google�ұ߹�濪ʼ*}
	<div id="content_right">
		{if $adsResult.googleAds[1]}
		<div id="preview" class="content_rightadd">
			<div class="bd_title"><img src="/images/ico_bd.gif" /></div>
			{foreach item=ads name=adsRight from=$adsResult.googleAds[1]}
			<ul>
				<li class="lititle"><a href="{$ads.url}" target="_blank">{$ads.LINE1}</a></li>
				<li class="litext">{$ads.LINE2}</li>
				<li class="liurl"><a href="{$ads.url}" target="_blank">{$ads.SiteUrl}</a></li>
			</ul>
			{/foreach}
		</div>
		{/if}
        {if $adsResult.baiduAds[1]}
		<div id="preview" class="content_rightadd{if $adsResult.googleAds[1]} newTopBg{/if}">
			{foreach item=ads name=adsRight from=$adsResult.baiduAds[1]}
			<ul>
				<li class="lititle"><a href="{$ads.url}" target="_blank">{$ads.title}</a></li>
				<li class="litext">{$ads.abstract}</li>
				<li class="liurl"><a href="{$ads.url}" target="_blank">{$ads.site}</a></li>
			</ul>
			{/foreach}
		</div>
		{/if}
	</div>
	{*Google�ұ߹�����*}
</div>
<!--�������ݽ���-->
			
{include file="new/foot.htm"}