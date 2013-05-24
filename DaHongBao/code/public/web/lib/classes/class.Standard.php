<?php

if ( !defined("CLASS_STANDARD_PHP") ){
   define("CLASS_STANDARD_PHP","YES");

		 require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
		 
   class Standard {
      var $ClassName = "Standard";
      var $Key       = "Standard_";
	  var $StandardInfo = array();
	  var $StandardList = array();
	  
      function Standard($id =-1){
//         $this->SQL[QUPDATE]  = "UPDATE Standard SET Name='::Name::', Descript='::Descript::', isActive=::isActive:: WHERE Standard_=::Standard_::";
//         $this->SQL[QINSERT]  = "INSERT INTO Standard (Standard_,Type,Value,isDefault) VALUES(::Standard_::,'::Type::','::Value::',::isDefault::)";
//         $this->SQL[QDELETE]  = "DELETE FROM Standard WHERE Standard_=::Standard_::";
         if ( $id > 0 ){
		    $sql = "SELECT * FROM Standard WHERE Standard_= $id";
			$this->StandardInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function insert($flag =1){
	  	  if ( $flag == 1 ){
		 	$standard = $this->getNextID("Standard");
         } else {
		 	$standard = $this->StandardInfo["Standard_"];
		 }
		 $sql = "INSERT INTO Standard (Standard_,Type,Value,isDefault)".
			   "VALUES(".$standard.",'".$this->StandardInfo["Type"].
			   "','".$this->StandardInfo["Value"]."','".$this->StandardInfo["isDefault"]."')";
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
	  
	  function get($name){
         return $this->StandardInfo[$name];
      }
	  
	  function set($name,$value){
         $this->StandardInfo[$name] = $value;
      }
	  
      function setdefault(){
	  	 $sql = "UPDATE Standard SET isDefault=0 WHERE Type='".$this->get("Type")."'";
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		 $sql = "UPDATE Standard SET isDefault=1 WHERE Standard_=".$this->get("Standard_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }
	  
	  function getStandardList($type =""){
	  	$sql = "SELECT * FROM Standard WHERE Type='".$type."'";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->StandardList = $rs;
		return $rs;
	  }
	  
	  function getdefault(){
	    for($i=0;$i<count($this->StandardList); $i++) {
			if ($this->StandardList[$i]["isDefault"] == 1) return $this->StandardList[$i]["Value"];
		}
	  }
   }
}
?>