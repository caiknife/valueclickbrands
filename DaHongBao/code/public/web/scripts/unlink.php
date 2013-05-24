<?
set_time_limit(0);
include("links.inc.php");
include(__INCLUDE_ROOT."etc/const.inc.php");

include(__INCLUDE_ROOT."lib/functions/func.Debug.php");
include(__INCLUDE_ROOT."lib/functions/func.Browser.php");
include(__INCLUDE_ROOT."lib/classes/class.rFastTemplate.php");
include(__INCLUDE_ROOT."lib/classes/class.Merchant.php");

$oMerchantList=new MerchantList();
$oMerchantList->setOrder("Name");
while ( $oMerchant = $oMerchantList->nextItem() ){
	$mer_name=str_replace(' ','_',$oMerchant->get("NameURL"));
	$dir_name="../pages/".$mer_name."/";
	if(is_dir($dir_name)){
		$d=dir($dir_name);
		while($entry=$d->read()){
			if(strchr($entry,"Where_to_Enter_Coupon") || strchr($entry,"Where to Enter Coupon")){
				if(is_file($dir_name.$entry)){
					if(unlink($dir_name.$entry)){
						echo $dir_name.$entry."<br>";
					} else{
						echo "can't delete ".$dir_name.$entry."<br>";
					}
					flush();
				}
			}
		}
		$d->close();
	}
}
?>