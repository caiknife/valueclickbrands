<?php
/**
 * init.php -- initialisation file
 *
 * File should be loaded in every file in script/
 *
 *
 * @category   Tracking
 * @package    Tracking_Script
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: init.php,v 1.1 2013/06/27 07:50:20 zcai Exp $
 */

/* base configuration */
if (!defined('TRACKING_ROOT_PATH')) {
    define('TRACKING_ROOT_PATH',        dirname(dirname(__FILE__)));
}

/* sets the include_path configuration option for the duration of the script */
$_includePath = array(TRACKING_ROOT_PATH . '/library', get_include_path());
set_include_path(implode(PATH_SEPARATOR, $_includePath));

require_once 'Mezi/Loader.php';
Mezi_Loader::registerAutoload();

/* check the environment */
$matches =  NULL;
$domain = substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.'));
if (preg_match("|([a-z]+)[0-9]*$domain|is", $_SERVER['HTTP_HOST'], $matches)) {
    $env = strtolower($matches[1]);
} else {
    $env = 'www';
}
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