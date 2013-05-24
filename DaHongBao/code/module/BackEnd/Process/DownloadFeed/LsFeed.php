<?php
/*
 * package_name : LsFeed.php
 * ------------------
 * linkshare parse feed 
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: LsFeed.php,v 1.3 2013/05/08 08:35:50 thomas_fu Exp $
 */
namespace BackEnd\Process\DownloadFeed;

use Custom\File\Csv;
use DOMDocument;
use BackEnd\Process\Exception;

class LsFeed extends AbstractFeed 
{

    /**
     * @const define Linkshare parameters
     */
    const LS_API = 'http://couponfeed.linksynergy.com/coupon?token=dbf1eb2f82d2bed11c238ea7397f3497c82ffec9002c611ca94e327e3139de3d&network=1';
    const LS_PG = '&pagenumber=';
     
    /**
     * 存放FEED文件夹名称
     * @var string
     */
    protected $feedFolderName = 'LS';
    
    /**
     *获取feed url 
     *@return string  
     */
    public function getFeedUri($page) 
    {
        return self::LS_API . self::LS_PG . $page;
    }
    
    /**
     * 解析FEED文件
     * @return null
     */
    public function parseFeed()
    {
        $page = 1;
        $totalPageCnt = 0;
        $hasHeadFlag = true;
        $dom = new DomDocument();
        $file = $this->getFeedFilePath() . $this->getFeedFileName();
        $csv = new Csv();
        $md5Rows = array();
        do { 
            $uri = $this->getFeedUri($page);
            $content = $this->getUriContent($uri);
            $dom->loadXML($content, LIBXML_NOCDATA);
            $totalLinkCnt = $dom->documentElement->getElementsByTagName('TotalMatches')->item(0)->nodeValue;
            $this->statInfo['TotalProdCnt'] = $totalLinkCnt;
            if ($totalLinkCnt <= 0 ) {
                throw new Exception\DownLoadFeedException('LS API Parse Error , url = ' . $uri);
            }
            $totalPageCnt = 
                $this->formatData($dom->documentElement->getElementsByTagName('TotalPages')->item(0)->nodeValue, 'int');
            $linkArr = $dom->documentElement->getElementsByTagName('link');
            foreach ($linkArr as $link) {
                $record = array();
                //商家ID
                $record['MerchantID'] = $this->formatData($link->getElementsByTagName('advertiserid')->item(0)->nodeValue);
                
                //商家名称
                $record['MerchantName'] = $this->formatData(
                    $link->getElementsByTagName('advertisername')->item(0)->nodeValue
                );
                
                //链接Url
                $record['Url'] = $this->formatData($link->getElementsByTagName('clickurl')->item(0)->nodeValue);
                
                $desc = $this->formatData(
                    $link->getElementsByTagName('offerdescription')->item(0)->nodeValue
                );

                //商家促销信息标题 从描述信息中提取
                $record['Title'] = $this->getTitleByDesc($desc);

                //商家CouponCode
                $record['CouponCode'] = $this->formatData($link->getElementsByTagName('couponcode')->item(0)->nodeValue);
                
                //商家促销信息开始时间
                $record['StartDate'] = $this->formatData(
                    $link->getElementsByTagName('offerstartdate')->item(0)->nodeValue
                );
                
                //商家促销信息结束时间
                $record['EndDate'] = $this->formatData(
                    $link->getElementsByTagName('offerenddate')->item(0)->nodeValue
                );
                if (strtolower($record['EndDate']) == 'ongoing') {
                    $record['EndDate'] = '3333-03-03 00:00:00';
                }
                
                //没有结束日期不导入
                if (empty($record['EndDate'])) {
                    $this->statInfo['ParseErrorCnt']++;
                    continue;
                }
                
                //商家促销信息分类
                $record['CategoryName'] = $this->formatData($link->getElementsByTagName('category')->item(0)->nodeValue);
                
                //商家促销信息描述
                $record['Description'] = $desc;
                
                //商家优惠卷使用限制
                $record['Restriction'] = $this->formatData(
                    $link->getElementsByTagName('couponrestriction')->item(0)->nodeValue
                );
                
                $record['Image'] = '';
                
                //验证是否之前已经存在此条记录
                $md5 = md5($record['MerchantID'] . $record['CouponCode'] . $record['Description'] 
                           . $record['StartDate'] . $record['EndDate'] . $record['Url']);
                if (isset($md5Rows[$md5])) {
                    $this->statInfo['ExistRows']++;
                } else {
                    $records[] = $record;
                    $md5Rows[$md5] = true;
                    //统计
                    $this->statInfo['SuccessCnt']++;
                }
            }
            
            if (count($records) >= 200) {
                $csv->storeFromArray($file, $records, $hasHeadFlag);
                $hasHeadFlag = false;
                $records = array();
            }
            $page++;
            
            //debug测试
            if ($this->isDebug && $this->statInfo['SuccessCnt'] > $this->debugCount) {
                break;
            }
        } while ($page <= $totalPageCnt);
        
        if (count($records) > 0) {
            $csv->storeFromArray($file, $records, $hasHeadFlag);
        }
        
//        if ($this->statInfo['SuccessCnt'] > 0) {
//            $this->setMd5File($md5Rows);
//        }
        
    }
}
?>