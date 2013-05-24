<script language="JavaScript" src="/jscript/nowcity.js"></script>
<div id="header">
	<div id="head">
		<!--begin head_left-->
		<div id="head_left">
			<div class="logo"><a href="/"><img src="/images/logo.gif" alt="大红包电子优惠券大全" width="310" height="68" border="0" /></a></div>			
		
		</div>
		<!--end head_left-->
		<!--begin head_right-->
		<div id="head_right">
			<div class="libook"><a href="/useraddcoupon.php">发布优惠券</a></div>
			<div class="libbs"><a href="/bbs/">&nbsp;&nbsp;BBS社区&nbsp;&nbsp;</a></div>
		</div>
		<!--end head_right-->
		<div class="head_middle">
					  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="410" height="68">
                    <param name="movie" value="/images/hk_AD.swf" />
                    <param name="quality" value="high" />
                    <embed src="/images/hk_AD.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="410" height="68"></embed>
		      </object>
		</div>
	</div>
	<div class="nav">
		<ul class="right">
			<li><a href="/hot_coupon.html" class="reddark" title="热门的优惠">热门的优惠</a></li>
			<li><a href="/new_coupon.html" class="reddark" title="最新优惠">最新优惠</a></li>
			<li><a href="/expire_coupon.html" class="reddark" title="快过期的优惠">快过期的优惠</a></li>
			<li><a href="/freeshipping_coupon.html" class="reddark" title="免费送货">免费送货</a></li>
		</ul>
		<form method="GET" name="searchform" action="/search.php" target="_parent" style="margin-top:3px;padding:0px">
		<input maxlength="50"  class="searchbox" type="text" name="searchText" value="{$seKey}"><input type="image" src="/images/search.gif" class="searchbotton">
		</form>
		<!--城市切换开始-->
	<div style="line-height: 20px;padding-left: 10px;"> &nbsp;&nbsp;&nbsp;&nbsp;[&nbsp;城市选择：<a href="/changecity.php?id=2&type=index" rel="nofollow">上海站</a>&nbsp;&nbsp;<a href="/changecity.php?id=1&type=index" rel="nofollow">北京站</a>&nbsp;&nbsp;<a href="/changecity.php?id=5&type=index" rel="nofollow">香港站</a>&nbsp;&nbsp;<a href="/changecity.php?id=99&type=index" rel="nofollow">全国</a>&nbsp;] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当前位置：
	{literal}
	<script>
	var cityid = GetCookie("cityid");
					if(cityid==0 || cityid==2){
						document.write("上海");
					}else if(cityid==99){
						document.write("全国");
					}else if(cityid==5){
						document.write("香港");
					}else{
						document.write("北京");
					}
				</script>
				{/literal}</div>
	</div>
		</div>  
	</div>
		<!--城市切换结束-->
	</div>
</div>