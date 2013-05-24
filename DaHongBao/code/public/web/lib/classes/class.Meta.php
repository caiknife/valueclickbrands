<?php

if ( !defined("CLASS_META_PHP") ){
   define("CLASS_META_PHP","YES");

   require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
   
   class Meta {
      var $ClassName = "Meta";
      var $Key       = "Meta_";
	  var $MetaInfo  = array();
	  var $MetaList = array();

      function Meta($id =-1){
//         $this->SQL[QUPDATE]  = "UPDATE Meta SET ItemType='::ItemType::', MetaTitle='::MetaTitle::', MetaDescription='::MetaDescription::', MetaKeywords='::MetaKeywords::', MetaFrame='::MetaFrame::', HiddenWords='::HiddenWords::' WHERE Meta_=::Meta_::";
//         $this->SQL[QINSERT]  = "INSERT INTO Meta (ItemType,MetaTitle,MetaDescription, MetaKeywords, MetaFrame, HiddenWords) VALUES('::ItemType::','::MetaTitle::','::MetaDescription::','::MetaKeywords::', '::MetaFrame::', '::HiddenWords::')";
//         $this->SQL[QDELETE]  = "DELETE FROM Meta WHERE Meta_=::Meta_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM Meta WHERE Meta_= $id";
			$this->MetaInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function find($name,$value){
	  	 $sql = "SELECT * FROM Meta WHERE ItemType='$value'";
		 $this->MetaInfo = DBQuery::instance()->getRow($sql);
		 return $this->MetaInfo;
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
	  
	  function insert($params =array()){
         
      }
	  
	  function getMetaList(){
	  	$sql = "SELECT * FROM Meta";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->MetaList = $rs;
		return $rs;
	  }
   }

}
?>