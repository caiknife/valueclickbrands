<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>大红包酒店频道酒店搜索</title>
<META NAME="description" CONTENT="{$detailInfo.Brief}">
<META NAME="keywords" CONTENT="{$detailInfo.ProductName}">
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
			<li><a href="/travel-vacations.html" >旅游线路</a></li>
			<li><a href="/travel-tickets.html">机票</a></li>
			<li><a href="/travel-hotels.html" class="active"><h1>度假酒店</h1></a></li>
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
						<div class="priorityList search_hotel_list">提示：你找的是<strong>{$hotelCity}{$hotelStar}</strong>的酒店</div>
					</div>
                	<div class="content">
                    	{section name=searchName loop=$searchList}
						<div class="cat_month_hot">
						<div class="tour_center_image">
							<a href="{$searchList[searchName].DetailURL}" title="{$searchList[searchName].ProductName}"  target="_blank">
								<img src="{$searchList[searchName].ImageURL}"/>
							</a>
						</div>
						<div class="tour_center_normal">
							<div  class="tour_des">
								<ul>
									<li class="tour_name">
									<a href="{$searchList[searchName].DetailURL}" title="{$searchList[searchName].ProductName}" target="_blank" >{$searchList[searchName].ProductName}</a>&nbsp;&nbsp;{$searchList[searchName].CategoryImage}
									</li>
									<li class="tour_text">
									{$searchList[searchName].Description|strip_tags|cutString:220:"<strong>......</strong>"}
									</li>
								</ul>
							</div>
							<div class="priorityPrice">
								<ul>
									<li class="price_view">￥{$searchList[searchName].r_LowestPrice}</li>
									<li>
									 <a class="cgreen fb" href="{$searchList[searchName].DetailURL}" target="_blank">
										<img src="/images/travel/cha_view.gif">
									  </a>
									</li>
								</ul>
							</div>
						</div>
					</div>
						{sectionelse}
						 <div><ul><li class="hotelerror">系统没有找到您要搜索的数据</li></ul></div>
						{/section}
						<div class="clr"></div>
                    	{$pageStr}
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end hottravel -->
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
			
			<!--start featurecoupon-->
            {include file="travel/couponListl.tpl"}
			<!--end featurecoupon-->	

        </div>
        <!--end leftcol -->
          
    </div>
    <!--end main -->
	
</div>
<!--end #content -->


<!--脚部开始-->
{include file="new/foot.htm"}
<!--脚部结束-->