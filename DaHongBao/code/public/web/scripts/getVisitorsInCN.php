<?php  

$flag = $_GET[flag];
$flag = "get@visitors";
if (md5($flag) == md5("get@visitors"))
{
	$fDate = Date("Y-m-d",mktime(0,0,0,date("m")-1,1,date("Y")));
	$tDate = Date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));
	$linkID    = mysql_connect("192.168.168.3","cm2ch","any");
	mysql_select_db("cm2ch",$linkID);
	$sql_query = "Select if(sum(Visitor),sum(Visitor),0) as num From SessionDaily Where Dat >= '$fDate' AND Dat < '$tDate'";
	
	$queryID = mysql_query($sql_query, $linkID);
	while ($row = mysql_fetch_array($queryID))
	{
	    echo $row[num];
	}
	exit;
}
?>
