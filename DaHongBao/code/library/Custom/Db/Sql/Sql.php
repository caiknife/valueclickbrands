<?php
/*
 * package_name : Sql.php
 * ------------------
 * fixed slave db bug
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: Sql.php,v 1.1 2013/04/15 10:56:30 rock Exp $
 */
namespace Custom\Db\Sql;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql as ZendSql;

class Sql extends ZendSql
{
    public function setAdapter(Adapter $adapter) {
        $this->adapter = $adapter;
    }
} 
?>