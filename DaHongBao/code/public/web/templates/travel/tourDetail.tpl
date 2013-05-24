<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>{$tourInfo.Name}</title>
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
			<li><a href="/travel-vacations.html"  class="active"><h1>旅游线路</h1></a></li>
			<li><a href="/travel-tickets.html">机票</a></li>
			<li><a href="/travel-hotels.html">度假酒店</a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
        <!--end leftcol -->
    	<div class="rightcol">
		
			<div class="detailwrapper">
				<div class="header"><h1>{$tourInfo.Name}</h1></div>
				<div class="baseinfo">
					<div class="imgbox">
						<img src="{$tourInfo.TourPictureUrl}" onerror="this.parentNode.style.width=0;this.width=0;this.height=0;"/>
					</div>
					<div class="colr">
						<ul>
							<li><strong>途 牛 价：</strong><span class="price">{$tourInfo.Price}元</span>/人</li>
							<li><strong>出 发 地：</strong>{$tourInfo.DepartRegionName}</li>
							<li><strong>出发日期：</strong>{if $startDateLess}<span id="lessdate">{$startDateLess}</span>
							<span class="disn" id="moredate">{$startDateArr}</span>&nbsp;<a href="javascript:void(0);" class="moredate">更多</a>{else}{$startDateArr}{/if}</li>
							<li><strong>往返交通：</strong>{$tourInfo.Traffic}</li>
							<li class="tel" style="width:220px;"><img src="/images/travel/icon_telephone.gif" align="absmiddle" style="margin: -7px 2px 0 0;">咨询或预订&nbsp;<span style="color:#076AC5;"><a href="{$tourInfo.0fferUrl}" target="_blank" rel="nofollow" style="color:#076AC5;">由途牛旅游网提供</a></span></li>	
						</ul>					
					</div>
					<div class="clr"></div>
				
				</div>
				<!-- end baseinfo -->
				
				<div class="moreinfo">
					<h2><a href="#" class="gototop">返回顶部</a>特色</h2>
					<div class="content">
						{$tourInfo.Info|nl2br}
					</div>
			  </div>
				<!-- end moreinfo -->

				<div class="moreinfo">
					<h2><a href="#" class="gototop">返回顶部</a>参考行程</h2>
					<div class="content">
						<div class="tourSectionContent">
						  {section name=dayInfo loop=$longDistance}
						  
						  <h3><span>第{$smarty.section.dayInfo.rownum}天</span><span>{$longDistance[dayInfo]}</span></h3>
						  
						  <!--start places-->
                          <div class="tourPlanPlacesList clear">
                            {section name=sightsName loop=$daySights[dayInfo]}
							<div id="22799" class="route_view_module">
                              <div>
							  	<a href="{$daySights[dayInfo][sightsName].PlaceUrl}" target="_blank">
								<img title="{$daySights[dayInfo][sightsName].PlaceName}" alt="{$daySights[dayInfo][sightsName].PlaceName}" 
									src="{$daySights[dayInfo][sightsName].PlaceImageUrl}" width="75" height="75" />
								</a>
							  </div>
                              <div class="placename">
							  <a href="{$daySights[dayInfo][sightsName].PlaceUrl}" target="_blank" class="cgrey">{$daySights[dayInfo][sightsName].PlaceName}</a></div>
                            </div>
							{/section}
							
							
							
							<div class="clr"></div>
                          </div>
						  <!--end places-->
						  
                          <div class="tourPlanContent">
                            <p></p>
                            <p>{$tourDes[dayInfo]}</p>
							
							<!--dineInfo-->
                            <p class="tourPlanFood cdyellow">早餐：{$dineInfo[dayInfo][0]}　　午餐：{$dineInfo[dayInfo][1]}　　晚餐：{$dineInfo[dayInfo][2]}</p>
                            <!--end dineInfo-->
							
							<!--Hotel-->
							{if $lives[dayInfo]}
							<p class="tourPlanHotel cdyellow">住宿：{$lives[dayInfo]}</p>
							{/if}
							<!--endHotel-->
                          </div>
						  
						  {/section}
						<div style="float:right;"><a href="{$tourInfo.0fferUrl}" target="_blank" rel="nofollow" style="color:#076AC5;"><strong>途牛网看看</storng></a></div>	                         
					  </div>
				  </div>
				</div>
				<!-- end moreinfo -->

			</div>
			<!-- end detailwrapper -->
            
        </div> 
        <!--end rightcol -->
		<div class="leftcol">
			<!--start searchwrapper -->
        	{include file="travel/leftSearch.tpl"}
			<!--end searchwrapper -->
            
			<!--start hottourlist-->
			{include file="travel/hotTour.tpl"}
            <!--end hottourlist-->
			
			<!--start hothotellist-->	
			{include file="travel/hotTickets.tpl"}
            <!--end hothotellist-->	
			
			<!--start tickets-->
			{include file="travel/hotHotel.tpl"}
            <!--end tickets-->	

        </div>
          
    </div>
    <!--end main -->
	
	
	
	
</div>
<!--end #content -->


<!--脚部开始-->
{include file="new/foot.htm"}
<!--脚部结束-->