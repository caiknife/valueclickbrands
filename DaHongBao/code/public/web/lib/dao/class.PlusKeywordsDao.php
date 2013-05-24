<?php
/*
 * Created on Oct 18, 2007
 * class.PlusKeywordsDao.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.PlusKeywordsDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class PlusKeywordsDao {
	/**
	 * 获取关键字信息
	 * 
	 * @param string $keywords
	 * @return array adsCount该关键字广告数量，附加关键字
	 */
	public static function getPlusKeywordsInfo($keywords) {
		$table = 'PlusKeywords';
		return DBQuery::instance()->getRow("SELECT AdsCount, PlusKeywords FROM $table WHERE `Keywords` = '".DBQuery::filter($keywords)."'");
	}
	
	/**
	 * 获取一个随机的驯化中keyword
	 * 
	 * @param string $keywords
	 * @return array 驯化关键字信息
	 */
	public static function getPlusKeywordsTaining($keywords) {
		$table = 'PlusKeywordsTraining';
		return DBQuery::instance()->getRow("SELECT `AdsCount`, `PlusKeywords1`,`PlusKeywords2`,`PlusKeywords3` FROM $table WHERE `Keywords` = '".DBQuery::filter($keywords)."'");
	}
	
	/**
	 * Training Keyword
	 *
	 * @param string $keyword 用户请求关键字
	 * @param array $pluskeywords 附加关键字数组
	 * @return void
	 */
	public static function training($keyword, $plusKeywords) {
		$table = 'PlusKeywordsTraining';
		$time = getDateTime('Y-m-d H:i:s');
		$insertSql = "INSERT IGNORE INTO $table (`ID`, `Keywords`, `AdsCount`, `PlusKeywords1`, `PlusKeywords2`, `PlusKeywords3`, `CreateTime`, `UpdateTime`) ";
		$insertSql .= " VALUES (null, '".DBQuery::filter($keyword)."', 0, '".DBQuery::filter($plusKeywords[0]['Keyword'])."', '".DBQuery::filter($plusKeywords[1]['Keyword'])."', '".DBQuery::filter($plusKeywords[2]['Keyword'])."', '{$time}', '{$time}') ";
		return DBQuery::instance(__DAHONGBAO_Master)->executeUpdate($insertSql);
	}
	
	public static function getPlusKeyword($keyword, $relationKeywords) {
		$plusKeywords = '';
		//1．	查询PlusKeywords表
		//1．1．	若AdsCount大于3，返回‘’（空字符串）
		//1．2．	若PlusKeywords为空，返回‘’（空字符串）
		//1．3．	若PlusKeywords不为空，返回PlusKeywords
		//1．4．	取不到结果，执行下一步
//				2．查询PlusKeywordsTraining表
//				2．1．若AdsCount大于3，返回‘’（空字符串）
//				2．2．随机选取PlusKeywords1,2,3(不为空的字段)，返回
//				2．3．取不到结果，返回false（表示不存在）
		$plusKeywordsInfo = PlusKeywordsDao::getPlusKeywordsInfo($keyword);
		//从关键字表取1
		if($plusKeywordsInfo) {
			$plusKeywords = $plusKeywordsInfo['PlusKeywords'];
		}
		//从驯化关键字表中随机取1,或驯化再随机取1
		else {
			$plusKeywordsInfo = PlusKeywordsDao::getPlusKeywordsTaining($keyword);
			if($plusKeywordsInfo) {
				$plusKeywordsInfo = array_filter($plusKeywordsInfo);
				$plusKeywords = $plusKeywordsInfo[array_rand($plusKeywordsInfo)];
			}
			else {
				if($relationKeywords[0]['Keyword']!=""){
					self::training($keyword, $relationKeywords);
					$rand = array_rand($relationKeywords);
					$plusKeywords = $relationKeywords[$rand]['Keyword'];
				}
			}
		}
		return $plusKeywords;
	}
}
?>