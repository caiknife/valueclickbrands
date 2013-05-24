<?php
require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

$number = $_GET['mphone'];
$amount = $_GET['amount'];
//$ip = $_SERVER["REMOTE_ADDR"];
if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !="") {
	$ip = $_SERVER["HTTP_RLNCLIENTIPADDR"];
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
}
$askurl = "http://cz.eachnet.com/eachcoupon/section.do?mphone=".$number."&amount=".$amount;
$re = file_get_contents($askurl);
preg_match("/<favamount>(.*)<\/favamount>/i",$re,$re);


//add log
$sql = "INSERT INTO QueryEachnetLog (`ID`,`Mobile`,`Amount`,`FavAmount`,`IP`,`AddTime`) ";
$sql .= "VALUES ('','$number','$amount','".$re[1]."','".$ip."',NOW())";
$result = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);


echo $re[1];
?>
