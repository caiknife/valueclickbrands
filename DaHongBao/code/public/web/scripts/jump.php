<?
set_time_limit(0);
include("links.inc.php");
include(__INCLUDE_ROOT."etc/const.inc.php");

include(__INCLUDE_ROOT."lib/functions/func.Debug.php");
include(__INCLUDE_ROOT."lib/functions/func.Browser.php");
include(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
include(__INCLUDE_ROOT."lib/classes/class.Merchant.php");

$str_js="<script language=\"JavaScript\">\n".
        "<!--\n".
				"window.location.href = 'index.html';\n".
				"//-->\n".
				"</script>\n";

$oMerchantList=new MerchantList();
$oMerchantList->setOrder("Name");
while ( $oMerchant = $oMerchantList->nextItem() ){
	$mer_name=str_replace(' ','_',$oMerchant->get("NameURL"));
	$dir_name="../pages/".$mer_name."/";
	if(is_dir($dir_name)){
		$d=dir($dir_name);
		while($entry=$d->read()){
			if(strchr($entry,$mer_name)){
				$f=fopen($dir_name.$entry,"r+");
				$content = fread($f, filesize($dir_name.$entry));
				rewind($f);
				fwrite($f,$str_js.$content);
				echo $dir_name.$entry."<br>";
				flush();
			}
		}
		$d->close();
	}
}
/*
$f=fopen("../pages/Dell_UK/Dell_UK_coupon_8.html","r");
$content=fread($f,filesize("../pages/Dell_UK/Dell_UK_coupon_8.html"));
echo substr($content,89);*/
?>