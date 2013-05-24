<?php
/*
 * package_name : executeCoupon.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: CouponProcess.php,v 1.3 2013/05/15 02:33:55 thomas_fu Exp $
 */
namespace BackEnd\Process;

use BackEnd\Process\ImportFeed;
use BackEnd\Process\ImportCoupon\ImportAffiliateCouponProcess;
use BackEnd\Process\Excetpion\CouponProcessException; 
use BackEnd\Process\GetFeedFileProcess;
use BackEnd\Model;
use Custom\Util\Utilities;

class CouponProcess extends Process
{
    /**
     * @param ServiceManager @sm
     */
    public function __construct($sm) 
    {
        if (!$sm instanceof \Zend\ServiceManager\ServiceLocatorInterface) {
            throw new Exception\CouponProcessException('need init service manager ');
        }
        $this->setServiceManager($sm);
        $adapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
        $this->affiliateTable = new Model\FeedConfig\AffiliateTable($adapter);
    }
    
    /**
     * 执行所有商家导入
     */
    public function executeAll()
    {
        $newFeedList = $this->getNeedImportFeedMerchant();
        $adapter = $this->getServiceManager()->get('Custom\Db\Adapter\DefaultAdapter');
        $processRuntimeTable = new Model\ProcessRuntimeTable($adapter);
        for($loop=0,$runId=0; $loop<1000 && $runId<count($newFeedList); $loop++) {
            $threadCount = $processRuntimeTable->getThreadCount("ImportFeed");
            Utilities::println("already run process count is $threadCount.");
            for( ; $threadCount<3 && $runId<count($newFeedList); $threadCount++) {
                //启动任务
                if ($newFeedList[$runId]['Type'] == 'Merchant') {
                    $id = $merid = $newFeedList[$runId]['MerID'];
                    $affiliateID = 0;
                    $lockKey = $newFeedList[$runId]['Type'] . '_' . $merid;
                } else {
                    $id = $affiliateID = $newFeedList[$runId]['AffiliateID'];
                    $merid = 0;
                    $lockKey = $newFeedList[$runId]['Type'] . '_' . $affiliateID;
                }
                $csvFile = $newFeedList[$runId]['NewFile'];
                Utilities::println(
                    Utilities::getDateTime() . ' Startup  Type = ' . $newFeedList[$runId]['Type'] . ', ID = ' . $id
                );
                //execute php script
                $phpFile = dirname(__DIR__) . '/Scripts/Tools/autoImport.php';
                $cmd = "php {$phpFile} \"merid={$merid}&affiliateID={$affiliateID}&fileName={$csvFile}\"";
                $outFileName = $this->getStdoutFile($id, $newFeedList[$runId]['Type'], 'importFeedLog');
                $processRuntimeTable->lock('ImportFeed', $lockKey, '', $cmd, $outFileName);
                Utilities::sysCall(
                    $cmd,
                    true,
                    $outFileName
                );
                $runId++;
                sleep(3); //等待3秒
            }
            sleep(1 * 60); //等待1分钟
        }
    }
    
    /**
     * 执行单个商家导入流程
     * @param int $merid
     * @param string $fileName
     */
    public function merchantExecute($merid, $fileName = null) 
    {
        if (empty($merid)) {
            throw new Exception\CouponProcessException('coupon execute process need merchant id');
        }
        $merid = $merid * 1;
        if ($fileName == null) {
            $feedFileProcess = new GetFeedFileProcess();
            $fileName = $feedFileProcess->getNewFileByFeedPath($merid, 'MERCHANT');
        }
        $this->importFeedProcess = new ImportFeed\ImportMerchantFeedProcess($merid, $fileName);
        
        //设置 serviceManger
        $this->importFeedProcess->init($this->getServiceManager());
        
        //导入到临时表UserDataFeed
        $this->importFeedProcess->execute();
        
        //用于前台测试。 上线需要删除
//         $this->importCouponProcess = new \BackEnd\Process\ImportCoupon\ImportMerchantCouponProcess($merid);
//         $this->importCouponProcess->init($this->getServiceManager());
//         $this->importCouponProcess->execute();
    }
    
    /**
     * 执行联盟FEED导入流程
     * @param int $affiliateID
     * @param string $fileName
     */
    public function affiliateExecute($affiliateID, $fileName = null) 
    {
        if (empty($affiliateID)) {
            throw new Exception\CouponProcessException('coupon execute process need affiliateID');
        }
        $affiliateID = $affiliateID * 1;
        $affiliateInfo = $this->affiliateTable->getInfoByID($affiliateID);
        if ($fileName == null) {
            $feedFileProcess = new GetFeedFileProcess();
            $fileName = $feedFileProcess->getNewFileByFeedPath($affiliateInfo['FeedName'], 'AFFILIATE');
        }

        $this->importFeedProcess = new ImportFeed\ImportAffiliateFeedProcess($affiliateID, $fileName);
        
        //设置初始化
        $this->importFeedProcess->init($this->getServiceManager());
        
        //导入到临时表UserDataFeed
        $this->importFeedProcess->execute();
        
        //来自于US联盟数据才可以直接上线
        if ($this->getUsSiteID() == $affiliateInfo['SiteID']) {
            $this->importCouponProcess = new ImportAffiliateCouponProcess($affiliateID);
            $this->importCouponProcess->init($this->getServiceManager());
            $this->importCouponProcess->execute();
        }
    }
    
    /**
     * 找出需要更新商家信息
     * @return array
     */
    public function getNeedImportFeedMerchant() 
    {
        $needImportMerFeed = array();
        $feedFileProcess = new GetFeedFileProcess();
        //找出需要导入联盟商家
        $adapter = $this->getServiceManager()->get('Custom\Db\Adapter\DefaultAdapter');
        $this->affiliateTable = new Model\FeedConfig\AffiliateTable($adapter);
        $this->affiliateFeedConfig = new Model\FeedConfig\AffiliateFeedConfigTable($adapter);
        foreach ($this->affiliateTable->getAutoDownFeedList() as $affiliateInfo) {
            //得到联盟配置
            $affiliateFeedConfig = $this->affiliateFeedConfig->getInfoByID($affiliateInfo['ID']);
            $newFile = $feedFileProcess->getNewFileByFeedPath($affiliateInfo['FeedName'], 'AFFILIATE');
            $newFileDateTime = $this->getFeedFileDateTime($newFile);
            if ($affiliateFeedConfig['ImportFeedFileDateTime'] < $newFileDateTime) {
                $needImportMerFeed[] = array(
                    'Type' => 'Affiliate',
                    'AffiliateID' => $affiliateInfo['ID'],
                    'NewFile'   => $newFile,
                );
            }
        }
        
        //执行导入所有商家Feed
        $this->merFeedConfigTable = new Model\FeedConfig\MerchantFeedConfigTable($adapter);
        $this->merchantTable = new Model\Merchant\MerchantTable($adapter);
        foreach ($this->merchantTable->getListByActive('YES') as $merInfo) {
            $newFile = $feedFileProcess->getNewFileByFeedPath($merInfo['MerchantID'], 'MERCHANT');
            $newFileDateTime = $this->getFeedFileDateTime($newFile);
            //获取feed配置信息
            $merFeedConfig = $this->merFeedConfigTable->getInfoByMerID($merInfo['MerchantID']);
            if (!empty($newFileDateTime) && $merFeedConfig['ImportFeedFileDateTime'] < $newFileDateTime) {
                $needImportMerFeed[] = array(
                    'Type' => 'Merchant',
                    'MerID' => $merInfo['MerchantID'],
                    'NewFile'   => $newFile,
                );
            }
        }
        print_r($needImportMerFeed);
        return $needImportMerFeed;
    }
    
    /**
     * 获取统计信息
     * @return array
     */
    public function getStatInfo() 
    {
        if (is_object($this->importFeedProcess)) {
            $statInfo['ImportFeed'] = $this->importFeedProcess->getStatInfo();
        } else {
            $statInfo['ImportFeed'] = array();
        }
        if (is_object($this->importCouponProcess)) {
            $statInfo['ImportCoupon'] = $this->importCouponProcess->getStatInfo();
        } else {
            $statInfo['ImportCoupon'] = array();
        }
        return $statInfo;
    }
}
?>