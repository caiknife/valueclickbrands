<?php
/**
 * Mezi Framework
 *
 * @category   Mezi
 * @package    Mezi_Utility
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Utility.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 */

/**
 * common functionality for all Mezi subpackages.
 *
 * @category   Mezi
 * @package    Mezi_Utility
 */
class Mezi_Utility
{
    /**
     * generate a unique string
     *
     * @param string $salt
     * @return string
     */
    public static function generateUniqueId($salt = '')
    {
        return md5(uniqid($salt . mt_rand(), TRUE));
    }
}