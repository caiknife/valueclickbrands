<?php
if ( !defined("CLASS_ACCESS_PHP") ){
   define("CLASS_ACCESS_PHP","YES");

    require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class Access{
      var $ClassName = "Access";
      var $Key       = "User_";
      var $Table     = "User";
      var $fLogin    = "Login";
      var $fPassword = "Password";
      var $ID        = -1;

      var $LTable    = "Link";
      var $ATable    = "Access";

      var $fName     = "Name";

      function Access($auto ="yes", $name =""){
         global $PHP_AUTH_USER, $PHP_AUTH_PW;
         if( !isset($PHP_AUTH_USER) ) {
            $this->access_login();
         }
         if ($auto == "yes"){
            if( !isset($PHP_AUTH_USER) ) {
               $this->access_login();
            }
            else{
			   $sql = "SELECT * FROM ".$this->Table." WHERE ".$this->fLogin."= '" . $PHP_AUTH_USER . "'";
			   $rs = DBQuery::instance()->getRow($sql);
               $this->ID = $rs[$this->Key];
               $this->fName = $rs[$this->fName];
               if ($rs[$this->fPassword] != $PHP_AUTH_PW ){
                  $this->access_denied();
               }
            }
         }
         else{
		 	$sql = "SELECT * FROM ".$this->Table." WHERE ".$this->fLogin."= '" . $name . "'";	 
            $rs = DBQuery::instance()->getRow($sql);
         }
      }

      function access_login(){
         Header("WWW-Authenticate: Basic realm=\"Admin area login\"");
         Header("HTTP/1.0 401 Unauthorized");
         exit();
      }

      function access_denied(){
         echo "<html>\n";
         echo "<body>\n";
            echo "<table width=\"100%\" height=\"100%\">\n";
               echo "<tr>";
                  echo "<td align=\"center\" valign=\"middle\">\n";
                     echo "<font size=\"+5\" color=\"red\">ACCESS DENIED!!!</font>\n";
                  echo "</td>\n";
               echo "</tr>\n";
            echo "</table>\n";
         echo "</body>\n";
         echo "</html>\n";
         exit();
      }

      function verify_access($page){
		 $sql = "SELECT a.Status FROM ".$this->ATable." a, ".$this->LTable." l WHERE a.".
		 		$this->LTable."_=l.".$this->LTable."_ AND a.".$this->Table."_=".$this->ID." AND l.Name='".$page."'";
		 $rs = DBQuery::instance()->getOne($sql);
         if ( $rs == 1 ){
         	return 1;
         }
         $this->access_denied();
      }
   }
}
?>