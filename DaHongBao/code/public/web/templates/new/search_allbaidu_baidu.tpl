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
<link href="../css/search_allbd_bd.css" rel="stylesheet" type="text/css" media="all" />
<script src="/jscript/prototype.js"></script>
</head>
<body>
<!--ͷ����ʼ-->
{include file="new/head_searchallbaidu.htm"}
<!--ͷ������-->
{if $getprodcntFlag == "yes"}
<!--productcnt:{$ProductCnt}--> 
<!--sponsorlinkcnt:{$AdsCnt}-->
{/if}
<!--�������ݿ�ʼ-->
<div id="content">
	{*�ٶ�ͷ����濪ʼ*}
	{if $adsbd->resultlist->result}
	<div class="topad">
		<div class="topad_title"><img src="/images/ico_bd.gif" /></div>
		{foreach from=$adsbd->resultlist->result item=l name=list}
		{if $smarty.foreach.list.index < 6}		
		<ul>
			<li class="li_title"><a href="{$l->uri}" target="_blank">{$l->title}</a></li>
			<li class="li_text"><a href="{$l->uri}" target="_blank">{$l->abstract}</a></li>
			<li class="li_url"><a href="{$l->uri}" target="_blank">{$l->SHORTURL} - <strong>�ƹ�</strong></a></li>		
		</ul>
		{/if}
		{/foreach}
	</div>
	{/if}
	{*�ٶ�ͷ��������*}
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
			<li>{if $smarty.get.ci=="4"}
				<strong>ͬ������</strong><span class="libring">({$searchlistcount.4})</span>
				{else}
					{if $searchlistcount.4>0}
					<a href="/se-{$searchTextFormat}-1-4/" title="ͬ������">ͬ������({$searchlistcount.4})</a>
					{else}
					ͬ������(0)
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
		{elseif $newsearchlist[loop].Type==4}
			<div class="coupon_box" onmouseover="this.className='coupon_boxover'" onmouseout="this.className='coupon_box'">
			<!--ͬ���������ݿ�ʼ-->
			<div class="coupon_left">				
				<div class="discount_box1">
					<ul>
						<li class="lititle"><a href="/life/view.php?id={$newsearchlist[loop].BaiXingID}" target="_blank">{$newsearchlist[loop].Title}</a></li>	
						<li class="litext">{$newsearchlist[loop].Content}</li>
					</ul>
				</div>
			</div>
			<!--ͬ���������ݽ���-->
			<!--ͬ������logo��ʼ-->
			<div class="coupon_logo"><a href="/se-{$smarty.get.searchText}-1-4/" title="����ͬ��������Ϣ"><img src="../../images/newsearch/city_logo.gif" alt="ͬ������" /></a></div>
			<!--ͬ������logo����-->
		</div>
		{/if}
		{/section}
		<!--�Żݾ����-->
		
		<!--��ҳ��ʼ-->
		{if $countall>0}
		{$pageString}
		{else}
			<div class="search_box">
			<ul>
				<li class="lititle">�Բ�����ʱû��"{$searchText}"��ص���Ϣ��</li>
			</ul>
			</div>
		
		{/if}
		<!--��ҳ����-->
	</div>
	<!--������ݽ���-->
	
	{*�ٶ��ұ߹�濪ʼ*}
	<div id="content_right">
		{if count($adsbd->resultlist->result) > 6}
		<div id="preview" class="content_rightadd">
			<div class="bd_title"><img src="/images/ico_bd.gif" /></div>
			{foreach from=$adsbd->resultlist->result item=l name=list}
			{if $smarty.foreach.list.index > 5 && $smarty.foreach.list.index < 12}
			<ul>
				<li class="lititle"><a href="{$l->uri}" target="_blank">{$l->title}</a></li>
				<li class="litext"><a href="{$l->uri}" target="_blank">{$l->abstract}</a></li>
				<li class="liurl"><a href="{$l->uri}" target="_blank">{$l->SHORTURL} - <strong>�ƹ�</strong></a></li>
			</ul>
			{/if}			
			{/foreach}			
		</div>
		{/if}
	</div>
	{*�ٶ��ұ߹�����*}
</div>
<!--�������ݽ���-->
			
{include file="new/foot.htm"}