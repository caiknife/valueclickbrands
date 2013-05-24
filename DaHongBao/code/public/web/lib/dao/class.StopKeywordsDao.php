<?php
/*
 * Created on Nov 30, 2007
 * class.StopKeywordsDao.php
 * -------------------------
 * 
 * ���ֹؼ���
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
	 * ����������ؼ����У��Ƿ������ֹؼ���
	 */
	public static function exists($keywords) {
		$keywords = trim($keywords);
		if($keywords == "") {
			return true;
		}
		if(strlen($keywords) <= 2) { //һ�����֣�����Ӣ���ֲ���Ҫ���
			return false;
		}
		$words = WordSplitter::instance()->execute($keywords);
		$words[] = $keywords;
		
		foreach($words as $word) {
			if(strlen($word) <= 2) {
				continue; //һ�����֣�����Ӣ���ֲ���Ҫ���
			}
			$queryWord[] = $word;
		}
		$queryWord = DBQuery::arrayFilter($queryWord);

		$sql = "SELECT Keywords FROM StopKeywords" .
				" WHERE Keywords in ('".implode("','", $queryWord)."')";
		$matchedKeywords = DBQuery::instance()->getOne($sql);
		if($matchedKeywords != null) {
			if($matchedKeywords == $keywords) { //��ȫƥ�䣬����2
				return 2;
			}
			return 1; //����ƥ�䣬����1
		}
		return 0;
	}
}
?>