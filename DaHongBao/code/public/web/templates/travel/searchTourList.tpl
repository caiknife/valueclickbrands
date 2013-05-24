<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��������Ƶ����·����</title>
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
			<li><a href="/travel-vacations.html"  class="active"><h1>������·</h1></a></li>
			<li><a href="/travel-tickets.html">��Ʊ</a></li>
			<li><a href="/travel-hotels.html">�ȼپƵ�</a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
    	
    	<div class="rightcol">
			{if $error}
				<div class="specialTicket">
            	<div class="travelmodule">
                	<div class="header">
                        <h2>������ʾ</h2>
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
						<h2>����·��&nbsp;&nbsp;</h2>
						<div class="priorityList">��ʾ�����ҵ���<strong>{$departCity}</strong>-<strong>{$destinationCity}</strong>�ȼ�·��</div>
					</div>
                	<div class="content">
                    	{section name=priorityName loop=$priorityList}
						<div class="cat_month_hot">
							<div class="tour_center_image">
								<a href="{$priorityList[priorityName].DetailURL}" title="{$priorityList[priorityName].Name}"  target="_blank">
									<img src="{$priorityList[priorityName].TourPictureUrl}" onerror="this.src = '/images/travel/tour_default.gif'"/>
								</a>
							</div>
							<div class="tour_center_normal">
								<div  class="tour_des">
									<ul>
										<li class="tour_name">
										<a href="{$priorityList[priorityName].DetailURL}" 
											target="_blank" >{$priorityList[priorityName].Name}</a>
										</li>
										<li class="tour_text">
										{$priorityList[priorityName].Info|strip_tags|cutString:220:"<strong>......</strong>"}
										</li>
									</ul>
								</div>
								<div class="priorityPrice">
									<ul>
										<li class="price_view">��{$priorityList[priorityName].Price}</li>
										<li>
										 <a class="cgreen fb" href="{$priorityList[priorityName].DetailURL}" target="_blank">
											<img src="/images/travel/cha_view.gif">
										  </a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						{/section}
						<div class="toursearchlist">
							<ul>
								{section name=searchName loop=$searchList}
								<li>
									<span>��{$searchList[searchName].Price}</span><a href="{$searchList[searchName].DetailURL}">{$searchList[searchName].Name}</a>  
								</li>
								{sectionelse}
								 <li>ϵͳû���ҵ���Ҫ����������</li>
								{/section}
							</ul>
						</div>
						<div class="clr"></div>
                    	{$pageStr}
                        <div class="clr"></div>
                    </div>
                </div>
			</div>
            <!--end hottravel -->
            

        	<div class="travelmidbanner" id="middlebanner"></div>
			
			<!--start relate places-->
			{include file="travel/relatePlaces.tpl"}
			<!--end relate places-->
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


<!--�Ų���ʼ-->
{include file="new/foot.htm"}
<!--�Ų�����-->