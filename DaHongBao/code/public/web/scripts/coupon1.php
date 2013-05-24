<?php
/*
**
**
**
*/

session_start();

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Standard.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
$sql = "SELECT MAX(Merchant_) FROM Merchant";
$merSequence = DBQuery::instance(__DAHONGBAO)->getOne($sql);
print($merSequence);
exit;
$TypeSearch = empty($TypeSearch) ? "all" : $TypeSearch;
$searchType = empty($searchType) ? "" : $searchType;

if ( !$searchText){
   header("Location: /No_Results".$searchType.".html");
   exit;
}

$tpl = new rFastTemplate(__INCLUDE_ROOT."templates");
$tpl->VerifyUnmatched = "no";
$tpl->define(array(
    'main'      => get_browser_template("cache/main.tpl"),
    'include'   => get_browser_template("search.tpl"),
));
$tpl->assign(array(
	'TEXT_TOP_DESC'		=> '商家',
	'TEXT_BOTTOM_DESC'	=> "商品",
    'BASE_HOSTNAME'     => BASE_HOSTNAME,
    LINK_ROOT      		=> __LINK_ROOT,
    PROMO_HEAD     		=> __DEFAULT_PROMO_HEAD,
    PROMO_TEXT     		=> __DEFAULT_PROMO_TEXT,
    PROMO_FOOTER_1 		=> __DEFAULT_PROMO_FOOTER_1,
    PROMO_FOOTER_2 		=> __DEFAULT_PROMO_FOOTER_2,
    PROMO_FOOTER_3 		=> __DEFAULT_PROMO_FOOTER_3,
    PROMO_URL      		=> __DEFAULT_PROMO_URL,
    SCRIPT_NAME    		=> $PHP_SELF,
    CATEGORY_CUR   		=> -1,
    MERCHANT_CUR   		=> -1,
    COUPON_CUR     		=> -1,
    KEYWORDS       		=> "",
    DESCRIPTION_H  		=> "",
    HEAD_JAVASCRIPT 	=> "",
    SE_WORDS       		=> "",
    PAGE_TITLE     		=> "搜索结果",
    NAVIGATION_PATH		=> "&nbsp;",
    INCLUDE1       		=> '',
    INCLUDE2       		=> '',
    'FORUMACTIVE'		=> COUPONLINKINACTIVE,
    'HOTCACTIVE'        => COUPONLINKINACTIVE,
    'NEWCACTIVE'        => COUPONLINKINACTIVE,
    'EXPCACTIVE'        => COUPONLINKINACTIVE,
    'FRSCACTIVE'        => COUPONLINKACTIVE,
    'SEARCH_KEYWORD'    => $searchText,
    'SEARCHTEXT64'    	=> base64_encode($searchText),
    'SAYRESULT'		  	=> '',
    'ISREALSEARCH'		=> $isRealSearch
));

$tpl->define_dynamic("cm_search_list","include");
$tpl->define_dynamic("coupon_list","include");
$tpl->define_dynamic('more_results','include');

if(!isset($part)) $part = 1;
$PageRec = 1;

if($part == 1){
    $oSearch = new Search($searchText,__SEARCH_ALL,$ST,$order);
    $oSearch->search_();
    $searchResSum = count($oSearch->CM2Merch);
    if($searchResSum>0) {

        if(!isset($p)) $p=1;
    	$pageCount = ceil($searchResSum/4);
    	if ($pageCount > 10) $pageCount=10;
    	$prePage = $PHP_SELF."?part=".$part."&searchText=".$searchText."&p=".($p-1);
    	$nexPage = $PHP_SELF."?part=".$part."&searchText=".$searchText."&p=".($p+1);

    	$pageStr = ($p==1) ? "上一页 &lt; " : "<a class='searchblue' href='".$prePage."'>上一页</a> &lt; ";
    	for ($i=1; $i<=$pageCount; $i++) {
    	   	$pageStr.= ($i==$p)
    	   			 ? "<span class=pagered>".$i."</span> "
    	   			 : "<a class='searchblack' href='".$PHP_SELF."?part=".$part."&searchText=".$searchText."&p=".$i."'>".$i."</a> ";
    	}
    	$pageStr.= ($p==$pageCount) ? "&gt; 下一页" : "&gt; <a class='searchblue' href='".$nexPage."'>下一页</a>";

        $tpl->assign(array(
        	'PAGE_STR' => $pageStr
        ));
    	
        $cur_page = $p>0?$p++:1;
        $CountRec = 0;

        while((list($key, $Merch) = each($oSearch->CM2Merch))&&($PageRec<=4)) {
            $mMerch = new Merchant($Merch["Merchant_"]);
            $tpl->assign(array(
                 'RAWMERCHANT' => trim($mMerch->get('displayURL')=='')?$mMerch->get('Name'):$mMerch->get('displayURL'),
                 'MERCHANT' => $mMerch->get('Name'),
            ));
            $mCoupon = new Coupon ($Merch["Coupon_"]);
            $CountRec++;
            if(((($cur_page-1)*4)>$CountRec) && ($cur_page!=1) ) continue;
            if( ($mCoupon->get("Code")=='')||(eregi('no code',$mCoupon->get("Code"))) ) {
                $coopTXT = 'No code required - click to see offer';
                $tpl->assign(array(
                     'COUPON_CODE_TEXT'   => 'to take advantage of this offer at '.$mMerch->get('Name'),
                     'CLICK_CODE'         => '',
                ));
            }
            /*
			elseif($mCoupon->isUniq()){
                $tpl->assign(array(
                     'COUPON_CODE_TEXT'   => 'for your unique Coupon Code. The code will appear in a pop-up window.',
                     'CLICK_CODE'         => '',
                ));
            }*/
            else{
                $coopTXT = '<font color="#FF0000">'.$mCoupon->get("Code").'</font>';
                $tpl->assign(array(
                     'COUPON_CODE_TEXT'   => 'to shop at '.$mMerch->get('Name').' and enter code '.$coopTXT.' at checkout to save.',//'to have this offer automatically added to your shopping cart at Dell.',
                     'CLICK_CODE'         => '',
                ));
            }
            $tpl->assign(array(
                'SEARCH_CODE'       => base64_encode($searchText),
                'COUNT_REC'         => $PageRec.'.',
                'COUPON_DESCRIPT'   => $mCoupon->get("Descript"),
                'EXPIRE'            => $mCoupon->expiredAt(),
                'COUPON_ID'         => $mCoupon->get("Coupon_"),
                'MERCHANT_ID'       => $mMerch->get('Merchant_'),
                'NEXT_PAGE'         => $p,
                'MERCHANT_SEE'		=> $mMerch->get('Name'),
                'NAMEURL'			=> $mMerch->get('NameURL')
            ));
            $tpl->parse("COUPON_LIST",".coupon_list");
            ++$PageRec;
            
        }
        $tpl->parse("SEARCH_LIST","cm_search_list");
        $tpl->parse("MORE_RESULTS","more_results");
        
    } else {
    	$part = 2;    	
    }
}

if($part == 2){
	$tpl->clear_dynamic("coupon_list");
	$tpl->define_dynamic("no_result","include");

	$tpl->assign(array(
	    'SAYRESULT'		  => '没有'
	));
	$tpl->parse("NO_RESULT","no_result");
	$tpl->parse("SEARCH_LIST","cm_search_list");
}


$tpl->parse("MAIN_CONTENT","include");
$tpl->assign(array(
     MAIN_CONTENT   => $tpl->fetch("MAIN_CONTENT")
));
$tpl->parse("MAIN","main");
$out = $tpl->fetch("MAIN");
$arrOut = explode("\n", $out);
foreach ($arrOut as $k=>$v) {
	if (eregi("<html>", $v)) {
		$phpat = $k;break;
	}
}
$newOut = array_slice($arrOut, $phpat);
$html = implode("\n", $newOut);
print($html);
?>