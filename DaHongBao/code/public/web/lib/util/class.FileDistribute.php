<?php
/*
 * Created on Jul 30, 2007
 * class.FileDistribute.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.FileDistribute.php,v 1.1 2013/04/15 10:58:02 rock Exp $
 * @link       http://www.smarter.com/
 */

class FileDistribute {
	public static $SYNC_TYPE_1 = "1"; //����ͬ���ļ�
	public static $SYNC_TYPE_2 = "2"; //����ͬ��Ŀ¼
	public static $SYNC_TYPE_3 = "3"; //����ݹ�ͬ��Ŀ¼
	public static $SYNC_EXEC = "/home/sites/dahongbao/bin/rsync_files_setting.sh";
	public static $SYNC_COMMIT_EXEC = "/home/sites/smartercn/bin/rsyncsetting.sh";
	public static $SYNC_IMAGES_EXEC = "/u0/sites/dahongbao/tools/scripts/rsyc_image_attach.sh";
	public static $SYNC_IMAGES_BETA_EXEC = "/home/sites/dahongbaobeta/tools/scripts/rsyc_image_attach.sh";
	
	
	public static function preCheck() {
		
		//define("__NEED_DISTRIBUTE","true");
		if(defined("__NEED_DISTRIBUTE") == false || __NEED_DISTRIBUTE == false) {
			return false;
		}
		return true;
	}
	
	/**
	 * Ŀ¼�ַ�
	 * $param $dir - Ŀ¼��
	 * @param $recursion - �Ƿ�ݹ�
	 * @return true - ��ִ��, false - δִ�� 
	 */
	public static function syncDirectory($dir, $recursion=false) {
		$startTime = time();
		if(self::preCheck($dir) == false) {
			return false; //ignore ����Ҫͬ��
		}
		$dir = rtrim($dir,'/'); //ȥ���һλ"/"
		if(is_dir(__ROOT_PATH . $dir) == false) {
			$msg = "Source file is not directory. (".__ROOT_PATH . $dir.")";
			self::addLog(array('ErrorMessage'=>"FileDistribute: ".$msg));
			throw new Exception($msg);
		}
		if($recursion == false) {
			$cmd = self::$SYNC_EXEC . " {$dir} " . self::$SYNC_TYPE_2;
		} else {
			$cmd = self::$SYNC_EXEC . " {$dir} " . self::$SYNC_TYPE_3;
		}
		//2008-01-10: ����rsync����ͬ��settingĿ¼
		//system($cmd);
		
		$arr = array();
		$arr['UseTime'] = time() - $startTime;
		$arr['Command'] = $cmd;
		self::addLog($arr);
		return true;
	}

	public static function syncImages($files) {
		if(!$files) return;
		if(BASE_HOSTNAME == "beta.dahongbao.com"){
			$cmd = self::$SYNC_IMAGES_BETA_EXEC. " {$files} ";
		}elseif(BASE_HOSTNAME == "www.dahongbao.com"){
			$cmd = self::$SYNC_IMAGES_EXEC. " {$files} ";
		}
		system($cmd);
		return true;
	}


	/**
	 * �ļ��ַ�
	 */
	public static function syncFile() {
		
		
		if(self::preCheck() == false) {
			return false; //ignore ����Ҫͬ��
		}
		$cmd = self::$SYNC_EXEC;
		//2008-01-10: ����rsync����ͬ��settingĿ¼
		system($cmd);

		return true;
	}
	
	public static function commit() {
		return self::syncFile();
	}
	
	public static function syncCache() {
		if(defined("__NEED_DISTRIBUTE") == false || __NEED_DISTRIBUTE == false) {
			return;
		}
		$startTime = time();
		$cmd = self::$SYNC_CACHE_EXEC;
		system($cmd);
		$arr = array();
		$arr['UseTime'] = time() - $startTime;
		$arr['Command'] = $cmd;
		self::addLog($arr);
	}
	
	public static function addLog($arr) {
//		$arr['CreateTime'] = date("Y-m-d H:i:s");
//		$sql = "INSERT INTO RP_FileDistribute ".self::toInsertStr($arr);
//		DBQuery::instance(__ETL)->executeUpdate($sql);
	}
	
	/*
	public static function toInsertStr($newRecord) {
		$keys = array_keys($newRecord);
		$fields = "";
		$values = "";
		foreach($keys as $key) {
			if($fields != "") {
				$fields .= ",";	
				$values .= ",";	
			}
			$fields .= $key;
			$values .= "'".DBQuery::filter($newRecord[$key])."'";
		}
		$str = "($fields) VALUES ($values)";
		return $str;
	}
	*/
}
?>