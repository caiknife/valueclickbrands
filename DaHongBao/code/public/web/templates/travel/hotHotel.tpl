{if $lowerPriceHotelList}
<div class="hotdestination">
            	<div class="travelmodule">
                	<div class="header"><h2>³¬Öµ¾Æµê</h2></div>
                	<div class="content leftHot">
						<ul>
							{section name=hotName  loop=$lowerPriceHotelList}
							<li>
								<div class="leftHotListName"><a href="{$lowerPriceHotelList[hotName].DetailURL}" target="_blank">{$lowerPriceHotelList[hotName].ProductName}</a></div>
								<div class="leftHotListPrice">£¤{$lowerPriceHotelList[hotName].r_LowestPrice}</div>
							</li>
							{/section}	
						</ul>
                    </div>
                </div>
</div>
{/if}