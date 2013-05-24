<?php
if ( !defined("CLASS_KEYWORD_PHP") ){
   define("CLASS_KEYWORD_PHP","YES");

    require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class Keyword {
      var $ClassName = "Keyword";
      var $Key       = "Keyword_";
      var $IP        = "";
	  var $KeywordInfo = array();

      function Keyword($keyword =-1){
         if ( $keyword > 0 ){
		 	$sql = "SELECT * FROM Keyword WHERE Keyword_= $keyword";
			$this->KeywordInfo = DBQuery::instance()->getRow($sql);
         }
      }
	  
	  function get($name){
         return $this->KeywordInfo[$name];
      }
	  
	  function set($name,$value){
         $this->KeywordInfo[$name] = $value;
      }
	  
      function insert($keyword){
         $this->set("Name",$keyword);
		 $sql = "INSERT INTO Keyword (Name) VALUES('$keyword')";
         DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         $this->find($keyword);
      }

      function find($keyword){
         $keyword = strlen($keyword) > 0 ? $keyword : "Unknown keyword";
		 $sql = "SELECT * FROM Keyword WHERE Name='$keyword'";
		 $this->KeywordInfo = DBQuery::instance()->getRow($sql);
		 return $this->KeywordInfo;
      }

      function update_referrer($keyword,$referrer){
      }

      function update_statistic($keyword,$page_type,$category =-1,$merchant =-1,$coupon =-1){
         
      }
   }
}
?>