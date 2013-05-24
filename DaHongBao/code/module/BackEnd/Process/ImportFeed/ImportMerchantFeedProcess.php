<?php
/*
 * package_name : ImportMerchantFeedProcess.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: ImportMerchantFeedProcess.php,v 1.2 2013/04/27 07:34:46 thomas_fu Exp $
 */
namespace BackEnd\Process\ImportFeed;

use BackEnd\Process\Exception\ImportFeedException;
use BackEnd\Model;

class ImportMerchantFeedProcess extends ImportFeedProcess 
{
    public function __construct($merid, $fileName) 
    {
        if (empty($merid)) {
            throw new ImportFeedException('need Merchant id');
        }
        $this->merid = $merid;
        $this->affiliateID = 0;
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
        $this->merchantTable = new Model\Merchant\MerchantTable($adapter);
        $merchantInfo = $this->merchantTable->getInfoById($this->merid);
        if ($merchantInfo['IsActive'] == 'NO') {
            throw new ImportFeedException('merchant invalid');
        }
    } 
}
?>

