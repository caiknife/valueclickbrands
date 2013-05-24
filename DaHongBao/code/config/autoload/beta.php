<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array (
    'db' => array (
        'smcninternal' => array (
            'driver' => 'Pdo',
             'dsn' => 'mysql:dbname=smcninternal;host=10.40.3.11',
            'username' => 'smartercn',
            'password' => 'dkjO4Z1T',
            'driver_options' => array (
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
            )
        ),
        'default' => array (
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=dahongbao_FrontEnd_UTF8;host=mmfdb101.beta.sha.mezimedia.com;port=3308',
            'username' => 'dahongbao',
            'password' => 'E4hiUO5E',
            'driver_options' => array (
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
            )
        ),
        'defaultSlave' => array (
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=dahongbao_FrontEnd_UTF8;host=mmfdb101.beta.sha.mezimedia.com;port=3308',
                'username' => 'dahongbao',
                'password' => 'E4hiUO5E',
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
            'dsn' => 'mysql:dbname=smartercn_User;host=mmfdb101.beta.sha.mezimedia.com;port=3308',
            'username' => 'smcn_user',
            'password' => 'Q1u5m2py',
            'driver_options' => array (
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
            )
        ),
        'smartercn_ETL_UTF8' => array (
            'driver' => 'Pdo',
            'dsn' => 'mysql:dbname=smartercn_ETL_UTF8;host=10.40.3.21',
            'username' => 'smartercn',
            'password' => 'dkjO4Z1T',
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
        $siteUrlArr = array(
            '__USER_DOMAIN'    => "http://ubeta.dahongbao.com",
            '__DHB_DOMAIN'     => "http://beta.dahongbao.com",
            '__DHBUS_DOMAIN'   => "http://usbeta.dahongbao.com",
            '__IMAGE_DOMAIN_NAME' => 'http://images.dahongbao.com',
        );
        return $siteUrlArr;
    },
    'semTag' => array(
        'TAG_SERVICE_BASE_URL' => 'http://semtag101.beta.wl.mezimedia.com:8890',
        '__BAIDU_TAG_SERVICE'  => 'http://semtag101.beta.wl.mezimedia.com:8891',
    )
);
