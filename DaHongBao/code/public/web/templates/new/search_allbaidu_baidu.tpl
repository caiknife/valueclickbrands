<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>{$searchText}电子优惠券,打折信息,促销活动</title>
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
<!--头部开始-->
{include file="new/head_searchallbaidu.htm"}
<!--头部结束-->
{if $getprodcntFlag == "yes"}
<!--productcnt:{$ProductCnt}--> 
<!--sponsorlinkcnt:{$AdsCnt}-->
{/if}
<!--主体内容开始-->
<div id="content">
	{*百度头部广告开始*}
	{if $adsbd->resultlist->result}
	<div class="topad">
		<div class="topad_title"><img src="/images/ico_bd.gif" /></div>
		{foreach from=$adsbd->resultlist->result item=l name=list}
		{if $smarty.foreach.list.index < 6}		
		<ul>
			<li class="li_title"><a href="{$l->uri}" target="_blank">{$l->title}</a></li>
			<li class="li_text"><a href="{$l->uri}" target="_blank">{$l->abstract}</a></li>
			<li class="li_url"><a href="{$l->uri}" target="_blank">{$l->SHORTURL} - <strong>推广</strong></a></li>		
		</ul>
		{/if}
		{/foreach}
	</div>
	{/if}
	{*百度头部广告结束*}
	<!--头部开始-->
	<div id="content_top">
		<ul>
			<li>{if $smarty.get.ci==""}<strong>全部</strong><span class="libring">({$countall})</span>{else}<a href="/se-{$smarty.get.searchText}-1-/">全部({$countall})</a>{/if}</li>
			<li>{if $smarty.get.ci=="1"}
				<strong>优惠券</strong><span class="libring">({$searchlistcount.1})</span>
				{else}
					{if $searchlistcount.1>0}
					<a href="/se-{$searchTextFormat}-1-1/" title="优惠券">优惠券({$searchlistcount.1})</a>
					{else}
					优惠券(0)
					{/if}
				{/if}
			</li>
			<li>{if $smarty.get.ci=="2"}
				<strong>折扣信息</strong><span class="libring">({$searchlistcount.2})</span>
				{else}
					{if $searchlistcount.2>0}
					<a href="/se-{$searchTextFormat}-1-2/" title="折扣信息">折扣信息({$searchlistcount.2})</a>
					{else}
					折扣信息(0)
					{/if}
				{/if}
			</li>
			<li>{if $smarty.get.ci=="4"}
				<strong>同城生活</strong><span class="libring">({$searchlistcount.4})</span>
				{else}
					{if $searchlistcount.4>0}
					<a href="/se-{$searchTextFormat}-1-4/" title="同城生活">同城生活({$searchlistcount.4})</a>
					{else}
					同城生活(0)
					{/if}
				{/if}
			</li>					
			<li>{if $smarty.get.ci=="3"}
				<strong>社区</strong><span class="libring">({$searchlistcount.3})</span>
				{else}
					{if $searchlistcount.3>0}
					<a href="/se-{$searchTextFormat}-1-3/" title="社区">社区({$searchlistcount.3})</a>
					{else}
					社区(0)
					{/if}
				{/if}
			</li>			
		</ul>
		<div class="content_topright">
			<div class="content_bring">共有 <strong>{$countall}</strong> 条符合 <strong>{$searchTextRe}</strong> 的搜索结果</div>
		</div>
	</div>
	<!--头部结束-->
	<div class="clr"></div>
	<!--左侧内容开始-->
	<div id="content_left">


		{section name=loop loop=$newsearchlist}
		<!--优惠卷开始-->
		{if $newsearchlist[loop].Type==1}
			<div class="coupon_box" onmouseover="this.className='coupon_boxover'" onmouseout="this.className='coupon_box'">
				<!--优惠卷内容开始-->
				<div class="coupon_left">
					<div class="coupon_boximg"><a href="{$newsearchlist[loop].LinkURL}" target="_blank"><img src="{$newsearchlist[loop].ImageURL}" alt="{$newsearchlist[loop].oriTitle}"  onerror="this.src='/images/dahongbao.gif'"/></a></div>
					<div class="coupon_boxtext">
						<ul>
							<li class="lititle">{if $newsearchlist[loop].MerchantName}{$newsearchlist[loop].MerchantName}{/if}<a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].Title}</a></li>		
							<li class="litext">{$newsearchlist[loop].Detail}</li>
							{if $newsearchlist[loop].tagname}<li class="litag">{$newsearchlist[loop].tagname}&nbsp;&nbsp;</li>{/if}
							<li class="lisustain"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].digest}人</a>支持</li>
							<li class="lireview"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].replies}人</a>评论</li>
							<li class="litime">过期时间：{$newsearchlist[loop].ExpireDate}</li>
						</ul>
					</div>
				</div>
				<!--优惠卷内容结束-->
				<!--优惠卷logo开始-->
				<div class="coupon_logo"><a href="/se-{$smarty.get.searchText}-1-1/" title="更多优惠券"><img src="../../images/newsearch/coupon_logo.gif" alt="优惠券" /></a></div>
				<!--优惠卷logo结束-->
			</div>
		{elseif $newsearchlist[loop].Type==2}
			<div class="coupon_box" onmouseover="this.className='coupon_boxover'" onmouseout="this.className='coupon_box'">
				<!--折扣信息内容开始-->
				<div class="coupon_left">
					{if $newsearchlist[loop].ImageDownload}
					<div class="discount_boximg"><a href="{$newsearchlist[loop].LinkURL}" target="_blank"><img src="{$newsearchlist[loop].ImageURL}" alt="{$newsearchlist[loop].oriTitle}" onerror="this.src='/images/dahongbao.gif'"/></a></div>
					{/if}
					<div class="{if $newsearchlist[loop].ImageDownload}discount_box{else}discount_box1{/if}">
						<ul>
							<li class="lititle"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].Title}</a></li>		
							<li class="litext">{$newsearchlist[loop].Detail}</li>
							{if $newsearchlist[loop].tagname}<li class="litag">{$newsearchlist[loop].tagname}&nbsp;&nbsp;</li>{/if}
							<li class="lisustain"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].digest}人</a>支持</li>
							<li class="lireview"><a href="{$newsearchlist[loop].LinkURL}" target="_blank">{$newsearchlist[loop].replies}人</a>评论</li>
							<li class="litime">过期时间：{$newsearchlist[loop].ExpireDate}</li>
						</ul>
					</div>
				</div>
				<!--折扣信息内容结束-->
				<!--折扣信息logo开始-->
				<div class="coupon_logo"><a href="/se-{$smarty.get.searchText}-1-2/" title="更多折扣信息"><img src="../../images/newsearch/discount_logo.gif" alt="折扣信息" /></a></div>
				<!--折扣信息logo结束-->
			</div>
		{elseif $newsearchlist[loop].Type==3}
			<div class="bbs_box" onmouseover="this.className='bbs_boxover'" onmouseout="this.className='bbs_box'">
			<!--社区具体内容开始-->
			<div class="bbs_boxleft">
				<ul>
					<li class="lititle"><a href="/bbs/read.php?tid={$newsearchlist[loop].tid}" target="_blank">{$newsearchlist[loop].Title}</a></li>
					<li class="litext">{$newsearchlist[loop].Detail}</li>
					<li class="liauthor">by <a href="/bbs/profile.php?action=show&uid={$newsearchlist[loop].authorid}" target="_blank">{$newsearchlist[loop].author}</a> 发表于</li>				
					<li class="litime">{$newsearchlist[loop].postdate}</li>
					<li class="libbspoint">{$newsearchlist[loop].hits} 人气</li>
				</ul>
			</div>
			<!--社区具体内容结束-->
			<!--折扣信息logo开始-->
			<div class="bbs_logo"><a href="/se-{$smarty.get.searchText}-1-3/" title="社区"><img src="../../images/newsearch/bbs_logo.gif" alt="更多社区信息" /></a></div>
			<!--折扣信息logo结束-->
		</div>
		{elseif $newsearchlist[loop].Type==4}
			<div class="coupon_box" onmouseover="this.className='coupon_boxover'" onmouseout="this.className='coupon_box'">
			<!--同城生活内容开始-->
			<div class="coupon_left">				
				<div class="discount_box1">
					<ul>
						<li class="lititle"><a href="/life/view.php?id={$newsearchlist[loop].BaiXingID}" target="_blank">{$newsearchlist[loop].Title}</a></li>	
						<li class="litext">{$newsearchlist[loop].Content}</li>
					</ul>
				</div>
			</div>
			<!--同城生活内容结束-->
			<!--同城生活logo开始-->
			<div class="coupon_logo"><a href="/se-{$smarty.get.searchText}-1-4/" title="更多同城生活信息"><img src="../../images/newsearch/city_logo.gif" alt="同城生活" /></a></div>
			<!--同城生活logo结束-->
		</div>
		{/if}
		{/section}
		<!--优惠卷结束-->
		
		<!--翻页开始-->
		{if $countall>0}
		{$pageString}
		{else}
			<div class="search_box">
			<ul>
				<li class="lititle">对不起，暂时没有"{$searchText}"相关的信息。</li>
			</ul>
			</div>
		
		{/if}
		<!--翻页结束-->
	</div>
	<!--左侧内容结束-->
	
	{*百度右边广告开始*}
	<div id="content_right">
		{if count($adsbd->resultlist->result) > 6}
		<div id="preview" class="content_rightadd">
			<div class="bd_title"><img src="/images/ico_bd.gif" /></div>
			{foreach from=$adsbd->resultlist->result item=l name=list}
			{if $smarty.foreach.list.index > 5 && $smarty.foreach.list.index < 12}
			<ul>
				<li class="lititle"><a href="{$l->uri}" target="_blank">{$l->title}</a></li>
				<li class="litext"><a href="{$l->uri}" target="_blank">{$l->abstract}</a></li>
				<li class="liurl"><a href="{$l->uri}" target="_blank">{$l->SHORTURL} - <strong>推广</strong></a></li>
			</ul>
			{/if}			
			{/foreach}			
		</div>
		{/if}
	</div>
	{*百度右边广告结束*}
</div>
<!--主体内容结束-->
			
{include file="new/foot.htm"}