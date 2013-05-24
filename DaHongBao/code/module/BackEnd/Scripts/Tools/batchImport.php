<?php
/*
 * package_name : overseaTool.php
 * ------------------
 * feed 下载
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: batchImport.php,v 1.3 2013/05/15 03:03:09 thomas_fu Exp $
 */

// Setup autoloading
require dirname(__DIR__) . '/init.php';

use BackEnd\Process\CouponProcess;
use BackEnd\Model\ProcessRuntimeTable;
use Custom\Util\Utilities;

$startTime = time();
Utilities::println(Utilities::getDateTime('Y-m-d H:i:s') . ',  starting process ...');

$serviceManger = $application->getServiceManager();
$adapter = $serviceManger->get('Custom\Db\Adapter\DefaultAdapter');
$processRuntimeTable = new ProcessRuntimeTable($adapter);
try {
//     $processRuntimeTable->lock('BatchImport');
    $autoProcess = new CouponProcess($serviceManger);
    $autoProcess->executeAll(); 
} catch (Exception $e) {
    print_r($e->getMessage());
    print_r($e->getTraceAsString());
}
//4 解锁
$processRuntimeTable->unLock('BatchImport');

Utilities::println('Done, Use time = ' . (round(microtime(true) - $startTime)));
?>