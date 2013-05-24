<?php
/*
 * package_name : PjnFeed.php
 * ------------------
 * download pepperjam feed
 * Main Field 
 *       id :            广告ID 
 *       program_id :    商家ID, 
 *       program_name:   商家名称
 *       name：                           优惠卷标题
 *       coupon_code     优惠卷Coupon
 *       description     描述
 *       url             优惠卷Url
 *       begin           优惠卷开始时间
 *       expire          优惠卷结束时间
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: PjnFeed.php,v 1.1 2013/04/15 10:57:08 rock Exp $
 */
namespace BackEnd\Process\DownloadFeed;

use Custom\File\Csv;
use Zend\Http\Request;
use BackEnd\Process\Exception;

class PjnFeed extends AbstractFeed
{
    /**
     * @const define Linkshare parameters
     */
    private $pjnUrl = 'http://feeds.pepperjamnetwork.com/coupon/download/?affiliate_id=18439&program_ids=';
    
    /**
     * 存放FEED文件夹名称
     * @var string
     */
    protected $feedFolderName = 'PJN';
    
    public function __construct() {
        $param = '67-71-92-102-108-110-139-149-150-151-152-153-154-155-156-157-158-159-160-179-259-267-268-269-270-271'
            . '-272-273-274-275-276-277-278-279-280-281-282-283-284-285-290-320-409-445-477-482-557-558-574-578-593'
            . '-596-620-621-642-705-708-790-1036-1465-1504-1506-1507-1509-1511-1512-1513-1516-1517-1518-1520-1521'
            . '-1523-1524-1525-1526-1527-1528-1529-1530-1531-1532-1533-1534-1536-1537-1538-1539-1540-1541-1566-1569'
            . '-1572-1573-1575-1588-1589-1592-1593-1595-1596-1598-1599-1601-1606-1607-1609-1610-1613-1615-1616-1631'
            . '-1633-1636-1637-1638-1639-1640-1641-1642-1643-1646-1647-1650-1653-1654-1655-1658-1659-1660-1661-1715'
            . '-1801-1844-1896-1909-1911-1913-1914-1918-2070-2109-2261-2262-2263-2271-2317-2342-2360-2364-2384-2432'
            . '-2531-2586-2637-2803-2851-3032-3070-3071-3107-3124-3127-3129-3131-3133-3134-3135-3136-3138-3139-3140'
            . '-3146-3209-3256-3275-3301-3387-3416-3432-3489-3505-3526-3556-3598-3604-3631-3636-3674-3681-3724-3729'
            . '-3771-3867-3900-3905-3924-3982-4033-4041-4043-4054-4081-4085-4087-4089-4093-4114-4117-4124-4132-4134'
            . '-4146-4163-4171-4236-4238-4333-4360-4389-4407-4465-4519-4574-4590-4620-4643-4647-4667-4669-4672-4684'
            . '-4692-4705-4757-4760-4766-4780-4783-4801-4816-4825-4832-4840-4865-4893-4894-4918-4925-4933-4940-4943'
            . '-4983-4989-5030-5081-5273-5281-5289-5290-5297-5315-5321-5325-5326-5329-5330-5333-5334-5335-5336-5339'
            . '-5342-5343-5344-5354-5359-5361-5362-5365-5367-5369-5371-5376-5378-5401-5412-5414-5415-5417-5423-5424'
            . '-5425-5426-5427-5441-5448-5449-5450-5461-5465-5478-5479-5480-5521-5522-5530-5531-5540-5549-5552-5556'
            . '-5557-5558-5561-5569-5570-5575-5578-5588-5595-5644-5645-5661-5691-5692-5694-5705-5708-5709-5719-5734'
            . '-5739-5740-5741-5753-5760-5764-5770-5771-5772-5786-5793-5805-5808-5811-5823-5824-5840-5843-5853-5867'
            . '-5868-5869-5870-5873-5901-5907-5913-5914-5941-5943-5953-5966-5967-5972-5978-5979-5982-5987-5988-5989'
            . '-5999-6002-6016-6035-6043-6044-6049-6050-6052-6054-6060-6061-6068-6076-6081-6082-6083-6085-6086-6087'
            . '-6088-6093-6097-6099-6100-6101-6105-6106-6112-6115-6116-6125-6128-6129-6130-6136-6137-6141-6143-6151'
            . '-6152-6155-6156-6161-6164-6165-6166-6168-6171-6182-6186-6187-6191-6193-6196-6199-6200-6201-6206-6211'
            . '-6214-6215-6217-6218-6222-6223-6225-6226-6227-6228-6229-6230-6231-6232-6233-6234-6235-6237-6239-6244'
            . '-6246-6249-6251-6253-6257-6258-6259-6262-6265-6267-6272-6274-6275-6276-6278-6279-6282-6283-6287-6288'
            . '-6289-6291-6292-6293-6299-6301-6303-6307-6312-6317-6320-6323-6324-6328-6330-6332-6334-6343-6345-6350'
            . '-6355-6359-6369-6373-6378-6382-6391-6398-6408-6411-6427-6435-6441';
        $this->pjnUrl .= $param;
        parent::__construct();
    }
    
    /**
     * 解析Feed
     * @return null
     */
    public function parseFeed() 
    {
        $content = $this->getUriContent($this->pjnUrl);
        //导入到临时FEED文件
        $tmpFile = $this->getFeedFilePath() . 'tmp.txt';
        $fpTmp = fopen($tmpFile, 'w');
        fwrite($fpTmp, $content);
        unset($content);
        fclose($fpTmp);
        
        $csv = new Csv();
        $md5Rows = array();
        $file = $this->getFeedFilePath() . $this->getFeedFileName();
        $hasHeadFlag = true;
        //feed 文件转换为数组
        $rowArr = $csv->loadToArray($tmpFile, true);
        if (empty($rowArr)) {
            throw new Exception\DownLoadFeedException('PJN Parse Feed Error, url = ' . $this->pjnUrl);
        }
        $this->statInfo['TotalProdCnt'] = count($rowArr);
        foreach ($rowArr as $index => $rowData) {
            $record = array();
            //联盟商家ID
            $record['MerchantID'] = $this->formatData($rowData['program_id'], 'int');
            
            //联盟商家名称
            $record['MerchantName'] = $this->formatData($rowData['program_name']);
            
            //LINK
            $record['Link'] = $this->formatData($rowData['url']);
            if (preg_match('/^http:\/\/www\./i', $record['Link'], $matches) == false) {
                $this->statInfo['ParseErrorCnt']++;
                continue;
            }
            //优惠标题
            $record['Title'] = $this->formatData($rowData['name']);
            
            //coupon code
            $record['CouponCode'] = $this->formatData($rowData['coupon_code']);
            
            //优惠开始时间
            $record['StartDate'] = $this->formatData($rowData['begin']);
            
            //优惠结束时间
            $record['EndDate'] = $this->formatData($rowData['expire']);
            
            //没有结束日期不导入
            if (empty($record['EndDate'])) {
                $this->statInfo['ParseErrorCnt']++;
                continue;
            }
                
            //描述
            $record['Description'] = $this->formatData($rowData['description']);
            
            //使用限制
            $record['Restriction'] = '';
            
            $record['Image'] = '';
            $md5 = md5(
                $record['MerchantID'] . $record['CouponCode'] . $record['Title'] . 
                $record['StartDate'] . $record['EndDate']
            );
            if ($md5Rows[$md5]) {
                $this->statInfo['ExistRows']++;
                continue;
            } else {
                $md5Rows[$md5] = true;
                $records[] = $record;
                //统计
                $this->statInfo['SuccessCnt']++;
            }
            
            if (count($records) > 200 
                || ( count($records) > 0 && ($index == $this->statInfo['TotalProdCnt'] - 1 && count($records) > 0))) {
                $csv->storeFromArray($file, $records, $hasHeadFlag);
                $records = array();
                $hasHeadFlag = false;
            }
            //debug测试
            if ($this->isDebug && $this->statInfo['SuccessCnt'] > $this->debugCount) {
                break;
            }
        }
        
//        if ($this->statInfo['SuccessCnt'] > 0) {
//            $this->setMd5File($md5Rows);
//        }
        //删除临时文件
        unlink($tmpFile);
    }
}
?>