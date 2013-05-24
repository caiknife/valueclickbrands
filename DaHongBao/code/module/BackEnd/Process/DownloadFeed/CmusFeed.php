<?php
/*
 * package_name : CmusFeed.php
 * ------------------
 * 下载Couponmouniterfeed文件
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CmusFeed.php,v 1.4 2013/05/20 09:47:36 thomas_fu Exp $
 */
namespace BackEnd\Process\DownloadFeed;

use Custom\File\Csv;
use Zend\Http\Request;
use BackEnd\Process\Exception;
use BackEnd\Model\Cmus;
use Zend\Db\Adapter\Adapter;
use Custom\Util\Utilities;



class CmusFeed extends AbstractFeed 
{
    
    /**
     * couponTable
     * @var object
     */
     protected $couponTable;
     
    /**
     * 存放FEED文件夹名称
     * @var string
     */
    protected $feedFolderName = 'CMUS';
    
    /**
     * 初始化一些Table
     */
    public function init() 
    {
        $sm = $this->getServiceManager();
        $adapter = $sm->get('Custom\Db\Adapter\CmusAdapter');
        $this->couponTable = new Cmus\CouponTable($adapter);
        $this->merchantTable = new Cmus\MerchantTable($adapter);
        $this->categoryTable = new Cmus\CategoryTable($adapter);
    }
    
    /**
     * 解析FEED文件
     */
    public function parseFeed() 
    {
        $this->init();
        $csv = new Csv();
        $md5Rows = array();
        $hasHeadFlag = true;
        $file = $this->getFeedFilePath() . $this->getFeedFileName();
        $limit = 50;
        $offset = 0;
        $this->statInfo['TotalProdCnt'] = $this->couponTable->fetchAll(true);
        do {
            $startTime = microtime(true);
            $couponList = $this->couponTable->fetchAll(false, $offset, $limit);
            $couponCnt = count($couponList);
            foreach ($couponList as $couponIndex => $coupon) {
                $record = array();
                //CouponID to AdsID
                $record['AdsID'] = $coupon['Coupon_'];
                //商家ID
                $record['MerchantID'] = $coupon['Merchant_'];
                
                //商家名称
                $merchantInfo = $this->merchantTable->getMerchantByID(
                    $record['MerchantID'], 
                    array('Merchant_', 'Name', 'URL')
                );

                $record['MerchantName'] = $merchantInfo['Name'];
                
                //Url
                if (empty($coupon['URL'])) {
                    $record['URL'] = $merchantInfo['URL'] ;
                } else {
                    $record['URL'] = $coupon['URL'];
                }
                
                //Title
                if (empty($coupon['Amount'])) {
                    if (intval($coupon['CAmount']) != 0 && !empty($coupon['CType'])) {
                        $title = 'Save ' . $coupon['CAmount'] . $coupon['CType'];
                    } else {
                        $title = 'Save At ' . $record['MerchantName'];
                    }
                } else {
                    $title = $coupon['Amount'];
                }
                $record['Title'] = $title;
                
                //CouponCode
                $record['CouponCode'] = $coupon['Code'];
                
                //开始时间
                $record['StartDate'] = $coupon['StartDate'];
                
                //结束时间
                if ($coupon['ExpireDate'] >= '3333-03-03 00:00:00' 
                    || strpos($coupon['ExpireDate'], '0000-00-00') !== false 
                    || empty($coupon['ExpireDate'])) {
                    $record['EndDate'] = '3333-03-03 00:00:00';
                }  else {
                    $record['EndDate'] = $coupon['ExpireDate'];
                }

                //CategoryName
//                 $categoryList = $this->categoryTable->getCateListByCouponID($coupon['Coupon_'], array('Name'));
//                 $categoryName = '';
//                 if (!empty($categoryList)) {
//                     foreach ($categoryList as $cateIndex => $cateName) {
//                         $categoryName .= str_replace(',', '', $cateName) . ',';
//                     }
//                 }
                $record['CategoryName'] = '';
                
                //coupon desc
                $record['Description'] = $coupon['Descript'];
                
                //coupon Restriction
                $record['Restriction'] = '';
                
                $record['ImageUrl'] = '';
                $md5 = md5($coupon['Coupon_'] . $coupon['AddDate'] . $coupon['LastChangeDate']);
                if (isset($md5Rows[$md5])) {
                    $this->statInfo['ExistRows']++;
                } else {
                    $md5Rows[$md5] = true;
                    $records[] = $record;
                    //统计
                    $this->statInfo['SuccessCnt']++;
                }
                //打印执行时间
                $getCouponUseTime += round(microtime(true) - $startTime, 2);
                if ($this->statInfo['SuccessCnt'] % 1000 == 0) {
                    Utilities::println('SuccessCnt = '. $this->statInfo['SuccessCnt'] . 'getCouponUseTime = ' . $getCouponUseTime);
                }
            }
            if (count($records) >= 50) {
                $csv->storeFromArray($file, $records, $hasHeadFlag);
                $hasHeadFlag = false;
                $records = array();
            }
            
            $offset += $limit;
            //debug测试
            if ($this->isDebug && $this->statInfo['SuccessCnt'] > $this->debugCount) {
                break;
            }
        } while ($couponCnt >= $limit);
        
        if (count($records) > 0) {
            $csv->storeFromArray($file, $records, $hasHeadFlag);
        }
    }
}
?>