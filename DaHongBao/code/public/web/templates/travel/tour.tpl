<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>������· - ���µ����ŵĹ�����������·���ۼ��۸�</title>
<META NAME="description" CONTENT="��������Ƶ��רҵ�Ƽ����ŵĹ�����������·�����¹����ܱ��κ;����������·,���¼۸񱨼ۡ�"/>
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
			<li><a href="/travel-vacations.html" class="active"><h1>������·</h1></a></li>
			<li><a href="/travel-tickets.html">��Ʊ</a></li>
			<li><a href="/travel-hotels.html">�ȼپƵ�</a></li>
		</ul>
	</div>
	<!-- end navtab -->
    
    <div class="main">
    	<div class="rightcol">
        	<div class="traveltopbanner" id="topbanner"></div>
            
            
            <div class="hottravel">
            	<div class="travelmodule" style="height:auto;">
                	<div class="header">
						<h2>�Ƽ�·��</h2>
					</div>
                	<div class="content">
                    	{section name=priorityName loop=$priorityList}
						<div class="cat_month_hot">
							<div class="tour_center_image">
								<a href="{$priorityList[priorityName].DetailURL}" target="_blank">
								<img style="float:left;width:90px;" alt="{$priorityList[priorityName].Name}" 
									src="{$priorityList[priorityName].TourPictureUrl}" onerror="this.src = '/images/travel/tour_default.gif'"/>
							    </a>
							</div>
							<div class="tour_center_normal">
								<div  class="tour_des">
									<ul>
										<li class="tour_name">
										<a href="{$priorityList[priorityName].DetailURL}" target="_blank">{$priorityList[priorityName].Name}</a>
										</li>
										<li class="tour_text">{$priorityList[priorityName].Info|strip_tags|cutString:170:"<strong>......</strong>"}</li>
									</ul>
								</div>
								<div class="priorityPrice">
									<ul>
										<li class="price_view">��{$priorityList[priorityName].Price}</li>
										<li><a class="cgreen fb"  href="{$priorityList[priorityName].DetailURL}" target="_blank">
										<img src="/images/travel/cha_view.gif"></a></li>
									</ul>
								</div>
							</div>
						</div>
						{/section}
                    	
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end hottravel -->


            <div class="specialTicket">
            	<div class="travelmodule">
                	<div class="header">
                        <h2>������·</h2>
                    </div>
                	<div class="content">
                    	<div class="tourlist">
                            <ul>
								{section name=tourName loop=$hotTourList}
                                <li>
									<span>��{$hotTourList[tourName].Price}</span><div class="hot_tour_channel"><a href="{$hotTourList[tourName].DetailURL}" target="_blank">{$hotTourList[tourName].Name}</a></div>  
								</li>
								{/section}
                            </ul>
                        </div>
                    
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end specialTicket -->
			
			<div class="recommendHotel">
            	<div class="travelmodule">
                	<div class="header"><h2>������·</h2></div>
                	<div class="content">
                    	<div class="list">
                        	<h3>������</h3>
                            <ul>
								{section name=newInList	loop=$newTourInList}
               					<li class="tourname new_tour_top">
									<div class="new_tour_channel"><a href="{$newTourInList[newInList].DetailURL}" class="newTourList" target="_blank">{$newTourInList[newInList].Name}</a></div>
									<span>��{$newTourInList[newInList].Price}</span>									
								</li>
								{/section}
                            </ul>
                        </div>
                    	<div class="list last">
                        	<h3>������</h3>
                            <ul>
               					{section name=newOutList loop=$newTourOutList}
               					<li class="tourname">
									<div class="new_tour_channel"><a href="{$newTourOutList[newOutList].DetailURL}" class="newTourList" target="_blank">{$newTourOutList[newOutList].Name}</a></div>
									<span style="display:block;float:left;">��{$newTourOutList[newOutList].Price}</span>
								</li>
								{/section}
                            </ul>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>
				<div class="clr"></div>
                <!--end travelmodule -->
            </div>
			<div class="clr"></div>
			
			<!--start relate places-->
			{include file="travel/relatePlaces.tpl"}
			<!--end relate places-->

        </div> 
        <!--end rightcol -->
		
		<div class="leftcol">
			<!--start searchwrapper -->
        	{include file="travel/leftSearch.tpl"}
			<!--end searchwrapper -->
            
			<!--start hottourlist-->
			<div class="hotdestination">
            	<div class="travelmodule">
                	<div class="header"><h2>���ž���</h2></div>
                	<div class="content">
						{section name=placesName loop=$hotPlacesList}
                        <a href="{$hotPlacesList[placesName].tagUrl}">{$hotPlacesList[placesName].RegionName}</a>
						{/section}
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
            <!--end hotdestination -->
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
			
			<div id="leftbanner" style="text-align:center;"></div>	

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
	condition = "page-tour";
	pageKey = "dhb_travel";
{literal}
smarter_ad = [{
		DocID: 'topbanner',
		PageKey: pageKey,
		SectionKey: 'top_banner',
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