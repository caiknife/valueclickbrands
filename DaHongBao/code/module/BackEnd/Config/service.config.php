<?php
use Zend\Session\Container;

use Zend\Session\SessionManager;

use BackEnd\Model;
use Zend\Db\ResultSet\ResultSet;
return array(
    'services' => array(
        'Auth' => new Zend\Authentication\AuthenticationService(new Zend\Authentication\Storage\NonPersistent() , new Zend\
            Authentication\Adapter\Ldap(include 'ldap.config.php')),
    ),
    'factories' => array(
        'UserTable' =>function($sm) {
            $adapterFactory = new Custom\Db\Adapter\SmcninternalAdapter();
            $dbAdapter = $adapterFactory->createService($sm);
            $result = new ResultSet();
            $result->setArrayObjectPrototype(new Model\Users\User());
            $table = new Model\Users\UserTable($dbAdapter , null , $result);
            
            return $table;
        },
        
        'RoleTable' => function ($sm){
            $adapterFactory = new Custom\Db\Adapter\SmcninternalAdapter();
            $dbAdapter = $adapterFactory->createService($sm);
            $result = new ResultSet();
            $result->setArrayObjectPrototype(new Model\Users\Role());
            $table = new Model\Users\RoleTable($dbAdapter , null , $result);
            
            return $table;
        },
        
        'ResourceTable' => function ($sm){
            $adapterFactory = new Custom\Db\Adapter\SmcninternalAdapter();
            $dbAdapter = $adapterFactory->createService($sm);
            $result = new ResultSet();
            $result->setArrayObjectPrototype(new Model\Users\Resource());
            $table = new Model\Users\ResourceTable($dbAdapter , null , $result);
            
            return $table;
        },
        
        'AclTable' => function ($sm){
            $adapterFactory = new Custom\Db\Adapter\SmcninternalAdapter();
            $dbAdapter = $adapterFactory->createService($sm);
            $result = new ResultSet();
            $result->setArrayObjectPrototype(new Model\Users\Acl());
            $table = new Model\Users\AclTable($dbAdapter , null , $result);
            
            return $table;
        },
        'SiteTable' => function ($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $result->setArrayObjectPrototype(new \CommModel\Site\Site());
            $table = new \CommModel\Site\SiteTable($dbAdapter , null , $result);
            
            return $table;
        },
        'ArticleCategoryTable' => function ($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $result->setArrayObjectPrototype(new \CommModel\Article\ArticleCategory());
            $table = new \CommModel\Article\ArticleCategoryTable($dbAdapter , null ,null , $result);
            
            return $table;
        },
        
        'ArticleTable' => function ($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $result->setArrayObjectPrototype(new \CommModel\Article\Article());
            $table = new \CommModel\Article\ArticleTable($dbAdapter , null , null , $result);
            
            return $table;
        },
        
        'MerchantTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Merchant\MerchantTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'AffiliateFeedConfigTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\FeedConfig\AffiliateFeedConfigTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'MerchantFeedConfigTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\FeedConfig\MerchantFeedConfigTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'AffiliateTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\FeedConfig\AffiliateTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'ProcessRuntimeTable' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\ProcessRuntimeTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'MerchantCategoryTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Merchant\MerchantCategoryTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'CategoryTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Category\CategoryTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'CouponTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Coupon\CouponTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'MerchantPaymentTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Merchant\MerchantPaymentTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'PaymentTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Merchant\PaymentTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'MerchantShippingTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Merchant\MerchantShippingTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'ShippingTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Merchant\ShippingTable($dbAdapter , null , null , $result);
            return $table;
        },
        'UserDataFeedTable' => function($sm)
        {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\FeedConfig\UserDataFeedTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'CouponCategoryTable' => function($sm)
        {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Coupon\CouponCategoryTable($dbAdapter , null , null , $result);
            return $table;
        },
        'CouponCodeTable' => function($sm)
        {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Coupon\CouponCodeTable($dbAdapter , null , null , $result);
            return $table;
        },
        'CouponExtraTable' => function($sm)
        {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Coupon\CouponExtraTable($dbAdapter , null , null , $result);
            return $table;
        },
        'CouponOperateDetailTable' => function($sm)
        {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Coupon\CouponOperateDetailTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'CouponSearchTable' => function($sm)
        {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Coupon\CouponSearchTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'MerchantAliasTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Merchant\MerchantAliasTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'RecommendTypeTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Recommend\RecommendTypeTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'RecommendTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $result = new ResultSet();
            $table = new Model\Recommend\RecommendTable($dbAdapter , null , null , $result);
            return $table;
        },
        
        'SmcnMerchantTable' => function($sm){
            $dbAdapter = $sm->get('Custom\Db\Adapter\SmcnAdapter');
            $result = new ResultSet();
            $table = new Model\Merchant\SmcnMerchantTable($dbAdapter , null , null , $result);
            return $table;
        },
        //缓存
        'cache' => function($sm){
            $config = $sm->get('Config');
            $path = $config['cache']['adapter']['options']['cache_dir'];
            if(!is_dir($path)){
                if(!mkdir($path , 0777 , true)){
                    throw new \Exception('无法创建' . $path);
                }
            }
            $cache = \Zend\Cache\StorageFactory::factory($config['cache']);
            return $cache;
        },
        
        //ACL
        'acl' => function($sm){
            $dbAdapterFactory = new Custom\Db\Adapter\SmcninternalAdapter();
            $dbAdapter = $dbAdapterFactory->createService($sm);
            
            return new \BackEnd\Model\Users\MyAcl($dbAdapter, $sm->get('cache'));
        },
        
        //Nav
        'backendNav' => '\Custom\Navigation\Service\BackendNavigation',
        
        'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        //add couponmountain adapter
        'Custom\Db\Adapter\CmusAdapter' => 'Custom\Db\Adapter\CmusAdapter',
        'Custom\Db\Adapter\SmcnAdapter' => 'Custom\Db\Adapter\SmcnAdapter',
        'Zend\Session\SessionManager' => function ($sm) {
            $config = $sm->get('config');
            if (isset($config['session'])) {
                $session = $config['session'];
    
                $sessionConfig = null;
                if (isset($session['config'])) {
                    $class = isset($session['config']['class'])  ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                    $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                    $sessionConfig = new $class();
                    $sessionConfig->setOptions($options);
                }
    
                $sessionStorage = null;
                if (isset($session['storage'])) {
                    $class = $session['storage'];
                    $sessionStorage = new $class();
                }
    
                $sessionSaveHandler = null;
                if (isset($session['save_handler'])) {
                    // class should be fetched from service manager since it will require constructor arguments
                    $sessionSaveHandler = $sm->get($session['save_handler']);
                }
    
                $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
    
                if (isset($session['validator'])) {
                    $chain = $sessionManager->getValidatorChain();
                    foreach ($session['validator'] as $validator) {
                        $validator = new $validator();
                        $chain->attach('session.validate', array($validator, 'isValid'));
    
                    }
                }
            } else {
                $sessionManager = new SessionManager();
            }
            Container::setDefaultManager($sessionManager);
            return $sessionManager;
        },
    ),
);