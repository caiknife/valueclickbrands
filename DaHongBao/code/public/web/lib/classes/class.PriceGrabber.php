<?php

if ( !defined("CLASS_PRICEGRABBER_PHP") ){
   define("CLASS_PRICEGRABBER_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class PriceGrabber{
      var $ClassName = "PriceGrabber";
      var $Key       = "PriceGrabber_";
	  var $PriceGrabberInfo = array();
	  var $PriceGrabberList = array();

      function PriceGrabber($id =-1){
//         $this->SQL[QUPDATE]  = "UPDATE PriceGrabber SET Name='::Name::', URL='::URL::', isActive=::isActive:: WHERE PriceGrabber_=::PriceGrabber_::";
//         $this->SQL[QINSERT]  = "INSERT INTO PriceGrabber (PriceGrabber_,Name,URL,isActive) VALUES(::PriceGrabber_::,'::Name::','::URL::',::isActive::)";
//         $this->SQL[QDELETE]  = "DELETE FROM PriceGrabber WHERE PriceGrabber_=::PriceGrabber_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM PriceGrabber WHERE PriceGrabber_= $id";
			$this->PriceGrabberInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function insert($flag =1){
         if ( $flag == 1 ){
            $new_id = $this->getNextID("PriceGrabber");
         } else {
			$new_id = $this->PriceGrabberInfo["PriceGrabber_"];
         }
		 $sql = "INSERT INTO PriceGrabber (PriceGrabber_,Name,URL,isActive) " .
		        "VALUES(".$new_id.",'".$this->PriceGrabberInfo["Name"]."','".
				$this->PriceGrabberInfo["URL"]."',".$this->PriceGrabberInfo["isActive"].")";
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
         return $this->PriceGrabberInfo[$name];
      }
	  
	  function set($name,$value){
         $this->PriceGrabberInfo[$name] = $value;
      }
	  
      function search_($name){
	  	 $sql = "SELECT * FROM PriceGrabber WHERE Name REGEXP '^$name$' AND isActive=1";
         $rs = DBQuery::instance()->executeQuery($sql);
		 return $rs;
      }

      function isFound(){
         if ( $this->get("PriceGrabber_") > 0 ) return true;
      }

      function find($name){
	  	 $sql = "SELECT * FROM PriceGrabber WHERE Name='$name'";
		 $this->PriceGrabberInfo = DBQuery::instance()->getRow($sql);
		 return $this->PriceGrabberInfos;
      }
	  
	  function getPriceGrabberList(){
	  	$sql = "SELECT * FROM PriceGrabber";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->PriceGrabberList = $rs;
		return $rs;
	  }
   }
}
?>