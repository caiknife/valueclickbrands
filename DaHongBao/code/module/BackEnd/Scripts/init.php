<?php
/*
 * package_name : init.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: init.php,v 1.1 2013/04/15 10:57:14 rock Exp $
 */
error_reporting( E_ALL & ~E_NOTICE );
ini_set('memory_limit', '1024M');

define('APPLICATION_PATH', dirname(__DIR__) . '/../../');
chdir(APPLICATION_PATH);

require 'config/init_autoloader.php';

//init application
$application = Zend\Mvc\Application::init(require 'config/application.config.php');
?>