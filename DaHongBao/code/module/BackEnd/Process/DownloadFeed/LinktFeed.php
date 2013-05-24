<?php
/*
 * package_name : LinktFeed.php
 * ------------------
 * 下载linktech优惠信息
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: LinktFeed.php,v 1.1 2013/04/15 10:57:08 rock Exp $
 */
namespace BackEnd\Process\DownloadFeed;

use Custom\File\Csv;
use DOMDocument;
use Zend\Http\Request;
use BackEnd\Process\Exception;

class LinktFeed extends AbstractFeed 
{
    /**
     * @define 请求url参数
     */
    const URL   = 'http://smart.linktech.cn/xml/event_createXml.php?';
    const AID   = 'A100003846'; //网站编号
    const UID   = '';   //广告主ID
    const MID    = '';  //反馈标签
    
    
    /**
     * 存放FEED文件夹名称
     * @var string
     */
    protected $feedFolderName = 'LINKT';
    
    /**
     * 存放categoryMapping关系
     * @var array
     */
    protected $categoryMapping = array(
        '00' => '综合百货',
        '03' => '图书/音像',
        '06' => '礼品/鲜花',
        '07' => '服装/服饰',
        '08' => '化妆品/美容',
        '09' => '宠物/玩具',
        '10' => '女性/内衣',
        '11' => '母婴/幼儿',
        '12' => '家居/电器',
        '13' => '餐饮/饮料',
        '14' => '办公用品',
        '15' => '手机/数码',
        '16' => '机票/酒店',
        '17' => '健身/体育/运动',
        '18' => '医药/保健',
        '19' => '成人用品',
        '26' => '教育/培训',
        '27' => '汽车用品',
        '29' => '金融/投资/理财',
        '39' => '电子刊物',
        '41' => '其他',
        '42' => '票务',
        '47' => '电视购物',
        '49' => '珠宝首饰',
        '50' => '箱包/鞋类/配饰',
        '51' => '团购类',
    );
    
    /**
     *获取feed url 
     *@return string  
     */
    public function getFeedUri() 
    {
        $feedUri = self::URL . 'a_id=' . self::AID . '&u_id=' . self::UID . '&m_id=' . self::MID;
        return $feedUri;
    }
    
    public function parseFeed() 
    {
        $file = $this->getFeedFilePath() . $this->getFeedFileName();
        $requestUri = $this->getFeedUri();
        $content = $this->getUriContent($requestUri);
        $dom = new DOMDocument();
        $dom->loadXML($content, LIBXML_NOCDATA);
        if (is_object($dom) == false) {
            throw new Exception\DownLoadFeedException('LinkTech Parse DOM Failed API Error, url = ' . $requestUri);
        }
        
        $csv = new Csv();
        $hasHeadFlag = true;
        $md5Rows = array();
        
        $itemArr = $dom->documentElement->getElementsByTagName('item');
        $this->statInfo['TotalProdCnt'] = $itemArr->length;
        
        foreach ($itemArr as $loop => $item) {
            $record = array();
            //联盟商家ID
            $record['MerchantID'] = $this->formatData($item->getElementsByTagName('merchant_id')->item(0)->nodeValue);
            
            //联盟商家名称
            $record['MerchantName'] = $this->formatData($item->getElementsByTagName('merchant_name')->item(0)->nodeValue);
            
            //优惠标题
            $record['Title'] = $this->formatData($item->getElementsByTagName('event_name')->item(0)->nodeValue);
            
            //LINK
            $record['Url'] = $this->formatData($item->getElementsByTagName('click_url')->item(0)->nodeValue);
            
            //coupon code
            $record['CouponCode'] = '';
            
            //categoryName
            $record['CategoryName'] = $this->formatData($item->getElementsByTagName('code_name')->item(0)->nodeValue);
            if (empty($record['CategoryName'])) {
                $categoryCode = $this->formatData($item->getElementsByTagName('category_code')->item(0)->nodeValue);
                $record['CategoryName'] = $this->categoryMapping[$categoryCode];
            }
            
            //优惠开始时间
            $record['StartDate'] = $this->formatData($item->getElementsByTagName('begin_yyyymmdd')->item(0)->nodeValue);
            
            //优惠结束时间
            $record['EndDate'] = $this->formatData($item->getElementsByTagName('end_yyyymmdd')->item(0)->nodeValue);
            
            //描述
            $record['Description'] = $this->formatData($item->getElementsByTagName('evt_intro')->item(0)->nodeValue);
            
            //使用限制
            $record['Restriction'] = $this->formatData($item->getElementsByTagName('restrictions')->item(0)->nodeValue);
            
            //图片banner
            $record['ImageUrl'] = $this->formatData($item->getElementsByTagName('evt_banner')->item(0)->nodeValue);
            
            $activeID = $this->formatData($item->getElementsByTagName('evt_code')->item(0)->nodeValue, 'int');
            
            $md5 = md5($activeID . $record['MerchantName'] . $record['Title'] . $record['Link']);
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