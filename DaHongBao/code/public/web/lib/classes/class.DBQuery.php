<?php
/**
 * class.DBQuery.php
 *-------------------------
 *
 * execute db query 
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive shopping engine 
 * that helps consumers to make smarter buying decisions online. We empower consumers to compare 
 * the attributes of over one million products in the computer and consumer electronics categories
 * and to read user product reviews in order to make informed purchase decisions. Consumers can then 
 * research the latest promotional and pricing information on products listed at a wide selection of 
 * online merchants, and read user reviews on those merchants.
 * The copyrights is reserved by http://www.mezimedia.com.  
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @author     Fan Xu <fan_xu@mezimedia.com>
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.DBQuery.php,v 1.1 2013/04/15 10:57:54 rock Exp $
 * @link       http://www.smarter.com/
 * @deprecated File deprecated in Release 2.0.0
 */

class SQLException extends Exception {}
 
require_once("DB.php");
//require_once("Config/Container.php");

class DBQuery {
	/** 是否支持事务 **/
	public static $supportTransaction = false;
	/** 已创建的连接集合 **/
	private static $instances = array();
	/** 默认连接 **/
    private static $defaultDsn = __DEFAULT_DSN;
    
    private $vDsn = null;
    private $vDbh = null;
	private $BugLevel = 3;
	private $queryMessage = null;
//	private static $defaultInstance = null;
	
    /**
     * 构造函数
     */
	private function __construct($dsn) {
		$this->vDsn = $dsn;
		$this->vDbh = $this->connect($dsn);
		$this->vDbh->setFetchMode(DB_FETCHMODE_ASSOC);
	}

	/**
	 * 数据库连接实例
	 */
    public static function instance($dsn="") {
    	if(empty($dsn)) {
    		$dsn = self::$defaultDsn;
    	}
    	$instance = self::$instances[$dsn];
    	if(!empty($instance) && is_object($instance)) {
    		return $instance;
    	}
    	$instance = new DBQuery($dsn);
    	self::$instances[$dsn] = $instance;
    	
			if (( $pos = strpos($dsn, '?')) !== false) {
    		$params = array();
				parse_str(substr($dsn, $pos + 1), $params);
				if(!empty($params['charset'])) {
					$instance->executeQuery("set names {$params['charset']}");
				}
			}

    	return $instance;
    }
    
    /**
     * 事务开始
     */
    public function startTransaction() {
		if(self::$supportTransaction == false || $this->vDbh == null) {
			return;
		}
    	$this->executeQuery("START TRANSACTION");
    }
    /**
     * 提交
     */
    public function commit() {
		if(self::$supportTransaction == false || $this->vDbh == null) {
			return;
		}
    	$this->executeQuery("COMMIT");
    }
    /**
     * 回滚
     */
    public function rollback() {
		if(self::$supportTransaction == false || $this->vDbh == null) {
			return;
		}
    	$this->executeQuery("ROLLBACK");
    }

	function setToolBug() {
		$this->BugLevel = 4;
	}
    
    function setDSN($dsn) {
        $this->vDsn = $dsn;
    }
    
    function getDSN() {
        return $this->vDsn;
    }

    function connect($dsn=null)
    {
        if (strlen($dsn) > 0){
            $this->setDSN($dsn);
        }
	//debug...
	//$startTime = Utilities::getMicrotime();
        $dbh = DB::connect($this->vDsn);
    //logDebug("connect use time: ".(Utilities::getMicrotime() - $startTime));
        $this->_errorProbe($dbh, "connect: ".$dsn);
        return $dbh;
    }
    
    function disconnect(){
    	$this->vDbh->disconnect();
    }
    
    function executeQuery($sql){
        $array = $this->vDbh->getAll($sql);
        $this->_errorProbe($array,$sql,$this->BugLevel);
        return $array;
    }
    
    function executeUpdate($sql){
        $result = $this->vDbh->query($sql);
        $this->_errorProbe($result,$sql,$this->BugLevel);
        return $result;
    }

	function autoExecute($table,$arrData,$mode=DB_AUTOQUERY_INSERT,$c=''){
		$result = $this->vDbh->autoExecute($table,$arrData,$mode,$c);
        //$this->_errorProbe($result,$sql,$this->BugLevel);
        return $result;
		//$res =& $this->_db->autoExecute();
	}

	function getInsertID() {
		return mysql_insert_id();
	}
    
    function affectedRows($sql){
        $rows = $this->vDbh->affectedRows($sql);
        $this->_errorProbe($rows,$sql,$this->BugLevel);
        return $rows;
    }
    
    function getOne($sql){
        $one = $this->vDbh->getOne($sql);
        $this->_errorProbe($one,$sql,$this->BugLevel);
        return $one;
    }
    
    function getRow($sql){
        $result = $this->vDbh->query($sql);
        $this->_errorProbe($result,$sql,$this->BugLevel);
        if ($row = $result->fetchRow()){
            return $row;
        }else{
            return "";
        }
    }

    function _errorProbe($obj, $sql, $level="3"){
//    	logDebug($sql, __MODEL_EMPTY);
        if (PEAR::isError($obj)){
	    	throw new SQLException($obj->getMessage()."; "."SQL=".$sql);
        }
/*
            $_br = "<br />\n";
            switch($level){
            case 0: /// stop
                die($obj->getMessage());
                break;
            case 1: /// echo then stop
                echo $obj->getMessage() . $_br . "Last Query: " . $sql . $_br;
                die;
                break;
            case 2: /// echo then pass
                echo $obj->getMessage() . $_br . "Last Query: " . $sql . $_br;
                break;
            case 3:
                // do nothing.
            case 4: //add by fan (2006-03-10)
                $this->message = "Query Error: " . $obj->getMessage();
                break;
            default:
                // do nothing.
                break;
            }
            return false;
        }
        else return true;
*/
    }
	
	function getQueryMessage() {
		return $this->queryMessage;
	}
	
	/**
	 * 过虑特殊字符
	 */
	public static function &filter(&$str) {
		if(count(self::$instances) == 0) {
			self::instance();
		}
		return mysql_real_escape_string($str);
	}
	
	/**
	 * 将数组转换到以','分隔的字符串
	 */
	public static function &arrayToString(&$arr) {
		if(!is_array($arr)) {
			return $arr;
		}
		$str = "";
		for($loop=0; $loop<count($arr); $loop++) {
			if($loop > 0) {
				$str .= ",";
			}
			$str .= $arr[$loop];
		}
		return $str;
	}
	
	/**
	 * 将采用分号隔开的多条SQL语句,解析到数组
	 */
	public static function &sqlsToArray($sqls) {
		$arr = array();
		$depth = 0;
		$len = strlen($sqls);
		$offset = 0;
		for($loop=0; $loop<$len; $loop++) {
			if($sqls[$loop] == "'") {
				if($depth == 0) {
					$depth = 1;
				} else if($loop<$len-1 && $sqls[$loop+1] == "'") {
					$loop++; //skip next "'"
				} else { //depth is 1
					$depth = 0;
				}
			} else if($sqls[$loop] == ";" && $depth == 0) { //find a row
				$sql = trim(substr($sqls, $offset, $loop-$offset));
				if(!empty($sql)) {
					$arr[] = $sql;
				}
				$offset = $loop + 1; //skip current ";"
			}
		}
		//last area
		if($offset < $len) {
			$sql = trim(substr($sqls, $offset));
			if(!empty($sql)) {
				$arr[] = $sql;
			}
		}
		return $arr;
	}
}
