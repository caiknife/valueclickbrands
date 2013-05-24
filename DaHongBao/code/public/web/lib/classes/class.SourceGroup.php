<?php

if ( !defined("CLASS_SOURCE_GROUP_PHP") ){
   define("CLASS_SOURCE_GROUP_PHP","YES");

         require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class SourceGroupR {
      var $ClassName = "SourceGroupR";
      var $Key       = "SourceGroup_";
	  var $SourceGroupRInfo = array();
	  var $SourceGroupListR = array();

      function SourceGroupR($id =-1){
        // $this->SQL[QUPDATE]  = "UPDATE SourceGroup SET Name='::Name::', UniqueID='::UniqueID::', isTrack ='::isTrack::', isPopup=::isPopup:: WHERE SourceGroup_=::SourceGroup_::";
//         $this->SQL[QINSERT]  = "INSERT INTO SourceGroup (SourceGroup_,Name,UniqueID, isTrack, isPopup) VALUES(::SourceGroup_::,'::Name::','::UniqueID::', '::isTrack::', ::isPopup:: )";
//         $this->SQL[QDELETE]  = "DELETE FROM SourceGroup WHERE SourceGroup_=::SourceGroup_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM SourceGroup WHERE SourceGroup_= $id ";
			$this->SourceGroupRInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function insert(){
	  	 $new_id = $this->getNextID("SourceGroup");
		 $sql = "INSERT INTO SourceGroup (SourceGroup_,Name,UniqueID, isTrack, isPopup)" .
		        "VALUES('".$new_id."','".$this->SourceGroupRInfo["Name"]."','".$this->SourceGroupRInfo["UniqueID"].
				"','".$this->SourceGroupRInfo["isTrack"]."','".$this->SourceGroupRInfo["isPopup"]."')";
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
	  
      function find($source){
	  	 $sql = "SELECT * FROM SourceGroup WHERE Name='$source'";
		 $this->SourceGroupRInfo = DBQuery::instance()->getRow($sql);
		 return $this->SourceGroupRInfo;
      }
	  
	  function getSourceGroupListR(){
	  	$sql = "SELECT * FROM SourceGroup ORDER BY Name";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->SourceGroupListR = $rs;
		return $rs;
	  }
   }
  
}
?>