<?PHP
require_once("../../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once(__INCLUDE_ROOT."lib/classes/class.City.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once(__INCLUDE_ROOT."lib/classes/class.topic.php");
require_once(__INCLUDE_ROOT."lib/dao/class.UserScoreDao.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once(__INCLUDE_ROOT.'lib/classes/class.Award.php');
//require_once(__INCLUDE_ROOT."lib/dao/class.HongBaoDao.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$phpwind = new PHPWIND();
$tpl = new sTemplate();
$tpl->setTemplate("new/myhongbao.htm");



$winduser = P_GetCookie("winduser");
if (empty($winduser)) {
	Header("location:/bbs/login.php");
} else {
	$user = explode("\t", $winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	$userhb = Award::getUserHB($userinfo[0]['uid']);
	$userinfo[0]['lastvisit'] = get_date($userinfo[0]['lastvisit']);
	$tpl->assign("userinfo", $userinfo[0]);
	$tpl->assign('islogon', 1);
}


$oUserScoreDao = new UserScoreDao($userinfo[0]['uid']);
$userrow = $oUserScoreDao->getUserScoreState();



$userrow['userhb'] = $userhb;

$userrow['CouponPostNum'] = $userrow['CouponPost']/$oUserScoreDao->POSTCOUPONSCORE;
$userrow['CouponOpposeNum'] = $userrow['CouponOppose']/$oUserScoreDao->OPPOSESCORE;
$userrow['CouponPostDeleteNum'] = $userrow['CouponPostDelete']/(-$oUserScoreDao->POSTCOUPONSCORE);
$userrow['CouponReviewNum'] = $userrow['CouponReview']/$oUserScoreDao->COMMITSCORE;
$userrow['CouponReviewDeleteNum'] = $userrow['CouponReviewDelete']/(-$oUserScoreDao->COMMITSCORE);
$userrow['CouponSupportNum'] = $userrow['CouponSupport']/$oUserScoreDao->SUPPORTSCORE;
$userrow['CouponSupportDeleteNum'] = $userrow['CouponSupportDelete']/(-$oUserScoreDao->SUPPORTSCORE);
$userrow['CouponOpposeDeleteNum'] = $userrow['CouponOpposeDelete']/(-$oUserScoreDao->OPPOSESCORE);

$userrow['CouponReviewHB'] = ($userrow['CouponReview']-$userrow['CouponReview']%100)/100;
$userrow['CouponReviewDeleteHB'] = ($userrow['CouponReviewDelete']-$userrow['CouponReviewDelete']%100)/100;
$userrow['CouponSupportHB'] = ($userrow['CouponSupport']-$userrow['CouponSupport']%100)/100;
$userrow['CouponSupportDeleteHB'] = ($userrow['CouponSupportDelete']-$userrow['CouponSupportDelete']%100)/100;
$userrow['CouponVisitHB'] = ($userrow['CouponVisit']-$userrow['CouponVisit']%100)/100;
$userrow['CouponOpposeHB'] = ($userrow['CouponOppose']-$userrow['CouponOppose']%100)/100;
$userrow['CouponPostHB'] = $userrow['CouponPost']/100;
$userrow['CouponPostDeleteHB'] = $userrow['CouponPostDelete']/100;
$userrow['CouponOpposeDeleteHB'] = ($userrow['CouponOpposeDelete'])/100;

$userrow['CouponSumHB'] = $userrow['CouponSupportDeleteHB'] + $userrow['CouponReviewHB']+$userrow['CouponReviewDeleteHB']+$userrow['CouponSupportHB']+$userrow['CouponVisitHB']+$userrow['CouponOpposeHB']+$userrow['CouponPostHB']+$userrow['CouponPostDeleteHB']+$userrow['CouponOpposeDeleteHB'];


$userrow['CouponReviewRemain'] = (100-$userrow['CouponReview']%100)/$oUserScoreDao->COMMITSCORE;
$userrow['CouponSupportRemain'] = (100-$userrow['CouponSupport']%100)/$oUserScoreDao->SUPPORTSCORE;
$userrow['CouponReviewDeleteRemain'] = (100+$userrow['CouponReviewDelete']%100)/$oUserScoreDao->COMMITSCORE;
$userrow['CouponSupportDeleteRemain'] = (100+$userrow['CouponSupportDelete']%100)/$oUserScoreDao->SUPPORTSCORE;
$userrow['CouponVisitRemain'] = (100-$userrow['CouponVisit']%100)/$oUserScoreDao->REFRESHSCORE;

///discount

$userrow['DiscountPostNum'] = $userrow['DiscountPost']/$oUserScoreDao->POSTCOUPONSCORE;
$userrow['DiscountPostDeleteNum'] = $userrow['DiscountPostDelete']/(-$oUserScoreDao->POSTCOUPONSCORE);
$userrow['DiscountReviewNum'] = $userrow['DiscountReview']/$oUserScoreDao->COMMITSCORE;
$userrow['DiscountReviewDeleteNum'] = $userrow['DiscountReviewDelete']/(-$oUserScoreDao->COMMITSCORE);
$userrow['DiscountSupportNum'] = $userrow['DiscountSupport']/$oUserScoreDao->SUPPORTSCORE;
$userrow['DiscountSupportDeleteNum'] = $userrow['DiscountSupportDelete']/(-$oUserScoreDao->SUPPORTSCORE);
$userrow['DiscountOpposeDeleteNum'] = $userrow['DiscountOpposeDelete']/(-$oUserScoreDao->OPPOSESCORE);
$userrow['DiscountOpposeNum'] = $userrow['DiscountOppose']/$oUserScoreDao->OPPOSESCORE;

$userrow['DiscountReviewHB'] = ($userrow['DiscountReview']-$userrow['DiscountReview']%100)/100;
$userrow['DiscountReviewDeleteHB'] = ($userrow['DiscountReviewDelete']-$userrow['DiscountReviewDelete']%100)/100;
$userrow['DiscountSupportHB'] = ($userrow['DiscountSupport']-$userrow['DiscountSupport']%100)/100;
$userrow['DiscountSupportDeleteHB'] = ($userrow['DiscountSupportDelete']-$userrow['DiscountSupportDelete']%100)/100;
$userrow['DiscountVisitHB'] = ($userrow['DiscountVisit']-$userrow['DiscountVisit']%100)/100;
$userrow['DiscountOpposeHB'] = ($userrow['DiscountOppose'])/100;
$userrow['DiscountPostHB'] = $userrow['DiscountPost']/100;
$userrow['DiscountPostDeleteHB'] = $userrow['DiscountPostDelete']/100;
$userrow['DiscountOpposeDeleteHB'] = ($userrow['DiscountOpposeDelete'])/100;

$userrow['DiscountSumHB'] = $userrow['DiscountSupportDeleteHB'] + $userrow['DiscountReviewHB']+$userrow['DiscountReviewDeleteHB']+$userrow['DiscountSupportHB']+$userrow['DiscountVisitHB']+$userrow['DiscountOpposeHB']+$userrow['DiscountPostHB']+$userrow['DiscountPostDeleteHB']+$userrow['DiscountOpposeDeleteHB'];


$userrow['DiscountReviewRemain'] = (100-$userrow['DiscountReview']%100)/$oUserScoreDao->COMMITSCORE;
$userrow['DiscountSupportRemain'] = (100-$userrow['DiscountSupport']%100)/$oUserScoreDao->SUPPORTSCORE;
$userrow['DiscountSupportDeleteRemain'] = (100+$userrow['DiscountSupportDelete']%100)/$oUserScoreDao->SUPPORTSCORE;
$userrow['DiscountReviewDeleteRemain'] = (100+$userrow['DiscountReviewDelete']%100)/$oUserScoreDao->COMMITSCORE;
$userrow['DiscountVisitRemain'] = (100-$userrow['DiscountVisit']%100)/$oUserScoreDao->REFRESHSCORE;

//bbs
$userrow['BBSPostNum'] = $userrow['BBSPost']/$oUserScoreDao->POSTBBSSCORE;
$userrow['BBSReplyNum'] = $userrow['BBSReply']/$oUserScoreDao->COMMITSCORE;
$userrow['BBSReplyDeleteNum'] = $userrow['BBSReplyDelete']/(-$oUserScoreDao->COMMITSCORE);
$userrow['BBSPostDeleteNum'] = $userrow['BBSPostDelete']/(-$oUserScoreDao->POSTBBSSCORE);

$userrow['BBSPostHB'] = $userrow['BBSPost']/100;
$userrow['BBSReplyHB'] = ($userrow['BBSReply']-$userrow['BBSReply']%100)/100;
$userrow['BBSPostDeleteHB'] = $userrow['BBSPostDelete']/100;
$userrow['BBSReplyDeleteHB'] = ($userrow['BBSReplyDelete']-$userrow['BBSReplyDelete']%100)/100;

$userrow['BBSReplyRemain'] = (100-$userrow['BBSReply']%100)/$oUserScoreDao->COMMITSCORE;
$userrow['BBSReplyDeleteRemain'] = (100+$userrow['BBSReplyDelete']%100)/$oUserScoreDao->COMMITSCORE;

//other
$userrow['OtherNum'] = $oUserScoreDao->getOtherScoreCount();

$userrow['OtherHB'] = $userrow['OtherScore']/100;
$userrow['OtherSumHB'] = $userrow['OtherHB']+$userrow['BBSPostHB']+$userrow['BBSReplyHB']+$userrow['BBSPostDeleteHB']+$userrow['BBSReplyDeleteHB'];

//echo $oUserScoreDao->getUserCouponCount();
//echo $oUserScoreDao->getUserCouponExpireCount();
$tpl->assign('couponcount', $oUserScoreDao->getUserCouponCount());
$tpl->assign('couponexpirecount', $oUserScoreDao->getUserCouponExpireCount());
$tpl->assign('discountcount', $oUserScoreDao->getUserDiscountCount());
$tpl->assign('discountexpirecount', $oUserScoreDao->getUserDiscountExpireCount());

$tpl->assign('userrow', $userrow);


$tpl->displayTemplate();


?>