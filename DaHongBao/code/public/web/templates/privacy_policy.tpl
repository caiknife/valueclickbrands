{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left" class="fillBg">
	{$RESOURCE_INCLUDE}
	<div class="categorymenu">
		<ul>
		{section name=outer loop=$category}
	  	<li><a href="{$category[outer].category_url}" class="cmenu">{$category[outer].category_name}</a></li>
		{/section}
		</ul>
	</div>
</div>

<div id="middle">
	<div class="mcontent">
		<div class="middlecontent">
			<div class="local">{$navigation_path}</div>	
			<!--end adv -->
		<div>
		<table>
		<tr>
    <td width="100%">
      <p class="H1"><b><font size="5">隐私条例 </font></b>  </p>

    <p class="MsoNormal"><font face="Arial" size="2">通常情形下，大红包不获取、保存、使用、公布用户个人身份识别资料；具体情况除外，例如，注册我方信息邮件，或者注册我方提供个性化服务（个人优惠信息）。对于此类信息，我方保留是否提供、如何提供、以及提供对象的决定权。具体条款，请参阅我方隐私政策如下：</i> <O:P> </O:P><br>
        <br>
        </font><font face="Arial" size="2">大红包是为热衷网上优惠信息的顾客提供的独立站点, 所有服务全部由Mezi Media之子机构大红包提供. Mezi Media 非常重视用户的隐私权, 因此, 我方一贯遵循信息操作原则, 并且致力于明晰信息操作公开情况。 该隐私条例既是一部分.<O:P> 
        </O:P><br>
        <br>
        </font><font face="Arial" size="2"><b style="mso-bidi-font-weight: normal">隐私条例包含范围:</b><br>
        此隐私条例适用于Mezi Media 获取、使用、保存、公开信息。任何例外情形，我方将在获取信息之前明确提示，同时用户会有选择权决定是否参与或提供信息。<O:P> 
        </O:P><br>
        <br>
        </font><font face="Arial" size="2">请注意，作为服务组成部分，Mezi Media 为第三方网站提供所需链接，同时为其提供搜索服务。我方对第三方获取信息之行为不负法律责任。同时，我方广告标识由第三方提供，可能获取用户cookies， 仅用于跟踪记录标识使用情况。</font></p>
      </td>
  </tr>
		</table>
		</div>
			{include file="foot.tpl"}
			<!--end footer -->
		</div>
		<!--end middlecontent -->
	</div>
	<!--end mcontent -->
</div>
<!--end middle -->
<!--
   make_stat({$category_cur},{$merchant_cur},{$coupon_cur},1);
   afp_stat();
//-->
</script>

</div>
<!--end main -->
</body>
</html>
