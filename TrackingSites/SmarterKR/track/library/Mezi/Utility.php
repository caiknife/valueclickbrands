<?php

/**
 * Mezi Framework
 *
 * @category Mezi
 * @package Mezi_Utility
 * @author Ben <ben_yan@mezimedia.com>
 * @copyright Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license http://www.mezimedia.com/license/
 * @version CVS: $Id: Utility.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
 */

/**
 * common functionality for all Mezi subpackages.
 *
 * @category Mezi
 * @package Mezi_Utility
 */
class Mezi_Utility
{

    private static $old_hash = 'q6RClj%p1PdwYhemFcXOrSQkNWbv9It0LfguVz84xyU3K+i5M7EToZA2naDHsBJG';

    //private static $new_hash = 'GJBsHDan2AZoTE7M5i.K3Uyx48zVugfL0tI9vbWNkQSrOXcFmehYwdP1p*jlCR6q';
    
    private static $new_hash = 'GJBsHDan2AZoTE7M5i$K3Uyx48zVugfL0tI9vbWNkQSrOXcFmehYwdP1p*jlCR6q';

    /**
     * generate a unique string
     *
     * @param string $salt
     * @return string
     */
    public static function generateUniqueId ($salt = '')
    {
        return md5(uniqid($salt . mt_rand(), TRUE));
    }

    public static function encrypt ($str)
    {
        $str = urlencode($str);
        $str = strtr($str, self::$old_hash, self::$new_hash);
        return $str;
    }

    public static function decrypt ($str)
    {
        $str = strtr($str, self::$new_hash, self::$old_hash);
        $str = urldecode($str);
        return $str;
    }
}