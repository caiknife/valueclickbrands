<?php
/*
 * Created on Nov 30, 2007
 * class.StopKeywordsDao.php
 * -------------------------
 * 
 * 屏敝关键字
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.StopKeywordsDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class StopKeywordsDao {
	
	/**
	 * 检查在搜索关键字中，是否有屏敝关键字
	 */
	public static function exists($keywords) {
		$keywords = trim($keywords);
		if($keywords == "") {
			return true;
		}
		if(strlen($keywords) <= 2) { //一个汉字，两个英文字不需要检查
			return false;
		}
		$words = WordSplitter::instance()->execute($keywords);
		$words[] = $keywords;
		
		foreach($words as $word) {
			if(strlen($word) <= 2) {
				continue; //一个汉字，两个英文字不需要检查
			}
			$queryWord[] = $word;
		}
		$queryWord = DBQuery::arrayFilter($queryWord);

		$sql = "SELECT Keywords FROM StopKeywords" .
				" WHERE Keywords in ('".implode("','", $queryWord)."')";
		$matchedKeywords = DBQuery::instance()->getOne($sql);
		if($matchedKeywords != null) {
			if($matchedKeywords == $keywords) { //完全匹配，返回2
				return 2;
			}
			return 1; //部分匹配，返回1
		}
		return 0;
	}
}
?>