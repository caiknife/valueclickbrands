<?php
/**
 * class.Page.php
 *-------------------------
 *
 * Page base class
 *
 * PHP versions 4
 *
 * LICENSE: This source file is from CouponMountain.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 4.0
 * @version    CVS: $Id: class.Page.php,v 1.1 2013/04/15 10:57:54 rock Exp $
 * @link       http://www.couponmountain.com/
 * @deprecated File deprecated in Release 2.0.0
 */

if ( !defined("CLASS_PAGE_PHP") ){
   define("CLASS_PAGE_PHP","YES");


   require_once(__INCLUDE_ROOT."lib/functions/func.Debug.php");
   require_once(__INCLUDE_ROOT."lib/functions/func.URL.php");
   require_once(__INCLUDE_ROOT."lib/functions/func.Browser.php");
   //require_once(__INCLUDE_ROOT."lib/classes/class.Variable.php");
   //require_once(__INCLUDE_ROOT."lib/classes/class.DBQuery.php");
   //require_once(__INCLUDE_ROOT."lib/classes/class.Template.php");
  //require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");
   //require_once(__INCLUDE_ROOT."lib/classes/class.Merchant.php");
   //require_once(__INCLUDE_ROOT."lib/classes/class.System.php");
   //require_once(__INCLUDE_ROOT."/lib/classes/class.Meta.php");


   class Page{
      var $ClassName = "Page";
      var $Key       = "Page_";
	  var $PageInfo = array();
	  var $PageList = array();
	  var $ListCurPage = 0;
	  var $ListPageSize = 0;
	  var $ListPagePos = 0;

      function Page($page =0){
         //$this->SQL[QDELETE]  = "DELETE FROM Page WHERE Page_=::Page_::";
//         $this->SQL[QUPDATE]  = "UPDATE Page SET Name='::Name::', Content='::Content::', Title='::Title::', MetaTitle='::Title::', MetaDescription='::MetaDescription::', MetaKeywords='::MetaKeywords::', MetaFrame='::MetaFrame::', HiddenWords='::HiddenWords::', isSpecial=::isSpecial::, isStatic=::isStatic::, isFull=::isFull::, isScript=::isScript::, GroupName='::GroupName::', PageNumber=::PageNumber::, LastGenerate='::LastGenerate::', isSitemap=::isSitemap:: WHERE Page_=::Page_::";
//         $this->SQL[QINSERT]  = "INSERT INTO Page (Page_,Name,Title,MetaTitle,MetaDescription,MetaKeywords,MetaFrame,HiddenWords,Content,isSpecial,isStatic,isFull,isScript,GroupName,PageNumber,LastGenerate,isSitemap) VALUES(::Page_::,'::Name::','::Title::','::Title::','::MetaDescription::','::MetaKeywords::','::MetaFrame::','::HiddenWords::','::Content::',::isSpecial::,::isStatic::,::isFull::,::isScript::,'::GroupName::',::PageNumber::,'00000000000000',::isSitemap::)";

         if ( $page > 0 ){
		 	$sql = "SELECT * FROM Page WHERE Page_= $page";
			$this->PageInfo = DBQuery::instance()->getRow($sql);
         }
      }

      function update() {
	  	$sql = "UPDATE Page SET Name='" . $this->get("Name") ."', Content='" . $this->get("Content") .
		       "', Title='" . $this->get("Title") ."', MetaTitle='" . $this->get("MetaTitle") .
			   "', MetaDescription='" . $this->get("MetaDescription") ."', MetaKeywords='" . $this->get("MetaKeywords") .
			   "', MetaFrame='" . $this->get("MetaFrame") ."', HiddenWords='" . $this->get("HiddenWords") .
			   "', isSpecial=" . $this->get("isSpecial") .", isStatic=" . $this->get("isStatic") .", isFull=" . $this->get("isFull") .
			   ", isScript=" . $this->get("isScript") .", GroupName='" . $this->get("GroupName") ."', PageNumber=" . $this->get("PageNumber") .
			   ", LastGenerate='" . $this->get("LastGenerate") ."', isSitemap=" . $this->get("isSitemap") ." WHERE Page_=" . $this->get("Page_");
		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  }
	  
	  
      function find($page){
	  	 $sql = "SELECT * FROM Page WHERE Name='$page'";
		 $this->PageInfo = DBQuery::instance()->getRow($sql);
		 return $this->PageInfo;
      }
		
	  function getNextID($name) {
	  	$sql = "SELECT ID FROM Sequence WHERE Name = '$name'";
		$rs = DBQuery::instance()->getOne($sql);
		$newID = $rs+1;
		$sql = "UPDATE Sequence SET ID = $newID WHERE Name = '$name'";
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
		return $newID;
	  }	

	  function get($name){
         return $this->PageInfo[$name];
      }
	  
	  function set($name,$value){
         $this->PageInfo[$name] = $value;
      }
	  
	  function setAll($array){
         $this->PageInfo = $array;
      }
	  
	  function setList($array){
         $this->PageList = $array;
      }
	  
      function insert(){
	  	 $new_id = $this->getNextID("Page");
		 $sql = "INSERT INTO Page (Page_,Name,Title,MetaTitle,MetaDescription,MetaKeywords," .
		        "MetaFrame,HiddenWords,Content,isSpecial,isStatic,isFull,isScript," .
				"GroupName,PageNumber,LastGenerate,isSitemap)" .
		        "VALUES('".$new_id."','".$this->PageInfo["Name"]."','".$this->PageInfo["Title"].
				"','".$this->PageInfo["MetaTitle"]."','".$this->PageInfo["MetaDescription"].
				"','".$this->PageInfo["MetaKeywords"]."','".$this->PageInfo["MetaFrame"].
				"','".$this->PageInfo["HiddenWords"]."','".$this->PageInfo["Content"].
				"','".$this->PageInfo["isSpecial"]."','".$this->PageInfo["isStatic"].
				"','".$this->PageInfo["isFull"]."','".$this->PageInfo["isScript"].
				"','".$this->PageInfo["GroupName"]."','".$this->PageInfo["PageNumber"].
				"','".$this->PageInfo["LastGenerate"]."','".$this->PageInfo["isSitemap"]."')";

		 DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }

      function delete(){
         @unlink(str_replace(" ","_",__INCLUDE_ROOT."pages/".$this->get("Name").".html"));
		 $sql = "DELETE FROM Page WHERE Page_= ".$this->PageInfo["Page_"];
         DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }

      function Cache($force =false){
         
         if ( $this->get("isSpecial") == 1 ) return;
         //if ( $this->get("isFull") == 1 ){
//            if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."pages/".$this->get("Name").".html"),"w")) ){
//               return ($this->get("Name")." - can't open file '".$this->get("Name").".html'  AAA<br>");
//            }
//            fputs($f,$this->get("Content"));
//            fclose($f);
//            return $this->get("Name")." is updated<br>";
//         }
         if ( $this->get("isScript") == 1 ){
            if ( !($f = @fopen(str_replace(" ","_",__INCLUDE_ROOT."pages/".$this->get("Name").".html"),"w")) ){
               return ($this->get("Name")." - can't open file '".$this->get("Name").".html'  <br>");
            }
            ob_start();
            system("(cd ".__INCLUDE_ROOT."tmp; ".__CURL_PROG." \"http://".constant("__SERVER_1_NAME")."/".$this->get("Name").".php\")");
            $content = ob_get_contents();
            ob_end_clean();
            $this->get("Name").".php";
            fputs($f,$content);
            fclose($f);
            return $this->get("Name")." is  updated<br>";
         }
      }
	  
	  function getMeta($field){
         $result = $this->get($field);
         if ( '' == trim($result) ){
            $oMeta = new Meta();
            $oMeta->find('ItemType',$this->ClassName);
            $result = $oMeta->get($field);
         }
         return $result;
      }

	  
      function getPeriod($dat){
         $file_time= (file_exists(__INCLUDE_ROOT."pages/".$this->get("Name").".html")) ? (filemtime(__INCLUDE_ROOT."pages/".$this->get("Name").".html")) : 0;
         $page_time = time();
         if (ereg ("([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})", $dat, $adat)){
            $page_time = mktime($adat[4],$adat[5],$adat[6],$adat[2],$adat[3],$adat[1]);
         }
         return ($page_time - $file_time);
      }
	  
	  function getPageList(){
	  	$sql = "SELECT * FROM Page";
		$rs = DBQuery::instance()->executeQuery($sql);
        $this->PageList = $rs;
		return $rs;
	  }
	  
	  function LastGenerate($dt){
	  	 $sql = "UPDATE Page SET LastDate=(LastDate), LastGenerate='".$dt."'";
         DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
      }

      function need_update(&$pages){
         // count of expires
		 $sql = "SELECT COUNT(*) cnt FROM Page WHERE LastDate>LastGenerate OR UpdateAlways=1 OR isScript=1";
		 $updatepage_cnt = DBQuery::instance()->getOne($sql);
         $pages = array();
         if ( $updatepage_cnt > 0 ){
		 	$sql = "SELECT * FROM Page WHERE LastDate>LastGenerate OR UpdateAlways=1 OR isScript=1";
			$rs = DBQuery::instance()->executeQuery($sql);
			for($i=0; $i<count($rs); $i++) {
				$pages[$rs[$i]["Page_"]] = $rs[$i]["Page_"];
			}
         }
      }
	  
	  function updateSpec($field_name) {
	  	$value = $this->get($field_name);
		$sql = "UPDATE Page SET $field_name = $value WHERE Page_ = " . $this->get("Page_");
		DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($sql);
	  }
	  
	  function setPage($merList,$page_num,$page_size =__PAGE_SIZE){
         if (count($merList) > 0 && $page_num > 0){
            reset($merList);
            $this->ListCurPage = $page_num;
            $this->ListPageSize = $page_size;
//            $num_item   = ($page_num * $this->ListPageSize - $this->ListPageSize + 1);
//            if ($num_item <= count($merList)){
//               for ($i = 1; $i < $num_item; $i++){
//                  @each($merList);
//               }
//            }
         }
         else{
            $this->ListPageSize = $page_size;
            $this->ListCurPage  = ($page_num == 0) ? $this->ListCurPage : $page_num;
         }
         $this->ListPagePos = 0;
      }
	  
	  function uniqstr($name){
         if ( strlen($this->get($name)) == 0 ) return true;
		 $sql = "SELECT COUNT(*) cnt FROM ".$this->ClassName." WHERE ".$name."='".$this->get($name)."' AND ".$this->Key."<> '".$this->get("Page_") ."'";
         $rs = DBQuery::instance()->getOne($sql);
         if ( $rs > 0 ){
            return false;
         }
         else{
            return true;
         }
      }
	  
	  function deleteHtml() {
	      $staticHtml = array ("picasa.html","firefox.html");
	  	  if(is_dir(__INCLUDE_ROOT."pages")) {
			    $d = @dir(__INCLUDE_ROOT."pages");
				while($entry=$d->read()) {
					 if ( $entry == "." || $entry == ".." ) continue;
					 if (in_array ($entry, $staticHtml)) {
					 	continue;
					 }
					 if(is_dir(__INCLUDE_ROOT."pages/".$entry)) {
					 	$subDir = @dir(__INCLUDE_ROOT."pages/".$entry);
						while($file=$subDir->read()) {
							if ( $file == "." || $file == ".." ) continue;
							@unlink(__INCLUDE_ROOT."pages/".$entry."/".$file);
						}
						$subDir->close();
					 } else {
					 	@unlink(__INCLUDE_ROOT."pages/".$entry);
					 }
	            }
				$d->close();
	  	  }
	  }
	  
	  function getIndexPage(){
	  		$sql = "SELECT * FROM IndexPage ORDER BY Type,Sort";
	  		$rs = DBQuery::instance()->executeQuery($sql);
	  		$newrs = array();
	  		foreach($rs as $key=>$value){
	  			switch ($value['Type']){
	  				case 1: 
	  					$newrs[1][]=$value;
	  					break;
	  				case 2: 
	  					$newrs[2][]=$value;
	  					break;
	  				case 3: 
	  					$newrs[3][]=$value;
	  					break;
	  				case 4: 
	  					$newrs[4][]=$value;
	  					break;
	  			
	  			}
	  		}
	  		return $newrs;
	  	
	  }
   }

}
?>
