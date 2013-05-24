<?php
/*
* package_name : DbSelect.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: DbSelect.php,v 1.2 2013/04/27 10:29:02 yjiang Exp $
*/

namespace Custom\Paginator\Adapter;

use Zend\Paginator\Adapter\DbSelect as Father;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class DbSelect extends Father
{
    /**
     * Returns the total number of rows in the result set.
     *
     * @return integer
     */
    public function count()
    {
        if ($this->rowCount !== null) {
            return $this->rowCount;
        }

        $select = clone $this->select;
        $select->reset(Select::COLUMNS);
        $select->reset(Select::LIMIT);
        $select->reset(Select::OFFSET);
        $select->reset(Select::ORDER);

        $select->columns(array('c' => new Expression('COUNT(1)')));
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();
        $row       = $result->current();
        
        if($result->count() > 1){
            $this->rowCount = $result->count();
        }else{
            $this->rowCount = $row['c'];
        }

        return $this->rowCount;
    }
}
