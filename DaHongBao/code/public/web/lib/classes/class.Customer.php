<?php
if ( !defined("CLASS_CUSTOMER_PHP") ){
   define("CLASS_CUSTOMER_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");


   class Customer {
      var $ClassName       = "Customer";
      var $Key             = "Customer_";
      var $Merchants       = "";
      var $MerchantsArray  = "";
      var $Coupons         = "";
      var $CouponsArray    = "";
      var $RemoteCustomers = array();
      var $oldEmail        = "";
	  var $CustomerInfo = array();
	  var $CustomerList = array();

      function Customer(){

      }
	  
	  function get($name){
         return $this->CustomerInfo[$name];
      }
	  
	  function set($name,$value){
         $this->CustomerInfo[$name] = $value;
      }
	  
      function checked($field, $value=1){
         if ( $field == "WeeklyNews" ){
		 	$sql = "SELECT * FROM NewsLetter WHERE Email='".$this->get("Email")."'";
			$rs = DBQuery::instance()->getRow($sql);
            if ( $rs["Email"] == $this->get("Email") ){
               return "checked";
            }
            return "";
         }
         else{
            if ( $this->get($field) == $value ){
            	return "CHECKED";
         	}
         }
		 return "";
      }

      function find($customer, $withremote =0){
         if ( 1>=strlen($customer) ) return;
		 $sql = "SELECT * FROM pw_members WHERE username='$customer'";
		 $this->CustomerInfo = DBQuery::instance()->getRow($sql);
		 
         if ( $withremote == 1 ){
            if ( __SERVER_NUM > 1 ){
               $oSystem = new System("__SERVER_CUR");
               if ( constant("__SERVER_".$oSystem->get("Value")."_BALANCE") == 1 ){
                  for ($i=1; $i<=__SERVER_NUM; $i++){
                     if ( $oSystem->get("Value") == $i ) continue;

                     if ( constant("__SERVER_".$i."_BALANCE") == 0 ) continue;

                     if ( $this->get("Customer_") > 0){
                        $this->RemoteCustomers[$i] = new RemoteCustomer($i,$customer);
                     }
                     else{
                        $this->RemoteCustomers[$i] = new RemoteCustomer($i,"");
                     }
                  }
               }
            }
         }
         return $this->CustomerInfo;
      }

      function insert(){
	
		$sql = "SELECT uid FROM pw_members WHERE username='".$this->get("Email")."'";
		$id = DBQuery::instance(__DAHONGBAO_Master)->getOne($sql);
	
			if($id>0){
				return false;
			}else{
				 $sql = "INSERT IGNORE INTO pw_members (uid,username,Password,Email) VALUES(" .
				  "'','".$this->get("Email")."','".md5($this->get("Password"))."','".
				  $this->get("Email")."')";

				DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);

			}
			return true;
      }


      function spec_update(){
	  	 $sql = "UPDATE Customer SET Forum='".$this->get("Forum")."', FName='".
		 	    $this->get("FName")."', LName='".$this->get("LName")."', Email='".
				$this->get("Email")."', Password='".$this->get("Password").
				"' WHERE Email='".$this->oldEmail."'";
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         if ( $this->ClassName != "RemoteCustomer" ){
            reset($this->RemoteCustomers);
            while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
			   $sql = "UPDATE Customer SET Forum='".$this->get("Forum")."', FName='".
		 	    	$this->get("FName")."', LName='".$this->get("LName")."', Email='".
					$this->get("Email")."', Password='".$this->get("Password").
					"' WHERE Email='".$this->oldEmail."'";
		 	   DBQuery::instance($oCust->connect)->executeUpdate($sql);
            }
         }
      }

      function update(){
         $sql = "UPDATE Customer SET Forum='".$this->get("Forum")."', FName='".$this->get("FName").
		 	    "', LName='".$this->get("LName")."', Password='".$this->get("Password").
				"', Email='".$this->get("Email")."', FreqLetter=".$this->get("FreqLetter").
				", LastLetter='".$this->get("LastLetter")."', AlertEmail=".$this->get("AlertEmail").
				" WHERE Customer_=".$this->get("Customer_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         if ( $this->ClassName != "RemoteCustomer" ){
            reset($this->RemoteCustomers);
            while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
               $oCust->set("Forum",$this->get("Forum"));
               $oCust->set("FName",$this->get("FName"));
               $oCust->set("LName",$this->get("LName"));
               $oCust->set("Password",$this->get("Password"));
               $oCust->set("Email",$this->get("Email"));
               $oCust->set("FreqLetter",$this->get("FreqLetter"));
               $oCust->set("LastLetter",$this->get("LastLetter"));
               $sql = "UPDATE Customer SET Forum='".$oCust->get("Forum")."', FName='".$oCust->get("FName").
					"', LName='".$oCust->get("LName")."', Password='".$oCust->get("Password").
					"', Email='".$oCust->get("Email")."', FreqLetter=".$oCust->get("FreqLetter").
					", LastLetter='".$oCust->get("LastLetter")."', AlertEmail=".$oCust->get("AlertEmail").
					" WHERE Customer_=".$oCust->get("Customer_")."";
		 	   DBQuery::instance($oCust->connect)->executeUpdate($sql);
            }
         }
      }

      function hasSaved($id){
	  	 $sql = "SELECT COUNT(*) cnt FROM Notify WHERE Customer_= $id";
		 $tmp1 = DBQuery::instance()->getOne($sql);
		 $sql = "SELECT COUNT(*) cnt FROM Wallet WHERE Customer_=$id";
		 $tmp2 = DBQuery::instance()->getOne($sql);
         if ( ($tmp1 + $tmp2) > 0 ){
            return array($tmp1,$tmp2);
         }
         return false;
      }

      function getSubscript(){
         switch ($this->get("FreqLetter")){
            case 1: return "每当优惠活动开始的时候接收通知"; break;
            case 2: return "每天通知"; break;
            case 3: return "每周通知"; break;
            default: return "不接收任何通知"; break;
         }
      }

	  function checklogin($em,$pass){
		$sql = "SELECT uid FROM pw_members WHERE username='".$em."' AND password='".md5($pass)."'";
		$id = DBQuery::instance()->getOne($sql);
		return $id;
	  }

	  function getid($em){
		$sql = "SELECT uid FROM pw_members WHERE username='".$em."'";
		$id = DBQuery::instance()->getOne($sql);
		return $id;
	  }

      function loadMerchants($em){
		$id = $this->getid($em);
		
		$sql = "SELECT m.* FROM Merchant m, Notify n WHERE n.Merchant_=m.Merchant_ AND m.isActive=1 AND n.Customer_=$id";

		$array = DBQuery::instance()->executeQuery($sql);
		return $array;
      }

	  function addemail($em){
		$sql = "SELECT email FROM 99mail WHERE email='".$em."'";
		$email = DBQuery::instance()->getOne($sql);
		if(empty($email)){
			$sql = "insert into 99mail (email,submit) VALUES ('$em','')";
			$array = DBQuery::instance(__DAHONGBAO_Master)->executeQuery($sql);
			return "1";
		}else{
			return "0";
		}
	  }

      function loadCoupons($id){
	  	 $sql = "SELECT p.*,m.Name,m.NameURL,m.isShow MerIsShow FROM Coupon p " .
		        "INNER JOIN Wallet w ON p.Coupon_=w.Coupon_ LEFT JOIN Merchant m ON m.Merchant_ = p.Merchant_ " .
		        "WHERE p.isActive=1 AND (p.ExpireDate >= CURDATE() OR p.ExpireDate='0000-00-00') AND w.Customer_=$id";
		 $this->Coupons = DBQuery::instance()->executeQuery($sql);
         $this->CouponsArray = $this->Coupons;
		 return $this->Coupons;
      }

      function checkMerchant($merchant =0){
	  	 for($i=0;$i<count($this->MerchantsArray); $i++) {
		 	if($this->MerchantsArray[$i]["Merchant_"] == $merchant) {
				return "checked";
			}
		 }
         return "";
      }

      function saveMerchants($merchants =array()){
         $this->Merchants = $merchants;
		 $sql = "DELETE FROM Notify WHERE Customer_=".$this->get("Customer_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         reset($this->RemoteCustomers);
         while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
		 	$sql = "DELETE FROM Notify WHERE Customer_=".$oCust->get("Customer_");
			DBQuery::instance($oCust->connect)->executeUpdate($sql);
         }
         if ( is_array($merchants) ){
            while ( list($key,$val) = @each($merchants) ){
               if ( empty($val) ) continue;
			   $sql = "INSERT INTO Notify (Customer_,Merchant_) VALUES(".$this->get("Customer_").",".$val.")";
			   DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
               reset($this->RemoteCustomers);
               while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
			   	  $sql = "INSERT INTO Notify (Customer_,Merchant_) VALUES(".$oCust->get("Customer_").",".$val.")";
				  DBQuery::instance($oCust->connect)->executeUpdate($sql);
               }
            }
         }
      }

      function addnewsletter(){
	  	 $sql = "INSERT INTO NewsLetter (Email) VALUES('".$this->get("Email")."')";
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         reset($this->RemoteCustomers);
         while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
			 $sql = "INSERT INTO NewsLetter (Email) VALUES('".$oCust->get("Email")."')";
			 DBQuery::instance($oCust->connect)->executeUpdate($sql);
         }
      }

      function delnewsletter(){
	  	 $sql = "DELETE FROM NewsLetter WHERE Email='".$this->get("Email")."'";
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         reset($this->RemoteCustomers);
         while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
            $sql = "DELETE FROM NewsLetter WHERE Email='".$oCust->get("Email")."'";
		 	DBQuery::instance($oCust->connect)->executeUpdate($sql);
         }
      }

      function addCoupon($coup,$uid){
	  	 $sql = "INSERT IGNORE INTO Wallet (Customer_,Coupon_) VALUES(".$uid.",".$coup.")";
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         reset($this->RemoteCustomers);
         while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
            $sql = "INSERT IGNORE INTO Wallet (Customer_,Coupon_) VALUES(".$uid.",".$coup.")";
			DBQuery::instance($oCust->connect)->executeUpdate($sql);
         }
      }

      function delCoupon($coup){
	  	 $sql = "DELETE FROM Wallet WHERE Customer_=".$this->get("Customer_")." AND Coupon_=".$coup;
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         reset($this->RemoteCustomers);
         while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
		 	$sql = "DELETE FROM Wallet WHERE Customer_=".$oCust->get("Customer_")." AND Coupon_=".$coup;
		 	DBQuery::instance($oCust->connect)->executeUpdate($sql);
         }
      }

//      function loadCoupons(){
//         $this->Coupons = new CustomerCouponList($this->get("Customer_"));
//      }

      function loadNewCoupon($sql){
         $this->Coupons = new CouponList($sql,FAST_LOAD);
      }
	  
	  function getCustomerList($sql){
	  	 $sql = "SELECT * FROM Customer";
	     $rs = DBQuery::instance()->executeQuery($sql);
	     $this->HotCouponList = $rs;
		 return $rs;
      }
   }

   class RemoteCustomer extends Customer{
      var $ClassName = "RemoteCustomer";
      var $Key       = "Customer_";
	  var $connect   = "";
      function RemoteCustomer($server, $email =""){
		 $this->connect = "mysql://".constant("__DB_USER_".$server).";".
		                  constant("__DB_PASS_".$server)."@".constant("__DB_HOST_".$server).
						  "/".constant("__DB_BASE_".$server);
		                  
         //VItem::VItem(constant("__DB_HOST_".$server),constant("__DB_BASE_".$server),constant("__DB_USER_".$server),constant("__DB_PASS_".$server));

//         $this->SQL[QSELECT]  = "SELECT * FROM Customer WHERE Email='::Email::'";
//         $this->SQL[QDELETE]  = "DELETE FROM Customer WHERE Customer_=::Customer_::";
//         $this->SQL[QUPDATE]  = "UPDATE Customer SET  Forum='::Forum::',FName='::FName::', LName='::LName::', Password='::Password::', Email='::Email::', FreqLetter=::FreqLetter::, AlertEmail=::AlertEmail::, LastLetter='::LastLetter::' WHERE Customer_=::Customer_::";
//         $this->SQL[QINSERT]  = "INSERT INTO Customer (Forum,FName,LName,Password,Email,FreqLetter,AlertEmail,LastLetter) VALUES('::Forum::','::FName::','::LName::','::Password::','::Email::',1,1,'::LastLetter::')";

         if ( strlen($email) > 0 ){
		 	$sql = "SELECT * FROM Customer WHERE Email='$email'";
			$this->CustomerInfo = DBQuery::instance($this->connect)->getRow($sql);
         }
      }
   }
}
?>
