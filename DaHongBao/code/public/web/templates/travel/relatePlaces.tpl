<!--start relate places-->
	{if $placeSights}
	<div class="specialTicket">
            	<div class="travelmodule">
                	<div class="header">
                        <h2>Ïà¹Ø¾°µã</h2>
                    </div>
                	<div class="content" style="padding-bottom:0px;">
                    	<div class="placeslist">
                            <ul>
								{section name=sightName loop=$placeSights max=8}
                                <li>
									<div class="relatePlacePicture">
										<a href="{$placeSights[sightName].url}">
											<img width="150" height="112" align="absmiddle" src="{$placeSights[sightName].PlaceImageUrl}"/>
										</a>
									</div>
									<div class="relatePlaceName">
										<a  href="{$placeSights[sightName].PlaceUrl}">{$placeSights[sightName].PlaceName}</a>
									</div> 
								</li>
								{/section}
                            </ul>
                        </div>
                    
                        <div class="clr"></div>
                    </div>
                </div>
                <!--end travelmodule -->
            </div>
	    {/if}
<!--end relate places-->