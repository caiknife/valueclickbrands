<?php

/* base configuration */
if (!defined('TRACKING_ROOT_PATH')) {
    define('TRACKING_ROOT_PATH',        dirname(dirname(__FILE__)));
}

/* sets the include_path configuration option for the duration of the script */
$_includePath = array(TRACKING_ROOT_PATH . '/library', get_include_path(), '.');
set_include_path(implode(PATH_SEPARATOR, $_includePath));

require_once TRACKING_ROOT_PATH."/library/Mezi/Loader.php";
Mezi_Loader::registerAutoload();

/* check the environment */
if(!isset($env)) {
    $matches =  NULL;
    $domain = substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.'));
    if (preg_match("|([a-z]+)[0-9]*$domain|is", $_SERVER['HTTP_HOST'], $matches)) {
        $env = strtolower($matches[1]);
    } else {
        $env = 'www';
    }
}
$domain = preg_replace('/:\d+$/', '', $domain);

//setcookie('jacky','xxxx');
//setcookie('jacky2', 'yyyy', 0, '/', $domain,false,true);

/* load the configuration by environment*/
$options = array('readOnly' => FALSE);
$trackingConfig         = new Mezi_Config_Ini(TRACKING_ROOT_PATH . '/config/tracking.ini', $env, $options);
$trackingConfig->root   = TRACKING_ROOT_PATH;

if (empty($trackingConfig->session->domain)) {
    $trackingConfig->session->domain = $domain;
}

Mezi_Config::getInstance()->merge($trackingConfig,      'tracking');

if ((boolean)$trackingConfig->fraudfilter->enable) {
    $fraudFilterConfig  = new Mezi_Config_Ini(TRACKING_ROOT_PATH . '/config/fraudfilter.ini', $env, $options);
    Mezi_Config::getInstance()->merge($fraudFilterConfig, 'fraudFilter');
}

unset($trackingConfig);