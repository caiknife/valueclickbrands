<?php
/*
 * package_name : PictureTable.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: ProcessRuntimeTable.php,v 1.2 2013/04/20 09:50:44 thomas_fu Exp $
 */

namespace BackEnd\Model;

use Custom\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Custom\Util\Utilities;

class ProcessRuntimeTable extends TableGateway
{
    protected $table = 'ProcessRuntime';
    
    /**
     * 列出运行中的任务
     */
    public function listRunning() 
    {
        $select = $this->getSql()->select();
        $select->order('ID DESC');
        return $this->selectWith($select);
    }
    
    /**
     * 加锁
     * @param string $section
     * @param string $lockKey
     * @param string $pid
     * @param string $command
     * @param string $stdout
     */
    public function lock($section, $lockKey="", $pid="", $command="", $stdout="")
    {
        //check exists
        $select = $this->getSql()->select();
        $select->where(array('Section' => $section, 'LockKey' => $lockKey));
        $lockRows = $this->selectWith($select)->current();
        if (!empty($lockRows)) {
            throw new \Exception("Locked. (Section=$section,LockKey=$lockKey)");
        }
        $data = array(
            'Section'           => $section,
            'LockKey'           => $lockKey,
            'PID'               => $pid,
            'Command'           => $command,
            'StdoutFileName'    => $stdout,
            'StartTime'         => Utilities::getDateTime('Y-m-d H:i:s'),
        );
        return $this->insert($data);
    }
    
    /**
     * 解锁
     * @param string $section
     * @param string $lockKey
     */
    public function unlock($section, $lockKey = "") 
    {
        return $this->delete(array('Section' => $section, 'LockKey' => $lockKey));
    }
    
    /**
     * @param string $section
     * @param string $lockKey
     * @return count
     */
    public function getThreadCount($section, $lockKey = NULL) {
        $select = $this->getSql()->select();
        $select->columns(array('ThreadCnt' => new Expression('count(*)')));
        $select->where(array('Section' => $section));
        if ($lockKey != NULL) {
            $select->where->like('LockKey', $lockKey . '%');
        }
        return $this->selectWith($select)->current()->ThreadCnt;
    }
}
?>