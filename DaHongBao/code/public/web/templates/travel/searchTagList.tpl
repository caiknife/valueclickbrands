<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>{$metaTitle}</title>
<META NAME="description" CONTENT="{$metaDes}">
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
			<li><a href="/travel-vacations.html"  class="active">������·</a></li>
			<li><a href="/travel-tickets.html">��Ʊ</a></li>
			<li><a href="/travel-hotels.html">�ȼپƵ�</a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
    	
    	<div class="rightcol">
            
            <div class="hottravel">
            	<div class="travelmodule" style="height:auto;">
                	<div class="header">
						<h1>Ŀ�ĵ�Ϊ{$regionName}����·</h1>
					</div>
                	<div class="content">
                    	{section name=listName loop=$searchList}
						<div class="cat_month_hot">
							<div class="tour_center_image">
								<a href="{$searchList[listName].DetailURL}" target="_blank">
									<img src="{$searchList[listName].TourPictureUrl}" onerror="this.src = '/images/travel/tour_default.gif'"/>
								</a>
							</div>
							<div class="tour_center_normal">
								<div  class="tour_des">
									<ul>
										<li class="tour_name">
										<a href="{$searchList[listName].DetailURL}" target="_blank" style="line-height:20px;">{$searchList[listName].Name}
										</a>
										</li>
										<li class="tour_text">
										{$searchList[listName].Info|strip_tags|cutString:220:"<strong>......</strong>"}
										</li>
									</ul>
								</div>
								<div class="priorityPrice">
									<ul>
										<li class="price_view">��{$searchList[listName].Price}</li>
										<li>
										  <a class="cgreen fb" title="" href="{$searchList[listName].DetailURL}" target="_blank">
											<img src="/images/travel/cha_view.gif">
										  </a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						{sectionelse}
						 <div>û��������е���·��</div>
						{/section}
						<div class="clr"></div>
                    	{$pagerStr}
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end hottravel -->

            
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