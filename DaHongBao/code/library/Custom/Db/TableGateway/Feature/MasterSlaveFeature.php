<?php
/*
 * package_name : MasterSlaveFeature.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: MasterSlaveFeature.php,v 1.3 2013/04/16 13:07:14 rizhang Exp $
 */
namespace Custom\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature;

class MasterSlaveFeature extends Feature\MasterSlaveFeature
{
    
    /**
     * preSelect()
     * Replace adapter with slave temporarily
     */
    public function preSelect()
    {
        $this->tableGateway->sql->setAdapter($this->slaveAdapter);
    }

    public function postSelect()
    {
        $this->tableGateway->sql->setAdapter($this->masterAdapter);
    }
}
?>