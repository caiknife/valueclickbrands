<?php
/*
 * package_name : CjFeed.php
 * ------------------
 * cj parse feed
 * 字段描述主要见 http://help.cj.com/en/web_services/web_services.htm#link_search_service_rest.htm 
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CjFeed.php,v 1.1 2013/04/15 10:57:08 rock Exp $
 */
namespace BackEnd\Process\DownloadFeed;

use Custom\File\Csv;
use DOMDocument;
use Zend\Http\Request;
use BackEnd\Process\Exception;

class CjFeed extends AbstractFeed 
{
    /**
     * @const string cj parameters
     */
    const CJ_HOST           = 'https://linksearch.api.cj.com/v2/link-search?';
    const CJ_WID            = 'website-id=766044';
    const CJ_RS             = '&advertiser-ids=joined';
    const CJ_PSIZE_NAME     = '&records-per-page=';
    const CJ_PSIZE_VALUE    = 100;
    const CJ_LT             = '&link-type=Text+Link';
    const CJ_PN             = '&page-number=';
    const CJ_DevKey         = '00867930108d385f476f6607bdd616fdf0e74b632b8b436421b8a4ea74ddd892c791bd6202349b91db0e4382f8ec1f657c4b4a9c05745e0ad955c4dae969ebbb89/79e647b4e95f8db782fb59cf33d0d9d854c3591db2fb9f3c9656211759b7a6ada28d8d54354c1b90b6db89972aff2797d831084bb11114b23f85fbf2a91ecad5';
    
    /**
     * 存放FEED文件夹名称
     * @var string
     */
     protected $feedFolderName = 'CJ';
     
    /**
     * 重新curl 请求参数
     */
    public function __construct() 
    {
        $options['curloptions'][CURLOPT_SSL_VERIFYPEER] = false;
        parent::__construct($options);
        $request = new Request();
        $request->getHeaders()->addHeaders(array('Authorization' => self::CJ_DevKey));
        $this->client->setRequest($request);
    }
    
    /**
     *获取feed url 
     *@return string  
     */
    public function getFeedUri($page) 
    {
        return self::CJ_HOST . self::CJ_WID . self::CJ_RS . 
            self::CJ_PSIZE_NAME . self::CJ_PSIZE_VALUE . self::CJ_LT . self::CJ_PN . $page;
    }
    
    
    /**
     * 解析FEED文件
     * @return null
     */
    public function parseFeed()
    {
        $page = 1;
        $totalPageCnt = 0;
        $dom = new DOMDocument();
        $file = $this->getFeedFilePath() . $this->getFeedFileName();
        $csv = new Csv();
        $md5Rows = array();
        $hasHeadFlag = true;
        do {
            $uri = $this->getFeedUri($page);
            $content = $this->getUriContent($uri);
            $dom->loadXML($content, LIBXML_NOCDATA);
            $errorMsg = @$dom->documentElement->getElementsByTagName('error-message')->item(0);
            $links = $dom->documentElement->getElementsByTagName('links')->item(0);
            if (is_object($dom) == false || !empty($errorMsg) || !is_object($links)) {
                throw new Exception\DownLoadFeedException('CJ API Error ' . $errorMsg . ', url = ' . $uri);
            }
            $links = $dom->documentElement->getElementsByTagName('links')->item(0);
            $totalLinkCnt = $this->formatData($links->getAttributeNode('total-matched')->value, 'int');
            if ($totalPageCnt <= 0 && $totalLinkCnt > 0) {
                $totalPageCnt = ceil($totalLinkCnt / self::CJ_PSIZE_VALUE);
                $this->statInfo['TotalProdCnt'] = $totalLinkCnt;
            }
            $linkArr = $dom->documentElement->getElementsByTagName('link');
            foreach ($linkArr as $link) {
                $language = $this->formatData($link->getElementsByTagName('language')->item(0)->nodeValue);
                //语言选择
                if (strtoupper($language) != 'EN') {
                    continue;
                }
                $record = array();
                
                //商家ID
                $record['MerchantID'] = $this->formatData($link->getElementsByTagName('advertiser-id')->item(0)->nodeValue);
                
                //商家名称
                $record['MerchantName'] = $this->formatData(
                    $link->getElementsByTagName('advertiser-name')->item(0)->nodeValue
                );
                
                //商家促销描述
                $linkHtml = $this->formatData($link->getElementsByTagName('link-code-html')->item(0)->nodeValue);
                
                //商家促销链接
                if (!preg_match('/<a\s+.*href="(.*)"[^>]*>/iUs', $linkHtml, $matchHref)) {
                    continue;
                }
                $record['Url'] = $matchHref[1];
                
                //商家促销信息描述
                $description = $this->formatData($link->getElementsByTagName('description')->item(0)->nodeValue);
                $linkText = strip_tags($linkHtml);
                if (empty($description) && !empty($linkText)) {
                    $description = $linkText;
                } else if (strlen($description) < 200 
                    && strpos(strtolower($linkText), strtolower($description)) === false) {
                    $description = $description . ',' . $linkText;
                }
                
                //商家促销信息标题 从描述信息中提取
                $record['Title'] = $this->getTitleByDesc($description);
                
                //商家CouponCode
                $record['CouponCode'] = '';
                
                //商家促销信息开始时间
                $record['StartDate'] = $this->formatData(
                    $link->getElementsByTagName('promotion-start-date')->item(0)->nodeValue
                );
                
                //商家促销信息结束时间
                $record['EndDate'] = $this->formatData(
                    $link->getElementsByTagName('promotion-end-date')->item(0)->nodeValue
                );
                
                //没有结束日期不导入
                if (empty($record['EndDate'])) {
                    $this->statInfo['ParseErrorCnt']++;
                    continue;
                }
                
                //商家促销信息分类
                $record['CategoryName'] = $this->formatData($link->getElementsByTagName('category')->item(0)->nodeValue);
                
                $record['Description'] = $description;
                
                //优惠卷使用规则
                $record['Restriction'] = $this->formatData($link->getElementsByTagName('destination')->item(0)->nodeValue);
                
                //联盟记录ID -> 验证是否之前已经存在此条记录
                $linkID = $this->formatData($link->getElementsByTagName('link-id')->item(0)->nodeValue);
                $md5 = md5($record['MerchantID'] . $record['Description'] . $linkID);
                if (isset($md5Rows[$md5])) {
                    $this->statInfo['ExistRows']++;
                } else {
                    $md5Rows[$md5] = true;
                    $records[] = $record;
                    //统计
                    $this->statInfo['SuccessCnt']++;
                }
            }
            $page++;
            if (count($records) >= 200) {
                $csv->storeFromArray($file, $records, $hasHeadFlag);
                $hasHeadFlag = false;
                $records = array();
            }
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