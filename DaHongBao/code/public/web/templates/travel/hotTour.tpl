<!--start hottourlist-->
	{if $tourHotList}
	<div class="hotdestination">
		<div class="travelmodule">
			<div class="header"><h2>热门路线</h2></div>
			<div class="content leftHot">
						<ul>
							{section name=hotName  loop=$tourHotList}
							<li>
								<div class="leftHotListName"><a href="{$tourHotList[hotName].DetailURL}" target="_blank">{$tourHotList[hotName].Name}</a></div>
								<div class="leftHotListPrice">￥{$tourHotList[hotName].Price}</div>
							</li>
							{/section}	
						</ul>
			</div>
		</div>
	</div>
	{/if}
<!--end hottourlist-->