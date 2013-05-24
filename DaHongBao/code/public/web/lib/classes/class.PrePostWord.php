<?php

if ( !defined("CLASS_PREPOSTWORD_PHP") ){
   define("CLASS_PREPOSTWORD_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class PrePostWord {
      var $ClassName = "PrePostWord";
      var $Key       = "PrePostWord_";
	  var $PrePostWordInfo = array();
	  var $PrePostWordList = array();
	  var $PrePostWordListS = array();

      function PrePostWord($id =-1){
//         $this->SQL[QUPDATE]  = "UPDATE PrePostWord SET Name='::Name::', isActive=::isActive:: WHERE PrePostWord_=::PrePostWord_::";
//         $this->SQL[QINSERT]  = "INSERT INTO PrePostWord (PrePostWord_,Name,isActive) VALUES(::PrePostWord_::,'::Name::',::isActive::)";
//         $this->SQL[QDELETE]  = "DELETE FROM PrePostWord WHERE PrePostWord_=::PrePostWord_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM PrePostWord WHERE PrePostWord_= $id";
            $this->PrePostWordInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function insert($flag =1){
         if ( $flag == 1 ){
            $new_id = $this->getNextID("PrePostWord");
         } else {
            $new_id = $this->PrePostWordInfo["PrePostWord_"];
         }
		 $sql = "INSERT INTO PrePostWord (PrePostWord_,Name,isActive) " .
		        "VALUES(".$new_id.",'".$this->PrePostWordInfo["Name"]."',".$this->PrePostWordInfo["isActive"].")";
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

	  
      function clear_prepost($SearchExpr){
         $SearchExpr = preg_replace(array("/\s+/"),array(" "),$SearchExpr);
         $SearchExpr = trim($SearchExpr);
         $prearrays = array();
         $postarrays= array();
         $likearrays= array();
         $prepostr  = array();
         //$oPrePostWordList = new PrePostWordListS();
		 $sql = "SELECT * FROM PrePostWord WHERE isActive=1 ORDER BY LENGTH(Name) DESC";
		 $rs = DBQuery::instance()->executeQuery($sql);
		 for($i=0; $i<count($rs); $i++) {
		 	$prearrays[]   = "/^".$rs[$i]["Name"]."[".__SEARCH_DELIMITER."]/i";
            $postarrays[]  = "/[".__SEARCH_DELIMITER."]".$rs[$i]["Name"]."$/i";
            $likearrays[]  = "/^".$rs[$i]["Name"]."$/i";
            $prepostr[]    = "";
		 }

         $psTmp = "";
         while ( $SearchExpr != $psTmp ){
            $psTmp = $SearchExpr;
            $SearchExpr = preg_replace($prearrays,$prepostr,$SearchExpr);
            $SearchExpr = preg_replace($postarrays,$prepostr,$SearchExpr);
            $SearchExpr = preg_replace(array("/\s+/"),array(" "),$SearchExpr);
            $SearchExpr = trim($SearchExpr);
         }
         $SearchExpr = preg_replace($likearrays,$prepostr,$SearchExpr);
         return $SearchExpr;
      }
	  
	  function getPrePostWordList(){
	  	$sql = "SELECT * FROM PrePostWord";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->PrePostWordList = $rs;
		return $rs;
	  }
	  
	  function getPrePostWordListS(){
	  	$sql = "SELECT * FROM PrePostWord WHERE isActive=1 ORDER BY LENGTH(Name) DESC";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->PrePostWordListS = $rs;
		return $rs;
	  }
   }

}
?>