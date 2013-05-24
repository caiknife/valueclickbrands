<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>各大城市热门酒店预订 - 大红包酒店信息频道</title>
<meta name="description" content="大红包酒店信息频道推荐专业的度假酒店预订服务，提供热门酒店介绍、度假酒店照片以及价格介绍。" />
<link href="/css/travel.css" rel="stylesheet" type="text/css" media="all" />
<link href="/css/calendar.css" rel="stylesheet" type="text/css" media="all" />
<script language="javascript" type="text/javascript" src="/jscript/jquery.js"></script>
<script language="javascript" type="text/javascript" src="/jscript/calendar.js"></script>
<script language="javascript" type="text/javascript" src="/jscript/travel.js"></script>
</head>
<body>
<!--头部开始-->
<div id="head" class="main_box">
<!--head_top 开始-->
	{include file="inc/top_travel.inc.tpl"}
<!--head_topnav 结束-->			
</div>
<!--主体内容开始-->
<div id="content">
	<div class="navtab">
		<ul>
			<li><a href="/travel.html">旅游度假</a></li>
			<li><a href="/travel-vacations.html">旅游线路</a></li>
			<li><a href="/travel-tickets.html">机票</a></li>
			<li><a href="/travel-hotels.html" class="active"><h1>度假酒店</h1></a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
    	<div class="rightcol">
        	<div class="traveltopbanner" id="topbanner"></div>
            
            
            <div class="recommendHotel">
            	<div class="travelmodule">
                	<div class="header"><h2>热门城市酒店</h2></div>
                	<div class="content">
                    	
						{foreach from=$priorityList item=priorityItem key = key name=priorityName}
						<div class="list {if $smarty.foreach.priorityName.iteration % 2 == 0}last{/if}" style="height:280px;">
                        	<h3>{$key}</h3>
							{section name=cityPriorityName loop=$priorityItem}
                            <ul>
								
                            	<li class="hotelimg">
                                	<a href="{$priorityItem[cityPriorityName].DetailURL}" target="_blank">
										<img src="{$priorityItem[cityPriorityName].ImageURL}"  alt="{$priorityItem[cityPriorityName].ProductName}" />
									</a>
                                </li>
                                <li class="hotelname">
								<a href="{$priorityItem[cityPriorityName].DetailURL}" target="_blank">{$priorityItem[cityPriorityName].ProductName}</a>
								</li>
                                <li class="hotelstar">星级：{$priorityItem[cityPriorityName].CategoryImage}</li>
                                <li>地址：{$priorityItem[cityPriorityName].Address}</li>
                                <li>电话：{$priorityItem[cityPriorityName].Tel}</li>
                            </ul>
							{/section}
                        </div>
						{/foreach}
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end recommendHotel -->



        	<div class="travelmidbanner" id="middlebanner"></div>

            <div class="recommendHotel hothotel">
            	<div class="travelmodule">
                	<div class="header"><h2>推荐酒店</h2></div>
                	<div class="content">
                    	<div class="list">
							{section name=hotHotelCity loop=$hotCityHotel}
							{if $smarty.section.hotHotelCity.rownum == 5} </div><div class="list last">{/if}
                            <ul>
                            	<li class="hotelimg">
                                	<a href="{$hotCityHotel[hotHotelCity].DetailURL}" target="_blank">
										<img src="{$hotCityHotel[hotHotelCity].ImageURL}"  alt="{$hotCityHotel[hotHotelCity].ProductName}" />
									</a>
                                </li>
                                <li class="hotelname"><a href="{$hotCityHotel[hotHotelCity].DetailURL}" target="_blank">
									{$hotCityHotel[hotHotelCity].ProductName}</a></li>
                                <li class="hotelstar">星级:{$hotCityHotel[hotHotelCity].CategoryImage}</li>
                                <li>地址：{$hotCityHotel[hotHotelCity].Address}</li>
                                <li>电话：{$hotCityHotel[hotHotelCity].Tel}</li>
                            </ul>
                           	{/section}
                        </div>
						
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end hotHotel -->
            
        </div> 
        <!--end rightcol -->
		
		<div class="leftcol">
			<!--start searchwrapper -->
        	{include file="travel/leftSearch.tpl"}
			<!--end searchwrapper -->
            
			<!--start tickets-->
			{include file="travel/hotTour.tpl"}
            <!--end tickets-->	
			
			<!--start hothotellist-->	
			{include file="travel/hotTickets.tpl"}
            <!--end hothotellist-->
			
			 <!--start featurecoupon-->
            {include file="travel/couponListl.tpl"}
			<!--end featurecoupon-->
			
			<div id="leftbanner" style="text-align:center;"></div>	
	
		<!--end leftcol -->
        </div>
          
    </div>
    <!--end main -->
	
</div>
<!--end #content -->

{literal}
<script type="text/javascript">
{/literal}
	smarter_adhost = "{$smarty.const.__ADHOST}";
	condition = "page-hotel";
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
<!--脚部开始-->
{include file="new/foot.htm"}
<!--脚部结束-->