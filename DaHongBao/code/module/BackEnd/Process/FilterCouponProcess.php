<?php
/*
 * package_name : FilterCouponProcess.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: FilterCouponProcess.php,v 1.5 2013/05/09 06:45:57 thomas_fu Exp $
 */
namespace BackEnd\process;

use Custom\Util\Utilities;

class FilterCouponProcess extends Process 
{
    //错误类型
    const FILTER_ERROR_DB = 'DbError';
    const FILTER_ERROR_MERCHANT = 'MechantError';
    const FILTER_ERROR_COMMON = 'CommonError';
    
    /**
     * @var 返回错误信息
     */
    protected $statInfo = array(
        'ExecuteCount' => 0,
        self::FILTER_ERROR_DB => 0,
        self::FILTER_ERROR_MERCHANT => 0,
        self::FILTER_ERROR_COMMON => 0
    );
    
    /**
     * @var 用于特殊商家清理
     */
    protected $merInstance = null;
    
    
    /**
     * 对 coupon 过滤等处理
     * @coupon object, 
     * @return coupon, false when filter failed. 
     */
    public function filter($couponRow) 
    {
        $this->statInfo['ExecuteCount']++;
        if (($couponRow = $this->filterFromDb($couponRow)) === false) {
            //1. 调用db清理
            $this->statInfo[self::FILTER_ERROR_DB]++;
        } else if (($couponRow = $this->filterFromMerchant($couponRow)) === false) {
            //2. 调用特殊商家清理逻辑
            $this->statInfo[self::FILTER_ERROR_MERCHANT]++;
        } else if (($couponRow = $this->filterFromCommon($couponRow)) === false) {
            //3. 共通清理
            $this->statInfo[self::FILTER_ERROR_COMMON]++;
        } else {
            return $couponRow;
        }
        return false;
    }
    
    /**
     * 来自filter页面上的清理逻辑
     */
    public function filterFromDb($couponRow) 
    {
        return $couponRow;
    }
    
    /**
     *  来自商家特殊的需求，需要特殊的配置文件
     */
    public function filterFromMerchant($couponRow) 
    {
        if (!isset($couponRow['MerchantID'])) {
            return $couponRow;
        }
        $merid = $couponRow['MerchantID'];
        if (!$this->merInstance[$merid]) { 
            $className = 'BackEnd\Process\MerchantFilter\Merchant'.$merid.'Filter';
            if (file_exists($className)) {
                $this->merInstance[$merid] = new $className();
            } else {
                $this->merInstance[$merid] = false;
            }
        }
        
        if ($this->merInstance[$merid] instanceof MerchantFilter\MerchantFilterInterface) {
            return $this->merInstance[$merid]->filter($couponRow);
        } 
        return $couponRow;
    }
    
    /**
     * 来自共同清理逻辑
     */
    public function filterFromCommon($couponRow) 
    {
        //us couponName不能为空
        if (empty($couponRow['CouponName']) && $couponRow['AffiliateID'] > 0) {
            return false;
        }
        //need CouponUrl
        if (empty($couponRow['CouponUrl'])) {
            return false;
        }
        
        $todayDate = date('Y-m-d', time()) . ' 00:00:00';
        $couponRow['CouponStartDate'] = preg_replace('/\s+/', " ", $couponRow['CouponStartDate']);
        //联盟COUPON需要开始时间
        if ($couponRow['AffiliateID'] > 0) {
            if (empty($couponRow['CouponStartDate'])) {
                return false;
            }  else if ($this->isUsSiteID($couponRow['SiteID'])) { //海淘数据
                $couponRow['CouponStartDate'] = date('Y-m-d H:i:s', strtotime($couponRow['CouponStartDate']));
                if (substr($couponRow['CouponStartDate'], -8) == '00:00:00') {
                    $couponRow['CouponStartDate'] = str_replace('00:00:00', '23:59:59', $couponRow['CouponStartDate']);
                }
            } else {
                $couponRow['CouponStartDate'] = Utilities::getDateTime('Y-m-d H:i:s', strtotime($couponRow['CouponStartDate']));
            }
        } else {
            //国内coupon时间，基本是原样显示。需要以后cleantool工具支持
            if (preg_match('/^([0-9]+)月([0-9]+)日$/i', $couponRow['CouponStartDate'], $match)) {
                $couponRow['CouponStartDate'] = Utilities::getDateTime('Y') . '-' . $match[1] . '-' . $match[2];
                $couponRow['CouponStartDate'] = Utilities::getDateTime('Y-m-d H:i:s', strtotime($couponRow['CouponStartDate']));
            }
        }
        
        $couponRow['CouponEndDate'] = preg_replace('/\s+/', " ", $couponRow['CouponEndDate']);
        //联盟coupon需要结束时间
        if ($couponRow['AffiliateID'] > 0) {
            if (empty($couponRow['CouponEndDate'])) {
                return false;
            } else if ($couponRow['CouponEndDate'] == '3333-03-03 00:00:00') {
                $couponRow['CouponEndDate'] = $couponRow['CouponEndDate'];
            } else if ($this->isUsSiteID($couponRow['SiteID'])) {
                $couponRow['CouponEndDate'] = date('Y-m-d H:i:s', strtotime($couponRow['CouponEndDate']));
                if (substr($couponRow['CouponEndDate'], -8) == '00:00:00') {
                    $couponRow['CouponEndDate'] = str_replace('00:00:00', '23:59:59', $couponRow['CouponEndDate']);
                }
            } else {
                $couponRow['CouponEndDate'] = Utilities::getDateTime('Y-m-d H:i:s', strtotime($couponRow['CouponEndDate']));
            }
            if ($couponRow['CouponEndDate'] < $couponRow['CouponStartDate'] || $couponRow['CouponEndDate'] < $todayDate) {
                return false;
            }
        } else {
            //国内时间，基本是原样显示。需要以后cleantool工具支持
            if (preg_match('/^([0-9]+)月([0-9]+)日$/i', $couponRow['CouponEndDate'], $match)) {
                $couponRow['CouponEndDate'] = Utilities::getDateTime('Y') . '-' . $match[1] . '-' . $match[2];
                $couponRow['CouponStartDate'] = Utilities::getDateTime('Y-m-d H:i:s', strtotime($couponRow['CouponStartDate']));
            }
        }
        return $couponRow;
    }
    
    /**
     * 返回
     */
    public function getStatInfo() 
    {
        return $this->statInfo;
    }
}
?>