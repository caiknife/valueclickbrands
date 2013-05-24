<?php
if ( !defined("CLASS_SYSTEM_PHP") ){
   define("CLASS_SYSTEM_PHP","YES");

    require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
	require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class System{
      var $ClassName = "System";
      var $Key       = "System_";
	  var $SystemInfo = array();

      function System($name =""){
		 
         //$this->SQL[QSELECT]  = "SELECT * FROM System WHERE Name='::Name::'";
         //$this->SQL[QUPDATE]  = "UPDATE System SET Value='::Value::' WHERE Name='::Name::'";
         if ( strlen($name) > 0 ){
		 	$sql = "SELECT * FROM System WHERE Name='$name'";
			$this->SystemInfo = DBQuery::instance(__DAHONGBAO_Master)->getRow($sql);
         }
      }
	  
	  function update($param=array()) {
	  	 if(!isset($param["Name"])) {
		 	$param["Name"] = $this->SystemInfo["Name"];
		 }
		 if(!isset($param["Value"])) {
		 	$param["Value"] = $this->SystemInfo["Value"];
		 }
		 $sql = "UPDATE System SET Value='".$param["Value"]."' WHERE Name='".$param["Name"]."'";
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  }
	  
	  function get($name){
         return $this->SystemInfo[$name];
      }
	  
	  function set($name,$value){
         $this->SystemInfo[$name] = $value;
      }
/*
      function update(){
         $this->run_spec("INSERT INTO NewsLetter (Email) VALUES('".$this->get("Email")."')");
         reset($this->RemoteCustomers);
         while ( list($key,$oCust) = @each($this->RemoteCustomers) ){
            $oCust->run_spec("INSERT INTO NewsLetter (Email) VALUES('".$this->get("Email")."')");
         }
      }
*/
   }
}
?>