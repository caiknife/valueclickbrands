<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>����ڵ���Ϣ - ���������̳��ṩ��������Ż�ȯ</title>
<META NAME="description" CONTENT="���������̳�Ϊ���ṩ��������IN�������е��Ż�ȯ���ۿ�ȯ�������ж��������鼮�������Ʒ����ױƷ���ʻ��ȸ�����Ʒ���ۿ���Ϣ��">
<META NAME="keywords" CONTENT="">
<meta name="copyright" content="www.dahongbao.com">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="verify-v1" content="QVIPb4M3AU1SQ7HQONfC2lrlH7RkeUn+PL1dLv8l5fs=" />
<link href="../css/detail.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<!--ͷ����ʼ-->
{include file="new/head.htm"}
<!--ͷ������-->
<!--�������ݿ�ʼ-->
<div id="content">
	<!--category_title ������ʼ-->
	<div id="category_title">
		<span class="bring">��ǰλ��:</span> <a href="/">��ҳ</a> > <h1>����ڵ��Żݹ���ȯ</h1>
	</div>
	<!--category_title ��������-->		
	<!--sidebar ��Ŀҳ������ݿ�ʼ-->
	<div id="sidebar">
		<!--sidebox �����Ż�ȯ��ʼ-->
		<div class="sidebox">
			<h2>�����Ż�ȯ</h2>
			<ul>
				{section name=loop loop=$newsestcoupon start=0 max=10}
				<li>����<a href="/Me-{$newsestcoupon[loop].NameURL}--Mi-{$newsestcoupon[loop].Merchant_}.html">{$newsestcoupon[loop].name1}</a>��<a href="/{$newsestcoupon[loop].NameURL}/coupon-{$newsestcoupon[loop].Coupon_}/">{$newsestcoupon[loop].Descript}</a></li>
				{/section}
			</ul>
		</div>
		<!--sidebox �����Ż�ȯ����-->
			
		<!--sidebox �����Ż�ȯ��ʼ-->
		<div class="sidebox">
			<h2>������Ѷ</h2>
			<ul>
				{section name=loop loop=$hotinfo start=0 max=10}
				<li>��<a href="/news---Ca-{$NameURL}--Ci-{$hotinfo[loop].fid}--number-{$hotinfo[loop].tid}.html">{$hotinfo[loop].subject}</a></li>
				{/section}
			</ul>
		</div>
		<!--sidebox �����Ż�ȯ����-->
			
		<!--sidebox �����Ż�ȯ��ʼ-->
		<div class="sidebox">
			<h2>��̳����</h2>
			<ul>
				{section name=loop loop=$hotbbs start=0 max=10}
				<li>��<a href="/bbs/read.php?tid={$hotbbs[loop].tid}">{$hotbbs[loop].subject}</a></li>
				{/section}
			</ul>
		</div>
		<!--sidebox �����Ż�ȯ����-->
	</div>
	<!--sidebar ��Ŀҳ������ݽ���-->	
	
	<!--detail �������ݿ�ʼ-->
	<div id="detail">
		<div id="detail_title2"><h2>���� ���ڵ��Ż�ȯ���ۿ�ȯ</h2></div>
		<!--list_box �б�ʼ-->
		<div class="list_box">
			<ul>
				{section name=index loop=$couponTodayList}
				<li class="lileft">��<a href="{$couponTodayList[index].couponURL}">{$couponTodayList[index].couponTitle}</a></li>
				<li class="liright">{$couponTodayList[index].status}</li>
				{/section}
			</ul>
		</div>
		<!--list_box �б����-->			
	</div>

	<div id="detail">
		<div id="detail_title2"><h2>���� ���ڵ��Ż�ȯ���ۿ�ȯ</h2></div>
		<!--list_box �б�ʼ-->
		<div class="list_box">
			<ul>
				{section name=index loop=$couponTomorrowList}
				<li class="lileft">��<a href="{$couponTomorrowList[index].couponURL}">{$couponTomorrowList[index].couponTitle}</a></li>
				<li class="liright">{$couponTomorrowList[index].status}</li>
				{/section}
			</ul>
		</div>
		<!--list_box �б����-->			
	</div>

	<div id="detail">
		<div id="detail_title2"><h2>���� ���ڵ��Ż�ȯ���ۿ�ȯ</h2></div>
		<!--list_box �б�ʼ-->
		<div class="list_box">
			<ul>
				{section name=index loop=$couponAfterTomorrowList}
				<li class="lileft">��<a href="{$couponAfterTomorrowList[index].couponURL}">{$couponAfterTomorrowList[index].couponTitle}</a></li>
				<li class="liright">{$couponAfterTomorrowList[index].status}</li>
				{/section}
			</ul>
		</div>
		<!--list_box �б����-->			
	</div>
	<!--detail �������ݽ���-->	
</div>
<!--�������ݽ���-->

{include file="new/foot.htm"}
