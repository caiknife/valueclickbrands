<?PHP
require_once("../etc/const.inc.php");
$return = $_SERVER['HTTP_REFERER'];

setcookie("cityid","",time()-9999999,"/");
setcookie("cityid",$_GET['id'],time()+9999999,'/',__COOKIE_DOMAIN);
$return = preg_replace("/--Pg-(\d+)/","",$return);
Header("Location: ".$return."");
exit();


?>