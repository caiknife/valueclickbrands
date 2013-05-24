<?php
/*
 * package_name : SasFeed.php
 * ------------------
 * shareasale parse feed
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: SasFeed.php,v 1.1 2013/04/15 10:57:08 rock Exp $
 */
namespace BackEnd\Process\DownloadFeed;

use Custom\File\Csv;
use DOMDocument;
use Zend\Http\Request;
use BackEnd\Process\Exception;

class SasFeed extends AbstractFeed 
{
    /**
     * Define ShareAsale parameters
     */
    const SAS_URI           = 'http://www.shareasale.com/dealdatabase2.xml';
    const SAS_TRACK_CODE    = 106070;
    
    /**
     * 存放FEED文件夹名称
     * @var string
     */
    protected $feedFolderName = 'SAS';
    
    public function parseFeed() 
    {
        $file = $this->getFeedFilePath() . $this->getFeedFileName();
        $content = $this->getUriContent(self::SAS_URI);
        $dom = new DOMDocument();
        $dom->loadXML($content, LIBXML_NOCDATA);
        if (is_object($dom) == false) {
            throw new Exception\DownLoadFeedException('SAS Parse DOM Failed API Error, url = ' . self::SAS_URI);
        }
        
        $csv = new Csv();
        $hasHeadFlag = true;
        $md5Rows = array();
        
        $itemArr = $dom->documentElement->getElementsByTagName('item');
        $this->statInfo['TotalProdCnt'] = $itemArr->length;
        
        foreach ($itemArr as $loop => $item) {
            $record = array();
            //联盟商家ID
            $record['MerchantID'] = $this->formatData($item->getElementsByTagName('merchantID')->item(0)->nodeValue);
            
            //联盟商家名称
            $record['MerchantName'] = $this->formatData($item->getElementsByTagName('merchantname')->item(0)->nodeValue);
            
            //优惠标题
            $record['Title'] = $this->formatData($item->getElementsByTagName('dealtitle')->item(0)->nodeValue);
            
            //LINK
            $link = $this->formatData($item->getElementsByTagName('trackingurl')->item(0)->nodeValue);
            $record['Link'] = str_replace('YOURUSERIDHERE', self::SAS_TRACK_CODE, $link);
            
            //coupon code
            $record['CouponCode'] = $this->formatData($item->getElementsByTagName('couponcode')->item(0)->nodeValue);
            
            //优惠开始时间
            $record['StartDate'] = $this->formatData($item->getElementsByTagName('dealstartdate')->item(0)->nodeValue);
            
            //优惠结束时间
            $record['EndDate'] = $this->formatData($item->getElementsByTagName('dealenddate')->item(0)->nodeValue);
            
            //没有结束日期不导入
            if (empty($record['EndDate'])) {
                $this->statInfo['ParseErrorCnt']++;
                continue;
            }
                
            //CategoryName
            $record['CategoryName'] = '';
            
            //描述
            $record['Description'] = $this->formatData($item->getElementsByTagName('description')->item(0)->nodeValue);
            
            //使用限制
            $record['Restriction'] = $this->formatData($item->getElementsByTagName('restrictions')->item(0)->nodeValue);
            
            $record['Image'] = '';
            
            $md5 = md5($record['MerchantID'] . $record['MerchantName'] . $record['CouponCode'] . $record['Title'] . $link);
            if ($md5Rows[$md5]) {
                $this->statInfo['ExistRows']++;
            } else {
                $md5Rows[$md5] = true;
                $records[] = $record;
                //统计
                $this->statInfo['SuccessCnt']++;
            }
            if (count($records) > 200 || ($loop == $itemArr->length - 1 && count($records) > 0)) {
                $csv->storeFromArray($file, $records, $hasHeadFlag);
                $records = array();
            }
            //debug测试
            if ($this->isDebug && $this->statInfo['SuccessCnt'] > $this->debugCount) {
                break;
            }
        }
        
//        if ($this->statInfo['SuccessCnt'] > 0) {
//            $this->setMd5File($md5Rows);
//        }
    }
}
?>