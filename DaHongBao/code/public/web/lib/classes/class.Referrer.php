<?php
if ( !defined("CLASS_REFERRER_PHP") ){
   define("CLASS_REFERRER_PHP","YES");

         require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
         require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class Referrer{
      var $ClassName = "Referrer";
      var $Key       = "Referrer_";
	  var $ReferrerInfo = array();
	  var $ReferrerStat = array();

      function Referrer($referrer =-1){
         //$this->SQL[QINSERT]  = "INSERT INTO Referrer (URL,Mask) VALUES('::URL::','::Mask::')";
         if ( $referrer > 0 ){
		 	$sql = "SELECT * FROM Referrer WHERE Referrer_= $referrer";
			$this->ReferrerInfo = DBQuery::instance()->getRow($sql);
         }
      }
	  
	  function get($name){
         return $this->ReferrerInfo[$name];
      }
	  
	  function set($name,$value){
         $this->ReferrerInfo[$name] = $value;
      }
	  
      function insert($referrer, $mask=""){
         $this->set("URL",$referrer);
         $this->set("Mask",$mask);
		 $sql = "INSERT INTO Referrer (URL,Mask) VALUES('$referrer','$mask')";
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
         $this->find($referrer);
      }

      function get_referrer($http_referer){
         return $http_referer;
      }

      function get_keyword($http_referer){
         return $http_referer;
      }

      function find($referrer){
         $referrer = strlen($referrer) > 0 ? $referrer : "Unknown referrer";
		 $sql = "SELECT * FROM Referrer WHERE URL='$referrer";
		 $this->ReferrerInfo = DBQuery::instance()->getRow($sql);
		 return $this->ReferrerInfo;
      }

      function update_statistic($referrer,$page_type,$category,$merchant,$coupon){
      }
	  
	  function getReferrerStat($bd,$ed){
	  	$sql = "SELECT r.*, SUM(s.Visitor) Click, SUM(v.Visitor) Refer, " .
		       "IF(SUM(s.Visitor)>0,ROUND((SUM(v.Visitor)/SUM(s.Visitor))*100),0) Convers " .
			   "FROM Referrer r LEFT JOIN SMerchant s ON (s.Referrer_=r.Referrer_ AND " .
			   "s.Dat>='".to_mysql_date($bd)."' AND s.Dat<='".to_mysql_date($ed)."') " .
			   "LEFT JOIN VReferrer v ON (v.Referrer_=r.Referrer_ AND v.Dat>='".to_mysql_date($bd)."' AND " .
			   "v.Dat<='".to_mysql_date($ed)."') GROUP BY r.Referrer_ ORDER BY r.URL";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->ReferrerStat = $rs;
		return $rs;
	  }
   }
}
?>