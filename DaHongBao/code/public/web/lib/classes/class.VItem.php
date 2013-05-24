<?php
/**
 * class.VItem.php
 *-------------------------
 *
 * base class
 *
 * PHP versions 4
 *
 * LICENSE: This source file is from CouponMountain.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 4.0
 * @version    CVS: $Id: class.VItem.php,v 1.1 2013/04/15 10:57:54 rock Exp $
 * @link       http://www.couponmountain.com/
 * @deprecated File deprecated in Release 2.0.0
 */

if (!defined("VITEM_CLASS_PHP")){
   define("VITEM_CLASS_PHP","Y");

   if ( !defined("FAST_LIST") )    define("FAST_LIST","1");
   if ( !defined("NORMAL_LIST") )  define("NORMAL_LIST","0");
   if ( !defined("ASC") )  define("ASC","asc");
   if ( !defined("DESC") ) define("DESC","desc");

         require_once(__INCLUDE_ROOT."/lib/classes/class.Query.php");
         require_once(__INCLUDE_ROOT."/lib/functions/func.Date.php");

   class VItem extends Query{
      var $ClassName = "VItem";

      var $ID       = -1;       // key value
      var $Key      = "";       // key name value
      var $Name     = "";

      function VItem($host ="", $base ="", $user ="", $pass =""){
         Query::Query($host, $base, $user, $pass);
      }

      function load(){
         $this->next();
         if ( $this->fieldbyname($this->Key) != "" ) $this->ID = $this->fieldbyname($this->Key);
      }

      function get($name){
         return $this->fieldbyname($name);
      }

      function getDate($name){
         return from_mysql_date($this->get($name));
      }

      function getDateText($name){
         return from_mysql_date_ext($this->get($name));
      }

      function getMeta($field){
         $result = $this->get($field);
         if ( '' == trim($result) ){
            $oMeta = new Meta();
            $oMeta->find('ItemType',$this->ClassName);
            $result = $oMeta->get($field);
         }
         switch ($field){
            case 'MetaKeywords'   :$result='<META NAME="keywords" CONTENT="'.$result.'">';
                break;
            case 'MetaDescription':$result='<META NAME="description" CONTENT="'.$result.'">';
                break;
         }
         return str_replace('~|NAME|~',$this->get('Name'),$result);
      }

      function setdate($name,$value){
         if ( strtoupper($value) == "N/A" ){
            $value_new = '00/00/0000';
         }
         else if ( strtoupper($value) == "O/G" ){
            $value_new = '03/03/3333';
         }
         else{
            $value_new = $value;
         }
         return $this->set($name,to_mysql_date($value_new));
      }

      function set($name,$value =""){
         if ( is_array($name) ){
            $this->Record = $name;
            $this->ID = $this->Record[$this->Key];
         }
         else{
            $this->Record[$name] = $value;
         }
         return 1;
      }

      function checked($field, $value2 =1){
         if ( $this->get($field) == $value2 ){
            return "CHECKED";
         }
      }

      function selected($field, $value2){
         if ( $this->get($field) == $value2 ){
            return "SELECTED";
         }
      }

      function nextid($className =""){
         if ( $className != "" ) return Query::nextid($className);
         return Query::nextid($this->ClassName);
      }

      function delete(){
         Query::delete(array($this->Key => $this->ID));
      }

      function uniqstr($name){
         if ( strlen($this->get($name)) == 0 ) return true;
         $tmp = $this->run_spec("SELECT COUNT(*) cnt FROM ".$this->ClassName." WHERE ".$name."='".$this->get($name)."' AND ".$this->Key."<>".$this->ID);
         if ( $tmp["cnt"] > 0 ){
            return false;
         }
         else{
            return true;
         }
      }

      function getTimeStamp($name){
         if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $dt, $adat))
            return $adat[3]."-".$adat[1]."-".$adat[2];

      }
   }

/**
Class VItemList

Public methods:   constructor VItemList($class_name, $class_list, $list_type)
                  function setOrder($field)
                  function setDirection($direct)
**/

   class VItemList extends Query{
      var $ClassName = "VItemList";

      var $Class     = "";
      var $Order     = "";
      var $Direction = ASC;
      var $Filter    = array();

      var $PageSize  = __PAGE_SIZE;
      var $CurPage   = 1;
      var $PagePos   = 0;

      var $Items  = array();
      var $Keys   = array();

      function VItemList($class_name, $class_list ="", $list_type =NORMAL_LIST){
         Query::Query();
         $this->Class   = $class_name;
         if ( is_array($class_list) ){
            $this->array_load($class_list);
         }
         else if ( is_string($class_list) ){
            if ( $list_type == NORMAL_LIST ){
               $this->normal_load($class_list);
            }
            else{
               $this->fast_load($class_list);
            }
         }
      }

      function normal_load($sql =""){
         if ( class_exists($this->Class) ){
            if ( "" != $sql ){
               $this->SQL[QSELECT] = $sql;
               $this->select();
               eval("\$tmpClass = new ".$this->Class."();");

               while ($this->next()){
                  eval("\$this->Items[".$this->fieldbyname($tmpClass->Key)."] = new ".$this->Class."(".$this->fieldbyname($tmpClass->Key).");");
               }
               reset($this->Items);
            }
         }
      }

      function fast_load($sql =""){
         if ( class_exists($this->Class) ){
            if ( "" != $sql ){
               $this->SQL[QSELECT] = $sql;
               $this->select();
               while ( $this->next() ){
                  eval("\$tmp_obj = new ".$this->Class."();");
                  $tmp_obj->set($this->Record);
                  $this->Items[$tmp_obj->ID] = $tmp_obj;
               }
               reset($this->Items);
            }
         }
      }

      function array_load($list =array()){
         if (class_exists($this->Class)){
            while (list($key,$val) = @each($list)){
               eval("\$this->Items[".($val)."] = new ".$this->Class."(".($val).");");
            }
            reset($this->Items);
         }
      }

      function setOrder($field_name ="", $spec =""){
         $old_order  = $this->Order;
         if ( $field_name != "" ){
            $this->Order   = $field_name;
         }
         if ( sizeof($this->Items) && $this->Order != "" &&
              ( $this->Order != $old_order || $spec != "" ) ){

            $tarr = array();
            reset($this->Items);
            while (list($key,$sarr) = @each($this->Items)){
               $tarr[$key] = $sarr->Record[$this->Order];
            }
            if ( $this->Direction == ASC ){
               uasort($tarr,"cmp");
            }
            else{
               uasort($tarr,"rcmp");
            }
            $result = array();
            reset($tarr);
            while (list($key,$val) = @each($tarr)){
               $result[$key] = $this->Items[$key];
            }
            $this->Items    = $result;
            reset($this->Items);
         }
      }

      function setOrderN($field_name ="", $spec =""){
         $old_order  = $this->Order;
         if ( $field_name != "" ){
            $this->Order   = $field_name;
         }
         if ( sizeof($this->Items) && $this->Order != "" &&
              ( $this->Order != $old_order || $spec != "" ) ){

            $tarr = array();
            reset($this->Items);
            while (list($key,$sarr) = @each($this->Items)){
               $tarr[$key] = $sarr->Record[$this->Order];
            }
            if ( $this->Direction == ASC ){
               uasort($tarr,"cmpn");
            }
            else{
               uasort($tarr,"rcmpn");
            }
            $result = array();
            reset($tarr);
            while (list($key,$val) = @each($tarr)){
               $result[$key] = $this->Items[$key];
            }
            $this->Items    = $result;
            reset($this->Items);
         }
      }

      function setOrderA($field_name ="", $spec =""){
         $old_order  = $this->Order;
         if ( $field_name != "" ){
            $this->Order   = $field_name;
         }
         if ( sizeof($this->Items) && $this->Order != "" &&
              ( $this->Order != $old_order || $spec != "" ) ){

            $tarr = array();
            reset($this->Items);
            while (list($key,$sarr) = @each($this->Items)){
               $tarr[$key] = $sarr->Record[$this->Order];
            }
            if ( $this->Direction == ASC ){
               uasort($tarr,"cmpa");
            }
            else{
               uasort($tarr,"rcmpa");
            }
            $result = array();
            reset($tarr);
            while (list($key,$val) = @each($tarr)){
               $result[$key] = $this->Items[$key];
            }
            $this->Items    = $result;
            reset($this->Items);
         }
      }

      function setOrderD($field_name ="", $spec =""){
         $old_order  = $this->Order;
         if ( $field_name != "" ){
            $this->Order   = $field_name;
         }
         if ( sizeof($this->Items) && $this->Order != "" &&
              ( $this->Order != $old_order || $spec != "" ) ){

            $tarr = array();
            reset($this->Items);
            while (list($key,$sarr) = @each($this->Items)){
               $tarr[$key] = $sarr->Record[$this->Order];
            }
            if ( $this->Direction == ASC ){
               uasort($tarr,"cmpd");
            }
            else{
               uasort($tarr,"rcmpd");
            }
            $result = array();
            reset($tarr);
            while (list($key,$val) = @each($tarr)){
               $result[$key] = $this->Items[$key];
            }
            $this->Items    = $result;
            reset($this->Items);
         }
      }

      function setMultiOrder($fields_name ="", $spec =""){
/*
         if ( ! is_array($fields_name) ){
            if ( sizeof($this->Items) ){
               $tarr = array();
               reset($this->Items);
               while ( list($key, $dir) = @each($fields_name) ){
                  while (list($key,$sarr) = @each($this->Items)){
                     $tarr[$key] = $sarr->Record[$this->Order];
                  }
                  if ( $this->Direction == ASC ){
                     uasort($tarr,"cmp");
                  }
                  else{
                     uasort($tarr,"rcmp");
                  }
                  $result = array();
                  reset($tarr);
                  while (list($key,$val) = @each($tarr)){
                     $result[$key] = $this->Items[$key];
                  }
                  $this->Items    = $result;
               }
               reset($this->Items);
            }
         }
*/
      }

      function setDirection($direct){
         $old_direct = $this->Direction;
         if ( $direct == DESC ){
            $this->Direction  = DESC;
         }
         else{
            $this->Direction  = ASC;
         }
         if ( $old_direct != $this->Direction ){
            $this->setOrder($this->Order,"spec");
         }
      }

      function setFilter($filter =array()){
         $old_filter = $this->Filter;
         if ( $old_filter != $this->Filter && sizeof($this->Filter) ){
         }
      }

      function getItemCount(){
         return sizeof($this->Items);
      }

      function getItemByNum($num){
         if ( $this->getItemCount() < 0 || $this->getItemCount() < $num ) return null;
         reset($this->Items);
         $item = null;
         for ($i=0; $i<=$num;$i++){
            $item = current($this->Items);
            next($this->Items);
         }
         return $item;
      }

      function nextItem($items =1){
         if ($items == 0){
            reset($this->Items);
            return null;
         }
         else{
            $tmp = current($this->Items);
            next($this->Items);
            return $tmp;
         }
      }

      function prevItem(){
         prev($this->Items);
         $tmp = current($this->Items);
         return $tmp;
      }

      function nextItemP(){
         $this->PagePos++;
         if ($this->PagePos <= $this->PageSize){
            $tmp = current($this->Items);
            next($this->Items);
            return $tmp;
         }
         else{
            return null;
         }
      }

      function getPageList(){
         $result  = "";
         $page_cnt= (floor($this->getItemCount()/$this->getPageSize())+(($this->getItemCount()%$this->getPageSize())>0));
         for ($i=1; $i<=$page_cnt;$i++){
            $result .= "<option value=\"$i\"".($i==$this->getPage() ? " selected" : "").">$i";
         }
         return $result;
      }

      function getPageSize(){
         return $this->PageSize;
      }

      function getPage(){
         return $this->CurPage;
      }

      function setPage($page_num,$page_size =__PAGE_SIZE){
         if (sizeof($this->Items) > 0 && $page_num > 0){
            reset($this->Items);
            $this->CurPage = $page_num;
            $this->PageSize = $page_size;
            $num_item   = ($page_num * $this->PageSize - $this->PageSize + 1);
            if ($num_item <= sizeof($this->Items)){
               for ($i = 1; $i < $num_item; $i++){
                  @each($this->Items);
               }
            }
         }
         else{
            $this->PageSize = $page_size;
            $this->CurPage  = ($page_num == 0) ? $this->CurPage : $page_num;
         }
         $this->PagePos = 0;
      }


      function getSelect($key_field,$name_field,$select_field =0,$key_list =array(), $maxlen =0){
         $result = "";
         if (sizeof($key_list) == 0){
            if (sizeof($this->Items)){
               reset($this->Items);
               if (!is_array($select_field)){
                  while (list($key,$oItem) = @each($this->Items)){
                     $result .= "<OPTION VALUE=\"".$oItem->Record[$key_field]."\"".($oItem->Record[$key_field]==$select_field ? " SELECTED" : "").($oItem->Record["isGray"]==1?" class=\"nullcoupon\"":"").">".($maxlen > 0 ? substr($oItem->Record[$name_field],0,$maxlen-1).(strlen($oItem->Record[$name_field]) > $maxlen ? "..." : "") : $oItem->Record[$name_field])."</OPTION>";
                  }
               }
               else{
               }
               reset($this->Items);
            }
         }
         else{
            if ( $this->getItemCount() > 0 ){
               reset($this->Items);
               if ( !is_array($select_field) ){
                  while ( list($key,$oItem) = @each($this->Items) ){
                     if ( in_array($oItem->Record[$key_field],$key_list) ){
                        $result .= "<OPTION VALUE=\"".$oItem->Record[$key_field]."\"".($oItem->Record[$key_field]==$select_field ? " SELECTED" : "").($oItem->Record["isGray"]==1?" class=\"nullcoupon\"":"").">".($maxlen > 0 ? substr($oItem->Record[$name_field],0,$maxlen-1).(strlen($oItem->Record[$name_field]) > $maxlen ? "..." : "") : $oItem->Record[$name_field])."</OPTION>";
                     }
                  }
               }
               reset($this->Items);
            }
         }
		 $result = iconv('UTF-8','GB2312',$result);
         return $result;
      }

      function getJavaArray(){
         reset($this->Items);
         $result = "";
         while ( list($key,$obj) = @each($this->Items) ){
            $result .= $key.",";
         }
         reset($this->Items);
         return substr($result,0,strlen($result)-1);
      }

      function getArray(){
         reset($this->Items);
         $result = array();
         while ( list($key,$obj) = @each($this->Items) ){
            array_push($result,$key);
         }
         reset($this->Items);
         return $result;
      }

      function getString($key_field,$name_field,$key_list =array(), $splitter =';'){
         $result = '';
         if (sizeof($key_list) == 0){
            if (sizeof($this->Items)){
               reset($this->Items);
               if (!is_array($select_field)){
                  while (list($key,$oItem) = @each($this->Items)){
                     $result .= $oItem->Record[$name_field].$splitter;
                  }
               }
               else{
               }
               reset($this->Items);
            }
         }
         else{
            if ( $this->getItemCount() > 0 ){
               reset($this->Items);
               if ( !is_array($select_field) ){
                  while ( list($key,$oItem) = @each($this->Items) ){
                     if ( in_array($oItem->Record[$key_field],$key_list) ){
                        $result .= $oItem->Record[$name_field].$splitter;
                     }
                  }
               }
               reset($this->Items);
            }
         }
         return $result;
      }

/*
      var $list_fields   = array();

      var $Class     = "";
      var $Sql       = "";
      var $LoadType  = "";

      var $Table  = "";
      var $Fields = "";
      var $Key    = "";
      var $Order  = "";
      var $Where  = "";

      var $PageSize  = 1000;
      var $CurPage   = 1;

      var $Items  = array();
      var $getItemsCounter = 0;


      function VItemList(){
         if (func_num_args() <= 2){
            $args = func_get_args();
            $this->VItemList_arr($args[0],$args[1]);
         }
         else if (func_num_args() < 4){
            $args = func_get_args();
            $this->VItemList_new($args[0],$args[1],$args[2]);
         }
         else{
            $args = func_get_args();
            $this->VItemList_old($args[0],$args[1],$args[2],$args[3],$args[4],$args[5]);
         }
      }

      function VItemList_new($class_name ="", $query ="", $fast =NORMAL_LIST){
         $this->Class   = $class_name;
         $this->Sql     = $query;
         $this->LoadType= $fast;
         if ($this->Class != "" && $this->Sql != ""){
            if ($this->LoadType == NORMAL_LIST) $this->load_new();
            else $this->fast_load_new();
         }
      }

      function VItemList_arr($class_name ="", $list =""){
         $this->Class   = $class_name;
         $this->Sql     = "";
         $this->LoadType= NORMAL_LIST;
         if ($this->LoadType == NORMAL_LIST) $this->load_arr($list);
      }

      function VItemList_old($class_name ="",$table ="",$key_field ="",$order ="",$where ="", $fast =NORMAL_LIST){
            $this->Class   = $class_name;
            $this->Table   = $table;
            $this->Key     = $key_field;
            $this->Where   = $where;
            $this->Order   = $order;
            if ($fast == NORMAL_LIST) $this->load();
            else $this->fast_load();
      }

      function getPageList(){
         $result  = "";
         $page_cnt= (floor($this->getItemCount()/$this->getPageSize())+(($this->getItemCount()%$this->getPageSize())>0));
         for ($i=1; $i<=$page_cnt;$i++){
            $result .= "<option value=\"$i\"".($i==$this->getPage() ? " selected" : "").">$i";
         }
         return $result;
      }

      function getPageSize(){
         return $this->PageSize;
      }

      function getPage(){
         return $this->CurPage;
      }

      function setPage($page_num,$page_size = 0){
         if (sizeof($this->Items) && $page_num > 0){
            reset($this->Items);
            $this->CurPage = $page_num;
            $this->PageSize = ($page_size == 0) ? $this->PageSize : $page_size;
            $num_item   = ($page_num * $this->PageSize - $this->PageSize + 1);
            if ($num_item <= sizeof($this->Items)){
               for ($i = 1; $i < $num_item; $i++){
                  @each($this->Items);
               }
            }
         }
         else{
            $this->PageSize = ($page_size == 0) ? $this->PageSize : $page_size;
            $this->CurPage  = ($page_num == 0) ? $this->CurPage : $page_num;
         }
         $this->getNextItem(0);
      }


      function getNextItem($items =1){
         if ($items == 0){
            $this->getItemsCounter = $items;
            return null;
         }
         else{
            $this->getItemsCounter++;
            if ($this->getItemsCounter <= $this->PageSize){
               $tmp = current($this->Items);
               next($this->Items);
               return $tmp;
            }
            else{
               return null;
            }
         }
      }

      function load_new(){
         if (class_exists($this->Class)){
            if ("" != $this->Sql){
               $this->SQL[QSELECT] = $this->Sql;
               $this->select();
               while ($this->next_record()){
                  eval("\$this->Items[".$this->field($this->Key)."] = new ".$this->Class."(".$this->field($this->Key).");");
               }
            }
         }
      }

      function load_arr($list =""){
         if (class_exists($this->Class)){
            if ($this->Fields != ""){
               $this->fast_load();
               return;
            }
            $item_list = split(";",$list);
            while (list($key,$val) = @each($item_list)){
               if (empty($val)) continue;
               eval("\$this->Items[".($val)."] = new ".$this->Class."(".($val).");");
            }
         }
      }

      function load(){
         if (class_exists($this->Class)){
            if ($this->Fields != ""){
               $this->fast_load();
               return;
            }
            $this->Sql     = "SELECT ".$this->Key." ".
                             "FROM ".$this->Table." ".
                             ("" != $this->Where ? "WHERE ".$this->Where." " : " " ).
                             ("" != $this->Order ? "ORDER BY ".$this->Order : " ");
//            if ($this->PageSize > 0){
//               $this->Sql .= " LIMIT ".($this->PageSize * $this->CurPage - $this->PageSize + 1).",".($this->PageSize);
//            }
            if ("" != $this->Sql){
               $this->SQL[QSELECT] = $this->Sql;
               $this->select();
               while ($this->next_record()){
                  eval("\$this->Items[".$this->field($this->Key)."] = new ".$this->Class."(".$this->field($this->Key).");");
               }
            }
         }
      }

      function fast_load_new(){
         if (class_exists($this->Class)){
            eval("\$tmp_obj = new ".$this->Class."();");
            if ($tmp_obj->FastLoad){
//               if ($this->PageSize > 0){
//                  $this->Sql .= " LIMIT ".($this->PageSize * $this->CurPage - $this->PageSize + 1).",".($this->PageSize);
//               }
               if ("" != $this->Sql){
                  //$this->SQL[QSELECT] = $this->Sql;
                  //$this->select();
                  $this->query($this->Sql);
                  while ($this->next_record()){
                     eval("\$tmp_obj = new ".$this->Class."();");
                     $tmp_obj->set($this->Record);
                     $this->Items[$tmp_obj->ID] = $tmp_obj;
                  }
               }
            }
            else{
               $this->load_new();
            }
         }
      }

      function fast_load(){
         if (class_exists($this->Class)){
            eval("\$tmp_obj = new ".$this->Class."();");
            if ($tmp_obj->FastLoad){
               $this->Sql     = "SELECT ".($this->Fields == "" ? "*" : $this->Fields)." ".
                                "FROM ".$this->Table." ".
                                ("" != $this->Where ? "WHERE ".$this->Where." " : " " ).
                                ("" != $this->Order ? "ORDER BY ".$this->Order : " ");
//               if ($this->PageSize > 0){
//                  $this->Sql .= " LIMIT ".($this->PageSize * $this->CurPage - $this->PageSize + 1).",".($this->PageSize);
//               }
               if ("" != $this->Sql){
                  $this->SQL[QSELECT] = $this->Sql;
                  $this->select();
                  while ($this->next_record()){
                     eval("\$tmp_obj = new ".$this->Class."();");
                     $tmp_obj->set($this->Record);
                     $this->Items[$tmp_obj->ID] = $tmp_obj;
                  }
               }
            }
            else{
               $this->load();
            }
         }
      }

      function get_select($key_field,$name_field,$select_field =0,$key_list =array()){
         $result = "";
         if (sizeof($key_list) == 0){
            if (sizeof($this->Items)){
               reset($this->Items);
               if (!is_array($select_field)){
                  while (list($key,$oItem) = @each($this->Items)){
                     $result .= "<OPTION VALUE=".$oItem->Record[$key_field].($oItem->Record[$key_field]==$select_field ? " SELECTED" : "").">".$oItem->Record[$name_field];
                  }
               }
               else{
               }
               reset($this->Items);
            }
         }
         else{
            if (sizeof($this->Items) > 0){
               reset($this->Items);
               if (!is_array($select_field)){
                  while (list($key,$oItem) = @each($this->Items)){
                     if (in_array($oItem->Record[$key_field],$key_list)){
                        $result .= "<OPTION VALUE=".$oItem->Record[$key_field].($oItem->Record[$key_field]==$select_field ? " SELECTED" : "").">".$oItem->Record[$name_field];
                     }
                  }
               }
               else{
               }
               reset($this->Items);
            }
         }
         return $result;
      }


      function setOrder($fname){
         if (sizeof($this->Items) && $fname != ""){

            $this->Order = $fname;

            $tarr = array();
            reset($this->Items);
            while (list($key,$sarr) = @each($this->Items)){
               $tarr[$key] = $sarr->Record[$this->Order];
            }
            uasort($tarr,"cmp");

            $result = array();
            reset($tarr);
            while (list($key,$val) = @each($tarr)){
               $result[$key] = $this->Items[$key];
            }
            $this->Items    = $result;
            reset($this->Items);
         }
      }

      function getOrder(){
         return $this->Order;
      }

      function countItems($fname,$fvalue){
        $result = 0;
        if (sizeof($this->Items)){
            reset($this->Items);
            while (list($key,$oItem) = each($this->Items)){
                if ($oItem->Record[$fname] == $fvalue){
                    $result++;
                }
            }
            reset($this->Items);
        }
        return $result;
      }


    // Build header of table list (begin)
      function get_head($num =1){
         if ($num == 0){
            reset($this->list_fields);
            return NULL;
         }
         else{
            $tmp = current($this->list_fields);
            next($this->list_fields);
            return $tmp;
         }
      }

      function head_size(){
         return sizeof($this->list_fields);
      }

    // Build header of table list (end)
*/
   }

   function rcmp($a,$b){
      if (intval($a) && intval($b)){
         if ($a == $b) return 0;
         return ($a > $b) ? -1 : 1;
      }
      else{
         $min_len = strlen($a) > strlen($b) ? strlen($b) : strlen($a);
         return strncmp(strtoupper($b),strtoupper($a),$min_len);
      }
   }

   function cmp($a,$b){
      if (intval($a) && intval($b)){
         if ($a == $b) return 0;
         return ($b > $a) ? -1 : 1;
      }
      else{
         $min_len = strlen($a) > strlen($b) ? strlen($b) : strlen($a);
         if ( $min_len == 0) return strlen($b) > strlen($a) ? -1 : 1;
         return strncmp(strtoupper($a),strtoupper($b),$min_len);
      }
   }

   function rcmpn($a,$b){
      preg_match("/[^0-9\.]*([0-9\.]*)[^0-9\.]*/",$a,$an);
      preg_match("/[^0-9\.]*([0-9\.]*)[^0-9\.]*/",$b,$bn);
      if ((float)$an[1] == (float)$bn[1]) return 0;
      return ((float)$an[1] > (float)$bn[1]) ? -1 : 1;
   }

   function cmpn($a,$b){
      preg_match("/[^0-9\.]*([0-9\.]*)[^0-9\.]*/",$a,$an);
      preg_match("/[^0-9\.]*([0-9\.]*)[^0-9\.]*/",$b,$bn);
      if ((float)$an[1] == (float)$bn[1]) return 0;
      return ((float)$bn[1] > (float)$an[1]) ? -1 : 1;
   }

   function rcmpa($a,$b){
      preg_match("/[^0-9\.]*([0-9\.]*)[^0-9\.]*/",$a,$an);
      preg_match("/[^0-9\.]*([0-9\.]*)[^0-9\.]*/",$b,$bn);
      if ( strncmp($a,"$",1) == 0 ) $an[1] = $an[1] * 1000.0;
      if ( strncmp($b,"$",1) == 0 ) $bn[1] = $bn[1] * 1000.0;
      if ((float)$an[1] == (float)$bn[1]) return 0;
      return ((float)$an[1] > (float)$bn[1]) ? -1 : 1;
   }

   function cmpa($a,$b){
      preg_match("/[^0-9\.]*([0-9\.]*)[^0-9\.]*/",$a,$an);
      preg_match("/[^0-9\.]*([0-9\.]*)[^0-9\.]*/",$b,$bn);
      if ( strncmp($a,"$",1) == 0 ) $an[1] = $an[1] * 1000.0;
      if ( strncmp($b,"$",1) == 0 ) $bn[1] = $bn[1] * 1000.0;
      if ((float)$an[1] == (float)$bn[1]) return 0;
      return ((float)$bn[1] > (float)$an[1]) ? -1 : 1;
   }

   function rcmpd($a,$b){
      if (mysql_to_timestamp($a) == mysql_to_timestamp($b)) return 0;
      return (mysql_to_timestamp($a) > mysql_to_timestamp($b)) ? 1 : -1;
   }

   function cmpd($a,$b){
      if ((float)mysql_to_timestamp($a) == (float)mysql_to_timestamp($b)) return 0;
      return ((float)mysql_to_timestamp($b) > (float)mysql_to_timestamp($a)) ? 1 : -1;
   }
}
?>