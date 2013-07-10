<?php

/**
 * common functionality for all Mezi subpackages.
 *
 * @category Mezi
 * @package Mezi_Utility
 */
class Mezi_Utility
{

    private static $old_hash = 'q6RClj%p1PdwYhemFcXOrSQkNWbv9It0LfguVz84xyU3K+i5M7EToZA2naDHsBJG';
    
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

    public static function encrypt ($str, $key = null)
    {
        $str = urlencode($str);
        $str = strtr($str, self::$old_hash, self::$new_hash);
        return $str;
    }

    public static function decrypt ($str, $key = null)
    {
        $str = strtr($str, self::$new_hash, self::$old_hash);
        $str = urldecode($str);
        return $str;
    }

    private static function keyED ($txt, $encrypt_key)
    {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i ++) {
            if ($ctr == strlen($encrypt_key)) $ctr = 0;
            $tmp .= substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1);
            $ctr ++;
        }
        return $tmp;
    }

    public static function encrypt_bak ($txt, $key)
    {
        srand((double) microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i ++) {
            if ($ctr == strlen($encrypt_key)) $ctr = 0;
            $tmp .= substr($encrypt_key, $ctr, 1) . (substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1));
            $ctr ++;
        }
        return urlencode(base64_encode(self::keyED($tmp, $key)));
    }

    public static function decrypt_bak ($txt, $key)
    {
        $txt = base64_decode(urldecode($txt));
        $txt = self::keyED($txt, $key);
        $tmp = "";
        for ($i = 0; $i < strlen($txt); $i ++) {
            $md5 = substr($txt, $i, 1);
            $i ++;
            $tmp .= (substr($txt, $i, 1) ^ $md5);
        }
        return $tmp;
    }
}
