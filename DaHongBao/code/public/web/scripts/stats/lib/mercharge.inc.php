<?php
//main func , wirte cpc log
function merchant_charge($merchantId, $cpcCost, $merType){
	$db = new MysqlDB(__W_DB_HOST, __W_DB_USER, __W_DB_PASS, __W_DB_BASE, __W_DB_PORT);
	$sql = "lock tables ". __W_DB_BASE .".MerchantAccount write;";
	$db->doQuery($sql);
	
	$sql    = "SELECT `Balance`,`Status` FROM ". __W_DB_BASE .".`MerchantAccount` WHERE `Merchant_` = '{$merchantId}'";
    $db->doQuery($sql);
    $row = $db->doFetch();
	if($row->Status == 1){			
		$newBalance = $row->Balance - $cpcCost;
		$balanceArr['old'] = $row->Balance;
		$balanceArr['new'] = $newBalance;

		$sql = "UPDATE  ". __W_DB_BASE .".`MerchantAccount` SET `Balance` = ".$newBalance." WHERE `Merchant_` = $merchantId "; 
		$db->doQuery($sql);
		if($newBalance <= 0){
			$sql = "UPDATE  ". __W_DB_BASE .".`MerchantAccount` SET `Status` = 0 WHERE `Merchant_` = $merchantId";
			$db->doQuery($sql);
		}

	$sql = "unlock tables;";
	$db->doQuery($sql);		
			//Call Kevin's function
//			if ($newBalance <= _BID_MIN_BALANCE) {
//				$merchantid = $merchantId;
//				//@fopen(__VERISIGN_CHARGE."&merid=$merchantid","r");			
//				$ch = curl_init();
//				curl_setopt($ch, CURLOPT_URL, __VERISIGN_CHARGE."&merid=$merchantid");
//				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//				$echors = @curl_exec($ch);
//				curl_close($ch);				
//			}
	}else{
		$sql = "unlock tables;";
		$db->doQuery($sql);		
	}
	return $balanceArr;	
}

?>