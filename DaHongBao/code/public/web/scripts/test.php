<?

$url = "/notfound.php";
header("HTTP/1.1 301 Moved Permanently");
header("Location: $url");
exit();
// include("../lib/functions/func.URL.php");
// echo getNavigation(array("1"=>"2"));
?>