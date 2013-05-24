<?PHP

$url = "/notfound.php";
header("HTTP/1.1 301 Moved Permanently");
header("Location: $url");
exit();

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
require_once(__INCLUDE_ROOT."lib/classes/class.phpwind.php");

require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
require_once(__INCLUDE_ROOT."lib/dao/class.UserScoreDao.php");


$oCategory = new Category();
$categoryList = $oCategory->getCategoryList("SitemapPriority");

if(empty($_POST['nameurl'])){
	$nameurl = "merchant";	
}else{
	$nameurl = $_POST['nameurl'];	
}

if($_POST['rep']=="true"){
	if(trim($_POST['content'])==""){

		?>
		<meta http-equiv="refresh" content="0;URL=/<?=$nameurl?>/coupon-<?=$_POST['couponid']?>/">
		<?
		
	}else{
		$phpwind = new PHPWIND();
	//$cp = preg_replace( "@\<a(.*?)\</a\>@is", "", $cp );
		//$_POST['content'] = Char_cv($_POST['content']);
		$_POST['content'] = htmlspecialchars($_POST['content']);
		$r = $phpwind->addpostsForDiscount($_POST['fid'],$_POST['tid'],$_POST['author'],$_POST['authorid'],$_POST['content'],$_POST['icon']);
		
		if($_POST['icon']==0){
			$checktype = "COMMIT";
		}else if($_POST['icon']==1){
			$checktype = "SUPPORT";
		}else if($_POST['icon']==2){
			$checktype = "OPPOSE";
		}
		$oUserScoreDao = new UserScoreDao($_POST['authorbelong']);
		$oUserScoreDao->addScore($checktype,$_POST['couponid'],"coupon",$_POST['authorid'],"","");
		
		
		?>
		<meta http-equiv="refresh" content="0;URL=/<?=$nameurl?>/coupon-<?=$_POST['couponid']?>/">
		<?
		
	}
}

if($_POST['rep']=="true1"){
	if(trim($_POST['content'])==""){
		?>
		<meta http-equiv="refresh" content="0;URL=/news---Ca-<?=$oCategory->get("NameURL")?>--Ci-<?=$_POST['fid']?>--number-<?=$_POST['tid']?>.html">
		<?
	}else{
		$phpwind = new PHPWIND();
		//$_POST['content'] = strip_tags($_POST['content'],"<a><img>");
		//$_POST['content'] = Char_cv($_POST['content']);
		$_POST['content'] = htmlspecialchars($_POST['content']);
		
		$r = $phpwind->addposts($_POST['fid'],$_POST['tid'],$_POST['author'],$_POST['authorid'],$_POST['content']);

		
		?>
		<meta http-equiv="refresh" content="0;URL=/news---Ca-<?=$oCategory->get("NameURL")?>--Ci-<?=$_POST['fid']?>--number-<?=$_POST['tid']?>.html">
		<?
	}
}

if($_POST['rep']=="true2"){
	if(trim($_POST['content'])==""){
		?>
		<meta http-equiv="refresh" content="0;URL=/discount_detail-<?=$_POST['couponid']?>.html">
		<?
	}else{
		$phpwind = new PHPWIND();
		//$_POST['content'] = strip_tags($_POST['content'],"<a><img>");
		//$_POST['content'] = Char_cv($_POST['content']);
		$_POST['content'] = htmlspecialchars($_POST['content']);
		
		$r = $phpwind->addpostsForDiscount($_POST['fid'],$_POST['tid'],$_POST['author'],$_POST['authorid'],$_POST['content'],$_POST['icon']);

		if($_POST['icon']==0){
			$checktype = "COMMIT";
		}else if($_POST['icon']==1){
			$checktype = "SUPPORT";
		}else if($_POST['icon']==2){
			$checktype = "OPPOSE";
		}
		$oUserScoreDao = new UserScoreDao($_POST['authorbelong']);
		$oUserScoreDao->addScore($checktype,$_POST['couponid'],"discount",$_POST['authorid'],"","");
		?>
		<meta http-equiv="refresh" content="0;URL=/discount_detail-<?=$_POST['couponid']?>.html">
		<?
		
	}
}



function Char_cv($msg){
	$msg = str_replace('&amp;','&',$msg);
	$msg = str_replace('&nbsp;',' ',$msg);
	$msg = str_replace('"','&quot;',$msg);
	$msg = str_replace("'",'&#39;',$msg);
	$msg = str_replace("<","&lt;",$msg);
	$msg = str_replace(">","&gt;",$msg);
	$msg = str_replace("\t","&nbsp; &nbsp; ",$msg);
	$msg = str_replace("\r","",$msg);
	$msg = str_replace("  ","&nbsp; ",$msg);
	return $msg;
}
?>