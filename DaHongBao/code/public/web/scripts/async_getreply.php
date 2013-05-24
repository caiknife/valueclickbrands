<?PHP

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");
require_once(__INCLUDE_ROOT.'lib/functions/func.phpwindplugin.php');
require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");

$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");

$phpwind = new PHPWIND();
$r = $phpwind -> getrepliesajax($_GET['tid'],$_GET['id']);

$str = "";
foreach ($r as $key=>$value){

	$icon = $value['icon'];


	if(empty($icon)){
		$value['icon'] = "/bbs/images/face/none.gif";
	}else{
		$icon = explode("|",$icon);
		if(empty($icon[1])){
			$value['icon'] = "/bbs/images/face/".$icon[0];
		}else{
			$checkout = $icon[1];
			$checkout = explode("/",$checkout);
			if(count($checkout)==1){
				$value['icon'] = "/bbs/attachment/upload/".$icon[1];
			}else{
				$value['icon'] = $icon[1];
			}
		}
	}

	$str .= $value['author']."***$$$###".get_date($value['postdate'])."***$$$###".$value['content']."***$$$###".$value['icon']."***$$$###".$value['picon'];
	$str .= "!#%$#@";
}

$str .= "^^%%!!@@##";

$p = $phpwind->getNewPageStr($_GET['id'],$_GET['pageall']);

$str .=$p;
header('Content-Type:text/html;charset=GBK');
echo $str;
?>