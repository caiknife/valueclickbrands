<?php

if ( !defined("CLASS_LINK_PHP") ){
   define("CLASS_LINK_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class Link{
      var $ClassName = "Link";
      var $Key       = "Link_";
	  var $LinkInfo = array();
	  var $LinkList = array();
	  var $LinkListAccess = array();	  

      function Link($id =-1){
//         $this->SQL[QUPDATE]  = "UPDATE Link SET Name='::Name::', Descript='::Descript::', URL='::URL::' WHERE Link_=::Link_::";
//         $this->SQL[QINSERT]  = "INSERT INTO Link (Name,Descript,URL) VALUES('::Name::','::Descript::','::URL::')";
//         $this->SQL[QDELETE]  = "DELETE FROM Link WHERE Link_=::Link_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM Link WHERE Link_= $id";
            $this->LinkInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function search_($name){
	  	 $sql = "SELECT * FROM Link WHERE Name='$name'";
		 $rs = DBQuery::instance()->executeQuery($sql);
		 return $rs;
      }
	  
	  function getLinkList(){
	  	$sql = "SELECT * FROM Link";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->LinkList = $rs;
		return $rs;
	  }
	  
	  function getLinkListAccess($user="0"){
	  	$sql = "SELECT l.* FROM Link l, Access a WHERE a.Link_=l.Link_ AND a.User_=".$user." AND a.Status>0";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->LinkListAccess = $rs;
		return $rs;
	  }
   }

}
?>