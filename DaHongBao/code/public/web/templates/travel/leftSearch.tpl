<!--start searchwrapper -->
<div class="searchwrapper">
            	<div class="tabbox">
                	<ul id="changebg" class="{if $searchType}{$searchType}{else}ticket{/if}">
                    	<li><a href="javascript:void(0);" tag="searchticket">��Ʊ��ѯ</a></li>
                    	<li><a href="javascript:void(0);" tag="searchhotel">�Ƶ��ѯ</a></li>
                    	<li><a href="javascript:void(0);" tag="searchtour">��·��ѯ</a></li>
                    </ul>
                </div>
                <div class="content" id="search_content">
                	<div class="searchticket {if $searchType == 'line' || $searchType == 'hotel'}disn{/if}">
                        <form action=/travel_search.php method="GET" name="searchFlight" onsubmit="return checkFlight();">
						<dl>
                        	<dt>���У�</dt>
                            <dd>�� <input type="text" class="destination" name="fromCity" value="{$fromCity|default:"�Ϻ�"}" id="flightFromCity" /> �� 
								<input type="text" class="txt" name="toCity" value="{$toCity}" id="flightToCity" style="width:100px;" />
							</dd>
							<div id="flightCitylist" class="cityTourList">
							  <div class="famousList">
								  <div class="hot_mudidi">
									<span>[�ر�]</span>���ų�������
								  </div>
								  <div>
								  <a href="#">�Ϻ�</a><a href="#">����</a> <a href="#">����</a><a href="#">����</a> <a href="#">����</a>
								  <a href="#">�ɶ�</a><a href="#">�Ͼ�</a> <a href="#">�人</a><a href="#">���</a> <a href="#">ʯ��ׯ</a>
								  <a href="#">������</a><a href="#">��ɳ</a> <a href="#">��³ľ��</a><a href="#">����</a> <a href="#">����</a>
								  <a href="#">����</a><a href="#">�Ϸ�</a> <a href="#">����</a><a href="#">�Ӱ�</a> <a href="#">����</a>
								  </div>
							   
							  </div>
							</div>
                        	<dt>���ڣ�</dt>
                          	<dd>�������� <input id="MilanIntel_FlightStartTimeTextBox"  class="destination" type="text" name="FlightStartTime" value="{$flightStartTime|default:$smarty.now|date_format:"%Y-%m-%d"}" onclick="DoActive(this)"/>
							<img id="MilanIntel_StartTimeBtn" align="absmiddle" style="cursor: pointer;" src="/images/travel/select_date_new.gif" onclick="DoActive(document.getElementById('MilanIntel_FlightStartTimeTextBox'))"/></dd>
                        	<dd><input type="image" src="/images/travel/search_but.gif" class="but" /></dd>
                        </dl>
						<input type="hidden" name="flight" value="flight">
						</form>
                    </div>
					<div class="searchhotel {if $searchType != 'hotel'}disn{/if}">
						<form action=/travel_search.php method="get"  name="searchHotel" onsubmit="return checkHotel();">
                        <dl>
                            <dd>��&nbsp;&nbsp;&nbsp;&nbsp;�У�<input type="text" class="destination" name="hotelCity" id="hotelCity" 
								value="{$hotelCity|default:"�Ϻ�"}"/></dd>
                          	<dd>��&nbsp;&nbsp;&nbsp;&nbsp;����<select name="hotelStar" class="destination">
										<option value="">����</option>
										<option value="���Ǽ��Ƶ�" {if $hotelStar == "���Ǽ��Ƶ�"}selected{/if}>���Ǽ�</option>
										<option value="���Ǽ��Ƶ�" {if $hotelStar == "���Ǽ��Ƶ�"}selected{/if}>���Ǽ�</option>
										<option value="���Ǽ��Ƶ�" {if $hotelStar == "���Ǽ��Ƶ�"}selected{/if}>���Ǽ�</option>
										<option value="�����;Ƶ�" {if $hotelStar == "�����;Ƶ�"}selected{/if}>������</option>
									  </select>
							</dd>
                          	<dd>�۸�Χ��<select name="hotelPrice" class="destination">
											<option value="">����</option>
											<option value="0-250" {if $hotelPrice == "0-250"}selected{/if}>250����</option>
											<option value="250-400" {if $hotelPrice == "250-400"}selected{/if}>250-400</option>
											<option value="400-600" {if $hotelPrice == "400-600"}selected{/if}>400-600</option>
											<option value="600-800" {if $hotelPrice == "600-800"}selected{/if}>600-800</option>
											<option value="800-"  {if $hotelPrice == "800-"}selected{/if}>800����</option>
									  	  </select>
							</dd>
                          	<dd>ָ���Ƶ꣺<input type="text" class="destination" name="hotelName" value="{$hotelName}" /></dd>
                        	<dd><input type="image" src="/images/travel/search_but.gif" class="but" /></dd>
                        </dl><input type="hidden" name="hotel" value="hotel">
						</form>
                    </div>
			<div class="searchtour {if $searchType != 'line'}disn{/if}">
			<form action=/travel_search.php method="get"  name="searchTour" onsubmit="return checkTour();">
                        <dl>
                        	<dt>���У�</dt>
                            <dd>�� <select name="departCity" class="departCity" onchange="changeDepartCity();">{html_options values=$departRegion output=$departRegion selected=$departCity}</select>&nbsp;��&nbsp;<input type="text" class="destination"  name="destination" id="destination" value="{$destinationCity}"/></dd>
	<div id="citylist" class="cityTourList">
      <div class="famousList">
		  <div class="hot_mudidi">
			<span>[�ر�]</span>���ŵ���/����
		  </div>
	  	  <div id="hotTourRegion">
		  
		  </div>
       
      </div>
    </div>
                        	<dt>���ڣ�</dt>
                          	<dd>�������� 
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
    <div class="CloseCalendarBtn" onclick="CloseMotherPanel()" >�ر�</div>
    <div class="outerLine">
      <div id="MilanLeftPanel" ></div>
      <div id="MilanRightPanel" ></div>
      <div class="clearboth"></div>
    </div>
  </div>