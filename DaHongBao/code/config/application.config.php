<?php
return array(
    'modules' => array(
        'FrontEnd',
        'BackEnd',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,beta,local}.php',
        ),
        'module_paths' => array(
            './module',
        ),
        'configCacheEnabled' => false,
        'cacheDir' => 'data/cache',
    ),
);
