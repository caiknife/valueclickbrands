<?php
/*
 * package_name : ImportFeedProcess.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: ImportFeedProcess.php,v 1.8 2013/05/20 09:57:01 thomas_fu Exp $
 */
namespace BackEnd\Process\ImportFeed;

use BackEnd\Process\Process as abstractProcess;
use BackEnd\Process\Exception\ImportFeedException;
use BackEnd\Process\Model;
use Custom\Util\Utilities;
use BackEnd\Process\FilterCouponProcess;

class ImportFeedProcess extends abstractProcess
{
    /**
     * Csv 文件句柄
     */     
    protected $fp;
    
    /**
     * 阅读器
     * @var object
     */
    protected $reader;
    
    /**
     * coupon过滤器
     * @var object
     */
    protected $filter;
    
    /**
     * 记录统计信息
     * @var array
     */
    protected $statInfo = array(
        'FileName' => '',
        'FileNameDateTime' => '',
        'InitCnt' => 0,
        'FilterFailedCnt' => 0,
        'NotMerchantFailed' => 0,
        'FailedCnt' => 0,
        'ExistCnt' => 0,
        'SuccessCnt' => 0,
        'Merchant' => array()
    );
    
    /**
     * 主要字段
     * @var array
     */
    protected $couponNeedField = array(
        'MerchantFeedName'  => true,
        'CouponName'       => true,
//        'CouponUrl'         => true,
        'CouponStartDate'   => true,
        'CouponEndDate'     => true,
    );
     
    /**
     * Mapping 对应关系
     * @var array
     */
    protected $couponFieldMapping = array(
        'Url'               => 'CouponUrl',
        'URL'               => 'CouponUrl',
        'Title'             => 'CouponName',
        'TITLE'             => 'CouponName',
        'IMAGE'             => 'CouponImageUrl',
        'ImageUrl'          => 'CouponImageUrl',
        'MerchantName'      => 'MerchantFeedName',
        'MERCHANT'          => 'MerchantFeedName',
        'HomePageUrl'       => 'MerchantHomePage',
        'HOMEPAGE'          => 'MerchantHomePage',
        'CouponCode'        => 'CouponCode',
        'CategoryName'      => 'CategoryName',
        'CATEGORY'          => 'CategoryName',
        'CouponPass'        => 'CouponPass',
        'StartDate'         => 'CouponStartDate',
        'START'             => 'CouponStartDate',
        'END'               => 'CouponEndDate',
        'EndDate'           => 'CouponEndDate',
        'Description'       => 'CouponDescription',
        'DESCRIPTION'       => 'CouponDescription',
        'Rules'             => 'CouponRestriction',
        'Restriction'       => 'CouponRestriction',
        'REDUCTION'         => 'CouponReduction',
        'BRAND'             => 'CouponBrandName',
        'Total'             => 'CouponAmount',
        'TOTAL'             => 'CouponAmount',
        'DisCount'          => 'CouponDiscount',
        'DISCOUNT'          => 'CouponDiscount',
        'Reduction'         => 'CouponReduction',
        'BRAND'             => 'CouponBrandName',
        'AdsID'             => 'AdsID'
    );
    
    public function __construct($fileName) 
    {
        $this->setReader($fileName);
        $this->filter = new FilterCouponProcess();
        $this->statInfo['FileName'] = $fileName;
    }
    
    /**
     * 执行CSV 导入到数据库
     */
    public function execute() 
    {
        $startTime = microtime(true);
        $downloadFeedCnt = 0;
        $merMapping = array();
        //删除已经过期临时数据
        $this->userDataFeedTable->deleteUserData($this->merid, $this->affiliateID);
        
        //读取每一条CSV
        while ($csvArr = $this->reader->streamRead($this->fp)) {
            $downloadFeedCnt++;
            if (empty($csvArr)) {
                continue;
            }
            //获取文件中的第一行
            if ($this->statInfo['InitCnt'] <= 0) {
                $mapping = $this->getFileMapping($csvArr);
                $this->statInfo['InitCnt']++;
                continue;
            }
            $dataFeedRow = $this->formatDataFeed($csvArr, $mapping);
            $dataFeedRow['AffiliateID'] = $this->affiliateID;
            if ($this->affiliateInfo['SiteID']) {
                $dataFeedRow['SiteID'] = $this->affiliateInfo['SiteID'];
            }
            if ($this->merid > 0) {
                $dataFeedRow['MerchantID'] = $this->merid;
            }
            $this->statInfo['InitCnt']++;
            //得到商家ID
            if ($this->affiliateID > 0 && $this->merid <= 0) {
                $md5 = md5($dataFeedRow['MerchantFeedName'], $this->affiliateID);
                if (isset($merMapping[$md5])) {
                    if ($merMapping[$md5] == false) {
                        continue;
                    }
                    $dataFeedRow['MerchantID'] = $merMapping[$md5];
                } else {
                    $dataFeedRow['MerchantID'] = $this->merchantAliasTable->getInfoByName(
                        $dataFeedRow['MerchantFeedName'],
                        $this->affiliateID
                    );
                    //检查商家是否在线
                    if ($dataFeedRow['MerchantID'] > 0) {
                        $merchantInfo = $this->merchantTable->getInfoById($dataFeedRow['MerchantID']);
                        if ($merchantInfo['IsActive'] != 'YES') {
                            $merMapping[$md5] = false;
                            continue;
                        }
                    }
                    $merMapping[$md5] = $dataFeedRow['MerchantID'];
                }
                if (empty($dataFeedRow['MerchantID'])) {
                    $this->statInfo['NotMerchantFailed']++;
                    //海淘数据不导入
                    if ($this->isUsSiteID($this->affiliateInfo['SiteID']) === true) {
                        continue; 
                    } else {
                        //国内联盟设置为空
                        $dataFeedRow['MerchantID'] = '';
                    }
                }
            }
            
            //clean tool 
            if (($dataFeedRow = $this->filter->filter($dataFeedRow)) === false) {
                $this->statInfo['FilterFailedCnt']++;
                continue;
            }
            //过滤文件之后删除SiteID
            unset($dataFeedRow['SiteID']);
            
            //去重表示
            if ($this->merid > 0) {
                $merchantSku = $this->merid;
            } else if (!empty($dataFeedRow['MerchantID'])) {
                $merchantSku = $dataFeedRow['MerchantID'];
            } else {
                $merchantSku = $dataFeedRow['MerchantFeedName'];
            }
            //CMUS所有数据都作为COUPON上线
            if ($this->isCmusAffiliate($this->affiliateID) === true) {
                $dataFeedRow['CouponType'] = 'COUPON';
                $dataFeedRow['IsFromCmus'] = 'YES';
            } else if (!empty($dataFeedRow['CouponCode'])) {
                //其它联盟COUPON 导入为不激活状态 =>方便Editor查看
                if ($this->affiliateID  > 0 && $this->isUsSiteID($this->affiliateInfo['SiteID'])) {
                    $dataFeedRow['IsActive'] = 'NO';
                }
                $dataFeedRow['CouponType'] = 'COUPON';
            } else {
                $dataFeedRow['CouponType'] = 'DISCOUNT';
            }
            //海淘折扣信息去重标准不需要CouponUrl
            if ($dataFeedRow['CouponType'] == 'DISCOUNT' && $this->isUsSiteID($this->affiliateInfo['SiteID'])) {
                $skuCouponUrl = '';
            } else {
                $skuCouponUrl = $dataFeedRow['CouponUrl'];
            }
            $dataFeedRowSku = md5(
                $merchantSku . $dataFeedRow['CouponName'] . 
                $dataFeedRow['CouponDescription'] . $skuCouponUrl . 
                $dataFeedRow['CouponStartDate'] . $dataFeedRow['CouponEndDate'] . $dataFeedRow['CategoryName']
            );
            if (($userDataFeedInfo = $this->userDataFeedTable->isExistDataFeedBySku($dataFeedRowSku)) === false) {
                //插入到数据库
                $dataFeedRow['SKU'] = $dataFeedRowSku;
                //把couponCode和CouponPass合并为一个值
                if (!empty($dataFeedRow ['CouponCode'])) {
                    $dataFeedRow ['CouponCode'] = 
                        $dataFeedRow ['CouponCode'] 
                        . $this->userDataFeedTable->couponPassSeparator 
                        . $dataFeedRow ['CouponPass'];
                }
                unset($dataFeedRow ['CouponPass']);
                $this->userDataFeedTable->insert($dataFeedRow);
                $this->statInfo['SuccessCnt']++;
                $this->statInfo['Merchant'][$dataFeedRow['MerchantID']]['SuccessCnt']++;
            } else {
                
                if ($userDataFeedInfo['Status'] == 'ONLINE' || $userDataFeedInfo['Status'] == 'DELETE') {
                    //已经审核，无需上线
                } else if (!empty($dataFeedRow['CouponCode']) 
                    && strpos($userDataFeedInfo['CouponCode'], $dataFeedRow['CouponCode']) === false) {
                    //如果有couponCode, 且couponCode不在数据库中
                    $this->userDataFeedTable->updateCouponCode(
                        $userDataFeedInfo['ID'],
                        $dataFeedRow['CouponCode'], 
                        $dataFeedRow['CouponPass']
                    );
                }
                $this->statInfo['ExistCnt']++;
            }
            if ($downloadFeedCnt % 100 == 0) {
                Utilities::println(
                    'ImportFeedCnt = ' . $downloadFeedCnt . ', UseTime = ' . round(microtime(true) - $startTime)
                );
            }
        }
        $this->statInfo['InitCnt'] = $this->statInfo['InitCnt'] > 0 ? $this->statInfo['InitCnt'] - 1 : 0;
        $this->statInfo['ImportFeedUseTime'] = round(microtime(true) - $startTime);
    }
    
    /**
     * 获取统计信息
     */
    public function getStatInfo() 
    {
        $this->statInfo['filter'] = $this->filter->getStatInfo();
        return $this->statInfo;
    }
    
    /**
     * 设置文件读取器
     */
    public function setReader($fileName) 
    {
        $fileType = mb_strtoupper($fileName);
        if (strpos($fileType, 'CSV') !== false) {
            $this->reader = new \Custom\File\Csv($fileName);
        } else if (strpos($fileType, 'XLSX') !== false) {
            $this->reader = new \Custom\File\Execel($fileName);
        } else {
            throw new ImportFeedException("Not Support File Type $fileType");
        }
        return $this->reader;
    }
    
    /**
     * 获取匹配coupon 列表匹配关系
     * @param array $fileColumns 文件中的第一行
     * @return array
     */
    public function getFileMapping($fileColumns) 
    {
        //转换对应关系
        $mapping = array();
        foreach ($fileColumns as $columnKey => $columnName) {
            if (isset($this->couponFieldMapping[$columnName])) {
                $mapping[$columnKey] = $this->couponFieldMapping[$columnName];
            }
        }
        //检查主要字段
        $mappingReverse = array_reverse($mapping);
        foreach ($this->couponNeedField as $fieldName) {
            if ($mappingReverse[$fieldName] === false) {
                throw new ImportFeedException(
                    'need main field.'.$fieldName. ', HeadRow = ' . var_export($fileColumns, true)
                );
            }
        }
        return $mapping;
    }
    
    /**
     * 重新格式化数组
     * @param array $csvArr dataFeedRow
     * @param array $mapping  mapping
     * @return array
     */
    public function formatDataFeed($csvArr, $mapping) 
    {
        $dataFeedRow = array();
        foreach ($csvArr as $index => $value) {
            if ($mapping[$index]) {
                $dataFeedRow[$mapping[$index]] = $value;
            }
        }
        return $dataFeedRow;
    }
}
?>