<?php
return array(
    'server1' => array(
        'host' => 'la-dc01.corp.valueclick.com',
        'port' => '3268',
        'useSsl' => FALSE,
        'accountDomainName' => 'corp.valueclick.com',
        'accountDomainNameShort' => 'CORP',
        'accountCanonicalForm' => '4',
        'accountFilterFormat' => '(&(objectClass=user)(sAMAccountName=%s))',
        'baseDn' => 'DC=valueclick,DC=com',
        'bindRequiresDn' => FALSE,
    ),
);