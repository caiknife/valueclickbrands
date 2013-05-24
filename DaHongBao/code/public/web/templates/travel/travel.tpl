<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>����Ƶ�� - ������·/�ؼۻ�Ʊ/�Ǽ��Ƶ� - ����</title>
<META NAME="description" CONTENT="��������Ƶ���ṩȫ���������·�����ŵĹ��������ξ����������һվʽ���η���">
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
			<li><a href="/travel.html" class="active"><h1>���ζȼ�</h1></a></li>
			<li><a href="/travel-vacations.html">������·</a></li>
			<li><a href="/travel-tickets.html">��Ʊ</a></li>
			<li><a href="/travel-hotels.html">�ȼپƵ�</a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
    	
    	<div class="rightcol">
        	<div class="traveltopbanner" id="topbanner"></div>
            
            
            <div class="hottravel">
            	<div class="travelmodule">
                	<div class="header"><h2>���ų���</h2></div>
                	<div class="content">
                    	<div class="hotlinelist">
                        	<div class="coll">������</div>
                            <div class="colr">
                            	{section name=tourIn loop=$tourIn}
								<div class="row"><p><span>��{$tourIn[tourIn].Price}</span> ��<a href="{$tourIn[tourIn].DetailURL}" target="_blank"><img src="images/travel/cha_view.gif" /></a></p><div class="homepageHot"><a href="{$tourIn[tourIn].DetailURL}" target="_blank">{$tourIn[tourIn].Name|strip_tags}</a> </div><span style="padding-left:10px;">{$tourIn[tourIn].r_StartTime|date_format:"%m"}��</span></div>
								{/section}
                            </div>
                        </div>
                    	<div class="hotlinelist">
                        	<div class="coll">������</div>
                            <div class="colr">
                            	{section name=tourOut loop=$tourOut}
								<div class="row"><p><span>��{$tourOut[tourOut].Price}</span> ��<a href="{$tourOut[tourOut].DetailURL}" target="_blank"><img src="images/travel/cha_view.gif" /></a></p><div class="homepageHot"><a href="{$tourOut[tourOut].DetailURL}" target="_blank">{$tourOut[tourOut].Name|strip_tags}</a> </div><span style="padding-left:10px;">{$tourOut[tourOut].r_StartTime|date_format:"%m"}��</span></div>
								{/section}
                            	
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end hottravel -->


            <div class="specialTicket">
            	<div class="travelmodule">
                	<div class="header">
                        <div class="tabbox">
                        	<ul id="hot_flight">
								{foreach  item=currName from=$flightsCityList key=key}
									<li><a href="javascript:void(0);" {if $key == 0}class="active"{/if} tag="{$currName}">{$currName}</a></li>
								{/foreach}
								<li><a href="/travel-tickets.html">����</a></li>
                            </ul>
                        </div>
                        <h2>�ؼۻ�Ʊ</h2>
                    </div>
                	<div class="content" id="flight_content">
					
                    	{foreach from=$lowerPriceFlightsList item=lowerFlights key = key name=foreachName}
                    	<div class="ticketlist {if $smarty.foreach.foreachName.iteration != 1}disn{/if}" tag={$key}>
                            <ul>
								{section name=lowerFlights loop=$lowerFlights}
                                <li {if $smarty.section.lowerFlights.rownum % 3 == 0}class="last"{/if}>
									<span>��{$lowerFlights[lowerFlights].Price}</span>
									<a href="javascript:fightSearch('{$lowerFlights[lowerFlights].FromCity}', '{$lowerFlights[lowerFlights].ToCity}', '{$lowerFlights[lowerFlights].StartTime}');">
										{$lowerFlights[lowerFlights].FromCity}-{$lowerFlights[lowerFlights].ToCity}
									</a>
									{$lowerFlights[lowerFlights].StartTime|date_format:"%m��%d��"}
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
            <!--end specialTicket -->

        	<div class="travelmidbanner" id="middlebanner"></div>

            <div class="specialHotel">
            	<div class="travelmodule">
                	<div class="header">
                        <div class="tabbox hot_hotel">
                        	<ul id="hot_hotel">
								{foreach  item=currName from=$hotelCityList key=key}
									<li><a href="javascript:void(0);" {if $key == 0}class="active"{/if} tag="{$currName}">{$currName}</a></li>
								{/foreach}
								<li><a href="/travel-hotels.html">����</a></li>
                            </ul>
                        </div>
                        <h2>��ֵ�Ƶ�</h2>
                    </div>
                	<div class="content" id="hotel_content">
						
                    	{foreach from=$lowerPriceHotelList item=lowerHotel key = key name=foreachName}
						
                    	<div class="hotellist travel_hotel {if $smarty.foreach.foreachName.iteration != 1}disn{/if}" tag={$key}>
                            <ul>
								{section name=lowerHotel loop=$lowerHotel}
                                <li {if $smarty.section.lowerHotel.rownum % 2 == 0}class="last"{/if}>
									<span>��{$lowerHotel[lowerHotel].r_LowestPrice}</span>
									<div class="travel_hotelCategoryName">{$lowerHotel[lowerHotel].CategoryName}</div>
									<div class="travel_hotelName">
									<a href="{$lowerHotel[lowerHotel].DetailURL}" target="_blank">{$lowerHotel[lowerHotel].ProductName}</a></div>
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
            <!--end specialHotel -->

           
            <!--end favBox -->
            
            
            
        </div> 
        <!--end rightcol -->
         
		 <div class="leftcol">
			<!--start searchwrapper -->
			{include file="travel/leftSearch.tpl"}
			<!--end searchwrapper -->	
            
            <div class="hotdestination">
            	<div class="travelmodule">
                	<div class="header"><h2>����Ŀ�ĵ�</h2></div>
                	<div class="content">
                    	<h3>����</h3>
						{section name=hotRegionIn loop=$hotRegionIn}
                        <a href="{$hotRegionIn[hotRegionIn].tagUrl}" target="_blank">{$hotRegionIn[hotRegionIn].RegionName}</a>
						{/section}
                    	<h3>����</h3>
                        {section name=hotRegionOut loop=$hotRegionOut}
                        <a href="{$hotRegionOut[hotRegionOut].tagUrl}" target="_blank">{$hotRegionOut[hotRegionOut].RegionName}</a>
						{/section}
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end hotdestination -->

            <!--start featurecoupon-->
            {include file="travel/couponListl.tpl"}
			<!--end featurecoupon-->

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
	condition = "__DEFAULT";
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
	}
];

</script>
{/literal}
<script type="text/javascript" src="{$smarty.const.__ADHOST}js/load.js"></script>

<!--�Ų���ʼ-->
{include file="new/foot.htm"}
<!--�Ų�����-->