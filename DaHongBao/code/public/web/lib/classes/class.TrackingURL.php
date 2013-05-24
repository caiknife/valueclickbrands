<?php
if ( !defined("CLASS_TRACKINGURL_PHP") ){
   define("CLASS_TRACKINGURL_PHP","YES");

         require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
         require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class TrackingURL{
      var $ClassName = "TrackingURL";
      var $Key       = "TrackingURL_";
	  var $TrackingURLInfo = array();
	  var $TrackingURLList = array();

      function TrackingURL($trackingurl =""){
//         $this->SQL[QUPDATE]  = "UPDATE TrackingURL SET Name='::Name::', Format='::Format::', ID='::ID::', AddValue='::AddValue::', Mask='::Mask::' WHERE TrackingURL_=::TrackingURL_::";
//         $this->SQL[QINSERT]  = "INSERT INTO TrackingURL (TrackingURL_,Name,Format,ID,AddValue,Mask) VALUES(::TrackingURL_::,'::Name::','::Format::','::ID::','::AddValue::','::Mask::')";
//         $this->SQL[QDELETE]  = "DELETE FROM TrackingURL WHERE TrackingURL_=::TrackingURL_::";
         if ( $trackingurl > 0 ){
		 	$sql = "SELECT * FROM TrackingURL WHERE TrackingURL_= $trackingurl";
			$this->TrackingURLInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function insert(){
	  	 $new_id = $this->getNextID("TrackingURL");
		 $sql = "INSERT INTO TrackingURL (TrackingURL_,Name,Format,ID,AddValue,Mask) " .
		        "VALUES('".$new_id."','".$this->TrackingURLInfo["Name"]."','".$this->TrackingURLInfo["Format"].
				"','".$this->TrackingURLInfo["AddValue"]."','".$this->TrackingURLInfo["Mask"]."')";
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

      function find($url){
	  	 $sql = "SELECT * FROM TrackingURL WHERE INSTR(UPPER('$url'),UPPER(Mask))";
		 $this->TrackingURLInfo = DBQuery::instance()->getRow($sql);
		 return $this->TrackingURLInfo;
      }
	  
	  function get($name){
         return $this->TrackingURLInfo[$name];
      }
	  
	  function set($name,$value){
         $this->TrackingURLInfo[$name] = $value;
      }

      function getUniq($source_group){
	  	 $sql = "SELECT UniqueID FROM TrackingGroup WHERE TrackingURL_=".$this->get("TrackingURL_")." AND SourceGroup_=".$source_group;
		 $tmp = DBQuery::instance()->getOne($sql);
         return $tmp;
      }
      function setUniq($source_group,$val){
	  	 $sql = "UPDATE TrackingGroup SET UniqueID='".$val."' WHERE TrackingURL_=".$this->get("TrackingURL_")." AND SourceGroup_=".$source_group;
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		 $sql = "INSERT INTO TrackingGroup (UniqueID,TrackingURL_,SourceGroup_) VALUES('".$val."',".$this->get("TrackingURL_").",".$source_group.")";
         DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }
	  
	  function getTrackingURLList(){
	  	$sql = "SELECT * FROM TrackingURL";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->TrackingURLList = $rs;
		return $rs;
	  }
   }
}
?>