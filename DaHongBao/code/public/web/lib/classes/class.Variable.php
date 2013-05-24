<?php
if ( !defined("CLASS_VARIABLE_PHP") ){
   define("CLASS_VARIABLE_PHP","YES");

         require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
         require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");

   class Variable{
      var $ClassName = "Variable";
      var $Key       = "Variable_";

      var $Language  = "en";
      var $VarName   = "";
      var $VarValue  = "";
	  var $VariableInfo = array();

      function Variable($id =0, $lang ="en"){
         $this->Language = $lang;

         //$this->SQL[QSELECT]  = "SELECT v.*, lv.Value FROM Variable v, Language l, LangVar lv WHERE l.Abbr='::Abbr::' AND l.Language_=lv.Language_ AND lv.Variable_=v.Variable_ AND v.Variable_=::Variable_::";
         if ( $id > 0 ){
		 	$sql = "SELECT v.*, lv.Value FROM Variable v, Language l, LangVar lv " .
			       "WHERE l.Abbr='".$this->Language."' AND l.Language_=lv.Language_ AND " .
				   "lv.Variable_=v.Variable_ AND v.Variable_= $id";
			$this->VariableInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function get($name, $lang=""){
         $language = $lang != "" ? $lang : $this->Language;
		 $sql = "SELECT v.*, lv.Value FROM Variable v, Language l, LangVar lv " .
		        "WHERE l.Abbr='".$this->Language."' AND l.Language_=lv.Language_ AND " .
				"lv.Variable_=v.Variable_ AND v.Name='".$name."'";
		 $rs = DBQuery::instance()->getRow($sql);
		 return $rs["Value"];

      }
   }
}
?>