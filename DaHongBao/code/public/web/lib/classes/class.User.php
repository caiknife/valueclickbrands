<?php

if ( !defined("CLASS_USER_PHP") ){
   define("CLASS_USER_PHP","YES");

         require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class User{
      var $ClassName = "User";
      var $Key       = "User_";
	  var $UserInfo = array();
	  var $UserList = array();
	  
      function User($id =-1){

//         $this->SQL[QUPDATE]  = "UPDATE User SET Name='::Name::', Login='::Login::', Password='::Password::' WHERE User_=::User_::";
//         $this->SQL[QINSERT]  = "INSERT INTO User (Name,Login,Password) VALUES('::Name::','::Login::','::Password::')";
//         $this->SQL[QDELETE]  = "DELETE FROM User WHERE User_=::User_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM User WHERE User_= $id";
			$this->UserInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function search_($name){
	  	 $sql = "SELECT * FROM User WHERE Name='$name'";
		 $rs = DBQuery::instance()->executeQuery($sql);
		 return $rs;
      }

      function permition($link_id =-1){
         $result = 0;
         if ( $link_id > 0 ){
		 	$sql = "SELECT * FROM Access WHERE User_=".$this->UserInfo["User_"]." AND Link_=".$link_id;
            $tmp = DBQuery::instance()->getRow($sql);
            if ( $tmp["Status"] == 1 ) $result = 1;
         }
         return $result;
      }

      function clearPermition(){
	  	 $sql = "DELETE FROM Access WHERE User_=".$this->UserInfo["User_"];
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }

      function setPermition($link_id){
	  	 $sql = "INSERT INTO Access VALUES(".$this->UserInfo["User_"].",".$link_id.",1)";
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }
	  
	  function getUserList(){
		   $sql = "SELECT * FROM User";
		   $rs = DBQuery::instance()->executeQuery($sql);
           $this->UserList = $rs;
		   return $rs;
      }
   }
}
?>