<?php
/*
 * package_name : ImportMerchantCouponProcess.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: ImportMerchantCouponProcess.php,v 1.1 2013/04/15 10:57:14 rock Exp $
 */
namespace BackEnd\Process\ImportCoupon;

use Custom\Util\Utilities;
use BackEnd\Model;
use BackEnd\Process\Exception\CouponProcessException;

class ImportMerchantCouponProcess extends ImportCouponProcess
{
    /**
     * 联盟ID
     * @var int
     */
    protected $merid = null;
    
    /**
     * @param int $affiliateID
     */
    public function __construct($merid) 
    {
        if (empty($merid)) {
            throw new CouponProcessException('merid is empty');
        }
        $this->merid = $merid * 1;
    }
    
    /**
     * 执行从UserDataFeed表 到 Coupon 表
     */
    public function execute() 
    {
        $startTime = microtime(true);
        $executeDataFeedCnt = 0;
        
        //获取联盟信息
        $merchantInfo = $this->merchantTable->getInfoById($this->merid);
        do {
            //批量获取UserDataFeed数据
            $dataFeedList = $this->userDataFeedTable->getListByMerID($this->merid, self::FETCH_MAX_ROWS);
            if (count($dataFeedList) <= 0) {
                break;
            }
            foreach ($dataFeedList as $dataFeedRow) {
                //临时设置
//                 if (!empty($dataFeedRow['CouponStartDate'])) {
//                     $dataFeedRow['CouponStartDate'] = date('Y-m-d H:i:s', strtotime($dataFeedRow['CouponStartDate']));
//                 }
//                 if (empty($dataFeedRow['CouponStartDate']) || '1970-01-01 00:00:00' == $dataFeedRow['CouponStartDate']) {
//                     $dataFeedRow['CouponStartDate'] = Utilities::getDateTime('Y-m-d') . ' 00:00:00';
//                 }
//                 if (!empty($dataFeedRow['CouponEndDate'])) {
//                     $dataFeedRow['CouponEndDate'] = date('Y-m-d H:i:s', strtotime($dataFeedRow['CouponEndDate']));
//                 }
//                 if (empty($dataFeedRow['CouponEndDate']) || '1970-01-01 00:00:00' == $dataFeedRow['CouponEndDate']) {
//                     $dataFeedRow['CouponEndDate'] = Utilities::getDateTime('Y-m-d', time() + 3600 * 24 * 30) . ' 00:00:00';
//                 }
                
                //临时设置结束
                
                $executeDataFeedCnt++;
                
                //0. 数据检测
                if ($this->checkData($dataFeedRow) === false) {
                    $this->userDataFeedTable->updateActiveByID($dataFeedRow['ID'], 'NO');
                    $this->statInfo['DataCheckErrorCnt']++;
                    continue;
                }
                
                //1. 再次判断此条记录是否已经存在
                $dataFeedRow['SKU'] = md5(
                    $dataFeedRow['MerchantID']  . $dataFeedRow['CouponName'] . 
                    $dataFeedRow['CouponDescription'] . $dataFeedRow['CouponUrl'] . $dataFeedRow['CouponStartDate'] .
                    $dataFeedRow['CouponEndDate']
                );
                //把CouponCode转换为数组
                $couponCodeList = $this->getCouponCodeList($dataFeedRow['CouponCode']);
                
                $couponInfo = $this->couponTable->getInfoBySku($dataFeedRow['SKU']);
                if (!empty($couponInfo)) {
                    //1.1 如果是Coupon插入CouponCode
                    if ($dataFeedRow['CouponType'] == 'COUPON') {
                        //值插入CouponCode
                        $newCouponCnt = 0;
                        foreach ($couponCodeList as $couponCode => $couponPass) {
                            if ($this->couponCodeTable->isExistCouponCode(
                                    $couponInfo['CouponID'],
                                    $couponCode,
                                    $couponPass) === false
                            ) {
                                $this->couponCodeTable->insert($couponInfo['CouponID'], $couponCode, $couponPass);
                                $newCouponCnt++;
                                $this->statInfo['NewCouponCodeCount']++;
                            } else {
                                $this->statInfo['ExistCouponCodeCount']++;
                            }
                        }
                        //插入couponExtra扩展表，统计信息
                        if ($newCouponCnt > 0) {
                            $this->couponExtraTable->updateCouponCnt($couponInfo['CouponID'], $newCouponCnt);
                        }
                        $this->statInfo['ExistCouponCount']++;
                    } else {
                        //1.2 存在的DISCOUNT
                        $this->statInfo['ExistDiscountCount']++;
                    }
                    //删除UserDataFeed此条数据
                    $this->userDataFeedTable->deleteByID($dataFeedRow['ID']);
                    continue;
                }
                
                //3.插入新的couponInfo
                $dataFeedRow['SiteID'] = $merchantInfo['SiteID'];
                $couponID = $this->couponTable->insert($dataFeedRow);
                if ($couponID <= 0) {
                    throw new CouponProcessException('can not get CouponID');
                }
                
                //插入操作信息
                $this->couponOperateDetailTable->insert($couponID, 'Auto', 'INSERT');
                
                //4. 插入到搜索表CouponSearch 这里只导入海淘数据（英文），不需要分词
                $dataFeedRow['CouponID'] = $couponID;
                $dataFeedRow['MerchantName'] = $merchantInfo['MerchantName'];
                $dataFeedRow['MerchantNameEN'] = $merchantInfo['MerchantNameEN'];
                
                $this->couponSearchTable->insert($dataFeedRow);
                
                //5.插入Coupon 的CategoryID
                $this->insertCouponCategeory($couponID, $this->merid, $dataFeedRow['CategoryName']);
                
                 //6.如果是couponType是COUPON,
                if ($dataFeedRow['CouponType'] == 'COUPON') {
                    $this->statInfo['NewCouponCount']++;
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
                    }
                } else {
                    //插入couponCode统计信息,方便前台调用
                    $this->couponExtraTable->insertCouponCnt($couponID, 0);
                    $this->statInfo['NewDiscountCount']++;
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
        
        //统计商家MerchantCategory
        $this->syncMerCategory($this->merid);
        $this->statInfo['UserDataRemainCount'] = $this->userDataFeedTable->getListCountByMerID($this->merid);
        $this->statInfo['ImportCouponUseTime'] = round(microtime(true) - $startTime);
    }
}
?>