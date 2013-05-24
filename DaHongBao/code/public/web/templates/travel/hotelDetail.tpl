<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>{$hotelInfo.ProductName}</title>
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
			<li><a href="/travel.html" target="_blank">旅游度假</a></li>
			<li><a href="/travel-vacations.html" >旅游线路</a></li>
			<li><a href="/travel-tickets.html" target="_blank">机票</a></li>
			<li><a href="/travel-hotels.html" target="_blank"  class="active">度假酒店</a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
        <!--end leftcol -->
    	<div class="rightcol">
		
			<div class="detailwrapper">
			
				<div class="baseinfo">
					<div class="imgbox" style="width:160px;">
						<img src="{$hotelInfo.ImageURL}" width=150 />
					</div>
					<div class="hotel_description" style="float:right">
						<div><h2 class="hotel_name">{$hotelInfo.ProductName}&nbsp;{$hotelInfo.CategoryImage}</h2></div>
						<div class="hotel_intro">{if $hotelInfo.moreDes}{$hotelInfo.lessDes}<a href="javascript:void(0);" onclick="more_detail();">更多</a>
							{else}{$hotelInfo.lessDes}{/if}<span class="disn" id="detail_more">{$hotelInfo.moreDes}</span></div>
							<strong>地址</strong>：{$hotelInfo.Address}  <strong>电话</strong>：{$hotelInfo.Tel}					
					</div>
					
					<div class="clr"></div>
				
				</div>
				<!-- end baseinfo -->
				
				<div class="clr"></div>
				
				<!--compare start-->
				<div class="hotel_compare">
					<div style="padding-bottom: 10px;"><h2 class="hotel_title_h2">{$hotelInfo.ProductName}房型与房价</h2></div>
					
					<div class="ht_rsdw">
						{foreach item=styleItem from=$hotelsStyle key=key name=styleName}
						<div class="hotel_dtitle" onclick="hotel_compare('hotel_compare_{$smarty.foreach.styleName.iteration}')">
							<div class="01">{$key}</div>
							<div class="02">价格</div>
							<div class="03">预定</div>
						</div>
						<div class="clr"></div>
						
						<div id="hotel_compare_{$smarty.foreach.styleName.iteration}">
							<table class="ht_rsd" cellspacing="0" cellpadding="0" border="0">
								{section loop=$styleItem name=styleSource}
								<tr>
									<td width="30%" class="hoteldetail_merchant_name">
									<a href="{$styleItem[styleSource].offerURL}" target="_blank" rel="nofollow">{$styleItem[styleSource].merchantName}</a></td>
									<td width="35%" class="hoteldetail_merchant_price"><strong>{$styleItem[styleSource].price}</strong></td>
									<td width="35%"><a href="{$styleItem[styleSource].offerURL}" target="_blank" rel="nofollow">
									<img src="/images/travel/btn_book.gif" align="middle"></a></td>
								</tr>
								{/section}
						
							</table>
						</div>
						{/foreach}
					</div>
					
					
				</div>
				<!--compare end-->

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
         <!--end leftcol --> 
    </div>
    <!--end main -->
	
</div>
<!--end #content -->


<!--脚部开始-->
{include file="new/foot.htm"}
<!--脚部结束-->