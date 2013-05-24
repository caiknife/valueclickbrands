<?php
/*
 * package_name : local.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: local.php,v 1.6 2013/05/20 02:44:46 yjiang Exp $
 */
return array (
    'db' => array (
        'smcninternal' => array (
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=smcninternal;host=mmfdb101.dev.sha.mezimedia.com',
            'username' => 'smartercn',
            'password' => 'any',
            'driver_options' => array (
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
            )
        ),
        'default' => array (
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=dahongbao_FrontEnd_UTF8;host=mmfdb101.dev.sha.mezimedia.com',
            'username' => 'smartercn',
            'password' => 'any',
            'driver_options' => array (
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
            )
        ),
        'defaultSlave' => array (
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=dahongbao_FrontEnd_UTF8;host=mmfdb101.dev.sha.mezimedia.com',
                'username' => 'smartercn',
                'password' => 'any',
                'driver_options' => array (
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                )
        ),
        'cmus' => array (
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=CMUSv3;host=10.32.5.100',
            'username' => 'we_beta',
            'password' => 'W3beTa0B',
            'driver_options' => array (
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
            ) 
        ),
        'user' => array (
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=smartercn_User;host=mmfdb101.dev.sha.mezimedia.com',
            'username' => 'smartercn',
            'password' => 'any',
            'driver_options' => array (
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
            )
        ),
        
        'smartercn_ETL_UTF8' => array (
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=smartercn_ETL_UTF8;host=mmfdb101.dev.sha.mezimedia.com',
            'username' => 'smartercn',
            'password' => 'any',
            'driver_options' => array (
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            )
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Custom\Db\Adapter\DefaultAdapter' => 'Custom\Db\Adapter\DefaultAdapter',
            'Custom\Db\Adapter\UserAdapter'    => 'Custom\Db\Adapter\UserAdapter',
            'Custom\Db\Adapter\DefaultSlaveAdapter'    => 'Custom\Db\Adapter\DefaultSlaveAdapter',
        ),
    ),
    'cache' => array (
        'adapter' => array(
            'name' => 'filesystem',
            'options' => array(
                'cache_dir' => 'data/files/cache',
            )
        ),
    ),
    'siteUrlConfig' => function($hostname) {
        if (preg_match('/.*?([0-9]+)\./i', $hostname, $match)) {
            $siteID = $match[1];
        } else {
            $siteID = 2;
        }
        $siteUrlArr = array(
            '__USER_DOMAIN'    => "http://udev{$siteID}.dahongbao.com",
            '__DHB_DOMAIN'     => "http://dev{$siteID}.dahongbao.com",
            '__DHBUS_DOMAIN'   => "http://usdev{$siteID}.dahongbao.com",
            '__IMAGE_DOMAIN_NAME' => 'http://images.dahongbao.com',
        );
        return $siteUrlArr;
    },
    'semTag' => array(
        'TAG_SERVICE_BASE_URL' => 'http://semtag101.beta.wl.mezimedia.com:8890',
        '__BAIDU_TAG_SERVICE'  => 'http://semtag101.beta.wl.mezimedia.com:8891',
    )
);
?>