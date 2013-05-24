<?php

class Debug {
    
    public static function isDebugMode () {
        return isset($_REQUEST['debug']) && (bool)$_REQUEST['debug'] ? true : false;
    }
    
    public static function isShowTplDebug() {
        return isset($_REQUEST['tpldebug']) && (bool)$_REQUEST['tpldebug'] ? true : false;
    }
    
    public static function showErrors() {
        if (isset($_REQUEST['errordebug']) && (bool)$_REQUEST['errordebug']) {
            error_reporting(E_ALL);
        }
    }
    
    public static function pr($data, $info=null) {
        if (self::isDebugMode()) {
            if ($info) {
                echo '<pre>'; print_r($info.":"); echo '</pre>';
            }
            echo '<pre>'; print_r($data); echo '</pre>';
        }
    }
    
}