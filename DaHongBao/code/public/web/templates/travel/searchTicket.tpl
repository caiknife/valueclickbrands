<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>大红包旅游频道机票搜索</title>
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
			<li><a href="/travel-tickets.html" class="active"><h1>机票</h1></a></li>
			<li><a href="/travel-hotels.html">度假酒店</a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
    	
    	<div class="rightcol">
			{if $error }
			 <div class="specialTicket">
            	<div class="travelmodule">
                	<div class="header">
                        <h2>错误提示</h2>
                    </div>
                	<div class="content" style="color:#ff0000">
 					<strong>{$error}</strong>
                    </div>
                </div>
             </div>
			{else}

            <div class="hottravel">
            	<div class="travelmodule" style="height:auto;">
                	<div class="header">
						<h2>搜索列表</h2>
						<div class="priorityList search_hotel_list">提示：你找的是 <strong>{$fromCity}</strong> 到 <strong>{$toCity}</strong> 的航班<span style="padding-left:200px;display:none;" class="load_message"><img src="/images/travel/icon_loading.gif" align="absmiddle" width="20px">正在搜索 <span id="load_message">海南航空公司</span></span></div>
					</div>
                	<div class="content">
                    	 
						 <div id="flightLoading">
							<div class="loadingPic">
								<p class="flightsLoadMsg">请稍等,您查询的结果正在实时搜索中...</p>
								<div><img src="/images/travel/loading.gif" alt="loading" width="114" height="16" /></div>
							</div>
						 </div>
						 
						 <div id="searchResult" class="disn">
						 	<div class="air_list_title">
								<ul>
									<li class="company_name">航空公司/航班号</li>
									<li class="title_airname">起止机场</li>
									<li><b class="fb_time">起降时间</b></li>
									<li class="air_plane">机型</li>
									<li class="air_tax">税费</li>
									<li class="price">推荐价格</li>	
								</ul>
							</div>
								
								
							<div class="air_list">
								
							</div>
								
						 </div>
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end hottravel -->
			<iframe id="loadiframe" width="0" scrolling="no" height="0" frameborder="0" style="display: none; margin-top: 0px; margin-bottom: 0px;" src="/ticket.php?switch=ticketSearchResult&fromCity={$fromCity}&toCity={$toCity}&flightStartTime={$flightStartTime}"></iframe>
            {/if}
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
        <!--end leftcol -->
          
    </div>
    <!--end main -->
	
</div>
<!--end #content -->


<!--脚部开始-->
{include file="new/foot.htm"}
<!--脚部结束-->