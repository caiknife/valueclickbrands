<!--start searchwrapper -->
<div class="searchwrapper">
            	<div class="tabbox">
                	<ul id="changebg" class="{if $searchType}{$searchType}{else}ticket{/if}">
                    	<li><a href="javascript:void(0);" tag="searchticket">机票查询</a></li>
                    	<li><a href="javascript:void(0);" tag="searchhotel">酒店查询</a></li>
                    	<li><a href="javascript:void(0);" tag="searchtour">线路查询</a></li>
                    </ul>
                </div>
                <div class="content" id="search_content">
                	<div class="searchticket {if $searchType == 'line' || $searchType == 'hotel'}disn{/if}">
                        <form action=/travel_search.php method="GET" name="searchFlight" onsubmit="return checkFlight();">
						<dl>
                        	<dt>城市：</dt>
                            <dd>从 <input type="text" class="destination" name="fromCity" value="{$fromCity|default:"上海"}" id="flightFromCity" /> 到 
								<input type="text" class="txt" name="toCity" value="{$toCity}" id="flightToCity" style="width:100px;" />
							</dd>
							<div id="flightCitylist" class="cityTourList">
							  <div class="famousList">
								  <div class="hot_mudidi">
									<span>[关闭]</span>热门出发城市
								  </div>
								  <div>
								  <a href="#">上海</a><a href="#">北京</a> <a href="#">广州</a><a href="#">深圳</a> <a href="#">杭州</a>
								  <a href="#">成都</a><a href="#">南京</a> <a href="#">武汉</a><a href="#">香港</a> <a href="#">石家庄</a>
								  <a href="#">哈尔滨</a><a href="#">长沙</a> <a href="#">乌鲁木齐</a><a href="#">三亚</a> <a href="#">济南</a>
								  <a href="#">宁波</a><a href="#">合肥</a> <a href="#">福州</a><a href="#">延安</a> <a href="#">西安</a>
								  </div>
							   
							  </div>
							</div>
                        	<dt>日期：</dt>
                          	<dd>出发日期 <input id="MilanIntel_FlightStartTimeTextBox"  class="destination" type="text" name="FlightStartTime" value="{$flightStartTime|default:$smarty.now|date_format:"%Y-%m-%d"}" onclick="DoActive(this)"/>
							<img id="MilanIntel_StartTimeBtn" align="absmiddle" style="cursor: pointer;" src="/images/travel/select_date_new.gif" onclick="DoActive(document.getElementById('MilanIntel_FlightStartTimeTextBox'))"/></dd>
                        	<dd><input type="image" src="/images/travel/search_but.gif" class="but" /></dd>
                        </dl>
						<input type="hidden" name="flight" value="flight">
						</form>
                    </div>
					<div class="searchhotel {if $searchType != 'hotel'}disn{/if}">
						<form action=/travel_search.php method="get"  name="searchHotel" onsubmit="return checkHotel();">
                        <dl>
                            <dd>城&nbsp;&nbsp;&nbsp;&nbsp;市：<input type="text" class="destination" name="hotelCity" id="hotelCity" 
								value="{$hotelCity|default:"上海"}"/></dd>
                          	<dd>星&nbsp;&nbsp;&nbsp;&nbsp;级：<select name="hotelStar" class="destination">
										<option value="">不限</option>
										<option value="五星级酒店" {if $hotelStar == "五星级酒店"}selected{/if}>五星级</option>
										<option value="四星级酒店" {if $hotelStar == "四星级酒店"}selected{/if}>四星级</option>
										<option value="三星级酒店" {if $hotelStar == "三星级酒店"}selected{/if}>三星级</option>
										<option value="经济型酒店" {if $hotelStar == "经济型酒店"}selected{/if}>经济型</option>
									  </select>
							</dd>
                          	<dd>价格范围：<select name="hotelPrice" class="destination">
											<option value="">不限</option>
											<option value="0-250" {if $hotelPrice == "0-250"}selected{/if}>250以下</option>
											<option value="250-400" {if $hotelPrice == "250-400"}selected{/if}>250-400</option>
											<option value="400-600" {if $hotelPrice == "400-600"}selected{/if}>400-600</option>
											<option value="600-800" {if $hotelPrice == "600-800"}selected{/if}>600-800</option>
											<option value="800-"  {if $hotelPrice == "800-"}selected{/if}>800以上</option>
									  	  </select>
							</dd>
                          	<dd>指定酒店：<input type="text" class="destination" name="hotelName" value="{$hotelName}" /></dd>
                        	<dd><input type="image" src="/images/travel/search_but.gif" class="but" /></dd>
                        </dl><input type="hidden" name="hotel" value="hotel">
						</form>
                    </div>
			<div class="searchtour {if $searchType != 'line'}disn{/if}">
			<form action=/travel_search.php method="get"  name="searchTour" onsubmit="return checkTour();">
                        <dl>
                        	<dt>城市：</dt>
                            <dd>从 <select name="departCity" class="departCity" onchange="changeDepartCity();">{html_options values=$departRegion output=$departRegion selected=$departCity}</select>&nbsp;到&nbsp;<input type="text" class="destination"  name="destination" id="destination" value="{$destinationCity}"/></dd>
	<div id="citylist" class="cityTourList">
      <div class="famousList">
		  <div class="hot_mudidi">
			<span>[关闭]</span>热门地区/景点
		  </div>
	  	  <div id="hotTourRegion">
		  
		  </div>
       
      </div>
    </div>
                        	<dt>日期：</dt>
                          	<dd>出发日期 
							<input id="MilanIntel_StartTimeTextBox"  class="txt" type="text" name="TourStartTime" value="{if $departStartTime}{$departStartTime}{else}{$smarty.now|date_format:"%Y-%m-%d"} {/if}" onclick="DoActive(this)"/>
							<img id="MilanIntel_StartTimeBtn" align="absmiddle" style="cursor: pointer;" 
							src="/images/travel/select_date_new.gif" onclick="DoActive(document.getElementById('MilanIntel_StartTimeTextBox'))"/>--<input id="MilanIntel_BackTimeTextBox" class="txt" type="text" name="TourEndTime"  value="{$departEndTime}" onclick="DoActive(this)"/>
							<img id="MilanIntel_BackTimeBtn" align="absmiddle" style="cursor: pointer;" src="/images/travel/select_date_new.gif" onclick="DoActive(document.getElementById('MilanIntel_BackTimeTextBox'))"/></dd>
                        	<dd><input type="image" src="/images/travel/search_but.gif" class="but" /><input type="hidden" name="tour" value="tour"></dd>
                        </dl>
			</form>
                    </div>
                </div>
            </div>
            <!--end searchwrapper -->
<div id="MilanCalendarPanel" style="display:none;" onmouseover="XP_isInDateDiv=true;" onmouseout="XP_isInDateDiv=false;" >
    <div class="CloseCalendarBtn" onclick="CloseMotherPanel()" >关闭</div>
    <div class="outerLine">
      <div id="MilanLeftPanel" ></div>
      <div id="MilanRightPanel" ></div>
      <div class="clearboth"></div>
    </div>
  </div>