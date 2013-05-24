<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�ؼۻ�ƱԤ������ - ������Ʊ</title>
<link href="/css/travel.css" rel="stylesheet" type="text/css" media="all" />
<link href="/css/calendar.css" rel="stylesheet" type="text/css" media="all" />
<script language="javascript" type="text/javascript" src="/jscript/jquery.js"></script>
<script language="javascript" type="text/javascript" src="/jscript/calendar.js"></script>
<script language="javascript" type="text/javascript" src="/jscript/travel.js"></script>
</head>
<body>
<!--ͷ����ʼ-->
<div id="head" class="main_box">
<!--head_top ��ʼ-->
	{include file="inc/top_travel.inc.tpl"}
<!--head_topnav ����-->			
</div>
<!--�������ݿ�ʼ-->
<div id="content">
	<div class="navtab">
		<ul>
			<li><a href="/travel.html">���ζȼ�</a></li>
			<li><a href="/travel-vacations.html">������·</a></li>
			<li><a href="/travel-tickets.html" class="active"><h1>��Ʊ</h1></a></li>
			<li><a href="/travel-hotels.html">�ȼپƵ�</a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
    	
    	<div class="rightcol">
        	<div class="traveltopbanner" id="topbanner"></div>
            
			
             <div class="recommendHotel">
            	<div class="travelmodule">
                	<div class="header"><h2>�ؼۻ�Ʊ</h2></div>
                	<div class="content">
                    	
						{foreach from=$lowerPriceTicketList item=ticketItem key = key name=priorityName}
						<div class="list {if $smarty.foreach.priorityName.iteration % 2 == 0}last{/if}" >
                        	<h3>{$key}</h3>
							<ul>
							{section name=ticketName loop=$ticketItem}
							<li class="ticketList">
								<span class="ticketPrice">��{$ticketItem[ticketName].Price}</span>
								{assign var="discount" value=$ticketItem[ticketName].DisCount/100*10}
								<span class="ticketDis">{if $discount >= 10}������{else}{$discount}��{/if}</span>
								<a href="javascript:fightSearch('{$ticketItem[ticketName].FromCity}', '{$ticketItem[ticketName].ToCity}', '{$ticketItem[ticketName].StartTime}');">
									{$ticketItem[ticketName].FromCity}-{$ticketItem[ticketName].ToCity}
								</a>
								{$ticketItem[ticketName].StartTime|date_format:"%m��%d��"}
							</li>
							{/section} 
							</ul>
                        </div>
						{/foreach}
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end recommendHotel -->
         
           
            <div class="travelmidbanner" id="middlebanner"></div>
            
        </div> 
        <!--end rightcol -->
         <div class="leftcol">
			<!--start searchwrapper -->
        	{include file="travel/leftSearch.tpl"}
			<!--end searchwrapper -->
			
			<!--start hothotellist-->	
			{include file="travel/hotTour.tpl"}
            <!--end hothotellist-->	
			
			<!--start tickets-->
			{include file="travel/hotHotel.tpl"}
            <!--end tickets-->	
			
			 <!--start featurecoupon-->
            {include file="travel/couponListl.tpl"}
			<!--end featurecoupon-->
			
			<!--start left banner-->
			<div id="leftbanner" style="text-align:center;"></div>
			<!--end left banner-->
			
        </div>
        <!--end leftcol --> 
    </div>
    <!--end main -->

</div>
<!--end #content -->

{literal}
<script type="text/javascript">
{/literal}
	smarter_adhost = "{$smarty.const.__ADHOST}";
	condition = "page-ticket";
	pageKey = "dhb_travel";
{literal}
smarter_ad = [{
		DocID: 'topbanner',
		PageKey: pageKey,
		SectionKey: 'top_banner',
		ConditionKey: condition
	},
	{
		DocID: 'middlebanner',
		PageKey: pageKey,
		SectionKey: 'middle_banner',
		ConditionKey: condition
	},
	{
		DocID: 'leftbanner',
		PageKey: pageKey,
		SectionKey: 'left_banner',
		ConditionKey: condition
	}
];

</script>
{/literal}
<script type="text/javascript" src="{$smarty.const.__ADHOST}js/load.js"></script>

<!--�Ų���ʼ-->
{include file="new/foot.htm"}
<!--�Ų�����-->