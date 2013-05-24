<?php

if ( !defined("CLASS_CUSTOMSEARCH_PHP") ){
   define("CLASS_CUSTOMSEARCH_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class CustomSearch{
      var $ClassName = "CustomSearch";
      var $Key       = "CustomSearch_";
	  var $CustomSearchInfo = array();
	  var $CustomSearchList = array();
	  
      function CustomSearch($id =-1){
//         $this->SQL[QUPDATE]  = "UPDATE CustomSearch SET Name='::Name::', URL='::URL::', isActive=::isActive:: WHERE CustomSearch_=::CustomSearch_::";
//         $this->SQL[QINSERT]  = "INSERT INTO CustomSearch (CustomSearch_,Name,URL,isActive) VALUES(::CustomSearch_::,'::Name::','::URL::',::isActive::)";
//         $this->SQL[QDELETE]  = "DELETE FROM CustomSearch WHERE CustomSearch_=::CustomSearch_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM CustomSearch WHERE CustomSearch_= $id";
			$this->CustomSearchInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function insert($flag =1){
         if ( $flag == 1 ){
            $new_id = $this->getNextID("CustomSearch");
         } else {
            $new_id = $this->CustomSearchInfo["CustomSearch_"];
         }
		 $sql = "INSERT INTO CustomSearch (CustomSearch_,Name,URL,isActive) " .
		        "VALUES('".$new_id."','".$this->CustomSearchInfo["Name"]."','".$this->CustomSearchInfo["URL"].
				"','".$this->CustomSearchInfo["isActive"]."')";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }
	  
	  function getNextID($name) {
	  	$sql = "SELECT ID FROM Sequence WHERE Name = '$name'";
		$rs = DBQuery::instance()->getOne($sql);
		$newID = $rs+1;
		$sql = "UPDATE Sequence SET ID = $newID WHERE Name = '$name'";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		return $newID;
	  }	

	  
      function search_($name){
	  	 $sql = "SELECT * FROM CustomSearch WHERE Name REGEXP '^$name$' AND isActive=1";
		 $rs = DBQuery::instance()->executeQuery($sql);
		 return $rs;
      }
	  
	  function get($name){
         return $this->CustomSearchInfo[$name];
      }
	  
	  function set($name,$value){
         $this->CustomSearchInfo[$name] = $value;
      }
	  
      function isFound(){
         if ( $this->get("CustomSearch_") > 0 ) return true;
      }

      function find($name){
	  	 $sql = "SELECT * FROM CustomSearch WHERE Name='$name'";
		 $this->CustomSearchInfo = DBQuery::instance()->getRow($sql);
		 return $this->CustomSearchInfo;
      }
	  
	  function getCustomSearchList(){
	  	$sql = "SELECT * FROM CustomSearch";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->CustomSearchList = $rs;
		return $rs;
	  }
   }
   
}
?>
