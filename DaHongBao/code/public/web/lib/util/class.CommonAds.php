<?php
/*
 * Created on 2010-7-8
 * 
 * �õ�SPL����
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
	protected $isRepeatArr = array();	//����λ�ã��Ƿ���Ҫ�ظ���ʾ 
	protected $splitCountArr = array();	//tplģ����ʾλ�ü�ÿ��λ�õĹ����Ŀ
	protected $adsNeedJS = true;	//���޹���ǣ��Ƿ�Ҫ������JS����
	protected $alreadyInJS = false;	//��ǰ״̬�� true��ʾ��ǰ����JS���õ�
	protected $adsPositionCount = 0;
	protected $chid = NULL;
	protected $timeout = 2;	//��ʱʱ��Ϊ2��
	protected $oldParamArr = array();	//ԭʼ����
	protected $adsCount = 0;
	protected $IsHighlight = '';
	protected $IsDisplayKeywordArr = array();	//����λ���Ƿ���Ҫ��ͷ����ʾ�����ؼ���
	protected $requestAdsCount = 0;	//�����������
	public $adsOvertime = false;	//�Ƿ�ʱ
	private static $pageTypeID = -1;
	protected static $googleAdtest = NULL;
	
		
	public function setGoogleAdtest($adtest) {
		self::$googleAdtest = $adtest == 'on' ? 'on' : 'off';
	}
	
	/**
	 * ��������
	 * @param array $paramArr ��ά���� array('splitCountArr' => array(3, 3, 6), 'keyword' => $keyword, 
	 * 										'keyword2' => $keyword2, isRepeatArr = array(false, false),
	 * 										'adsNeedJS' => true, chid => $chid
	 * 										)
	 * @return boolean 
	 */
	public function initParams($paramArr) {
		//0. �������
		if(empty($paramArr['keyword'])
			|| is_array($paramArr['splitCountArr']) == false
			|| count($paramArr['splitCountArr']) == 0) {
			return false;
		}
		//1. �����ؼ���
		$this->keyword = $paramArr['keyword'];
		//2. �ָ���漰ÿ��λ�õĹ����
		$this->splitCountArr = $paramArr['splitCountArr'];
		$this->adsPositionCount = count($this->splitCountArr);
		//3. �����ظ���λ�á����ٲ���������bool or string���ͣ��Զ�����������λ��
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
		//4. �޹��ʱ���Ƿ���ҪJS�ٴ�����, Ĭ��Ϊ����Ҫ��������
		if (isset($paramArr['adsNeedJS'])) {
			$this->adsNeedJS = $paramArr['adsNeedJS'];
		} else {
			$this->adsNeedJS = false;
		}
		
		//6. �Ƿ���Ҫת�� <b> �� <strong>
		if (isset($paramArr['IsHighlight'])) {
			$this->IsHighlight = $paramArr['IsHighlight'];
		} else {
			$this->IsHighlight = true;
		}
		//7. ��ʱ��������
		if(isset($paramArr['timeout']) && $paramArr['timeout'] > 0) {
			$this->timeout = $paramArr['timeout'];
		} else {
			$this->timeout = 2; //Ĭ��2��
		}
		//8. �Ƿ���Ҫ��ͷ����ʾ �����ؼ���
		if (!empty($paramArr['IsDisplayKeywordArr']) && is_array($paramArr['IsDisplayKeywordArr'])) {
			$this->IsDisplayKeywordArr = $paramArr['IsDisplayKeywordArr'];
		} else {
			$this->IsDisplayKeywordArr = array();
		}
		//9. ����
		$this->chid = $paramArr['chid'];
		
		foreach ($this->splitCountArr as $splitCount) {
			$this->requestAdsCount += $splitCount;
		}
		return true;
	}
	
	/**
	 * �õ�SPL����
	 */
	public function getAdsScript($params) {
		$adsContent = "";
		$startTime = time();
		//1. ���������ж�
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
		//2. ��ʼ������
		if($this->initParams($params) == false) {
			return "";
		}
		//3. ����ads APIȡ�ù��
		$adsArr = $this->getAdsContent();
		$this->adsCount = count($adsArr);
		//4. ������ʾ
		if ($this->IsHighlight) {
			$adsArr = $this->highlightAds($adsArr);
		}
		//5. ��$this->splitCountArr�ָ����
		$adsGroupArr = $this->splitAds($adsArr);
		return $adsGroupArr;
	}
	
	/**
	 * �ָ�������� ���ݲ���splitCountArr��isRepeatArr
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
				if ($this->isRepeatArr[$loop] === true) {	//�ظ���ʾ
					if ($totalCount < $startPostion) { //����������Ҫ��
						$newAdsArr[$loop] = array_slice($adsArr, 0, $this->splitCountArr[$loop]);
					}
					else {//��������, ������ʾ
						$newAdsArr[$loop] = array_slice($adsArr, $startPostion, $this->splitCountArr[$loop]);
					}
				}
				else if ($totalCount > $startPostion) { //����Ҫ�ظ���ʾ
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
	 * ���ع������
	 * @return int
	 */
	public function getAdsCnt() {
		return $this->adsCount;
	}
	
	/**
	 * ������ʾ
	 * @param array $adsData �������
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

