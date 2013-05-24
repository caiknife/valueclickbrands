{include file="simple_head.tpl"}
{include file="head.tpl"}
<div id="main">
<div id="left">
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
					<div class="fourpageinfo">
				<IMG  src="/images/book_im_001.gif" alt="所有商家"  class="f">		
				<div class="fourpageinforight txt3E3E42" style="margin-left:116px; height:77px; border-bottom:#8b8b8b 1px solid;">
					<p style="margin-left:15px;"><span class="black b">1000多家商家提供优惠信息,省钱尽在大红包</span>
					点击下面任何一个商家名称，即可了解此商家最近的优惠活动情况。 
商家名称后面的数字代表此商家提供的优惠活动个数. 购物愉快！  </p>
				</div>
  	  	  </div>
			<!--end fourpageinfo -->
		<div class="allmerchantlist">
			<div class="character">
				<a href="/all_merchant.html" class="reddark">特色商家</a> | <a href="#0-9" class="blue">0-9</a> | <a href="#A" class="blue">A</a> | <a href="#B" class="blue">B</a> | <a href="#C" class="blue">C</a> | <a href="#D" class="blue">D</a> | <a href="#E" class="blue">E</a> | <a href="#F" class="blue">F</a> | <a href="#G" class="blue">G</a> | <a href="#H" class="blue">H</a> | <a href="#I" class="blue">I</a> | <a href="#J" class="blue">J</a> | <a href="#K" class="blue">K</a> | <a href="#L" class="blue">L</a> | <a href="#M" class="blue">M</a> | <a href="#N" class="blue">N</a> | <a href="#O" class="blue">O</a> | <a href="#P" class="blue">P</a> | <a href="#Q" class="blue">Q</a> | <a href="#R" class="blue">R</a> | <a href="#S" class="blue">S</a> | <a href="#T" class="blue">T</a> | <a href="#U" class="blue">U</a> | <a href="#V" class="blue">V</a> | <a href="#W" class="blue">W</a> | <a href="#X" class="blue">X</a> | <a href="#Y" class="blue">Y</a> | <a href="#Z" class="blue">Z</a>
			</div>
			{if $MerchantListOfNum}
			<div class="allmerchantlistbox">
				<span class="b"><a name="0-9">0-9</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfNum}
						<li>{if $MerchantListOfNum[index].MerURL}<a href="{$MerchantListOfNum[index].MerURL}">{$MerchantListOfNum[index].MerInfo}</a>{else}{$MerchantListOfNum[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfA}
			<div class="allmerchantlistbox">
				<span class="b"><a name="A">A</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfA}
						<li>{if $MerchantListOfA[index].MerURL}<a href="{$MerchantListOfA[index].MerURL}">{$MerchantListOfA[index].MerInfo}</a>{else}{$MerchantListOfA[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfB}
			<div class="allmerchantlistbox">
				<span class="b"><a name="B">B</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfB}
						<li>{if $MerchantListOfB[index].MerURL}<a href="{$MerchantListOfB[index].MerURL}">{$MerchantListOfB[index].MerInfo}</a>{else}{$MerchantListOfB[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfC}
			<div class="allmerchantlistbox">
				<span class="b"><a name="C">C</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfC}
						<li>{if $MerchantListOfC[index].MerURL}<a href="{$MerchantListOfC[index].MerURL}">{$MerchantListOfC[index].MerInfo}</a>{else}{$MerchantListOfC[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfD}
			<div class="allmerchantlistbox">
				<span class="b"><a name="D">D</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfD}
						<li>{if $MerchantListOfD[index].MerURL}<a href="{$MerchantListOfD[index].MerURL}">{$MerchantListOfD[index].MerInfo}</a>{else}{$MerchantListOfD[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfE}
			<div class="allmerchantlistbox">
				<span class="b"><a name="E">E</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfE}
						<li>{if $MerchantListOfE[index].MerURL}<a href="{$MerchantListOfE[index].MerURL}">{$MerchantListOfE[index].MerInfo}</a>{else}{$MerchantListOfE[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfF}
			<div class="allmerchantlistbox">
				<span class="b"><a name="F">F</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfF}
						<li>{if $MerchantListOfF[index].MerURL}<a href="{$MerchantListOfF[index].MerURL}">{$MerchantListOfF[index].MerInfo}</a>{else}{$MerchantListOfF[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfG}
			<div class="allmerchantlistbox">
				<span class="b"><a name="G">G</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfG}
						<li>{if $MerchantListOfG[index].MerURL}<a href="{$MerchantListOfG[index].MerURL}">{$MerchantListOfG[index].MerInfo}</a>{else}{$MerchantListOfG[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfH}
			<div class="allmerchantlistbox">
				<span class="b"><a name="H">H</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfH}
						<li>{if $MerchantListOfH[index].MerURL}<a href="{$MerchantListOfH[index].MerURL}">{$MerchantListOfH[index].MerInfo}</a>{else}{$MerchantListOfH[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfI}
			<div class="allmerchantlistbox">
				<span class="b"><a name="I">I</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfI}
						<li>{if $MerchantListOfI[index].MerURL}<a href="{$MerchantListOfI[index].MerURL}">{$MerchantListOfI[index].MerInfo}</a>{else}{$MerchantListOfI[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfJ}
			<div class="allmerchantlistbox">
				<span class="b"><a name="J">J</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfJ}
						<li>{if $MerchantListOfJ[index].MerURL}<a href="{$MerchantListOfJ[index].MerURL}">{$MerchantListOfJ[index].MerInfo}</a>{else}{$MerchantListOfJ[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfK}
			<div class="allmerchantlistbox">
				<span class="b"><a name="K">K</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfK}
						<li>{if $MerchantListOfK[index].MerURL}<a href="{$MerchantListOfK[index].MerURL}">{$MerchantListOfK[index].MerInfo}</a>{else}{$MerchantListOfK[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfL}
			<div class="allmerchantlistbox">
				<span class="b"><a name="L">L</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfL}
						<li>{if $MerchantListOfL[index].MerURL}<a href="{$MerchantListOfL[index].MerURL}">{$MerchantListOfL[index].MerInfo}</a>{else}{$MerchantListOfL[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfM}
			<div class="allmerchantlistbox">
				<span class="b"><a name="M">M</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfM}
						<li>{if $MerchantListOfM[index].MerURL}<a href="{$MerchantListOfM[index].MerURL}">{$MerchantListOfM[index].MerInfo}</a>{else}{$MerchantListOfM[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfN}
			<div class="allmerchantlistbox">
				<span class="b"><a name="N">N</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfN}
						<li>{if $MerchantListOfN[index].MerURL}<a href="{$MerchantListOfN[index].MerURL}">{$MerchantListOfN[index].MerInfo}</a>{else}{$MerchantListOfN[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfO}
			<div class="allmerchantlistbox">
				<span class="b"><a name="O">O</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfO}
						<li>{if $MerchantListOfO[index].MerURL}<a href="{$MerchantListOfO[index].MerURL}">{$MerchantListOfO[index].MerInfo}</a>{else}{$MerchantListOfO[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfP}
			<div class="allmerchantlistbox">
				<span class="b"><a name="P">P</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfP}
						<li>{if $MerchantListOfP[index].MerURL}<a href="{$MerchantListOfP[index].MerURL}">{$MerchantListOfP[index].MerInfo}</a>{else}{$MerchantListOfP[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfQ}
			<div class="allmerchantlistbox">
				<span class="b"><a name="Q">Q</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfQ}
						<li>{if $MerchantListOfQ[index].MerURL}<a href="{$MerchantListOfQ[index].MerURL}">{$MerchantListOfQ[index].MerInfo}</a>{else}{$MerchantListOfQ[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfR}
			<div class="allmerchantlistbox">
				<span class="b"><a name="R">R</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfR}
						<li>{if $MerchantListOfR[index].MerURL}<a href="{$MerchantListOfR[index].MerURL}">{$MerchantListOfR[index].MerInfo}</a>{else}{$MerchantListOfR[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfS}
			<div class="allmerchantlistbox">
				<span class="b"><a name="S">S</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfS}
						<li>{if $MerchantListOfS[index].MerURL}<a href="{$MerchantListOfS[index].MerURL}">{$MerchantListOfS[index].MerInfo}</a>{else}{$MerchantListOfS[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfT}
			<div class="allmerchantlistbox">
				<span class="b"><a name="T">T</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfT}
						<li>{if $MerchantListOfT[index].MerURL}<a href="{$MerchantListOfT[index].MerURL}">{$MerchantListOfT[index].MerInfo}</a>{else}{$MerchantListOfT[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfU}
			<div class="allmerchantlistbox">
				<span class="b"><a name="U">U</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfU}
						<li>{if $MerchantListOfU[index].MerURL}<a href="{$MerchantListOfU[index].MerURL}">{$MerchantListOfU[index].MerInfo}</a>{else}{$MerchantListOfU[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfV}
			<div class="allmerchantlistbox">
				<span class="b"><a name="V">V</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfV}
						<li>{if $MerchantListOfV[index].MerURL}<a href="{$MerchantListOfV[index].MerURL}">{$MerchantListOfV[index].MerInfo}</a>{else}{$MerchantListOfV[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfW}
			<div class="allmerchantlistbox">
				<span class="b"><a name="W">W</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfW}
						<li>{if $MerchantListOfW[index].MerURL}<a href="{$MerchantListOfW[index].MerURL}">{$MerchantListOfW[index].MerInfo}</a>{else}{$MerchantListOfW[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfX}
			<div class="allmerchantlistbox">
				<span class="b"><a name="X">X</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfX}
						<li>{if $MerchantListOfX[index].MerURL}<a href="{$MerchantListOfX[index].MerURL}">{$MerchantListOfX[index].MerInfo}</a>{else}{$MerchantListOfX[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfY}
			<div class="allmerchantlistbox">
				<span class="b"><a name="Y">Y</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfY}
						<li>{if $MerchantListOfY[index].MerURL}<a href="{$MerchantListOfY[index].MerURL}">{$MerchantListOfY[index].MerInfo}</a>{else}{$MerchantListOfY[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			{if $MerchantListOfZ}
			<div class="allmerchantlistbox">
				<span class="b"><a name="Z">Z</a></span>
				<img src="/images/sp.gif" class="layerimg" />				
					<ul>
					{section name=index loop=$MerchantListOfZ}
						<li>{if $MerchantListOfZ[index].MerURL}<a href="{$MerchantListOfZ[index].MerURL}">{$MerchantListOfZ[index].MerInfo}</a>{else}{$MerchantListOfZ[index].MerInfo}{/if}</li>
					{/section}
					</ul>
				<div class="cl"></div>
			</div>
			{/if}
			<div class="moremerchant">
				<a href="/all_merchant.html" class="reddark">特色商家</a>
			</div>
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
