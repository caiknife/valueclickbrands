<?php
/*
 * package_name : ImportAffiliateFeedProcess.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: ImportAffiliateFeedProcess.php,v 1.2 2013/04/27 07:34:46 thomas_fu Exp $
 */
namespace BackEnd\Process\ImportFeed;

use BackEnd\Process\Exception\ImportFeedException;
use BackEnd\Process\Process;
use BackEnd\Model;

class ImportAffiliateFeedProcess extends ImportFeedProcess 
{
    /**
     * 存放联盟信息
     * @var array()
     */
    public $affiliateInfo = array();
    
    public function __construct($affiliateID, $fileName)
    {
        if (empty($affiliateID)) {
            throw new ImportFeedException('need affiliate id');
        }
        $this->affiliateID = $affiliateID;
        
        if (($this->fp = fopen($fileName, 'r')) == false) {
            throw new ImportFeedException('can not open file ' . $fileName);
        }
        $fileName = basename($fileName);
        parent::__construct($fileName);
        $this->statInfo['FileNameDateTime'] = $this->getFeedFileDateTime($fileName);
    }
    
    public function init($sm) 
    {
        $this->setServiceManager($sm);
        $adapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
        $this->userDataFeedTable = new Model\FeedConfig\UserDataFeedTable($adapter);
        $this->merchantAliasTable = new Model\Merchant\MerchantAliasTable($adapter);
        $this->affiliateTable = new Model\FeedConfig\AffiliateTable($adapter);
        $this->affiliateInfo = $this->affiliateTable->getInfoById($this->affiliateID);
        $this->merchantTable = new Model\Merchant\MerchantTable($adapter);
    } 

}
?>