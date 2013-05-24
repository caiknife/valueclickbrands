<?php
/*
 * Created on 2010-7-8
 * 
 * 得到SPL内容
 * 
 * @author     Thomas_FU
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.CommonAds.php,v 1.1 2013/04/15 10:58:02 rock Exp $
 * @link       http://www.smarter.com/
 * @deprecated File deprecated in Release 2.0.0
 */

class CommonAds {
	
	protected $keyword = NULL;
	protected $keyword2 = NULL;
	protected $isRepeatArr = array();	//各个位置，是否需要重复显示 
	protected $splitCountArr = array();	//tpl模板显示位置及每个位置的广告数目
	protected $adsNeedJS = true;	//当无广告是，是否要重新用JS请求
	protected $alreadyInJS = false;	//当前状态， true表示当前是用JS调用的
	protected $adsPositionCount = 0;
	protected $chid = NULL;
	protected $timeout = 2;	//超时时间为2秒
	protected $oldParamArr = array();	//原始参数
	protected $adsCount = 0;
	protected $IsHighlight = '';
	protected $IsDisplayKeywordArr = array();	//各个位置是否需要在头部显示搜索关键词
	protected $requestAdsCount = 0;	//请求广告的数量
	public $adsOvertime = false;	//是否超时
	private static $pageTypeID = -1;
	protected static $googleAdtest = NULL;
	
		
	public function setGoogleAdtest($adtest) {
		self::$googleAdtest = $adtest == 'on' ? 'on' : 'off';
	}
	
	/**
	 * 参数设置
	 * @param array $paramArr 二维数组 array('splitCountArr' => array(3, 3, 6), 'keyword' => $keyword, 
	 * 										'keyword2' => $keyword2, isRepeatArr = array(false, false),
	 * 										'adsNeedJS' => true, chid => $chid
	 * 										)
	 * @return boolean 
	 */
	public function initParams($paramArr) {
		//0. 参数检查
		if(empty($paramArr['keyword'])
			|| is_array($paramArr['splitCountArr']) == false
			|| count($paramArr['splitCountArr']) == 0) {
			return false;
		}
		//1. 两个关键字
		$this->keyword = $paramArr['keyword'];
		//2. 分隔广告及每个位置的广告数
		$this->splitCountArr = $paramArr['splitCountArr'];
		$this->adsPositionCount = count($this->splitCountArr);
		//3. 广告可重复的位置。快速参数，传递bool or string类型，自动拷贝到所有位置
		if(!empty($paramArr['isRepeatArr'])) {
			if(is_array($paramArr['isRepeatArr'])) {
				if(count($paramArr['isRepeatArr']) != $this->adsPositionCount) {
					throw new Exception("array size must equal between 'isRepeatArr' to 'splitCountArr'.");
				}
				$this->isRepeatArr = $paramArr['isRepeatArr'];
			}
			else {
				if(is_bool($paramArr['isRepeatArr'])) {
					$isRepeat = $paramArr['isRepeatArr'];
				} else {
					$isRepeat = strtolower($paramArr['isRepeatArr']) == 'true' ? true : false;
				}
				for($i=0; $i<$this->adsPositionCount; $i++) {
					$this->isRepeatArr[] = $isRepeat;
				}
			}
		} else {
			$this->isRepeatArr[] = array();
		}
		//4. 无广告时，是否需要JS再次请求, 默认为不需要二次请求
		if (isset($paramArr['adsNeedJS'])) {
			$this->adsNeedJS = $paramArr['adsNeedJS'];
		} else {
			$this->adsNeedJS = false;
		}
		
		//6. 是否需要转换 <b> 到 <strong>
		if (isset($paramArr['IsHighlight'])) {
			$this->IsHighlight = $paramArr['IsHighlight'];
		} else {
			$this->IsHighlight = true;
		}
		//7. 超时参数设置
		if(isset($paramArr['timeout']) && $paramArr['timeout'] > 0) {
			$this->timeout = $paramArr['timeout'];
		} else {
			$this->timeout = 2; //默认2秒
		}
		//8. 是否需要在头部显示 搜索关键词
		if (!empty($paramArr['IsDisplayKeywordArr']) && is_array($paramArr['IsDisplayKeywordArr'])) {
			$this->IsDisplayKeywordArr = $paramArr['IsDisplayKeywordArr'];
		} else {
			$this->IsDisplayKeywordArr = array();
		}
		//9. 其它
		$this->chid = $paramArr['chid'];
		
		foreach ($this->splitCountArr as $splitCount) {
			$this->requestAdsCount += $splitCount;
		}
		return true;
	}
	
	/**
	 * 得到SPL内容
	 */
	public function getAdsScript($params) {
		$adsContent = "";
		$startTime = time();
		//1. 流量质量判断
        if (BlockAdSenceDao::isBlockAdSence() == true) {
            return false;
        }
        
        //block Ads if the traffic is in black list
        //make a list of valid traffic type, then negate it
        if (!(in_array(Tracking_Session::getInstance()->getTrafficType(), 
            array(Tracking_Constant::TRAFFIC_GOOD_IP, Tracking_Constant::TRAFFIC_GOOD_USERAGENT, Tracking_Constant::TRAFFIC_PRIVATE_IP)) || 
            Tracking_Session::getInstance()->getTrafficType() >= Tracking_Constant::TRAFFIC_NORMAL)) {
            return false;
        }
		//2. 初始化参数
		if($this->initParams($params) == false) {
			return "";
		}
		//3. 调用ads API取得广告
		$adsArr = $this->getAdsContent();
		$this->adsCount = count($adsArr);
		//4. 高亮显示
		if ($this->IsHighlight) {
			$adsArr = $this->highlightAds($adsArr);
		}
		//5. 按$this->splitCountArr分隔广告
		$adsGroupArr = $this->splitAds($adsArr);
		return $adsGroupArr;
	}
	
	/**
	 * 分隔广告数组 根据参数splitCountArr和isRepeatArr
	 * @param array $adsArr
	 * @return array 
	 */
	protected function splitAds(&$adsArr) {
		if (!is_array($adsArr)) {
			return false;
		}
		$totalCount = count($adsArr);
		$startPostion = 0;
		$newAdsArr = array();
		for ($loop = 0; $loop < count($this->splitCountArr); $loop++) {
			if ($loop != 0) {
				if ($this->isRepeatArr[$loop] === true) {	//重复显示
					if ($totalCount < $startPostion) { //数量不够需要添补
						$newAdsArr[$loop] = array_slice($adsArr, 0, $this->splitCountArr[$loop]);
					}
					else {//数量充足, 正常显示
						$newAdsArr[$loop] = array_slice($adsArr, $startPostion, $this->splitCountArr[$loop]);
					}
				}
				else if ($totalCount > $startPostion) { //不需要重复显示
					$newAdsArr[$loop] = array_slice($adsArr, $startPostion, $this->splitCountArr[$loop]);
				}
			}
			else {
				$newAdsArr[$loop] = array_slice($adsArr, $startPostion, $this->splitCountArr[$loop]);
			}
			$startPostion += $this->splitCountArr[$loop];
		}
		return $newAdsArr;
	}
	

	/**
	 * 返回广告数量
	 * @return int
	 */
	public function getAdsCnt() {
		return $this->adsCount;
	}
	
	/**
	 * 高亮显示
	 * @param array $adsData 广告数组
	 * @return array
	 */
	protected function highlightAds(&$adsData) {
		if($adsData == null) {
			return $adsData;
		}
		$searchWords = array('<b>', '</b>');
		$replaceWords = array('<strong>', '</strong>');
		//end modify.
		for($i=0; $i<count($adsData); $i++) {
			if(!empty($adsData[$i]['LINE1'])) {
				$adsData[$i]['LINE1'] = str_ireplace(
					$searchWords, $replaceWords, $adsData[$i]['LINE1']);
			}
			if(!empty($adsData[$i]['LINE2'])) {
				$adsData[$i]['LINE2'] = str_ireplace(
					$searchWords, $replaceWords, $adsData[$i]['LINE2']);
			}
			if(!empty($adsData[$i]['LINE3'])) {
				$adsData[$i]['LINE3'] = str_ireplace(
					$searchWords, $replaceWords, $adsData[$i]['LINE3']);
			}
		}
		return $adsData;
	}
}

