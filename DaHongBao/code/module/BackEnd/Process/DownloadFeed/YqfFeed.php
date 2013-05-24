<?php
/*
 * package_name : YqfFeed.php
 * ------------------
 * 更新一起发Coupon文件
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: YqfFeed.php,v 1.1 2013/04/15 10:57:08 rock Exp $
 */
namespace BackEnd\Process\DownloadFeed;

use Custom\File\Csv;
use DOMDocument;
use Zend\Http\Request;
use BackEnd\Process\Exception\DownLoadFeedException;
use Custom\Util\Utilities;

class YqfFeed extends AbstractFeed 
{
    /**
     * @define 请求url参数
     */
    const URL   = 'http://o.yiqifa.com/servlet/QueryCoupons?';
    const SID   = 475;
    const SK    = '911c8eb3c230c3350564857be60ac4f4';   //PASSWORD MD5
    
    /**
     * 返回字段列表
     */
    protected $fieldMapping = array(
        1   => 'create_time',
        2   => 'action_id',
        3   => 'action_name',
        4   => 'coupons_money',
        5   => 'comments',
        6   => 'start_date',
        7   => 'end_date',
        8   => 'is_general',
        9   => 'coupons_no',
        10  => 'coupons_password',
        11  => 'app_time',
    );
    
    /**
     * 错误类型
     * @var array()
     */
    protected $errType = array(
        1 => 'invaild id',
        2 => 'invaild password',
        3 => 'request param error (sid or action_id is int type, se, ed type error)',
        4 => 'very long from st to ed ',
    );
    
    /**
     * 存放FEED文件夹名称
     * @var string
     */
    protected $feedFolderName = 'YQF';
    
    /**
     *获取feed url 
     *@return string  
     */
    public function getFeedUri($page) 
    {
        $page = $page * 1;
        $feedUri = self::URL . 'sid=' . self::SID . '&sk=' . self::SK 
            . '&sd=' . Utilities::getDateTime('Y-m-d', time() - 3600 * 24 * 20) 
//            . '&ed=' . Utilities::getDateTime('Y-m-d', time() + 3600 * 24 * 10)
            . '&page=' . $page;
        return $feedUri;
    }
    
    public function parseFeed() 
    {
        $page = 1;
        $totalPageCnt = 0;
        $hasHeadFlag = true;
        $file = $this->getFeedFilePath() . $this->getFeedFileName();
        $csv = new Csv();
        $md5Rows = array();
        do { 
            $uri = $this->getFeedUri($page);
            $content = trim($this->getUriContent($uri));
            if (empty($content)) {
                break;
            } else if (isset($this->errType[$content])) {
                throw new DownLoadFeedException('error, url = ' . $uri . "\n" . $this->errType[$content]);
            }
            $couponArr = explode("\n", iconv('GBK', 'UTF-8//IGNORE', $content));
            $this->statInfo['TotalProdCnt'] += count($couponArr);
            foreach ($couponArr as $couponStr) {
                $couponInfo = $this->getMappingRecord($couponStr);
                if (empty($couponInfo)) {
                    continue;
                }
                $record = array();
                //商家ID
                $record['MerchantID'] = '';
                
                //商家名称
                $record['MerchantName'] = str_replace(array('CPS', 'cps'), '', $couponInfo['action_name']);
                
                //链接Url
                $record['Url'] = $record['MerchantName'];
                
                $record['Title'] = $this->formatData($couponInfo['action_name'] . $couponInfo['coupons_money']);

                //商家CouponCode
                $record['CouponCode'] = $this->formatData($couponInfo['coupons_no']);
                
                //CouponCode 密码
                $record['CouponPass'] = $this->formatData($couponInfo['coupons_password']);
                
                //商家促销信息开始时间
                
                $record['StartDate'] = $this->formatData($couponInfo['start_date']);
                
                //商家促销信息结束时间
                $record['EndDate'] = $this->formatData($couponInfo['end_date']);
                
                if (strtotime($record['EndDate']) < (time() - 3600 * 24)) {
                    $this->statInfo['ExpireCnt']++;
                    continue;
                }
                
                //商家促销信息分类
                $record['CategoryName'] = $couponInfo['is_general'] == 'no' ? '全场通用' : '非全场通用';
                
                //商家促销信息描述
                $record['Description'] = $this->formatData($couponInfo['comments']);
                
                //商家优惠卷使用限制
                $record['Restriction'] = '';
                $record['ImageUrl'] = '';
                //验证是否之前已经存在此条记录
                $activeID = $couponInfo['action_id'];
                $md5 = md5($activeID . $record['CouponCode'] . $record['CouponPass']);
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
            //debug 测试
            if ($this->isDebug && $this->statInfo['SuccessCnt'] > $this->debugCount) {
                break;
            }
            $page++;
        } while (true);

        if (count($records) > 0) {
            $csv->storeFromArray($file, $records, $hasHeadFlag);
        }
        
//        if ($this->statInfo['SuccessCnt'] > 0) {
//            $this->setMd5File($md5Rows);
//        }
    }
    
    /**
     * 格式化coupon记录为数组
     * @param string $str
     * @return array
     */
    private function getMappingRecord($couponStr) 
    {
        if (empty($couponStr)) {
            return array();
        }
        $coupon = array();
        foreach (explode('||', $couponStr) as $index => $value) {
            $coupon[$this->fieldMapping[$index + 1]] = $value;
        }
        return $coupon;
    }
}
?>