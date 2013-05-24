<?xml version="1.0" encoding="GBK"?>
<items totalSize="{$total}">
	{section name=index loop=$couponList}
	{if $couponList[index].ID}
	<item id="{$couponList[index].ID}" no="{$couponList[index].NO}" expire="{$couponList[index].END}" start="{$couponList[index].START}">
		<merchant id="{$couponList[index].MerID}" name="{$couponList[index].MerName}"/>
		{if $couponList[index].ImageURL}
		<image url="{$couponList[index].ImageURL}" width="{$couponList[index].ImageX}" height="{$couponList[index].imageY}"/>
		{/if}
		<couponurl><![CDATA[{$couponList[index].couponURL}]]></couponurl>
		<merchanturl><![CDATA[{$couponList[index].MerURL}]]></merchanturl>
		<city><![CDATA[{$couponList[index].City}]]></city>
		<description><![CDATA[{$couponList[index].Descript}]]></description>
		<detail><![CDATA[{$couponList[index].Detail}]]></detail>
	</item>
	{/if}
	{/section}
</items>
