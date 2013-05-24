<?php
/*
 * Created on 2009-07-13
 * class.AdWordsDao.php
 * -------------------------
 * ��ȡ��ͬ�Ĺ��
 *
 * @author Thomas fu
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.AdWordsDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class AdWordsDao {
    
    /*�õ�baidu���*/
    static public function getBaiduAdsScript($baiduParams){}
  
    
    /*����google��棬��google ��治��ʱ����google��*/
    static public function getAdsScript($adsParams, $baiduParams = array()) {
        $adsContent = array();
        if ((defined("__ADS_BAIDU_DISABLE") && __ADS_BAIDU_DISABLE === true) && $baiduParams['splitCountArr']) {
            foreach ($baiduParams['splitCountArr'] as $splitCount) {
                if ($splitCount > 0) {
                    $adsParams['splitCountArr'][] = $splitCount;
                }
            }
            $disableBaidu = true;
        }

        $ads = new GoogleAds();
        $adsContent['googleAds'] = $ads->getAdsScript($adsParams);
        $totalCount = $ads->getAdsCnt();

        $requestCount = array_sum($adsParams['splitCountArr']);
        //���Google��治����BAIDU��油
        if (!$disableBaidu && $baiduParams['splitCountArr']) {
            foreach ($baiduParams['splitCountArr'] as $splitKey => $splitCount) {
                if ($splitCount < 0) {
                    $tmpGoogelRequestCount += $adsParams['splitCountArr'][$splitKey];
                    $tmpPositionCount = $totalCount - $tmpGoogelRequestCount;
                    if ($tmpPositionCount >= 0) {//����Ҫ��
                        $baiduParams['splitCountArr'][$splitKey] = 0;
                    } else if (abs($tmpPositionCount) >= abs($splitCount)) {//��ಹ$splitCount
                        $baiduParams['splitCountArr'][$splitKey] = abs($splitCount);
                    } else {
                        $baiduParams['splitCountArr'][$splitKey] = abs($tmpPositionCount);
                    }   
                }
            }
        }
        if (!$disableBaidu && $baiduParams['splitCountArr'] 
            && array_sum($baiduParams['splitCountArr']) > 0) {
            $baiduParams = $adsParams ? array_merge($adsParams, $baiduParams) : $baiduParams;
            $adsBaidu = new BaiduAds();
            $adsContent['baiduAds'] = $adsBaidu->getAdsScript($baiduParams);
            $adsBaiduCnt = $adsBaidu->getAdsCnt();
            $totalCount += $adsBaiduCnt;
        }
        return $adsContent;
    }
}

?>