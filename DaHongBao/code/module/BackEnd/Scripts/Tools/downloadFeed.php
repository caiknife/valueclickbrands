<?php
/*
 * package_name : downloadFeed.php
 * ------------------
 * 下载单个联盟商家FEED
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: downloadFeed.php,v 1.1 2013/04/15 10:57:14 rock Exp $
 */
 
// Setup autoloading
require dirname(__DIR__) . '/init.php';

use BackEnd\Model\ProcessRuntimeTable;
use Custom\Util\Utilities;
use BackEnd\Model;

$startTime = microtime(true);
$startDateTime = Utilities::getDateTime();
Utilities::println($startDateTime . ',  starting process ...');

$affiliateID = '';

parse_str($argv[1]);
if ($affiliateID <= 0) {
    Utilities::println('param error, need affiliateid');
    exit;
}
$serviceManger = $application->getServiceManager();
$adapter = $serviceManger->get('Custom\Db\Adapter\DefaultAdapter');
$affiliateTable = new Model\FeedConfig\AffiliateTable($adapter);
$processRuntimeTable = new Model\ProcessRuntimeTable($adapter);
$affiliateFeedConfigTable = new Model\FeedConfig\AffiliateFeedConfigTable($adapter);

$affiliateInfo = $affiliateTable->getInfoByID($affiliateID);
if (empty($affiliateInfo)) {
    Utilities::println("Error, Not exist affiliateid = " . $affiliateID);
    exit;
}

try {
    $update['DownFeedFileRunStatus'] = 'RUNNING';

    //下载之前更新下载状态
    $affiliateFeedConfigTable->replace($update, $affiliateID);
        
    //执行下载
    $className = ucfirst(strtolower($affiliateInfo['FeedName'])) . 'Feed';
    $className = "BackEnd\\Process\\DownloadFeed\\" . $className;
    $feedObj = new $className();
    $feedObj->setServiceManager($serviceManger);
        
    //设置为debug模式
//     $feedObj->setDebug(true);
    $feedObj->parseFeed();
        
    //更新下载数据
    $update['DownFeedFileRunStatus'] = 'SUCCESS';
    $statInfo = $feedObj->statInfo();
    $update['DownFeedFileStartTime'] = $startDateTime;
    $update['DownFeedFileEndTime'] = Utilities::getDateTime();
    $update['DownFeedFileName'] = $statInfo['FileName'];
    $update['DownFeedFileTotalCount'] = $statInfo['SuccessCnt'];
    $affiliateFeedConfigTable->replace($update, $affiliateID);
} catch (\Exception $e) {
    $errMsg = $e->getMessage() . $e->getTraceAsString();
    $statInfo = $feedObj->statInfo();
    $update['DownFeedFileRunStatus'] = 'FAIL';
    $update['DownFeedFileName'] = $statInfo['FileName'];
    $update['DownFeedRunDetailInfo'] = $errMsg;
    $affiliateFeedConfigTable->replace($update, $affiliateID);
    $processRuntimeTable->unLock('DownLoadFeed', $affiliateInfo['FeedName']);
    print_r($errMsg);
}

print_r(var_export($statInfo, true));
//解锁
$processRuntimeTable->unLock('DownLoadFeed', $affiliateInfo['FeedName']);

Utilities::println('End use time = ' . (round(microtime(true) - $startTime)));
?>