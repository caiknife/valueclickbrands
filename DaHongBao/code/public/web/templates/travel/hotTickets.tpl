<!--start hothotellist-->
			{if $lowerPriceFlightsList}	
            <div class="hotdestination">
            	<div class="travelmodule">
                	<div class="header"><h2>特价机票</h2></div>
                	<div class="content leftHot">
						<ul>
							
							{section name=hotName  loop=$lowerPriceFlightsList}
							<li>
								<div class="leftHotFlightListName">
									<a href="javascript:fightSearch('{$lowerPriceFlightsList[hotName].FromCity}', '{$lowerPriceFlightsList[hotName].ToCity}', '{$lowerPriceFlightsList[hotName].StartTime}');">
										{$lowerPriceFlightsList[hotName].FromCity}-{$lowerPriceFlightsList[hotName].ToCity}
									</a>
								</div>
								<div class="flightsDate">{$lowerPriceFlightsList[hotName].StartTime|date_format:"%m月%d日"}</div>
								<div class="leftHotFlightListPrice">￥{$lowerPriceFlightsList[hotName].Price}</div>
							</li>
							{/section}	
						</ul>
                    </div>
                </div>
            </div>
			{/if}
 <!--end hothotellist-->	