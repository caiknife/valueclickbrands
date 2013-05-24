<?php
if ( !defined("CLASS_SESSION_PHP") ){
   define("CLASS_SESSION_PHP","YES");

         require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
         require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class Session{
      var $ClassName = "Session";
      var $Key       = "Session_";
      var $IP        = "";
	  var $SessionInfo = array();

      function Session($ip =""){
         $this->IP = $ip;
      }

      function update(){
         
         if ( strlen($this->IP) > 0 ){
		 	$sql = "UPDATE SessionDaily Set Visitor=(Visitor+1) WHERE Dat=CURDATE()";
			$back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            if ( $back == 0 ){
				$sql = "INSERT INTO SessionDaily (Dat,Visitor,Uniq) VALUES(CURDATE(),1,0)";
				DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            }
			$sql = "INSERT INTO Visitor (Dat,IP) VALUES(CURDATE(),'".$this->IP."')";
			$back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
            if ( $back > 0 ){
				$sql = "UPDATE SessionDaily Set Uniq=(Uniq+1) WHERE Dat=CURDATE()";
				DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
                return 2; // new session & uniq user
            }
            return 1; // new session
         }
         return 0;
      }
   }
}
?>