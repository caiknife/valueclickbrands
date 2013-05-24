<?php

/**
 * Created on May 17, 2007
 * class.CategoryDao.php
 * -------------------------
 *
 * Description...
 *
 * @author Rollenc Luo <rollenc_luo@mezimedia.com> <rollenc@gmail.com> <{@link http://www.rollenc.com>
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 *
 * @link       http://www.smarter.com/
 */
define('MAX_CATEGORY_IN_CHANNEL', 3);
class CategoryDao {
	protected static $categoryPool = array ();
	/**
	 * �������Ϣͬ���������ļ���
	 */
	public static function updateEnv() {
		$channelIDs = CommonDao :: channel(NULL, "ID");
		$csv = new CSV();
		$all = array ();
		for ($loop = 0; $loop < count($channelIDs); $loop++) {
			$chid = $channelIDs[$loop];
			$categoryTbl = CommonDao :: channel($chid, "CategoryTable");
			$catRshTbl = CommonDao :: channel($chid, "CatRshTable");
			$sql = "SELECT $chid AS ChannelID,A.CategoryID,A.CategoryName,A.CategoryEnName,A.AdsKeywords" .
			" ,A.CallPhoneKeywords,A.IsLeaf,B.CategoryParentID AS ParentID FROM $categoryTbl A" .
			" INNER JOIN $catRshTbl B ON B.CategoryChildID=A.CategoryID";
			$rs = DBQuery :: instance()->executeQuery($sql);
			if ($rs == NULL) {
				continue;
			}
			$filename = __SETTING_FULLPATH . "config/category_{$chid}.srl";
			//			//CSV file
			//			$csv->storeFromArray($filename, $rs, true);
			//			$all = array_merge($all, $rs);
			//serialize file
			$record = array ();
			for ($i = 0; $i < count($rs); $i++) {
				$record[$rs[$i]['CategoryID']] = $rs[$i];
				$all[$chid][$rs[$i]['CategoryID']] = $rs[$i];
			}
			$fp = fopen($filename, "w+");
			if ($fp == NULL) {
				throw new Exception("can not open $filename.");
			}
			fwrite($fp, serialize($record));
			fclose($fp);
		}
		$filename = __SETTING_FULLPATH . "config/category_all.php";
		
		Utilities::setArrayCache($filename, $all);
		FileDistribute::syncDirectory(__SETTING_PATH.'config');
	}

	/**
	 * ��һ��Ƶ��Ϊ��λ,װ�������Ϣ
	 */
	protected static function & load($chid) {
		if (self :: $categoryPool != NULL) {
			return;
		}
		if (self :: $categoryPool != NULL) {
			return;
		}
		$filename = __SETTING_FULLPATH . "config/category_all.php";
		self :: $categoryPool = Utilities::getArrayCache($filename);
	}

	public static function & get($chid, $catid, $field = NULL) {
		if (isset (self :: $categoryPool[$chid]) == false) {
			self :: load($chid);
		}
		if (isset (self :: $categoryPool[$chid][$catid]) == false) {
			$rtn = NULL;
			return $rtn;
		}
		if ($field == NULL) {
			return self :: $categoryPool[$chid][$catid];
		}
		return self :: $categoryPool[$chid][$catid][$field];
	}
	
	static public function getCategoryInfoByName($chid, $name) {
		if (isset (self :: $categoryPool[$chid]) == false) {
			self :: load($chid);
		}
		foreach(self :: $categoryPool[$chid] as $catid => $category) {
			
			if($category['CategoryName'] == $name) {
				return self::getCategoryInfo($chid, $catid);
			}
		}
		return array();
	}


	public static function getCategoryByChannel($chid){
		$categorytable = CommonDao :: channel($chid, "CategoryTable");
		$catRshTbl = CommonDao :: channel($chid, "CatRshTable");
		$sql = "SELECT * FROM $categorytable as A INNER JOIN $catRshTbl B ON A.CategoryID = B.CategoryChildID". 
		" WHERE IsTop = 'NO' AND IsActive='YES' AND B.CategoryParentID = 0 ORDER BY A.Rank DESC, A.CategoryID ASC";
		return DBQuery :: instance()->executeQuery($sql);	
	}
	
	public static function getCategoryParent($chid,$cid){
		$categorytablersh = "C".$chid."CategoryRsh";
		$sql = "SELECT CategoryParentID FROM $categorytablersh WHERE CategoryChildID=$cid";
		return DBQuery :: instance()->getOne($sql);	
	}
	
	public static function formatCategoryNav($chid, $categories, $currentCategoryID=0) {
		for($i=0, $end=count($categories); $i<$end; $i++) {
			$categories[$i]['URL'] = PathManager::getCategoryUrl($chid, $categories[$i]['CategoryID']);
			if($currentCategoryID == $categories[$i]['CategoryID']) {
				$categories[$i]['IsSelected'] = 'YES';
			} else {
				$categories[$i]['IsSelected'] = 'NO';
			}
		}
		return $categories;
	}

	/**
	 * ��ȡcategory info
	 */
	static public function getCategoryInfo($chid, $catid) {
		$rs = self::get($chid, $catid);
		$params['chid'] = $chid;
		$params['catid'] = $rs['CategoryID'];
		$params['CategoryEnName'] = $rs['CategoryEnName'];
		$params['IsLeaf'] = $rs['IsLeaf'];
		$rs['Href'] = ProductListDao :: getUrl($params);
		return $rs;
	}
	
	public static function getAdsKeywords($chid, $catid) {
		$info = self::getCategoryInfo($chid, $catid);
		if(!empty($info['AdsKeywords'])) {
			return $info['AdsKeywords'];
		} else {
			return $info['CategoryName'];
		}
		
	}

	/**
	 * @��    �� ������dinglingling �Ĺؼ���
	 * @����ʱ�� ��April 14,2008
	 * @������Ա ��menny
	 */
	public static function getCallPhoneKeywords($chid,$catid) {
		$info = self::getCategoryInfo($chid, $catid);
		if(!empty($info['CallPhoneKeywords'])) {
			return $info['CallPhoneKeywords'];
		} else {
			return $info['CategoryName'];
		}
	}

	/**
	 * ��Channel ID����
	 */
	static public function deviteToChannel($categories) {
		$result = array ();
		foreach ($categories as $category) {
			$tmp = DictionaryDao :: parseCategoryID($category);
			$result[$tmp['CH_ID']][] = $tmp['C_ID'];
		}
		return $result;
	}
	/**
	 * ���ݽӿ����°��ŷ���ֵ
	 */
	static public function rebuild($categories) {
		foreach ($categories as $chid => & $category) {
			$category = implode(',', $category);
		}
		if (!$category) {
			$category = 0;
		}
		return $categories;
	}

	/**
	 * ��ȡ�����𣬲����кϲ������ϵȴ���
	 */
	static public function parseKeywordsCatgory($words) {
		$categories = CategoryDao :: fetchRelatedCategory($words);
		$categories = CategoryDao :: deviteToChannel($categories);
		$categories = CategoryDao :: mergeCategory($categories);
		$categories = CategoryDao :: jointlyCategory($categories);
		$categories = CategoryDao :: rebuild($categories);
		return $categories;
	}

	/**
	 * ȡ����ص�Category
	 * @see Dictionary::fetchRelatedCategory
	 * @param array $words
	 */
	static public function fetchRelatedCategory($words) {
		$dic = new DictionaryDao();
		return $dic->fetchRelatedCategory($words);
	}

	/**
	 * �ϲ�Category�����
	 *
	 * ������A�����B�����࣬����B��ȥ��A
	 * @return array �������ֵ�category
	 */
	static public function mergeCategory($categories) {

		foreach ($categories as $chid => & $categoryofChannel) {
			for ($i = count($categoryofChannel) - 1; $i > 0; $i--) {
				$leftCategories = array_diff($categoryofChannel, array (
					$categoryofChannel[$i]
				));
				foreach ($leftCategories as $tmpCategory) {
					if (self :: isSubCategory($chid, $categoryofChannel[$i], $tmpCategory)) {
						$categoryofChannel = array_diff($categoryofChannel, array (
							$tmpCategory
						));
						break;
					}
				}
			}
		}
		return $categories;
	}

	static public function MergeLeafCategory($categories) {
		foreach ($categories as $chid => & $categoryofChannel) {
			//ȡҶ�����
			$leafCategories = array();
			foreach($categoryofChannel as $cat4Leaf) {
				if($cat4Leaf != 0) {
					$leafCategories = array_merge($leafCategories, self::GetFlatLeafCategories($chid, $cat4Leaf));
				}
			}
			$categoryofChannel = $leafCategories;
		}
		return $categories;
	}

	/**
	 * ȡ���Ĺ�������
	 *
	 * ������A�Ĳ��ΪO->P->Q�����B�Ĳ��ΪO->P->M,��ȡ�乫������O->P
	 * @param array $categories һά����
	 * @return array �������ֵ�category
	 */
	static public function jointlyCategory($categories) {
		return self::MergeLeafCategory($categories);

		foreach ($categories as $chid => & $categoryofChannel) {
			if ($categoryofChannel && $categoryofChannel[0]==0) {
				continue;
			}
			//���categoryofChannel��ĿΪ1������ϲ���ֱ�ӷ���
			if (count($categoryofChannel) <= 1) {
				//continue;
			}
			//���categoryofChannel��Ŀ������������������category����Ϊ�գ�������channel����
			elseif (count($categoryofChannel) > MAX_CATEGORY_IN_CHANNEL) {
				$categoryofChannel = array ();
				continue;
			}
			else {
				$tmpCategoryofChannel = self :: getFlatParentCategorys($chid, $categoryofChannel[0]);
				for ($i = 1; $i < count($categoryofChannel); $i++) {
					$tmpCategoryofChannel = array_intersect($tmpCategoryofChannel, self :: getFlatParentCategorys($chid, $categoryofChannel[$i]));
				}
				$categoryofChannel = array_slice($tmpCategoryofChannel, -1);
			}
			//ȡҶ�����
			$leafCategories = array();
			foreach($categoryofChannel as $cat4Leaf) {
				$leafCategories = array_merge($leafCategories, self::GetFlatLeafCategories($chid, $cat4Leaf));
			}
			$categoryofChannel = $leafCategories;
		}
		return $categories;
	}

	/**
	 * ��ȡ����
	 * @param int $chid
	 * @param int $catid
	 * @return array һά����
	 * @todo Ч���Ż���Ŀǰʹ��mysqlѭ���ó�
	 */
	static public function getFlatParentCategorys($chid, $catid) {
		$CatRshTable = CommonDao :: channel($chid, "CatRshTable");
		$CategoryTable = CommonDao :: channel($chid, "CategoryTable");
		$parentCategories = array ();
		while ($catid > 0) {
			$parentCategories[] = $catid;
			$catid = self::get($chid, $catid, 'ParentID');
		}
		return $parentCategories;
	}

	/**
	 * ��������
	 */
	static public function isSubCategory($chid, $category1, $category2) {
		return in_array($category2, self :: getFlatParentCategorys($chid, $category1));
	}

	static public function getFlatLeafCategories($chid, $catid) {
		$flatCategories = array();
		if (self :: isLeaf($chid, $catid)) {
			$flatCategories = array (
				$catid
			);
		} else {
			$CatRshTable = CommonDao :: channel($chid, "CatRshTable");
			$CategoryTable = CommonDao :: channel($chid, "CategoryTable");
			$sql = "SELECT CategoryID,CategoryName,CategoryEnName,IsLeaf" .
			" FROM " . $CategoryTable . " C" .
			" INNER JOIN " . $CatRshTable . " CR ON C.CategoryID = CR.CategoryChildID" .
			" WHERE C.IsActive='YES' " .
			" AND CR.CategoryParentID=" . $catid .
			" AND C.r_OnlineProductCount>0" .
			" ORDER BY CategoryName";
			$rs = DBQuery :: instance()->executeQuery($sql);
			$flatCategories = array ();

			foreach ($rs as $category) {
				$flatCategories = array_merge($flatCategories, self :: GetFlatLeafCategories($chid, $category['CategoryID']));
			}
		}
		return $flatCategories;
	}

	/**
	 * �ж�Leaf Category
	 *
	 * @param int $chid Channel Id
	 * @param int $catid Category ID
	 * @param string $leaf Yes or No.
	 * @return bool
	*
	*/
	static public function isLeaf($chid, $catid, $leaf = null) {
		if ($leaf === null) {
			$leaf = self::get($chid, $catid, 'IsLeaf');
		}
		return $leaf == 'YES' ? true : false;
	}

//	/**
//	 * ȡ�����е������
//	 * @param int $chid
//	 * @param int $catid
//	 * @return array ���ֲ�ε�һά����
//	 * @deprecated version - May 21, 2007  ��getFlatParentCategorys�����������Ч�����ڴ˺���
//	 */
//	static public function getFlatSubCategories($chid, $catid) {
//		if (self :: isLeaf($chid, $catid)) {
//			$flatCategories = array (
//				$catid
//			);
//		} else {
//			$CatRshTable = CommonDao :: channel($chid, "CatRshTable");
//			$CategoryTable = CommonDao :: channel($chid, "CategoryTable");
//			$sql = "SELECT CategoryID,CategoryName,CategoryEnName,IsLeaf" .
//			" FROM " . $CategoryTable . " C" .
//			" INNER JOIN " . $CatRshTable . " CR ON C.CategoryID = CR.CategoryChildID" .
//			" WHERE C.IsActive='YES' " .
//			" AND CR.CategoryParentID=" . $catid .
//			" AND C.r_OnlineProductCount>0" .
//			" ORDER BY CategoryName";
//			$rs = DBQuery :: instance()->executeQuery($sql);
//			$flatCategories = array ();
//
//			foreach ($rs as $category) {
//				$flatCategories = array_merge($flatCategories, self :: getFlatSubCategories($chid, $category['CategoryID']));
//			}
//		}
//		return $flatCategories;
//	}

//	/**
//	 * ȡ���¼����
//	 */
//	static public function getSubCategory($chid, $category) {
//		$chid = $chid;
//		$catid = $category['CategoryID'];
//		$CatRshTable = CommonDao :: channel($chid, "CatRshTable");
//		if (empty ($params['merId'])) {
//			$CategoryTable = CommonDao :: channel($chid, "CategoryTable");
//			$sql = "SELECT CategoryID,CategoryName,CategoryEnName,IsLeaf" .
//			" FROM " . $CategoryTable . " C" .
//			" INNER JOIN " . $CatRshTable . " CR ON C.CategoryID = CR.CategoryChildID" .
//			" WHERE C.IsActive='YES' " .
//			" AND CR.CategoryParentID=" . $catid .
//			" AND C.r_OnlineProductCount>0" .
//			" ORDER BY CategoryName";
//		} else {
//			$CategoryTable = CommonDao :: channel($chid, "MerBIdCatTable");
//			$sql = "SELECT CategoryID,CatName CategoryName,IsLeaf" .
//			" FROM MerCat " .
//			" WHERE IsLeaf='YES'" .
//			" AND ChannelID=" . $chid .
//			" AND MerchantID=" . $params['merId'] .
//			" AND r_OnlineProductCount>0" .
//			" ORDER BY CategoryName";
//		}
//		$rs = DBQuery :: instance()->executeQuery($sql);
//		for ($loop = 0; $loop < count($rs); $loop++) {
//			$params['catid'] = $rs[$loop]['CategoryID'];
//			$params['CategoryEnName'] = $rs[$loop]['CategoryEnName'];
//			$params['IsLeaf'] = $rs[$loop]['IsLeaf'];
//			$rs[$loop]['Href'] = ProductListDao :: getUrl($params);
//		}
//		return $rs;
//	}

	/**
	 * ��ȡ���ȼ�
	 * ��TopPriority�� => Array(

	//��LeafCategoryID�����ڣ���ʡ�Էָ�����-��

	        ChannelID + ��-�� + LeafCategoryID => 1 ,

	        ��
	 * @param string �ؼ���
	 * @return array ���ȼ�
	 */
	static public function getTopPriority($words) {
		$dic = new DictionaryDao();
		$categories = $dic->fetchRelatedPriority($words);
		return CategoryDao :: rebuildPriority($categories);
	}
	
	/**
	 * ����ר������
	 * 
	 * @param array $words 
	 */
	static public function getProper($words) {
		$dic = new DictionaryDao();
		$channales = $dic->fetchRelatedProper($words);
		return CategoryDao :: rebuildProper($channales);
	}
	
	static public function rebuildProper($channales) {
		$result = array();
		foreach($channales as $channelId) {
			$result[''.$channelId] = 1;
		}
		return $result;
	}

	/**
	 * ��������Priority����Ӧ�ӿ�
	 * @param string $categories �ö��ŷָ��CHID_CID��ʽ
	 */
	static public function rebuildPriority($categories) {
		$result = array ();
		$arrCategories = array_filter(explode(',', $categories));
		$i = 1;
		foreach ($arrCategories as $category) {
			list ($chid, $cid) = explode('_', $category);
			if($cid != 0) {
				$cc = $chid . '-' . $cid;
			}
			else {
				$cc = $chid . '';
			}
			$result[$cc] = $i++;
		}
		return $result;
	}
	
	public static function fetchLeafCategory($chid) {
		$tblCategory = CommonDao::channel($chid, "CategoryTable");
		$sql = "SELECT * FROM $tblCategory" .
				" WHERE IsLeaf='YES' AND IsActive='YES'" .
				" ORDER BY CategoryID";
		return DBQuery::instance()->executeQuery($sql);	
	}
	
	public static function fetchLeafCategoryForSelect($chid, $existCategoryIDs=NULL) {
		$rs = self::fetchLeafCategory($chid);
		if($existCategoryIDs != NULL) {
			$existCategoryIDs = array_combine($existCategoryIDs, $existCategoryIDs);
		}
		$ret = array();
		foreach($rs as $row) {
			if($existCategoryIDs != NULL && !isset($existCategoryIDs[$row['CategoryID']])) {
				continue;
			}
			$ret[$row['CategoryID']] = $row['CategoryName'];
		}
		return $ret;	
	}
	
	public static function getAdsKeyword ($chid, $cid, $categoryName = ''){
		$categoryTable = CommonDao::channel($chid, "CategoryTable");
		$sql = "SELECT AdsKeywords from $categoryTable where CategoryID={$cid}";
		$keyword =  DBQuery::instance()->getOne($sql);
		
		if($keyword) {
			return $keyword;
		}
		if($categoryName) {
			return $categoryName;
		}
		return self::get($chid, $catid, 'CategoryName');
	}
	
	//get category breadcrumbs 
	public static function getCateBreadcrumbsByCateID($chid, $catid) {
		do {
			if ($categoryPath = self::get($chid, $catid)) {
				$categorybreadcrumbs[] = array("navigationName" => $categoryPath["CategoryName"],
					'navigationUrl' => PathManager::getCategoryURL($chid, $catid), 'categoryID' => $catid, 
					$catid = $categoryPath["ParentID"]
				);
			}
			$catid = $categoryPath["ParentID"];
		} while ($categoryPath["ParentID"]);
		return $categorybreadcrumbs;
	}
	
	/**
	 * ��֯Category���ӹ�ϵ Ŀǰֻ֧��һ��
	 *
	 * @param int $chid 
	 * @param int $parentId ��ȡĳһ��category
	 * @return array
	 */
	public static function getCategoryChild($chid, $parentId = NULL) {
		if (isset (self :: $categoryPool[$chid]) == false) {
			self :: load($chid);
		}
		foreach (self :: $categoryPool[$chid] as $category) {
			$category['navigationUrl'] = PathManager::getCategoryURL($chid, $category["CategoryID"]);
			if ($parentId) {
				if ($category["ParentID"] == $parentId) {
					$childCategory[$category["ParentID"]][] = $category;
				}
			}
			else if ($category["ParentID"]) {
					$childCategory[$category["ParentID"]][] = $category;
			}
		}
		return $childCategory;
	}
}
?>