<?php
/*
 * package_name : batchDownloadFeed.php
 * ------------------
 * feed 下载
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: batchDownloadFeed.php,v 1.1 2013/04/15 10:57:14 rock Exp $
 */

// Setup autoloading
require dirname(__DIR__) . '/init.php';

use BackEnd\Model\ProcessRuntimeTable;
use Custom\Util\Utilities;
use BackEnd\Model;
use BackEnd\Process\Process;

$startTime = microtime(true);
$startDateTime = Utilities::getDateTime();
Utilities::println($startDateTime . ',  starting process ...');

$serviceManger = $application->getServiceManager();
$adapter = $serviceManger->get('Custom\Db\Adapter\DefaultAdapter');
$affiliateTable = new Model\FeedConfig\AffiliateTable($adapter);
$processRuntimeTable = new Model\ProcessRuntimeTable($adapter);

$process = new Process();
//加锁
$processRuntimeTable->lock('DownLoadFeed');

try {
    $affiliateList = $affiliateTable->getAutoDownFeedList();
    foreach ($affiliateList as $affiliateInfo) {
        $affiliateID = $affiliateInfo['ID'];
        Utilities::println($startDateTime . ' Startup  AffiliateID = ' . $affiliateID);
        $scriptPath = __DIR__;
        $cmd = "php {$scriptPath}/downloadFeed.php \"affiliateID={$affiliateID}\"";
        $outFileLog = $process->getStdoutFile($affiliateID, 'Affiliate', 'downloadFeedLog');
        //加锁
        $processRuntimeTable->lock('DownLoadFeed', $affiliateInfo['FeedName'], '', $cmd, $outFileLog);
        Utilities::sysCall($cmd, true, $outFileLog);
    }
} catch (\Exception $e) {
    $errMsg = $e->getMessage() . $e->getTraceAsString();
    print_r($errMsg);
}

//解锁
$processRuntimeTable->unLock('DownLoadFeed');

Utilities::println('End use time = ' . (round(microtime(true) - $startTime)));
?>