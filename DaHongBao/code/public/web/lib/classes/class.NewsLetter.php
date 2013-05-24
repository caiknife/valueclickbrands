<?php

if ( !defined("CLASS_NEWSLETTER_PHP") ){
   define("CLASS_NEWSLETTER_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class NewsLetter{
      var $ClassName = "NewsLetter";
      var $Key       = "NewsLetter_";
      var $Categories= array();
	  var $NewsLetterInfo= array();
	  var $NewsLetterList = array();

      function NewsLetter($id =-1){
         $this->SQL[QSELECT]  = "SELECT * FROM NewsLetter WHERE NewsLetter_=::NewsLetter_::";
         $this->SQL[QUPDATE]  = "UPDATE NewsLetter SET Email='::Email::' WHERE NewsLetter_=::NewsLetter_::";
         $this->SQL[QINSERT]  = "INSERT INTO NewsLetter (Email) VALUES('::Email::')";
         $this->SQL[QDELETE]  = "DELETE FROM NewsLetter WHERE NewsLetter_=::NewsLetter_::";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM NewsLetter WHERE NewsLetter_= $id";
			$this->NewsLetterInfo = DBQuery::instance()->getRow($sql);
         }
      }

/*
      function insert(){
         $new_id = $this->nextid();
         Query::insert(array("NewsLetter_" => $new_id));
         $this->select(array("NewsLetter_" => $new_id));
         $this->load();
      }
*/
      
      function find($email){
	  	 $sql = "SELECT * FROM NewsLetter WHERE Email='$email'";
		 $this->NewsLetterInfo = DBQuery::instance()->getRow($sql);
		 return $this->NewsLetterInfo;
       }
	   
	   function getNewsLetterList(){
	  	$sql = "SELECT * FROM NewsLetter";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->NewsLetterList = $rs;
		return $rs;
	  }
   }
 
}
?>