<?php
/*
 * File     : class.QueryMySQL.php
 * Author   : Valeriy Zavolodko (vals@ukr.net)

 * Interface:
      Constructor       : Query($Host,$Base,$User,$Pass)
      Destructor        : 
      Public Properties : 
      Public Methods    : query($sql_str)
                          free()
                          rec2param()
                          select($params)
                          insert($params) 
                          update($params) 
                          delete($params) 
                          get_record()     
                          next()
                          prev()
                          seek()
                          fieldbyname($Name)
                          fieldbynum($Num)
                          nextid($Table) 

      Private Properties:
      Private Methods   : connect()
                          run_query($q_type,$params) 
                          parse($type_sql)
                          lock($table, $mode)
                          unlock()
*/

if (!defined("CLASS_QUERY_MYSQL_PHP")){
	define("CLASS_QUERY_MYSQL_PHP","Y");

	require_once(__INCLUDE_ROOT."/lib/functions/func.Debug.php");
	require_once(__INCLUDE_ROOT."/lib/classes/class.DBQuery.php");

	if (!defined("QSELECT")) define("QSELECT","select");
	if (!defined("QUPDATE")) define("QUPDATE","update");
	if (!defined("QINSERT")) define("QINSERT","insert");
	if (!defined("QDELETE")) define("QDELETE","delete");

	if (!defined("YES")) define("YES","1");
	if (!defined("NO"))  define("YES","0");

	class Query {//{{{

		/* public:  */
		var $ClassName     = "Query";

		/* public: connection parameters */
		var $Host     = "";
		var $Database = "";
		var $User     = "";
		var $Password = "";

		/* public: configuration parameters */
		var $Auto_Free     = NO;            ## Set to 1 for automatic mysql_free_result()
		var $DebugLevel    = __DEBUG_LEVEL; ## 0 - off debug, 1 - error message, 2 - all message
		var $Halt_On_Error = "yes";         ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
		var $Seq_Table     = "Sequence";

		/* public: queries */
		var $SQL    = array(QSELECT => "",  ## predefined query for select
		QUPDATE => "",  ## predefined query for update
		QINSERT => "",  ## predefined query for insert
		QDELETE => "",  ## predefined query for delete
		"query" => ""); ## temporary query
		var $Params = array();              ## query params

		/* public: result array and current row number */
		var $Record    = array();
		var $Row       = 0;
		var $NumRows   = 0;
		var $NumFields = 0;
		var $RowAffect = 0;

		/* public: current error number and error text */
		var $Errno    = 0;
		var $Error    = "";

		/* private: link and query handles */
		var $Link_ID  = 0;
		var $Query_ID = 0;

		/************* Methods *************/
		/* public: constructor */
		function Query($host ="", $base ="", $user ="", $pass =""){
			$this->Host       = ( "" != $host ? $host : __DB_HOST );
			$this->Database   = ( "" != $base ? $base : __DB_BASE );
			$this->User       = ( "" != $user ? $user : __DB_USER );
			$this->Password   = ( "" != $pass ? $pass : __DB_PASS );
			$this->connect();
		}

		/* public: perform a query */
		function run($Query_String =""){
			//echo "(run)".$Query_String."<br>";
			/* No empty queries, please, since PHP4 chokes on them. */
			if ( "" == $Query_String ){
				/* The empty query string is passed on from the constructor,
				* when calling the class without a query, e.g. in situations
				* like these: '$db = new DB_Sql_Subclass;'
				*/
				debug($this,1,"Empty query");
				return 0;
			}else{
				///$Query_String = iconv('GB2312','UTF-8',$Query_String);	// this is a bug, peterzh
			}
			if ( !$this->connect() ){
				/* we already complained in connect() about that. */
				debug($this,1,"Not connected");
				return 0;
			};
			debug($this,2,"SQL: $Query_String");

			# New query, discard previous result.
			if ( !strncmp("SELECT",$Query_String,6) ){
				if ( $this->Query_ID ){
					$this->free();
				}
				//            do{
				$this->Query_ID   = @mysql_query($Query_String,$this->Link_ID);
				$this->Errno      = @mysql_errno();
				$this->Error      = @mysql_error();
				if ( !$this->Query_ID && $this->Errno != 0 ){
					halt($this,"Invalid SQL: ".$Query_String."(".$this->Error."-".$this->Errno."-".$this->Host."-".$this->ClassName.")");
				}
				//               if ( $this->Errno == 1100 ) {
				//                  $this->unlock();
				//                  continue;
				//               }
				$this->Row        = 0;
				$this->NumRows    = @mysql_num_rows($this->Query_ID);
				$this->NumFields  = @mysql_num_fields($this->Query_ID);
				//            }
				//            while (mysql_errno($this->Link_ID) != 0);
			}
			else{
				@mysql_query($Query_String,$this->Link_ID);
			}

			# Will return nada if it fails. That's fine.
			return $this->Query_ID;
		}

		/* public: perform a query */
		function run_spec($Query_String =""){
			//echo $Query_String."<br>";
			/* No empty queries, please, since PHP4 chokes on them. */
			if ( "" == $Query_String ){
				/* The empty query string is passed on from the constructor,
				* when calling the class without a query, e.g. in situations
				* like these: '$db = new DB_Sql_Subclass;'
				*/
				debug($this,1,"Empty query");
				return 0;
			}
			if ( !$this->connect() ){
				/* we already complained in connect() about that. */
				debug($this,1,"Not connected");
				return 0;
			};
			debug($this,2,"SQL: $Query_String");

			$tmp = @mysql_query($Query_String,$this->Link_ID);
			$this->Errno      = @mysql_errno();
			$this->Error      = @mysql_error();
			$this->RowAffect  = @mysql_affected_rows();
			$rtmp = @mysql_fetch_array($tmp);
			@mysql_free_result($tmp);
			return $rtmp;
		}

		/* public: discard the query result */
		function free(){
			@mysql_free_result($this->Query_ID);
			$this->Query_ID   = 0;
			$this->Row        = 0;
			$this->NumRows    = 0;
			$this->NumFields  = 0;
			$this->Record     = array();
		}

		/* public: copy Record data to Params for query */
		function rec2param(){
			reset($this->Record);
			while ( list($pname,$pvalue) = @each($this->Record) ){
				$this->Params[$pname]   = $pvalue;
			}
			reset($this->Params);
		}

		function select($params =array()){ return $this->run_query(QSELECT,$params); }
		function update($params =array()){ return $this->run_query(QUPDATE,$params); }
		function insert($params =array()){ return $this->run_query(QINSERT,$params); }
		function delete($params =array()){ return $this->run_query(QDELETE,$params); }

		function get_record(){
			if ( $this->Row == 0 && $this->NumRows > 0 ){
				$this->next();
			}
			if ( is_array($this->Record) ){
				return $this->Record;
			}
			else{
				return false;
			}
		}

		function next() {
			if ( !$this->Query_ID ) {
				halt($this,"next_record called with no query pending.");
				return 0;
			}

			$this->Record = @mysql_fetch_array( $this->Query_ID );
			$this->Row   += 1;
			$this->Errno  = mysql_errno();
			$this->Error  = mysql_error();

			$stat = is_array($this->Record);
			if ( !$stat && $this->Auto_Free ){
				$this->free();
			}
			return $stat;
		}

		function prev(){
			if ( !$this->Query_ID ) {
				halt($this,"next_record called with no query pending.");
				return 0;
			}

			if ( $this->seek($this->Row - 1) ){
				$this->Record = @mysql_fetch_array( $this->Query_ID );
				$this->Row   += 1;
				$this->Errno  = mysql_errno();
				$this->Error  = mysql_error();

				$stat = is_array($this->Record);
				if ( !$stat && $this->Auto_Free ){
					$this->free();
				}
				return $stat;
			}
		}

		/* public: position in result set */
		function seek($pos = 0){
			$status = @mysql_data_seek($this->Query_ID, $pos);
			if ( $status ){
				$this->Row = $pos;
			}
			else{
				halt($this,"seek($pos) failed: result has ".$this->num_rows()." rows");

				/* half assed attempt to save the day,
				* but do not consider this documented or even
				* desireable behaviour.
				*/
				@mysql_data_seek($this->Query_ID, $this->num_rows());
				$this->Row = $this->num_rows;
				return 0;
			}
			return 1;
		}

		function fieldbyname($Name){
			return iconv('UTF-8','GB2312',$this->Record[$Name]);
		}

		function fieldbynum($Num){
			if ( is_array($this->Record) && $Num <= $this->NumRows){
				reset($this->Record);
				$field = next($this->Record);
				for ($i = 0; $i < $Num; $i++){
					$field = next($this->Record);
				}
				return $field;
			}
			else{
				return "";
			}
		}

		/* public: sequence numbers */
		function nextid($seq_name) {
			$this->connect();
			if ( $this->lock($this->Seq_Table) ) {
				/* get sequence number (locked) and increment */
				$q  = sprintf("SELECT ID FROM %s WHERE Name = '%s'",
				$this->Seq_Table,
				$seq_name);
				$id  = @mysql_query($q, $this->Link_ID);
				$res = @mysql_fetch_array($id);

				/* No current value, make one */
				if ( !is_array($res) ) {
					$currentid = 0;
					$q = sprintf("INSERT INTO %s VALUES('%s', %s)",
					$this->Seq_Table,
					$seq_name,
					$currentid);
					$id = @mysql_query($q, $this->Link_ID);
				}
				else{
					$currentid = $res["ID"];
				}
				$nextid = $currentid + 1;
				$q = sprintf("UPDATE %s SET ID = '%s' WHERE Name = '%s'",
				$this->Seq_Table,
				$nextid,
				$seq_name);
				$id = @mysql_query($q, $this->Link_ID);
				$this->unlock();
			}
			else{
				halt($this,"cannot lock ".$this->Seq_Table." - has it been created?");
				return 0;
			}
			return $nextid;
		}



		/* private: connection manager */
		function connect() {
			/* establish connection, select database */
			if ( 0 == $this->Link_ID ){
				$this->Link_ID = @mysql_pconnect($this->Host, $this->User, $this->Password);
				if ( !$this->Link_ID ){
					halt($this,"pconnect($this->Host, $this->User) failed.",@mysql_error(),@mysql_errno());
					return 0;
				}
				if ( !@mysql_select_db($this->Database,$this->Link_ID) ){
					halt($this,"cannot use database $Database".mysql_error());
					return 0;
				}
			}
			return $this->Link_ID;
		}

		function run_query($q_type,$params){
			if ( sizeof($params) ){
				while ( list($pname,$pvalue) = @each($params) ){
					$this->Params[$pname]   = $pvalue;
				}
			}
			reset($this->Params);

			if ( $this->parse($q_type) ){
				
				return $this->run($this->SQL["query"]);
			}
			else{
				halt($this,"Wrong query parameters: ".$this->SQL["query"]."(".$this->ClassName.")");
				return 0;
			}
		}

		/* peterzh last edit at 20060302 */
		function parse($type_sql =QSELECT){
			$tmp_sql = $this->SQL[$type_sql];
			if ( "" != $tmp_sql ){
				while ( list($pname,$pvalue) = @each($this->Params) ){
					if ( strpos($tmp_sql, "'::".$pname."::'") ){
						while ( $pvalue != stripslashes($pvalue) ) $pvalue = stripslashes($pvalue);
						$pvalue = $this->getUtf8Code($pvalue);   //add by peterzh
						$pvalue = DBQuery::filter($pvalue);
					}
					$tmp_sql = str_replace("::$pname::","$pvalue","$tmp_sql");
				}

				$this->SQL["query"]  = $tmp_sql;
				if ( ereg(".*::.*::.*",$this->SQL["query"]) ){
					return 0;
				}
				else{
					return 1;
				}
			}
			return 0;
		}

		/* public: table locking */
		function lock($table, $mode="write"){
			$this->connect();
			$query="lock tables ";
			if ( is_array($table) ){
				while (list($key,$value)=each($table)) {
					if ($key=="read" && $key!=0) {
						$query.="$value read, ";
					}
					else{
						$query.="$value $mode, ";
					}
				}
				$query=substr($query,0,-2);
			}
			else{
				$query.="$table $mode";
			}
			$res = @mysql_query($query, $this->Link_ID);
			if ( !$res ){
				$this->halt("lock($table, $mode) failed.");
				return 0;
			}
			return $res;
		}

		function unlock(){
			$this->connect();
			$res = @mysql_query("unlock tables");
			if ( !$res ){
				$this->halt("unlock() failed.");
				return 0;
			}
			return $res;
		}
		
		/* peterzh 20060302 */
		function getUtf8Code($value){
			$value_1   =   $value;
			$value_2   =   @iconv("UTF-8" , "GB2312", $value_1);
			$value_3   =   @iconv("GB2312", "UTF-8",  $value_2);

			if (strlen($value_1) == strlen($value_3)){
				return   $value_1;
			}else{
				return   iconv("GB2312", "UTF-8", $value_1);
			}
		}
	}///}}}
}


/*
  function halt($msg) {
    $this->Error = @mysql_error($this->Link_ID);
    $this->Errno = @mysql_errno($this->Link_ID);
    if ($this->Halt_On_Error == "no")
      return;

    $this->haltmsg($msg);

    if ($this->Halt_On_Error != "report")
      die("Session halted.");
  }

  function haltmsg($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MySQL Error</b>: %s (%s)<br>\n",
      $this->Errno,
      $this->Error);
  }






  function metadata($table='',$full=false) {
    $count = 0;
    $id    = 0;
    $res   = array();

    /*
     * Due to compatibility problems with Table we changed the behavior
     * of metadata();
     * depending on $full, metadata returns the following values:
     *
     * - full is false (default):
     * $result[]:
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *
     * - full is true
     * $result[]:
     *   ["num_fields"] number of metadata records
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *   ["meta"][field name]  index of field named "field name"
     *   The last one is used, if you have a field name, but no index.
     *   Test:  if (isset($result['meta']['myfield'])) { ...
     */

    // if no $table specified, assume that we are working with a query
    // result
/*
    if ($table) {
      $this->connect();
      $id = @mysql_list_fields($this->Database, $table);
      if (!$id)
        $this->halt("Metadata query failed.");
    } else {
      $id = $this->Query_ID;
      if (!$id)
        $this->halt("No query specified.");
    }

    $count = @mysql_num_fields($id);

    // made this IF due to performance (one if is faster than $count if's)
    if (!$full) {
      for ($i=0; $i<$count; $i++) {
        $res[$i]["table"] = @mysql_field_table ($id, $i);
        $res[$i]["name"]  = @mysql_field_name  ($id, $i);
        $res[$i]["type"]  = @mysql_field_type  ($id, $i);
        $res[$i]["len"]   = @mysql_field_len   ($id, $i);
        $res[$i]["flags"] = @mysql_field_flags ($id, $i);
      }
    } else { // full
      $res["num_fields"]= $count;

      for ($i=0; $i<$count; $i++) {
        $res[$i]["table"] = @mysql_field_table ($id, $i);
        $res[$i]["name"]  = @mysql_field_name  ($id, $i);
        $res[$i]["type"]  = @mysql_field_type  ($id, $i);
        $res[$i]["len"]   = @mysql_field_len   ($id, $i);
        $res[$i]["flags"] = @mysql_field_flags ($id, $i);
        $res["meta"][$res[$i]["name"]] = $i;
      }
    }

    // free the result only if we were called on a table
    if ($table) @mysql_free_result($id);
    return $res;
  }


  function table_names() {
    $this->query("SHOW TABLES");
    $i=0;
    while ($info=mysql_fetch_row($this->Query_ID))
     {
      $return[$i]["table_name"]= $info[0];
      $return[$i]["tablespace_name"]=$this->Database;
      $return[$i]["database"]=$this->Database;
      $i++;
     }
   return $return;
  }

   function debug($str){
      if ($this->Debug && "" != $str){
         $debug_line = sprintf("%s : %s\n",date("d/m/Y H:i:s"),$str);
            if ("" != $this->Debug_File){
               error_log($debug_line,3,$this->Debug_File);
               if ($f = @fopen($this->Debug_File,"a")){
                  @fputs($f,$debug_line);
                  @fclose($f);
               }
            }
            else{
               error_log($debug_line,0);
               print($debug_line."<br>");
            }
         }
      }
   }

   function set_debug($file ="query.log"){
      $this->Debug_File = $file;
   }
}
*/
?>
