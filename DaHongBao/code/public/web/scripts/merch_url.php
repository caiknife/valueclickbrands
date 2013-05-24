<?
    require_once('links.inc.php');
    require_once(__INCLUDE_ROOT.'etc/const.inc.php');
    require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");

   $mList = new MerchantList();
   $mList->setOrder('Name', ASC);
   $f = fopen(__INCLUDE_ROOT.'tmp/merch_url.txt','w');
   if($f) {
echo $mList->getItemCount().'<br>';
      while($mMerch = $mList->nextItem()) {
          fputs($f, $mMerch->get("Name").',http://www.dahongbao.com/'.str_replace(" ","_",$mMerch->get("Name"))."/".str_replace(" ","_",$mMerch->get("Name"))."_coupon.html\r\n");
          $mMerch->get('Name').'<br>';
      }
      fclose($f);
   }
   else{
    echo 'Can\'t open file';
   }


?>