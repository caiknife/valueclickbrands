<?php
/*
 * Created on 2009-07-13
 * class.AdWordsDao.php
 * -------------------------
 * 获取不同的广告
 *
 * @author Thomas fu
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.AdWordsDao.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com/
 */

class AdWordsDao {
    
    /*得到baidu广告*/
    static public function getBaiduAdsScript($baiduParams){}
  
    
    /*请求google广告，当google 广告不足时，用google补*/
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
        //如果Google广告不够用BAIDU广告补
        if (!$disableBaidu && $baiduParams['splitCountArr']) {
            foreach ($baiduParams['splitCountArr'] as $splitKey => $splitCount) {
                if ($splitCount < 0) {
                    $tmpGoogelRequestCount += $adsParams['splitCountArr'][$splitKey];
                    $tmpPositionCount = $totalCount - $tmpGoogelRequestCount;
                    if ($tmpPositionCount >= 0) {//不需要补
                        $baiduParams['splitCountArr'][$splitKey] = 0;
                    } else if (abs($tmpPositionCount) >= abs($splitCount)) {//最多补$splitCount
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