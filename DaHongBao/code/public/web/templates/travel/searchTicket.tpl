<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��������Ƶ����Ʊ����</title>
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
			{if $error }
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
						<h2>�����б�</h2>
						<div class="priorityList search_hotel_list">��ʾ�����ҵ��� <strong>{$fromCity}</strong> �� <strong>{$toCity}</strong> �ĺ���<span style="padding-left:200px;display:none;" class="load_message"><img src="/images/travel/icon_loading.gif" align="absmiddle" width="20px">�������� <span id="load_message">���Ϻ��չ�˾</span></span></div>
					</div>
                	<div class="content">
                    	 
						 <div id="flightLoading">
							<div class="loadingPic">
								<p class="flightsLoadMsg">���Ե�,����ѯ�Ľ������ʵʱ������...</p>
								<div><img src="/images/travel/loading.gif" alt="loading" width="114" height="16" /></div>
							</div>
						 </div>
						 
						 <div id="searchResult" class="disn">
						 	<div class="air_list_title">
								<ul>
									<li class="company_name">���չ�˾/�����</li>
									<li class="title_airname">��ֹ����</li>
									<li><b class="fb_time">��ʱ��</b></li>
									<li class="air_plane">����</li>
									<li class="air_tax">˰��</li>
									<li class="price">�Ƽ��۸�</li>	
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


<!--�Ų���ʼ-->
{include file="new/foot.htm"}
<!--�Ų�����-->