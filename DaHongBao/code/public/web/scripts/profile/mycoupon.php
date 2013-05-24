<?PHP
require_once ("../../etc/const.inc.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once (__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Meta.php");
require_once (__INCLUDE_ROOT."lib/classes/class.City.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Coupon.php");
require_once (__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Page.php");
require_once (__INCLUDE_ROOT."lib/classes/class.topic.php");
require_once (__INCLUDE_ROOT."lib/dao/class.UserScoreDao.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once (__INCLUDE_ROOT."lib/functions/func.Browser.php");
require_once (__INCLUDE_ROOT."lib/functions/func.Debug.php");
require_once (__INCLUDE_ROOT."lib/classes/class.Utilities.php");
require_once (__INCLUDE_ROOT.'lib/classes/class.Award.php');
//require_once(__INCLUDE_ROOT."lib/dao/class.HongBaoDao.php");
require_once (__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');

$phpwind = new PHPWIND();
$tpl = new sTemplate();
$tpl->setTemplate("new/mycoupon.htm");
$page = $_GET['page'];

$winduser = P_GetCookie("winduser");
if (empty ($winduser)) {
	Header("location:/bbs/login.php");
} else {
	$user = explode("\t", $winduser);
	$userinfo = $phpwind->getuserinfo($user[0]);
	$userhb = Award :: getUserHB($userinfo[0]['uid']);
	$userinfo[0]['lastvisit'] = get_date($userinfo[0]['lastvisit']);
	$tpl->assign("userinfo", $userinfo[0]);
	$tpl->assign('islogon', 1);
}

//print_r($userinfo);
$icon = $userinfo[0]['icon'];
if (empty ($icon)) {
	$touxiang = "/bbs/images/face/none.gif";
} else {
	$icon = explode("|", $icon);
	if (empty ($icon[1])) {
		$touxiang = "/bbs/images/face/".$icon[0];
	} else {
		$checkout = $icon[1];
		$checkout = explode("/", $checkout);
		if (count($checkout) == 1) {
			$touxiang = __IMAGE_SRC."attachment/upload/".$icon[1];
		} else {
			$touxiang = $icon[1];
		}
	}
}
$tpl->assign("touxiang", $touxiang);
$tpl->assign("userhb", $userhb);

$oUserScoreDao = new UserScoreDao($userinfo[0]['uid']);
$userrow = $oUserScoreDao->getUserScoreState();

$userrow['userhb'] = $userhb;


if (empty ($page)) {
		$page = 1;
	}
if ($_GET['switch'] == 'myaddcoupon') {
	$usercouponlist = $phpwind->getUserCoupon($userinfo[0]['uid'], $page);
	$tpl->assign("usercouponlist", $usercouponlist);

	$usercouponlistCount = $phpwind->getUserCouponCount($userinfo[0]['uid']);

	$pageCount = ceil($usercouponlistCount / 10);
	$pageStr = getNewPageStr($page, $pageCount, '', 'myaddcoupon');
	$tpl->assign("pageStr", $pageStr);
}
elseif ($_GET['switch'] == 'myfavorcoupon') {
	$userfavorcouponlist = $phpwind->getUserFavorCoupon($userinfo[0]['uid'], $page);
	$tpl->assign("userfavorcouponlist", $userfavorcouponlist);

	$getUserFavorCouponCount = $phpwind->getUserFavorCouponCount($userinfo[0]['uid']);

	$pageCount = ceil($getUserFavorCouponCount / 10);
	$pageStr = getNewPageStr($page, $pageCount, '', 'myfavorcoupon');
	$tpl->assign("pageStr", $pageStr);

}elseif ($_GET['switch'] == 'myuseraddcoupon') {
	$useraddcouponlist = $phpwind->getUserAddCoupon($userinfo[0]['uid'], $page);
	$tpl->assign("useraddcouponlist", $useraddcouponlist);

	$getUserFavorCouponCount = $phpwind->getUserAddCouponCount($userinfo[0]['uid']);

	$pageCount = ceil($getUserFavorCouponCount / 10);
	$pageStr = getNewPageStr($page, $pageCount, '', 'myfavorcoupon');
	$tpl->assign("pageStr", $pageStr);

} else {
	$usercouponlist = $phpwind->getUserCoupon($userinfo[0]['uid'], $page);
	$tpl->assign("usercouponlist", $usercouponlist);

	$userfavorcouponlist = $phpwind->getUserFavorCoupon($userinfo[0]['uid'], $page);
	$tpl->assign("userfavorcouponlist", $userfavorcouponlist);

	$useraddcouponlist = $phpwind->getUserAddCoupon($userinfo[0]['uid'], $page);
	$tpl->assign("useraddcouponlist", $useraddcouponlist);

}

function getNewPageStr($nowPage, $maxPage, $sort = NULL, $switch) {

	$str = "<div class=\"page\"><ul><li>当前第".$nowPage."页,共".$maxPage."页</li>";
	if ($nowPage > 1) {
		$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => $nowPage -1, "Sort" => $sort));
		$str .= "<li><a href='$url'>上一页</a></li>";
	}
	if ($maxPage <= 6) {
		for ($i = 1; $i <= $maxPage; $i ++) {
			$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => $i, "Sort" => $sort));
			if ($nowPage == $i) {
				$str .= "<li>".$i."</li>";
			} else {
				$str .= "<li><a href='$url'>$i</a></li>";
			}
		}
		if ($nowPage == $maxPage && $maxPage != "1") {
			$str .= "";
		}
	}
	elseif ($nowPage > 4 && $nowPage < ($maxPage -2)) {
		$starti = $nowPage -2;
		$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => 1, "Sort" => $sort));
		$str .= "<li><a href='$url' class='blue'>1</a></li>";
		$str .= "<li>...</li>";

		if ($nowPage > ($maxPage -6)) {
			$startlimit = $maxPage;
		} else {
			$startlimit = $starti +4;
		}

		for ($i = $starti; $i <= $startlimit && $i <= $maxPage; $i ++) {
			$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => $i, "Sort" => $sort));
			if ($nowPage == $i) {
				$str .= "<li>$i</li>";
			} else {
				$str .= "<li><a href='$url'>$i</a></li>";
			}
		}

		if ($nowPage > ($maxPage -6)) {

		} else {
			$str .= "<li>...</li>";
		}

	}
	elseif ($nowPage <= 4) {
		for ($i = 1; $i <= 5; $i ++) {
			$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => $i, "Sort" => $sort));
			if ($nowPage == $i) {
				$str .= "<li>".$i."</li>";
			} else {
				$str .= "<li><a href='$url'>$i</a></li>";
			}
		}
		$str .= "<li>...</li>";
	} else {
		$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => 1, "Sort" => $sort));
		$str .= "<li><a href='$url' class='blue'>1</a></li>";
		if ($nowPage >= ($maxPage -2)) {
			$str .= "<li>...</li>";
		} else {

		}
		$starti = $maxPage -4;
		for ($i = $starti; $i <= $maxPage; $i ++) {
			$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => $i, "Sort" => $sort));
			if ($nowPage == $i) {
				$str .= "<li>".$i."</li>";
			} else {
				$str .= "<li><a href='$url'>$i</a></li>";
			}
		}
		if ($nowPage != $maxPage) {
			if ($nowPage >= ($maxPage -2)) {

			} else {
				$str .= "<li>...</li>";
			}
		}
	}

	if ($maxPage > 6 && ($nowPage < $maxPage -5)) {
		$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => $maxPage, "Sort" => $sort));
		$str .= "<li><a href='$url'>".$maxPage."</a></li>";
	}

	if ($nowPage != $maxPage) {
		$url = Utilities :: getNewURL("mycoupon", array ("switch" => $switch, "Page" => $nowPage +1, "Sort" => $sort));
		$str .= "<li><a href='$url'>下一页</a></li>";
	}
	$str .= "</ul></div>";
	return $str;
}

$tpl->displayTemplate();
?>