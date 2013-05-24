<?PHP
    include("links.inc.php");
    include(__INCLUDE_ROOT."etc/const.inc.php");

    include(__INCLUDE_ROOT."lib/functions/func.Debug.php");
    include(__INCLUDE_ROOT."lib/classes/class.Merchant.php");

    if(isset($f)) {
        $tmpMerch = new ForumMerchantList(0,'SELECT distinct m.* FROM Merchant m, TopicMer tm WHERE m.isActive=1 AND m.merchant_=tm.merchant_ AND m.isFrame=1');
    }
    else{
        $tmpMerch = new ForumMerchantList($t);
    }
    $Count= $tmpMerch->getItemCount();
    if($Count>3){
        $Count = 3;
    }

    for($i=1;$i<=$Count; $i++) {
        $Col.='0,';
    }
    $Col = substr($Col, 0, strlen($Col)-1);
    $Col[0] ='*';
    echo '<frameset cols="'.$Col.'" frameborder="YES" border="0" framespacing="0">';
    $i =1;

    while(($Merch = $tmpMerch->nextItem())&&($Count>0)){
//        if($Merch->get('isFrame')==1) {
          echo  '<frame name="topFrame'.$i++.'" border="1" scrolling="NO" noresize src="'.$Merch->get('URL').'">';
          $Count--;
//        }
//        echo  '<frame name="topFrame'.$i++.'" border="1" scrolling="NO" noresize src="'.BASE_HOSTNAME.__LINK_ROOT.'redir.php?m='.$Merch->get('Merchant_').'">';
    }
    echo  '</frameset>';

?>