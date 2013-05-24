<?php

if ( !defined("CLASS_SOURCE_PHP") ){
   define("CLASS_SOURCE_PHP","YES");

   require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
   require_once(__INCLUDE_ROOT."/lib/classes/class.VItem.php");

   class Source {
      var $ClassName = "Source";
      var $Key       = "Source_";
	  var $SourceInfo = array();
	  var $SourceList = array();
	  var $SourceByGroupList = array();
	  
      function Source($id =-1){
//         $this->SQL[QUPDATE]  = "UPDATE Source SET Name='::Name::', Descript='::Descript::', Grouped='::Grouped::', SiteID='::SiteID::', SourceGroup_=::SourceGroup_::, UniqueID='::UniqueID::', SourceID='::SourceID::' WHERE Source_=::Source_::";
//         $this->SQL[QINSERT]  = "INSERT INTO Source (Source_,Name,Descript,Grouped,SiteID,SourceGroup_,UniqueID,SourceID) VALUES(::Source_::,'::Name::','::Descript::','::Grouped::','::SiteID::',::SourceGroup_::,'::UniqueID::','::SourceID::')";
//         $this->SQL[QDELETE]  = "DELETE FROM Source WHERE Source_=::Source_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM Source WHERE Source_ = $id";
			$this->SourceInfo = DBQuery::instance()->getRow($sql);
         }
      }
      
      function getIncomingClick($bd,$ed){
	  	 $sql = "SELECT SUM(Visitor) click FROM VSource WHERE Source_=".$this->SourceInfo["Source_"].
		        " AND Dat>='".to_mysql_date($bd)."' AND Dat<='".to_mysql_date($ed)."'";
		 $rs = DBQuery::instance()->getOne($sql);
         if ($rs > 0){
            return $rs;
         }
         return 0;
      }

      function getConversion($bd,$ed){
	     $sql = "SELECT SUM(Visitor) click FROM SMerchant WHERE Source_=".$this->SourceInfo["Source_"].
		        " AND Dat>='".to_mysql_date($bd)."' AND Dat<='".to_mysql_date($ed)."'";
		 $rs = DBQuery::instance()->getOne($sql);
         if ($rs > 0){
            return $rs;
         }
         return 0;
      }

      function insert(){
         $new_id = $this->getNextID("Source");
		 $sql = "INSERT INTO Source (Source_,Name,Descript,Grouped,SiteID,SourceGroup_,UniqueID,SourceID)" .
		        "VALUES('".$new_id."','".$this->SourceInfo["Name"]."','".$this->SourceInfo["Descript"].
				"','".$this->SourceInfo["Grouped"]."','".$this->SourceInfo["SiteID"]."',".$this->SourceInfo["SourceGroup_"].
				",'".$this->SourceInfo["UniqueID"]."','".$this->SourceInfo["SourceID"]."')";
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
	  	 $sql = "SELECT * FROM Source WHERE Name='$source'";
		 $this->SourceInfo = DBQuery::instance()->getRow($sql);
		 return $this->SourceInfo;
      }
	  
	  function get($name){
         return $this->MetaInfo[$name];
      }
	  
	  function set($name,$value){
         $this->MetaInfo[$name] = $value;
      }
	  
	  function update($params =array()){
         
      }
	  
	  function delete($params =array()){
         
      }
	  
	  function getSourceList(){
	  	$sql = "SELECT * FROM Source";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->SourceList = $rs;
		return $rs;
	  }
	  
	  function getSourceByGroupList($group_name =""){
	  	$sql = "SELECT * FROM Source WHERE Grouped='".$group_name."'";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->SourceByGroupList = $rs;
		return $rs;
	  }
   }

   class SourceGroup {
      var $ClassName = "SourceGroup";
      var $Key       = "Name";
      var $Sale      = 0.0;
	  var $SourceGroupInfo = array();
	  var $SourceGroupList = array();
	  
      function getSale($bd,$ed){
	  	 $sql = "SELECT SUM(SalesSum) Sales FROM Sales WHERE SourceGroup='".$this->SourceGroupInfo["Name"].
		 	    "' AND Dat>='".to_mysql_date($bd)."' AND Dat<='".to_mysql_date($ed)."'";
         $rs = DBQuery::instance()->getOne($sql);
         if ($rs > 0){
            return $rs;
         }
         return 0;
      }

      function getIncomingClick($bd,$ed){
	  	 $sql = "SELECT SUM(Visitor) click FROM VSource, Source WHERE Source.Grouped='".$this->SourceGroupInfo["Name"].
		        "' AND VSource.Source_=Source.Source_ AND VSource.Dat>='".to_mysql_date($bd)."' AND VSource.Dat<='".to_mysql_date($ed)."'";
         $rs = DBQuery::instance()->getOne($sql);
         if ($rs > 0){
            return $rs;
         }
         return 0;
      }

      function getMerchantClick($merchant,$bd,$ed){
	  	 $sql = "SELECT SUM(Visitor) click FROM SMerchant, Source WHERE Source.Grouped='".$this->SourceGroupInfo["Name"].
		 	    "' AND SMerchant.Source_=Source.Source_ AND SMerchant.Dat>='".to_mysql_date($bd).
				"' AND SMerchant.Dat<='".to_mysql_date($ed)."' AND SMerchant.Merchant_=".$merchant;
         $rs = DBQuery::instance()->getOne($sql);
         if ($rs > 0){
            return $rs;
         }
         return 0;
      }

      function getMerchantRevenue($merchant,$bd,$ed){
	  	 $sql = "SELECT SUM(SalesSum) summa FROM Sales WHERE SourceGroup='".$this->SourceGroupInfo["Name"].
		 		"' AND Dat>='".to_mysql_date($bd)."' AND Dat<='".to_mysql_date($ed)."' AND Merchant_=".$merchant;
         $rs = DBQuery::instance()->getOne($sql);
         if ($rs > 0.0){
            return $rs;
         }
         return 0;
      }
	  
	  function getSourceGroupList(){
	  	$sql = "SELECT DISTINCT(Grouped) Name FROM Source WHERE Grouped<>'' ORDER BY Grouped";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->SourceGroupList = $rs;
		return $rs;
	  }
   }

   class SalesGroup {
      var $ClassName = "SalesGroup";
      var $Key       = "Name";
	  var $SalesList = array();
	  
	  function getSalesList($merchant,$bd,$ed){
	  	$sql = "SELECT SourceGroup Name, SUM(SalesSum) Sales FROM Sales WHERE Merchant_=".$merchant.
		       " AND Dat>='".to_mysql_date($bd)."' AND Dat<='".to_mysql_date($ed)."' " .
			   "GROUP BY Name ORDER BY Name";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->SalesList = $rs;
		return $rs;
	  }
   }
}
?>
