<?php
/*
 * package_name : ImportAffiliateCouponProcess.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: ImportAffiliateCouponProcess.php,v 1.8 2013/05/21 06:16:33 thomas_fu Exp $
 */
namespace BackEnd\Process\ImportCoupon;

use BackEnd\Process\Process;
use Custom\Util\Utilities;
use BackEnd\Model;
use BackEnd\Process\Exception\CouponProcessException;

class ImportAffiliateCouponProcess extends ImportCouponProcess
{
    /**
     * 获取dataFeed最大的条数
     */
    const FETCH_MAX_ROWS = 100;
    
    /**
     * 联盟IDisExistCouponCode
     * @var int
     */
    protected $affiliateID = null;
    
    protected $merCateList = array();
    
    
    /**
     * @param int $affiliateID
     */
    public function __construct($affiliateID) 
    {
        if (empty($affiliateID)) {
            throw new CouponProcessException('affiliateID is empty');
        }
        $this->affiliateID = $affiliateID * 1;
    }
    
    /**
     * 执行从UserDataFeed表 到 Coupon 表
     */
    public function execute() 
    {
        $startTime = microtime(true);
        $executeDataFeedCnt = 0;
        $updateCouponList = array();
        
        //获取联盟信息
        $affiliateInfo = $this->affiliateTable->getInfoById($this->affiliateID);
        $onLineCouponCnt = $this->couponTable->getListCountByAffiliateID($this->affiliateID);
        do {
            //批量获取UserDataFeed数据
            $dataFeedList = $this->userDataFeedTable->getListByAffID($this->affiliateID, self::FETCH_MAX_ROWS);
            if (count($dataFeedList) <= 0) {
                break;
            }
            foreach ($dataFeedList as $dataFeedRow) {
                $executeDataFeedCnt++;
                
                //0. 数据检测
                if ($this->checkData($dataFeedRow) === false) {
                    $this->userDataFeedTable->updateActiveByID($dataFeedRow['ID'], 'NO');
                    $this->statInfo['DataCheckErrorCnt']++;
                    continue;
                }
//                 print_r($dataFeedRow);
                //1. 再次判断此条记录是否已经存在
                $merid = $dataFeedRow['MerchantID'];
                if ($dataFeedRow['CouponType'] == 'DISCOUNT' && $this->isUsSiteID($affiliateInfo['SiteID'])) {
                    $skuCouponUrl = '';
                } else {
                    $skuCouponUrl = $dataFeedRow['CouponUrl'];
                }
                $dataFeedRow['SKU'] = md5(
                    $merid . $dataFeedRow['CouponName'] . $dataFeedRow['CouponDescription'] 
                    . $skuCouponUrl . $dataFeedRow['CouponStartDate'] . $dataFeedRow['CouponEndDate']
                );
                //把CouponCode转换为数组
                $couponCodeList = $this->getCouponCodeList($dataFeedRow['CouponCode']);
                if ($this->isCmusAffiliate($this->affiliateID)) {
                    $couponInfo = $this->couponTable->getInfoByAdsID($dataFeedRow['AdsID'], $this->affiliateID);
                } else {
                    $couponInfo = $this->couponTable->getInfoBySku($dataFeedRow['SKU']);
                }
                if (!empty($couponInfo)) {
                    //1.1 如果是Coupon插入CouponCode
                    if ($dataFeedRow['CouponType'] == 'COUPON') {
                        if ($this->isCmusAffiliate($this->affiliateID)) {
                            //need update field
                            $needUpdateField = $this->getNeedUpdateArr($couponInfo, $dataFeedRow);
                            if (!empty($needUpdateField)) {
                                $this->couponTable->update($needUpdateField, array('CouponID' => $couponInfo['CouponID']));
                            }
                            $updateCouponList[] = $couponInfo['CouponID'];
                        }
                        //值插入CouponCode
                        $newCouponCnt = 0;
                        foreach ($couponCodeList as $couponCode => $couponPass) {
                            //已经存在的couponCode
                            if ($this->couponCodeTable->isExistCouponCode($couponInfo['CouponID'], $couponCode) === true) {
                                $this->statInfo['ExistCouponCodeCount']++;
                                $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['ExistCouponCodeCount']++;
                            } else {
                                //此coupon已经存在couponcode， 但couponCode已经变化。
                                if ($this->couponCodeTable->isExistCouponCode($couponInfo['CouponID']) === true) {
                                    $this->couponCodeTable->updateCouponCode($couponInfo['CouponID'], $couponCode, $couponPass);
                                } else {
                                    //需要插入couponCode
                                    $this->couponCodeTable->insertCouponCode($couponInfo['CouponID'], $couponCode, $couponPass);
                                    $newCouponCnt++;
                                    $this->statInfo['NewCouponCodeCount']++;
                                    $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['NewCouponCodeCount']++;
                                }
                            }
                            //海淘数据，每一条coupon只有一个couponCode
                            break;
                        }
                        
                        //插入couponExtra扩展表，统计信息
                        if ($newCouponCnt > 0) {
                            $this->couponExtraTable->updateCouponCnt($couponInfo['CouponID'], $newCouponCnt);
                        }
                        $this->statInfo['ExistCouponCount']++;
                        $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['ExistCouponCount']++;
                    } else {
                        //1.2 存在的DISCOUNT
                        $this->statInfo['ExistDiscountCount']++;
                        $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['ExistDiscountCount']++;
                    }
                    //删除UserDataFeed此条数据
                    $this->userDataFeedTable->deleteByID($dataFeedRow['ID']);
                    continue;
                }
                if (!isset($this->merchantList[$merid])) {
                    $this->merchantList[$merid] = $this->merchantTable->getNameByMerID($merid);
                }
                //2 CMUS所有数据都以COUPON上线，所有联盟的数据都以DISCOUNT上线，除了DISCOUNT包含有CMUS的CouponCode
                if ($dataFeedRow['CouponType'] == 'DISCOUNT'
                    && $this->getUsSiteID() == $affiliateInfo['SiteID']
                    && $this->isExistDiscountFromCmusCoupon($dataFeedRow) === true) {
                    $this->statInfo['CouponHasDiscountCount']++;
                    $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['CouponHasDiscountCount']++;
                    //删除UserDataFeed此条数据
                    $this->userDataFeedTable->deleteByID($dataFeedRow['ID']);
                    continue;
                }
                
                //3.插入新的couponInfo
                $dataFeedRow['SiteID'] = $affiliateInfo['SiteID'];
                $couponID = $this->couponTable->insert($dataFeedRow);
                if ($couponID <= 0) {
                    throw new CouponProcessException('can not get CouponID');
                }
                if ($this->isCmusAffiliate($this->affiliateID)) {
                    $updateCouponList[] = $couponID;
                }
                
                //插入操作信息
                $this->couponOperateDetailTable->insert($couponID, 'Auto', 'INSERT');
                
                //4. 插入到搜索表CouponSearch 这里只导入海淘数据（英文），不需要分词
                $dataFeedRow['CouponID'] = $couponID;
                $dataFeedRow['MerchantName'] = $this->merchantList[$merid]['MerchantName'];
                $dataFeedRow['MerchantNameEN'] = $this->merchantList[$merid]['MerchantNameEN'];
                $dataFeedRow['MerchantAliasName'] = $this->merchantList[$merid]['MerchantAliasName'];
                $this->couponSearchTable->insert($dataFeedRow);
//                WordSplitter::instance()->execute($prodInfo['Name']);
                
                //5.插入Coupon 的CategoryID
                $this->insertCouponCategeory($couponID, $merid, $dataFeedRow['CategoryName']);
                
                //6.如果是couponType是COUPON,
                if ($dataFeedRow['CouponType'] == 'COUPON') {
                    $this->statInfo['NewCouponCount']++;
                    $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['NewCouponCount']++;
                    //插入couponCode
                    if (!empty($dataFeedRow['CouponCode'])) {
                        $couponCodeCnt = 0;    //CouponCode数量
                        foreach ($couponCodeList as $couponCode => $couponPass) {
                            $this->couponCodeTable->insertCouponCode($couponID, $couponCode, $couponPass);
                            $couponCodeCnt++;
                        }
                        //插入couponCode统计信息,方便前台调用
                        $this->couponExtraTable->insertCouponCnt($couponID, $couponCodeCnt);
                        $this->statInfo['NewCouponCodeCount']++;
                        $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['NewCouponCodeCount']++;
                    }
                } else {
                    //插入couponCode统计信息,方便前台调用
                    $this->couponExtraTable->insertCouponCnt($couponID, $couponCodeCnt);
                    $this->statInfo['NewDiscountCount']++;
                    $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['NewDiscountCount']++;
                }
                
                //7. 插入成功删除临时表数据
               $this->userDataFeedTable->deleteByID($dataFeedRow['ID']);
                
                if ($executeDataFeedCnt % 100 == 0) {
                    Utilities::println(
                        'ExecuteFeedCnt = ' . $executeDataFeedCnt . ', UseTime = ' . round(microtime(true) - $startTime)
                    );
                }
            }
            
        } while (count($dataFeedList) >= self::FETCH_MAX_ROWS);
        //需要下线产品
        if (!empty($updateCouponList)) {
            $updateCouponCnt = count($updateCouponList);
            //下线率超过20%的不进行下线操作
            if ($onLineCouponCnt - $updateCouponCnt > 0 
                && round(($onLineCouponCnt - $updateCouponCnt) / $onLineCouponCnt, 1) > 0.2
            ) {
                Utilities::println('need Offline Coupon more than 20%. needOffCoupon = '. ($onLineCouponCnt - $updateCouponCnt));
            } else {
                //@todo has bug when CouponID Cnt more than 1k
                Utilities::println('need Offine Coupon Count = ' . $updateCouponCnt);
                $this->couponTable->updateCouponOffLine($updateCouponList, $this->affiliateID);
            }
        }
        
        //统计商家MerchantCategory
        $this->syncMerListCategory();
        $this->statInfo['UserDataRemainCount'] = $this->userDataFeedTable->getListCountByAffID($this->affiliateID);
        $this->setMerUserDataRemainCount();
        $this->statInfo['ImportCouponUseTime'] = round(microtime(true) - $startTime, 2);
    }
    
    /**
     * 统计这个商家每个分类的优惠卷
     * @todo: 现在是直接获取所有商家，等以后后台自动更新脚本去掉此步骤
     */
    protected function syncMerListCategory() 
    {
        if (empty($this->statInfo['Merchant'])) {
            return false;
        }
        
        foreach ($this->statInfo['Merchant'] as $merid => $merInfo) {
            $this->syncMerCategory($merid);
        }
    }
    
    /**
     * 设置UserDataRemainCount统计信息
     * @return boolean
     */
    protected function setMerUserDataRemainCount()
    {
        if (empty($this->statInfo['Merchant'])) {
            return false;
        }
    
        foreach ($this->statInfo['Merchant'] as $merid => $merInfo) {
            $this->statInfo['Merchant'][$merid]['UserDataRemainCount'] = 
                $this->userDataFeedTable->getListCountByMerID($merid);
        }
    }
    
    /**
     * 得到需要更新数据
     * @param array $couponInfo
     * @param array $dataFeedRow
     * @return array
     */
    protected function getNeedUpdateArr($oldCouponInfo, $newCouponInfo) 
    {
        if (empty($oldCouponInfo) || empty($newCouponInfo)) {
            return array();
        }
        $needUpdateArr = array();
        if ($oldCouponInfo['CouponName'] != $newCouponInfo['CouponName']) {
            $needUpdateArr['CouponName'] = $newCouponInfo['CouponName'];
        }
        if ($oldCouponInfo['CouponDescription'] != $newCouponInfo['CouponDescription']) {
            $needUpdateArr['CouponDescription'] = $newCouponInfo['CouponDescription'];
        }
        if ($oldCouponInfo['CouponStartDate'] != $newCouponInfo['CouponStartDate']) {
            $needUpdateArr['CouponStartDate'] = $newCouponInfo['CouponStartDate'];
        }
        if ($oldCouponInfo['CouponEndDate'] != $newCouponInfo['CouponEndDate']) {
            $needUpdateArr['CouponEndDate'] = $newCouponInfo['CouponEndDate'];
        }
        if ($oldCouponInfo['IsActive'] == 'NO') {
            $needUpdateArr['IsActive'] = 'YES';
        }
        return $needUpdateArr;
    }
}
?>