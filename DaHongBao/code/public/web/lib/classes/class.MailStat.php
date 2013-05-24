<?php

if ( !defined("CLASS_MAILSTAT_PHP") ){
   define("CLASS_MAILSTAT_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class MailStat{
      var $ClassName = "MailStat";
      var $Key       = "Dat";
	  var $MailStatInfo = array();
	  var $MailStatList = array();

      function MailStat($id =-1){
         //$this->SQL[QUPDATE]  = "UPDATE MailStat SET MailASAP =(MailASAP), Descript='::Descript::', isActive=::isActive::, LastGenerate=(::LastGenerate::) WHERE MailStat_=::MailStat_::";
         //$this->SQL[QINSERT]  = "INSERT INTO MailStat (MailStat_,Name,Descript,isActive,LastGenerate) VALUES(::MailStat_::,'::Name::','::Descript::',::isActive::,'00000000000000')";
        // $this->SQL[QDELETE]  = "DELETE FROM MailStat WHERE Dat='::Dat::'";
         if ( $id > 0 ){
		 	$sql = "SELECT * FROM MailStat WHERE Dat='$id'";
			$this->MailStatInfo = DBQuery::instance()->getRow($sql);
         }
      }
      
      function addLetter($field, $num =1){
	  	 $sql = "UPDATE MailStat SET ".$field."=(".$field."+".$num.") WHERE Dat=CURDATE()";
		 $back = DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         if ( $back == 0 ){
		 	$sql = "INSERT INTO MailStat (Dat,".$field.") VALUES(CURDATE(),".$num.")";
            DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         }
      }
	  
	  function getMailStatList(){
	  	$sql = "SELECT * FROM MailStat WHERE Dat>='".to_mysql_date($bd).
		       "' AND Dat<='".to_mysql_date($ed)."' ORDER BY Dat";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->MailStatList = $rs;
		return $rs;
	  }
   }
  
}
?>