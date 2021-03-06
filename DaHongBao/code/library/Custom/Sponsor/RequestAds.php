<?php
/*
 * package_name : class.RequestAds.php
 * ------------------
 * 请求google baidu sogou 等 广告入口
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: RequestAds.php,v 1.2 2013/04/22 10:39:44 rizhang Exp $
 */
namespace Custom\Sponsor;

use Custom\Util\Utilities;
use Custom\Util\TrackingFE;

class RequestAds {
    
    protected static $refKeywords = NULL;     //ref keyword

     /**
      * param $adsParams
      *                 ['google'] = array(
      *                     'splitCount' => array(1, 2, 3),
      *                 );
      *                 ['sogou'] = array(
      *                     'splitCount' => array(-1, -2, -3),
      *                 )
      *                 ['baidu'] = array(
      *                     'splitCount' => array(-1, -2, -3),
      *                 )
      * 如果 splitCount中是负数，表示是补上一个广告位广告，参数顺序就是请求广告优先级顺序
      * param $response 系统response 对象
      */
    public static function getAdsScript($adsParams) {
        //1. SOURCE=YODAO或者当广告被点击次数过多不请求GOOGLE广告, 
        $source = strtoupper(TrackingFE::getSource());
        if (strtoupper(TrackingFE::getSourceGroup()) == 'YODAO' 
            || TrackingFE::isSponsorClickLimit()) {
            $adsParams['google']['DisableRequestAds'] = TRUE;
        }
        //2. 根据一定的条件是否要刷新页面
        if (TrackingFE::isReloadPage()) {
            //$response->setTplValue("reloadPage", true);
            $sessionID = \Tracking_Session::getInstance()->getSessionId();
            //$response->setTplValue("mm_sid", $sessionID);
        }
        //3. 先初始化每个广告对象
        $beforeParams = array();
        $AdsContent = array();
        $totalCount = 0;
        foreach ($adsParams as $adsName => $params) {
            $beforeParams = $params = self::getParams($params, $beforeParams);
            if (array_sum($params['splitCountArr']) > 0) {
                if ($params['DisableRequestAds'] !== TRUE) {
                    $className = ucfirst($adsName).'Ads';
                    $className = "Custom\\Sponsor\\Ads\\".$className;
                    $adsObj = new $className();
                    $adsContent = $adsObj->getAdsScript($params);
                    $beforeParams['ResultCnt'] = $adsObj->getAdsCnt();
                }  else{
                    $beforeParams['ResultCnt'] = 0;
                }
                $totalCount += $beforeParams['ResultCnt'];
                
                //3.1 只有google广告，有结果返回且是ref keyword设置这个ref keyword 广告数量
                if ($adsName == 'google' 
                    && self::$refKeywords 
                    && $params['DisableRequestAds'] !== TRUE 
                    && $adsObj->adsOvertime === FALSE) {
                    Utilities::setRefKeywordForAds(self::$refKeywords, $beforeParams['ResultCnt']);
                }

                //3.2 临时性需求，SEM + SEO 增加输出编码后的Keyword
                if ($adsName == 'baidu'){
                   $semCodeKeyword = Utilities::encode($params['keyword']).
                        "||".array_sum($params['splitCountArr']);
                    //$response->setTplValue("__CodeKeyword", $semCodeKeyword);
                }
                //3.3 临时性需求当 SOURCE 包含SOGOU_2CN且结果返回小于10条，需要二次请求,只请求，不展示
                if ($adsName == 'sogou' 
                    //&& $response->getTplValue('notRequestSogouAds') !== true 
                    && strtoupper(TrackingFE::getSourceGroup()) == 'SOGOU' 
                    && $beforeParams['ResultCnt'] < 10) {
                    $sogouSecondRequestAdsParams['sogou'] = $beforeParams;
                    //$response->setTplValue('notRequestSogouAds', true);
                    $sogouSecondRequestAdsParams['sogou']['adsNeedJS'] = false;
                    $sogouSecondRequestAdsParams['sogou']['notRuturnResult'] = true;
                    self::getAdsScript($sogouSecondRequestAdsParams);
                }
                //3.4 输出结果
                /*
                $response->setTplValue("Ads{$adsName}Cnt", $beforeParams['ResultCnt']);
                $response->setTplValue("Ads{$adsName}Content", $adsContent);
                */
                $AdsContent[$adsName]['AdsCnt'] = $beforeParams['ResultCnt'];
                $AdsContent[$adsName]['AdsContent'] = $adsContent;
            }
        }
        
        //4. 输出广告总的广告条数
        //$response->setTplValue("AdsCnt", $totalCount);
        return $AdsContent;
    }
    
    /**
     * 根据上一次请求广告结果，格式本次请求广告参数
     * @param  $params        array 本次请求广告参数
     * @param  $beforeParams  array 上次请求广告参数
     * @return array 格式化之后的参数
     */
    public static function getParams($params, $beforeParams = array()) {
        //1. 是否是Referer keywords 请求广告
        if (empty(self::$refKeywords) && ($keywords = Utilities::getRefKeywordForAds())) {
            $params['keyword'] = self::$refKeywords = $keywords;
        }
        
        //2. 默认不需要二次请求， 如果强制二次请求可以在具体Action指定
        if (!isset($params['adsNeedJS'])) {
            $params['adsNeedJS'] = false;
        }
        
        //3. 不需要合并参数
        if (empty($beforeParams)) {
            return $params;
        }
        
        //4. 根据上一次请求广告结果，格式本次请求广告数量参数
        foreach ($params['splitCountArr'] as $splitKey => $splitCount) {
            if ($splitCount > 0) {
                continue;
            }
            $tmpRequestCount += $beforeParams['splitCountArr'][$splitKey];
            $tmpPositionCount = $beforeParams['ResultCnt'] - $tmpRequestCount;
            if ($tmpPositionCount >= 0) { //不需要补
                $params['splitCountArr'][$splitKey] = 0;
            } else if (abs($tmpPositionCount) >= abs($splitCount)) { //最多补$splitCount
                $params['splitCountArr'][$splitKey] = abs($splitCount);
            } else {
                $params['splitCountArr'][$splitKey] = abs($tmpPositionCount);
            }
        }
        unset($beforeParams['ResultCnt']);
        unset($beforeParams['DisableRequestAds']);
        $params = array_merge($beforeParams, $params);
        return $params;
    }
 }
?>