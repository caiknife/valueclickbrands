<?php

/**
 * class.SearchAction.php
 *-------------------------
 *
 * The show the detail of search product
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive shopping engine
 * that helps consumers to make smarter buying decisions online. We empower consumers to compare
 * the attributes of over one million products in the common channels and common categories
 * and to read user product reviews in order to make informed purchase decisions. Consumers can then
 * research the latest promotional and pricing information on products listed at a wide selection of
 * online merchants, and read user reviews on those merchants.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2006, Mezimedia. All rights reserved.
 *
 * @author     cooc <cooc_luo@smater.com.cn>
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.AliwangwangAction.php,v 1.1 2013/04/15 10:57:53 rock Exp $
 * @link       http://www.smarter.com.cn/
 * @deprecated File deprecated in Release 3.0.0
 */

class AliwangwangAction extends BaseAction {
	private $cityid = "";

	private $usercategory = ""; //默认值1。全部优惠券

	private $pagecoupon = 6;

	private $isdingzhi = 0;
	private $wwid = "";

	/**
	 * 入力check
	 */
	protected function check($request, $response) {
		require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
		//if city id is empty .. default 21 and set cookie
		$oCity = new City($_COOKIE['cityid']);
		$cityarray = $oCity->getCityArray();
		$citylist = $oCity->getCityList();

		$response->setTplValue('nowcityname',$cityarray['cityname']);
		$response->setTplValue('citylist',$citylist);

		$this->cityid = $_COOKIE['cityid'];

		$WWUserID = $request->getParameter("WWUserID");
		if($WWUserID){
			setcookie("WWUserID",$WWUserID,time()+9999999,"/");
			$this->wwid = $WWUserID;
		}else{
			$this->wwid = $_COOKIE['WWUserID'];
		}

		if(empty($this->wwid)){
			die("need wwid");
		}
	

		//获得用户定制优惠券数量
		if(!empty($_COOKIE['WWUserID'])){
			$WWUserID = $_COOKIE['WWUserID'];
			$categoryrow = AliwangwangDao::getWWUserCategory($WWUserID,$this->cityid);

			if($categoryrow['Category']==""){
				$this->isdingzhi = 0;
				$response->setTplValue('isdingzhi',$this->isdingzhi);
			}else{
				$this->usercategory = $categoryrow['Category'];
				$this->isdingzhi = 1;
				$response->setTplValue('dingzhicount',AliwangwangDao::getCategorylistCount($categoryrow['Category'],$this->cityid));
				$response->setTplValue('isdingzhi',$this->isdingzhi);
			}
		}
	}

	/**
	 * 分支
	 */
	protected function service($request, $response) {
		switch($request->getSwitch()) {
		case "dingzhi":
			 $response->setTplValue('headtype',3);
			 $this->doDingZhiAction($request, $response);
			 break;
		case "detail":
			 $this->doDetailAction($request, $response);
			 break;
		case "discount":
			 $response->setTplValue('headtype',2);
			 $this->doDiscountAction($request, $response);
			 break;
		case "tuijian":
			 $this->doTuiAction($request, $response);
			 break;
		case "search":
			 $this->doSearchAction($request, $response);
			 break;
		case "dofind":
			 $this->doFindAction($request, $response);
			 break;
		default:
			$response->setTplValue('headtype',1);
			$this->doIndexAction($request, $response);	
		}
	}

	/**
	 * 初始化
	 */
	protected function doIndexAction($request, $response) {
		$hascoupon = 1; 
		$pageid = $request->getParameter("pageid");
		if (empty($pageid) || ($pageid <=0)) {
			$pageid = 1;
		}
		$cid = $request->getParameter("cid");
		if($cid){
			$barindex = $cid;
			$response->setTplValue('cid',$cid);
			if($cid==1){
				$cid=AliwangwangDao::getCategoryAll();
			}
			if($cid==9){
				if(empty($this->usercategory)){
					$hascoupon = 0; 
				}else{
					$count = AliwangwangDao::getCategorylistCount($this->usercategory,$this->cityid);
					$querycategory = $this->usercategory;
				}
				
			}else{
				
				$count = AliwangwangDao::getCategorylistCount($cid,$this->cityid);
				$querycategory = $cid;
			}
		}else{
			if($this->usercategory){
				$barindex=9;
				$count = AliwangwangDao::getCategorylistCount($this->usercategory,$this->cityid);
				$querycategory = $this->usercategory;
			}else{
				$barindex=1;
				$cid=AliwangwangDao::getCategoryAll();
				$count = AliwangwangDao::getCategorylistCount($cid,$this->cityid);
				$querycategory = $cid;
			}	
		}
		if($hascoupon == 1){
			//print_R($allCategoryCoupon);
			$pageCount = ceil($count / $this->pagecoupon);
			if($pageid>$pageCount){
				$pageid = $pageCount;
			}
			$allCategoryCoupon = AliwangwangDao::getCategorylist($querycategory, $pageid, $this->cityid, $sort ,$this->pagecoupon);
			
			$allMerchantInfo = null;
			//print_r($allCategoryCoupon);
			for ($k = 0; $k < $this->pagecoupon; $k ++) {
				$current = $k;
				if (isset ($allCategoryCoupon[$current])) {
					$infoTmp["couponTitle"] = Utilities :: cutString($allCategoryCoupon[$current]["Descript"], 40);
					if ($allCategoryCoupon[$current]["ExpireDate"] == "0000-00-00") {
						$infoTmp["couponStatus"] = "1";
					} else {
						$infoTmp["couponStatus"] = $allCategoryCoupon[$current]["ExpireDate"];
					}

					$infoTmp["Coupon_"] = $allCategoryCoupon[$current]["Coupon_"];
					$infoTmp["ExpireDate"] = $allCategoryCoupon[$current]["ExpireDate"];
					if ($allCategoryCoupon[$current]["ImageDownload"] == "1") {
						$infoTmp["HasImage"] = 1;
						$infoTmp["ImageURL"] = __IMAGE_SRC.Utilities :: getSmallImageURL($allCategoryCoupon[$current]["Coupon_"]);
					} else {
						$infoTmp["HasImage"] = 0;
					}
					$allMerchantInfo[] = $infoTmp;
				} else {
					break;
				}
			}
		}

		$response->setTplValue('pageCount',$pageCount);
		$response->setTplValue('pageid',$pageid);
		
		$response->setTplValue('allCategoryCoupon',$allMerchantInfo);

		$hotcoupon_include = PageDao::getPage("ALI_INDEX");
		//$hotcoupon_include = strlen($oPage->get("Content")) > 0 ? $oPage->get("Content") : "";
		$response->setTplValue('hotcoupon_include',$hotcoupon_include);

		$categorylist = AliwangwangDao::getCategory();
		$response->setTplValue('categorylist',$categorylist);

		//搜索类别
		$searchcategory = AliwangwangDao::getSearchCategory();
		$response->setTplValue('searchcategory',$searchcategory);

		//头部bar定义
		$response->setTplValue('cid',$barindex);

		//头部bar定义
		$response->setTplValue('hascoupon',$hascoupon);

	
		$tplname="new/aliwangwangindex";
		$response->setTplName($tplname);
	}

	protected function doFindAction($request, $response) {
		$type = $_POST['cid'];
		$q  = trim($_POST['q']);	
		if(!$q){
			if($type==1){
				$url = UrlManager::aliUrl("coupon");
			}elseif($type==2){
				$url = UrlManager::aliUrl("discount");
			}else{
				$url = UrlManager::aliUrl("coupon","","",$type);
			}
			echo "<script>window.location.href='".$url."'</script>";
			exit();
		}
		$q = Utilities::encode($q);
		$url = UrlManager::aliUrl("search",0,$type,0,$q);
	
		echo "<script>window.location.href='".$url."'</script>";
		exit();
	}


	protected function doDingZhiAction($request, $response) {
		if($request->isPost()){
			$dzarray = array();
			foreach ($_POST as $key=>$value){
				if(substr($key,0,1)=='c'){
					$dzarray[] = $value;
				}
			}

			if(empty($dzarray)){
				$return = UrlManager::aliUrl("dingzhi");
				echo "<script>alert('请选择您感兴趣的优惠券类别')</script>";
				echo "<script>window.location.href='".$return."'</script>";
				exit();
			}else{
				$cityid = $this->cityid;
				AliwangwangDao::addDingzhi($dzarray,$cityid,$this->wwid);
				$return = UrlManager::aliUrl("dingzhi");
				echo "<script>alert('定制成功')</script>";
				echo "<script>window.location.href='".$return."'</script>";
				exit();
			}
		}
		
		//退订功能
		$type = $request->getParameter("type");
		if($type=="del"){
			$cityid = $this->cityid;
			AliwangwangDao::delDingzhi($cityid,$this->wwid);
			$return = UrlManager::aliUrl("dingzhi");
			echo "<script>alert('退订成功')</script>";
			echo "<script>window.location.href='".$return."'</script>";
			exit();
		}

		$pageid = $request->getParameter("pageid");
		if (empty($pageid)) {
			$pageid = 1;
		}

		if($this->usercategory){
			$count = AliwangwangDao::getCategorylistCount($this->usercategory,$this->cityid);
			$pageCount = ceil($count / $this->pagecoupon);
			if($pageid>$pageCount){
				$pageid = $pageCount;
			}
			$allCategoryCoupon = AliwangwangDao::getCategorylist($this->usercategory, $pageid, $this->cityid, $sort ,$this->pagecoupon);
		}else{
			$cid=AliwangwangDao::getCategoryAll();
			$count = AliwangwangDao::getCategorylistCount($cid,$this->cityid);
			$pageCount = ceil($count / $this->pagecoupon);
			if($pageid>$pageCount){
				$pageid = $pageCount;
			}
			$allCategoryCoupon = AliwangwangDao::getCategorylist($cid, $pageid, $this->cityid, $sort ,$this->pagecoupon);
		}

		$pageCount = ceil($count / $this->pagecoupon);
		
		$allMerchantInfo = null;
		for ($k = 0; $k < $this->pagecoupon; $k ++) {
			$current = $k;
			if (isset ($allCategoryCoupon[$current])) {

				$infoTmp["merName"] = $allCategoryCoupon[$current]["Name"];
				if ($allCategoryCoupon[$current]["isFree"] == 0) {
					$infoTmp["couponUrl"] = Utilities :: getURL("couponUnion", array("Category" => $_GET['cid'], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
					$infoTmp["printUrl"] = Utilities :: getURL("couponUnion", array("Category" => $_GET['cid'], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
				} else {
					$infoTmp["couponUrl"] = Utilities :: getURL("couponFree", array("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
					if ($allCategoryCoupon[$current]["ImageDownload"] == 1) {
						$printdetail = Utilities :: getURL("couponPrint", array("Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
						$infoTmp["printUrl"] = "/print.php?url=".$printdetail;
					} else {
						$infoTmp["printUrl"] = Utilities :: getURL("couponFree", array("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
					}
				}

				$infoTmp["detailUrl"] = Utilities :: getURL("couponFree", array("NameURL" => $allCategoryCoupon[$current]["NameURL"], "Coupon_" => $allCategoryCoupon[$current]["Coupon_"]));
				$infoTmp["couponTitle"] = Utilities :: cutString($allCategoryCoupon[$current]["Descript"], 40);
				$infoTmp["NameURL"] = $allCategoryCoupon[$current]["NameURL"];
				$infoTmp["Merchant_"] = $allCategoryCoupon[$current]["Merchant_"];
				if ($allCategoryCoupon[$current]["ExpireDate"] == "0000-00-00") {
					$infoTmp["couponStatus"] = "1";
				} else {
					$infoTmp["couponStatus"] = $allCategoryCoupon[$current]["ExpireDate"];
				}

				$infoTmp["Coupon_"] = $allCategoryCoupon[$current]["Coupon_"];

			
				if ($allCategoryCoupon[$current]["ImageDownload"] == "1") {
					$infoTmp["HasImage"] = 1;
					$infoTmp["ImageURL"] = __IMAGE_SRC.Utilities :: getSmallImageURL($allCategoryCoupon[$current]["Coupon_"]);
				} else {
					$infoTmp["HasImage"] = 0;
				}


				$allMerchantInfo[] = $infoTmp;
			} else {
				break;
			}
		}
		$response->setTplValue('allMerchantInfo',$allMerchantInfo);
		
		$clist = AliwangwangDao::getCategory();
		$response->setTplValue('clist',$clist);

		//搜索类别
		$searchcategory = AliwangwangDao::getSearchCategory();
		$response->setTplValue('searchcategory',$searchcategory);

		$categorylist = AliwangwangDao::getDingZhiCategory();
		$response->setTplValue('categorylist',$categorylist);

		$response->setTplValue('usercategory',$this->usercategory);
		//echo $this->usercategory;

		$response->setTplValue('pageCount',$pageCount);
		$response->setTplValue('pageid',$pageid);

		$tplname="new/aliwangwangdingzhi";
		$response->setTplName($tplname);
	}

	protected function doDetailAction($request, $response) {
		$id = $request->getParameter("id");
		$coupondetail = CouponDao::getCouponDetail($id);

		if($coupondetail['CouponType']==9){
			$headtype = 2;
		}else{
			$headtype = 1;
		}

		if ($coupondetail["ExpireDate"] == "0000-00-00") {
				$coupondetail["couponStatus"] = "1";
		} else {
				//$coupondetail["couponStatus"] = $allCategoryCoupon[$current]["ExpireDate"];
		}

		if($coupondetail["ImageDownload"]=="1"){
			$coupondetail["HasImage"]=1;
			$coupondetail["ImageURL"] = Utilities::getImageURL($coupondetail["Coupon_"]);
			//echo $couponRow["ImageURL"];
		}else{
			$coupondetail["HasImage"]=0;
		}

		$weekarray = array("","一","二","三","四","五","六","天");
		$startdate = $coupondetail['StartDate'];
		$startdate = explode("-",$startdate);
		$weekid = date("N",mktime(0, 0, 0, $startdate[1], $startdate[2], $startdate[0]));
		$week1 = $weekarray[$weekid];
		$coupondetail['StartWeek'] = $week1;

		$expiredate = $coupondetail['ExpireDate'];
		$expiredate = explode("-",$expiredate);
		$weekid = date("N",mktime(0, 0, 0, $expiredate[1], $expiredate[2], $expiredate[0]));
		$week1 = $weekarray[$weekid];
		$coupondetail['EndWeek'] = $week1;
		
		//搜索类别
		$searchcategory = AliwangwangDao::getSearchCategory();
		$response->setTplValue('searchcategory',$searchcategory);

		$coupondetail['Detail'] = Utilities :: cutString($coupondetail['Detail'],829);
		$response->setTplValue('coupondetail',$coupondetail);

		$response->setTplValue('headtype',$headtype);
		
		$tplname="new/aliwangwangdetail";
		$response->setTplName($tplname);
	}

	protected function doDiscountAction($request, $response) {
		$pageid = $request->getParameter("pageid");
		if (empty($pageid) || ($pageid <=0)) {
			$pageid = 1;
		}

		$type = $request->getParameter("type");

		//搜索类别
		$searchcategory = AliwangwangDao::getSearchCategory();
		$response->setTplValue('searchcategory',$searchcategory);

		$count = AliwangwangDao::getNewDiscountListCount($this->cityid);
		if($pageid>ceil($count/6)){
			$pageid = ceil($count/6);
		}
		$response->setTplValue('page',ceil($count/6));

		$discountlist = AliwangwangDao::getNewDiscountList($this->cityid,$pageid,$type);
		foreach($discountlist as $key=>$value){
			$discountlist[$key]['OriDescript'] = $discountlist[$key]['Descript'];
			$discountlist[$key]['Descript'] = Utilities :: cutString($discountlist[$key]['Descript'],29);
		}
		$response->setTplValue('discountlist',$discountlist);
		

		$hotdiscountlist = AliwangwangDao::getHotDiscountList($this->cityid);
		foreach($hotdiscountlist as $key=>$value){
			$hotdiscountlist[$key]['OriDescript'] = $hotdiscountlist[$key]['Descript'];
			$hotdiscountlist[$key]['Descript'] = Utilities :: cutString($hotdiscountlist[$key]['Descript'],29);
		}
		$response->setTplValue('hotdiscountlist',$hotdiscountlist);

		$response->setTplValue('pageid',$pageid);
		$response->setTplValue('type',$type);
		
		$tplname="new/aliwangwangdiscount";
		$response->setTplName($tplname);
	}

	protected function doTuiAction($request, $response) {
		$id = $request->getParameter("id");
		$coupondetail = CouponDao::getCouponDetail($id);

		$coupondetail['Detail'] = strip_tags($coupondetail['Detail']);

		if($request->isPost()){

			require_once(__INCLUDE_ROOT."lib/classes/class.xxObject.php");
			require_once(__INCLUDE_ROOT."lib/classes/class.xxMail.php");

			   $mail=new xxMail(array("X-Mailer: xxMail Class"));
			   $mail->_CRLF="\r\n";
			   
			   $tmpStr = "你好，".$mail->_CRLF.$mail->_CRLF."　　阿里旺旺有免费优惠券下载软件啦！我已下载了“".$coupondetail['Descript']."”，你也来试试看吧！".$mail->_CRLF."　　安装地址：http://mall.alisoft.com/apps/shopwindow/showDetailAppAction!show.jspa?app.appId=18430".$mail->_CRLF.$mail->_CRLF.$mail->_CRLF.$_POST['sendauthor']."发送自大红包优惠券".$mail->_CRLF.date("Y年m月d日",time()).$mail->_CRLF."（该服务由阿里软件和大红包网站合作提供）";
			   $tmpStr = $tmpStr;
			   $tmpStr = str_replace("&#160;",$mail->_CRLF,$tmpStr);
			   $tmpStr = iconv("gb2312", "utf-8", $tmpStr);
			   $sendfriendtitle = "=?GBK?B?".base64_encode("你的朋友".$_POST['sendauthor']."向您推荐了大红包免费优惠券")."?=";
			   $mail->addText($tmpStr);

			   $sendfrom = "=?GBK?B?".base64_encode("大红包优惠券")."?=";
			   $email = "support@dahongbao.com";
			   if(!$mail->buildMessage()) $mail->setError("Email发送错误！！");

				if($_POST['username1']){
					 $mail->send(
						'',
						$_POST['username1'],
						$sendfrom,
						$email,
						$sendfriendtitle,
						""
					 );
				  }

				  if($_POST['username2']){
					 $mail->send(
						'',
						$_POST['username2'],
						$sendfrom,
						$email,
						$sendfriendtitle,
						""
					 );
				  }
	
			 echo "<script>alert('发送成功')</script>";
			 $return = UrlManager::aliUrl();
			 echo "<script>window.location.href='".$return."'</script>";
			 exit();

		}
		
		if ($coupondetail["ExpireDate"] == "0000-00-00") {
				$coupondetail["couponStatus"] = "1";
		} else {
				//$coupondetail["couponStatus"] = $allCategoryCoupon[$current]["ExpireDate"];
		}
	
		//搜索类别
		$searchcategory = AliwangwangDao::getSearchCategory();
		$response->setTplValue('searchcategory',$searchcategory);

		$response->setTplValue('coupondetail',$coupondetail);
	
		$tplname="new/aliwangwangtuijian";
		$response->setTplName($tplname);
	}

	protected function doSearchAction($request, $response) {
		$hasresult = 1;
		$searchTextOri = $request->getParameter("q");
		//echo $searchTextOri;
		$cid = $request->getParameter("type");
		$pageid = $request->getParameter("pageid");


		if (empty($pageid) || ($pageid <=0)) {
			$pageid = 1;
		}
		$tplname="new/aliwangwangsearchcoupon";
		if($cid==2){
			$tplname = "new/aliwangwangsearchdiscount";
			$type = 2;
			$headtype = 2;
		}else{
			$type = 1;
			$headtype = 1;
		}
		$searchText = trim(Utilities::decode($searchTextOri)); //解码;
		

		$oSearch = new SearchDao($searchText,$type); 
		$searchlist = $oSearch->search_();
	
		if(empty($searchlist)){
			$hasresult = 0;
		}else{
			if($cid==2 || $cid==1){
				$searchlist = AliwangwangDao::searchByCategory($cid,$searchlist);
				$searchcount = count($searchlist);
				if(empty($searchlist)){
					$hasresult = 0;
				}else{
					if($cid==2){
						$pageCount = ceil($searchcount / 12);
						if($pageid>$pageCount){
							$pageid = $pageCount;
						}
						$searchlist = AliwangwangDao::getCouponDetailList($searchlist,$pageid,12);
					}else{
						$pageCount = ceil($searchcount / 6);
						if($pageid>$pageCount){
							$pageid = $pageCount;
						}
						$searchlist = AliwangwangDao::getCouponDetailList($searchlist,$pageid,6);
					}
				}
			}else{
				$searchlist = AliwangwangDao::searchByCategory($cid,$searchlist);
				$searchcount = count($searchlist);
				
				$pageCount = ceil($searchcount / 6);
				if($pageid>$pageCount){
					$pageid = $pageCount;
				}
				if(empty($searchlist)){
					$hasresult = 0;
				}else{
					$searchlist = AliwangwangDao::getCouponDetailList($searchlist,$pageid,6);
				}
			}
			
			$returnlist = array();
			$i=0;
			foreach($searchlist as $key=>$value){
				$returnlist[$i] = $value;
				$returnlist[$i]['OriDescript'] = $returnlist[$i]['Descript'];
				$returnlist[$i]['StartDate'] = $returnlist[$i]['StartDate'];
				$returnlist[$i]['Descript'] = Utilities :: cutString($returnlist[$i]['Descript'],19);
				if($returnlist[$i]['ExpireDate']=='0000-00-00'){
					$returnlist[$i]["couponStatus"] = "1";
				} else {
					$returnlist[$i]["couponStatus"] = $returnlist[$i]["ExpireDate"];
				}

				if ($returnlist[$i]["ImageDownload"] == "1") {
					$returnlist[$i]["HasImage"] = 1;
					$returnlist[$i]["ImageURL"] = __IMAGE_SRC.Utilities :: getSmallImageURL($returnlist[$i]["Coupon_"]);
				} else {
					$returnlist[$i]["HasImage"] = 0;
				}
				$i++;
			}
		}

		$searchcategory = AliwangwangDao::getSearchCategory();
		$response->setTplValue('searchcategory',$searchcategory);

		$response->setTplValue('nowsearchcategory',$cid);
		$response->setTplValue('searchTextOri',$searchText);
		$response->setTplValue('headtype',$headtype);

		$response->setTplValue('searchlist',$returnlist);
		$response->setTplValue('hasresult',$hasresult);

		$response->setTplValue('pageCount',$pageCount);
		$response->setTplValue('pageid',$pageid);

		$response->setTplName($tplname);
	}


}
?>