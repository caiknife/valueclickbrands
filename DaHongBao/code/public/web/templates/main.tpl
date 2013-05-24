{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<!--Start Left-->
<div id="left" class="fillBg">
	<!--Start Resource-->
	{$RESOURCE_INCLUDE}
	<!--End Resource-->
	<!-- BEGIN DYNAMIC BLOCK: category_list -->
	<div class="categorymenu">
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
	<!-- END DYNAMIC BLOCK: category_list -->
	<!--Start-->
	{$NEWCOUPON_INCLUDE}
	<!--End-->
	<!--Start Hotcoupon-->
	<!--Start-->
	{$HOTCOUPON_INCLUDE}
	<!--End-->
	<!--End Hotcoupon-->

</div>
<!--End Left-->

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			{$NAVIGATION_PATH}
			{$MAIN_CONTENT}
			{include file="foot.tpl"}
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->
</div>
<!--end main -->
</body>
</html>