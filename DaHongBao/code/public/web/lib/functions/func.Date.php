<?php

if (!defined("FUNC_DATE_PHP")){
   define("FUNC_DATE_PHP","Y");

   function from_mysql_date($mysql_dt,$format ="%m/%d/%Y"){
      $result = $format;
      if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $mysql_dt, $adat))
         if (intval($adat[1])==0 && intval($adat[2])==0 && intval($adat[3])==0){
            return "未定";
         }
         else if (intval($adat[1])==3333 && intval($adat[2])==3 && intval($adat[3])==3){
            return "进行中";
         }
         else{
            return (ereg_replace("%Y",$adat[1],ereg_replace("%d",$adat[3],ereg_replace("%m",$adat[2],$result))));
         }
      else
         return "未定";
   }

   function from_mysql_date_ext($mysql_dt,$format ="%M %d, %Y"){
      $result = $format;
      if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $mysql_dt, $adat))
         if (intval($adat[1])==0 && intval($adat[2])==0 && intval($adat[3])==0){
            return "未定";
         }
         else if (intval($adat[1])==3333 && intval($adat[2])==3 && intval($adat[3])==3){
            return "进行中";
         }
         else{
            return date("Y年m月d日",mktime(0,0,0,$adat[2],$adat[3],$adat[1]));
            //return date("M j, Y",mktime(0,0,0,$adat[2],$adat[3],$adat[1]));
            //return (ereg_replace("%Y",$adat[1],ereg_replace("%d",$adat[3],ereg_replace("%m",$adat[2],$result))));
         }
      else
         return "未定";
   }

   function to_mysql_date($dt){
      $result = $format;
      if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $dt, $adat))
            return $adat[3]."-".$adat[1]."-".$adat[2];
      else
         return "0000-00-00";
   }

   function mdt2d($datetime){
      $space_pos = strpos($datetime," ");
      if ($space_pos > 0){
         $mysql_date = substr($datetime,0,$space_pos);
      }
      else{
         $mysql_date = $datetime;
      }

      return from_mysql_date($mysql_date);
   }

   function md2str($datetime){
      $result = $datetime;
      $space_pos = strpos($datetime," ");
      if ($space_pos > 0){
         $mysql_date = substr($datetime,0,$space_pos);
         $mysql_time = substr($datetime,$space_pos+1);
      }
      else{
         $mysql_date = $datetime;
         $mysql_time = "00:00:00";
      }
      if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $datetime, $adat)){
         $tmp_date = mktime(0,0,0,$adat[2],$adat[3],$adat[1]);
         $result = date("D M j %Q Y",$tmp_date);
         $result = str_replace("%Q",$mysql_time,$result);
      }
      return $result;
   }

   function mysql_to_timestamp($mysql_dt){
      $result = $format;
      if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $mysql_dt, $adat))
         if (intval($adat[1])==0 && intval($adat[2])==0 && intval($adat[3])==0){
            return "11110101010101";
         }
         else if (intval($adat[1])==3333 && intval($adat[2])==3 && intval($adat[3])==3){
            return "11110101010102";
         }
         else{
            return $adat[1].$adat[2].$adat[3]."010101";
         }
      else
         return "11110101010101";
   }
   
   function getUserDate($value){
	    return from_mysql_date($value);
   }

    function getUserDateText($value){
	    return from_mysql_date_ext($value);
    }
	
	function setUserdate($name,$value){
         if ( strtoupper($value) == "N/A" ){
            $value_new = '00/00/0000';
         }
         else if ( strtoupper($value) == "O/G" ){
            $value_new = '03/03/3333';
         }
         else{
            $value_new = $value;
         }
         return to_mysql_date($value_new);
   }
}
?>