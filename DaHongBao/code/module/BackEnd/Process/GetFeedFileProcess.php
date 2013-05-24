<?php
/*
 * package_name : getFeedFileProcess.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: GetFeedFileProcess.php,v 1.3 2013/04/19 03:36:42 thomas_fu Exp $
 */
namespace BackEnd\Process;

use BackEnd\Process\Process as AbstractProcess;

class GetFeedFileProcess extends Process 
{
    /**
     * 返回文件下所有的文件，并按照时间排序
     * @param string $dirPath
     * @param string $feedType
     * @param string $fileType
     */
    public function getNewFileList($dirPath, $feedType = 'MERCHANT', $fileType = 'csv') 
    {
        foreach (glob("{$dirPath}/*.{$fileType}.[0-9]*") as $fileName) {
            $basename = basename($fileName);
            //可以根据规则提取日期作为KEY
            $fileNameKey = $this->getFeedFileDateTime($basename);
            $fileList[$fileNameKey] = $fileName;
        }
        if (empty($fileList)) {
            return array();
        }
        //返回有序的文件列表
        krsort($fileList);
        return $fileList;
    }
    
    /**
     * 根据FEED路径得到这文件下最新文件
     * @param string $feedPath
     * @return $fileName
     */
    public function getNewFileByFeedPath($feedPath, $feedType = 'MERCHANT') {
        if (empty($feedPath)) {
            return null;
        }
        if ($feedType == 'MERCHANT') {
            $dirPath = $this->getBackEndOptions('merchantFeedPath') . $feedPath . '/Discount';
        } else {
            $dirPath = $this->getBackEndOptions('affiliateFeedPath') . $feedPath;
        }
        $fileList = $this->getNewFileList($dirPath, $feedType);
        if (empty($fileList)) {
            return null;
        }
        $newFileName = array_shift($fileList);
        return $newFileName;
    }
}
?>