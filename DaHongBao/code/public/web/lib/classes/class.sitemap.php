<?php
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");

class Sitemap {
	function Sitemap(){
		//echo "123";

		$oCoupon = new Coupon();
		$couponList = $oCoupon->getNewCouponListForCoupon();
		for ( $i=0; $i < count($couponList) && $i < 10; $i++ ){
			$newCouponFinal[$i]["couponURL"] = Utilities::getURL("couponUnion", array("Merchant_" => $couponList[$i]["Merchant_"],
												"Coupon_" => $couponList[$i]["Coupon_"]));
			$newCouponFinal[$i]["couponTitle"] = $couponList[$i]["Descript"];
		}


		$oCategory = new Category();
		$categoryarray = $oCategory->getCategoryList();

			$categoryList = $oCategory->getCategoryList("SitemapPriority");
			for($j=0; $j<count($categoryList); $j++) {
			$categoryForShow[$j]["category_url"] = Utilities::getURL("category", 
				array("NameURL" => $categoryList[$j]["NameURL"],"Cid" => $categoryList[$j]["Category_"],"Page" => 1));
			$categoryForShow[$j]["category_name"] = $categoryList[$j]["Name"];
			}
			$tpl->assign("category", $categoryForShow);
		
		$tpl = new sTemplate();
		$tpl->setTemplate("new/map1.htm");
		$tpl->assign("newCouponlist", $newCouponFinal);
		$tpl->assign("categoryarray",$categoryarray);

		$strContent = $tpl->getTemplateContents();
				
		if($this->doCreateHtmlFile(__INCLUDE_ROOT."pages/sitemap/index.html",$strContent)){
			echo "OK";
		}

		for($i=0;$i<count($categoryarray);$i++){
			$couponarray = $this->getCategorylist($categoryarray[$i]['Category_'],"","all");
			$allcouponnum = count($couponarray);

			$pageNum = ceil(count($couponarray)/100);
			for($j=0;$j<$pageNum;$j++){
				$tpl = new sTemplate();
				if($j==0){
					$url = __INCLUDE_ROOT."pages/sitemap/".$categoryarray[$i]['NameURL'].".html";
				}else{
					$url = __INCLUDE_ROOT."pages/sitemap/".$categoryarray[$i]['NameURL']."-".($j+1).".html";
				}
				if($categoryarray[$i]['Name']=="其他"){
					$categoryarray[$i]['Name']="其他类";
				}

				$pageStr = $this->getPageStrs($allcouponnum, 100, $j*100, $categoryarray[$i]['NameURL'], "", $categoryarray[$i]['Name']."优惠券");
				
				$couponarray = $this->getCategorylist($categoryarray[$i]['Category_'],$j,"");
				for($k=0;$k<count($couponarray);$k++){
				//	$couponarray[$k]["Descript"] = Utilities::cutString($couponarray[$k]["Descript"],40);

					if($couponarray[$k]["isFree"] == 0) {
						$couponarray[$k]["couponUrl"] = $couponarray[$k]["couponUrl"];
					} else {
						$couponarray[$k]["couponUrl"] = Utilities::getURL("couponFree", array("NameURL" => $couponarray[$k]["NameURL"],"Coupon_" => $couponarray[$k]["Coupon_"]));
					}
				}

				
				
				$tpl->setTemplate("map2.tpl");
				$tpl->assign("couponarray",$couponarray);
				$tpl->assign("newCouponlist", $newCouponFinal);
				$tpl->assign("pageStr",$pageStr);
				$tpl->assign("name",$categoryarray[$i]['Name']);
				$tpl->assign("nameurl",$categoryarray[$i]['NameURL']);
				

				$strContent = $tpl->getTemplateContents();
				if($this->doCreateHtmlFile($url,$strContent)){
					echo "OK";
				}
			}

		}
	}

	function getPageStrs($allnum, $perpage, $bn, $perName, $perID, $itemstr) {
        $newbn = $bn + $perpage;
        $allpage = ceil($allnum/$perpage);
        $curpage = ceil($newbn/$perpage);
        $this->curPage = $curpage;
        $this->perPage = $perpage;
        $this->totalPage = $allpage;
        $this->totalRow = $allnum;
        $this->url = $thisurl;
        $this->itemStr = $itemstr;
        $URLHead = $perName . "-" . $perID;
        if ($allpage>10)
        {//{{
            if ($curpage<=10)
            {
                if($curpage==10) {
                    $frompx = 10;
                } else {
                    $frompx = 1;
                }

                $topx = $frompx+10;
                if ($curpage==10) {
                    $PageURL = $perName . ".html";
                    $eachpage .= "<A href='".$PageURL."' class='blue'>1</A> ... ";
                }
                for($px=$frompx;$px<=$topx;$px++){
                    if($px>10 && $curpage<10){
                         continue;
                    }
                    if($curpage!=$px){
                        if($px==10) {
                            $PageURL = $URLHead . "" . $px . ".html";
                            $eachpage .= "<A href='".$PageURL."' class='blue'>".($px)."</A> ";
                        }else{
							if($px == 1) {
								$PageURL = $perName . ".html";
							}else{
								$PageURL = $URLHead . "" . $px . ".html";
							}
                            //$PageURL = $URLHead . "_" . $px . ".html";
                            $eachpage .= "<A href='".$PageURL."' class='blue'>".($px)."</A> ";
                        }

                    }else{
                        if($curpage==10){
                            $pp = $px-1;
                            $PageURL = $URLHead . "" . $pp . ".html";
                            $eachpage .= "<A href='".$PageURL."' class='blue'>".($px-1)."</A> ";
                        }
                        $eachpage .= "<B>".$px."</B> ";
                    }
                }

                if($curpage!=$allpage){
                    $PageURL = $URLHead . "" . $allpage . ".html";
                    $eachpage .= "... <A href='".$PageURL."' class='blue'>".$allpage."</A> ";
                }
            }
            else
            {
                $PageURL = $perName . ".html";
                $eachpage .= "<A href='".$PageURL."' class='blue'>1</A> ... ";
                if ($curpage >($allpage-10)) {
                    $frompx = $curpage-($curpage%10);
                    $topx = $allpage;

                    if($curpage==$frompx){
                        $frompx = $frompx-10;
                        if(($frompx+11)==$allpage)
                            $topx = $frompx+10;
                        else
                            $topx = $frompx+11;
                        if($topx>$allpage){
                            $topx = $allpage;
                        }
                    }

                    for($px=$frompx;$px<=$topx;$px++){
                        if($curpage!=$px){
							if($px == 1) {
								$PageURL = $perName .".html";							
							}else {
	                            $PageURL = $URLHead . "" . $px . ".html";
							}
                            
                            if($px==$frompx && ($curpage%10==0)){

                                    $eachpage .= "<A href='".$PageURL."' class='blue'>".$px."</A> ";
                            }else{
                                if($px==$topx)
                                    $eachpage .= "<A href='".$PageURL."' class='blue'>".$px."</A> ";

                                else
                                    $eachpage .= "<A href='".$PageURL."' class='blue'>".$px."</A> ";
                            }
                        }else{
                                $eachpage .= "<B>".$curpage."</B> ";
                        }
                    }
                } else {

                    if($curpage%10==0){
                        $frompx = $curpage-10;
                    }else{
                        $frompx = $curpage-($curpage%10);
                    }

                    $topx = $frompx+10;
                    for($px=$frompx;$px<=$topx;$px++){
                        if($curpage!=$px){
                            $PageURL = $URLHead . "" . $px . ".html";
                            if($px==$frompx){
                                $eachpage .= "<A href='".$PageURL."' class='blue'>".$px."</A> ";
                            }else{
                                $eachpage .= "<A href='".$PageURL."' class='blue'>".$px."</A> ";
                            }
                        }else{

                            if($px==$frompx){
                                if($curpage%10 ==0){
                                    $PageURL = $URLHead . "" . ($px-1) . ".html";
                                    $eachpage .= "<A href='".$PageURL."' class='blue'>".($px-1)."</A> ";
                                }

                                $eachpage .=" ".$px." ";
                            }elseif($px==$topx){
                                $eachpage .=" ".$px." ";
                                if($curpage%10 ==0){
                                    $PageURL = $URLHead . "" . ($px+1) . ".html";
                                    $eachpage .= "<A href='".$PageURL."' class='blue'>".($px+1)."</A> ";
                                }

                            }else{
                                $eachpage .=" <B>".$px."</B> ";
                            }
                        }
                    }
                    if($curpage!=$allpage){
                        $PageURL = $URLHead . "" . $allpage . ".html";
                        $eachpage .= "... <A href='".$PageURL."' class='blue'>".$allpage."</A> ";
                    }
                }
            }
        }//}}
        else
        {//{{
            for ($px=1;$px<=$allpage;$px++) {
                if($curpage!=$px) {
                    if ($px == 1) {
                        $PageURL = $perName . ".html";
                        $eachpage .= "<A href='".$PageURL."' class='blue'>".$px."</A> ";
                    } else {
                        $PageURL = $URLHead . "" . $px . ".html";
                        $eachpage .= "<A href='".$PageURL."' class='blue'>".$px."</A> ";
                    }
                } else {
                    $eachpage .= "<B>".$px."</B> ";
                }
            }
        }//}}
        $eachpage ="&lt; ".$eachpage." &gt;";

        $fromrow = $perpage*($curpage-1);
        //$fromrow = $fromrow;
        $torow = $fromrow+$perpage;

        //kelvin 2004-08-27
        if($torow>$allnum) $torow = $allnum;

        if($fromrow==0) $fromrow=1;
        if($curpage==1) {
            if($curpage>=$allpage) {
                $pagestr = "显示 ".$itemstr." ".$fromrow."-".$torow." 共 ".$allnum." 条 ".$eachpage;
            } else {
                $PageURL = $URLHead . "" . ($curpage+1) . ".html";
                $pagestr = "显示 ".$itemstr." ".$fromrow."-".$torow." 共 ".$allnum." 条 ".$eachpage." <A href='".$PageURL."' class='blue'>后".$perpage."条</A> ";
            }
        } else {
            if($curpage>=$allpage){
                $PageURL = $URLHead . "" . ($curpage-1) . ".html";
                $pagestr = "显示 ".$itemstr." ".($fromrow+1)."-".$torow." 共 ".$allnum." 条 | <A href='".$PageURL."' class='blue'>前".$perpage."条</A> ".$eachpage;
            }else{
				if($curpage-1==1){
					$PageURL = $perName .".html";
				}else{
					$PageURL = $URLHead . "" . ($curpage-1) . ".html";
				}

                $PageURL2 = $URLHead . "" . ($curpage+1) . ".html";
                $pagestr = "显示 ".$itemstr." ".($fromrow+1)."-".$torow." 共 ".$allnum." 条 | <A href='".$PageURL."' class='blue'>前".$perpage."条</A> ".$eachpage." <A href='".$PageURL2."' class='blue'>后".$perpage."条</A> ";
            }
        }
        if($allpage>17) {
            //$pagestr = "<br>".$pagestr;
			$pagestr = $pagestr;
        }
        if($allnum<1) {
            $pagestr = "no ".$itemstr;
        }

        $this->fromRow = $fromrow;

        return $pagestr;
    }

	function getCategorylist($categoryid,$page,$type){
		if($type=="all"){
			$sql = "SELECT p.Coupon_,p.CouponType,p.Merchant_,p.Descript,ExpireDate,StartDate,Amount,City,CityID,Hasmap," .
			        "p.isFeatured,p.isActive,p.isDelete,isFreeShipping," .
					"IF(p.Descript <> p.Detail,1,0) isMore," .
					"IF(length(p.Detail) > 0,1,0) hasDetail," .
					"ImageURL1,p.ImageDownload,p.isFree,m.Name,m.NameURL,m.isShow FROM Coupon p " .
			        "INNER JOIN CoupCat c ON c.Coupon_=p.Coupon_ " .
					"LEFT JOIN Merchant m ON m.Merchant_=p.Merchant_ " .
					"WHERE c.Category_=".$categoryid.
					" AND (m.isFree=1 or m.isFree IS NULL or m.isFree=0)" .
					" AND p.isActive=1 " .
				    " AND LENGTH(p.Descript) >= 4" .
					" ORDER BY m.isBold DESC,m.isPremium DESC,m.Rating DESC,m.Merchant_,p.ExpireDate ASC";
		}else{
			$start = $page*100;
			$sql = "SELECT p.Coupon_,p.CouponType,p.Merchant_,p.Descript,ExpireDate,StartDate,Amount,City,CityID,Hasmap," .
			        "p.isFeatured,p.isActive,p.isDelete,isFreeShipping," .
					"IF(p.Descript <> p.Detail,1,0) isMore," .
					"IF(length(p.Detail) > 0,1,0) hasDetail," .
					"ImageURL1,p.ImageDownload,p.isFree,m.Name,m.NameURL,m.isShow FROM Coupon p " .
			        "INNER JOIN CoupCat c ON c.Coupon_=p.Coupon_ " .
					"LEFT JOIN Merchant m ON m.Merchant_=p.Merchant_ " .
					"WHERE c.Category_=".$categoryid.
					" AND (m.isFree=1 or m.isFree IS NULL or m.isFree=0)" .
					" AND p.isActive=1 " .
				    " AND LENGTH(p.Descript) >= 4" .
					" ORDER BY m.isBold DESC,m.isPremium DESC,m.Rating DESC,m.Merchant_,p.ExpireDate ASC limit $start,100";
		}
			$allCategoryCoupon = DBQuery::instance()->executeQuery($sql);
			 for($i=0; $i<count($allCategoryCoupon); $i++) {
			 	
			 	if($allCategoryCoupon[$i]["isFree"] == 0){
					$allCategoryCoupon[$i]["couponUrl"] = Utilities::getURL("couponUnion", array("Category" => $categoryid,
				                        "Coupon_" => $allCategoryCoupon[$i]["Coupon_"]));
				}
				
				
			 }
	
			return $allCategoryCoupon;
	  }

	function doCreateHtmlFile($strFileName, $strContent) {
        $strFileName = $strFileName;
        if(file_exists($strFileName)) {
            unlink($strFileName);
        }
        $fp = fopen($strFileName , "w+");
        if(!$fp)
        {
            echo "[CreateHTMLFile]: file "  . $strFileName . " open error" . "\n";
            return false;
        }
        if(!fwrite($fp, $strContent))
        {

            fclose($fp);
            echo "[CreateHTMLFile]: Write file ". $strFileName . " error ". "\n";
            return false;
        }
        
		echo "Create file: $strFileName\n";
        fclose($fp);
        $this->doPageCount++;
        return true;
    }
}

     
?>
