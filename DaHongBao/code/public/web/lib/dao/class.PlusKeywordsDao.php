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
	 * ��ȡ�ؼ�����Ϣ
	 * 
	 * @param string $keywords
	 * @return array adsCount�ùؼ��ֹ�����������ӹؼ���
	 */
	public static function getPlusKeywordsInfo($keywords) {
		$table = 'PlusKeywords';
		return DBQuery::instance()->getRow("SELECT AdsCount, PlusKeywords FROM $table WHERE `Keywords` = '".DBQuery::filter($keywords)."'");
	}
	
	/**
	 * ��ȡһ�������ѱ����keyword
	 * 
	 * @param string $keywords
	 * @return array ѱ���ؼ�����Ϣ
	 */
	public static function getPlusKeywordsTaining($keywords) {
		$table = 'PlusKeywordsTraining';
		return DBQuery::instance()->getRow("SELECT `AdsCount`, `PlusKeywords1`,`PlusKeywords2`,`PlusKeywords3` FROM $table WHERE `Keywords` = '".DBQuery::filter($keywords)."'");
	}
	
	/**
	 * Training Keyword
	 *
	 * @param string $keyword �û�����ؼ���
	 * @param array $pluskeywords ���ӹؼ�������
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
		//1��	��ѯPlusKeywords��
		//1��1��	��AdsCount����3�����ء��������ַ�����
		//1��2��	��PlusKeywordsΪ�գ����ء��������ַ�����
		//1��3��	��PlusKeywords��Ϊ�գ�����PlusKeywords
		//1��4��	ȡ���������ִ����һ��
//				2����ѯPlusKeywordsTraining��
//				2��1����AdsCount����3�����ء��������ַ�����
//				2��2�����ѡȡPlusKeywords1,2,3(��Ϊ�յ��ֶ�)������
//				2��3��ȡ�������������false����ʾ�����ڣ�
		$plusKeywordsInfo = PlusKeywordsDao::getPlusKeywordsInfo($keyword);
		//�ӹؼ��ֱ�ȡ1
		if($plusKeywordsInfo) {
			$plusKeywords = $plusKeywordsInfo['PlusKeywords'];
		}
		//��ѱ���ؼ��ֱ������ȡ1,��ѱ�������ȡ1
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