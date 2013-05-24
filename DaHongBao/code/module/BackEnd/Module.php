<?php
/*
 * package_name : Module.php
* ------------------
* typecomment
*
* PHP versions 5
*
* @Author   : thomas(thomas_fu@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: Module.php,v 1.6 2013/04/25 03:44:37 yjiang Exp $
*/
namespace BackEnd;

use BackEnd\Model\Users\MyAcl;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Session\Container;

use Zend\EventManager\EventInterface as Event;

class Module
{
    function onBootstrap(Event $e){
        $this->bootstrapSession($e);
        $app = $e->getApplication();
        $em = $app->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH_ERROR, function($e) {
            $sm = $e->getApplication()->getServiceManager();
            if ($e->getParam('exception')) {
                $e->getResponse()->setStatusCode(404);
                $report = sprintf("Exception: %s\nTrace:\n%s\n", 
                    $e->getParam('exception')->getMessage(), 
                    $e->getParam('exception')->getTraceAsString()
                );
                $sm->get('log')->err($report);
            }
        });
        $em->attach(MvcEvent::EVENT_ROUTE, function($e) use ($em){
            $sm = $e->getApplication()->getServiceManager();
            $route = $e->getRouteMatch();
            $controller = $route->getParam('controller');
            $action = $route->getParam('action');
            $session = new Container('user');
            if($controller && $action){
                //验证是否登录
                if($controller == 'login' || ($controller == 'acl' && $action == 'clearCache') 
                    || $controller == 'ajax'
                    || (isset($session->UserID) && $session->Role == 'admin')){
                    return;
                }
                if(!isset($session->UserID)){
                    header('Location:/login');
                    exit;
                }
                //验证ACL
                $controller = strtolower($controller);
                $resource = $controller . '_' . $action;
                $myAcl = $sm->get('acl');
                try{
                    if(empty($session->Role) || !$myAcl->acl->isAllowed($session->Role 
                        , $resource)){
                        throw new \Exception('Not Allow');
                    }
                }catch(\Exception $err){
                    $e->setError('error');
                    $e->setParam('exception', $err);
                    $em->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
                }
            }
        });
        
    }
    public function bootstrapSession($e)
    {
        
        $session = $e->getApplication()
        ->getServiceManager()
        ->get('Zend\Session\SessionManager');
        $session->start();
        
        $container = new Container('initialized');
        if (!isset($container->init)) {
             $session->regenerateId(true);
             $container->init = 1;
        }
    }
    
    public function writeErroLog(MvcEvent $e) 
    {
       
    }
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/Config/module.config.php';
    }

    public function getServiceConfig(){
        return include __DIR__ . '/Config/service.config.php';
    }
    
    public function getViewHelperConfig(){
        return include __DIR__ . '/Config/viewhelper.config.php';
    }
    
    public function getControllerConfig(){
        return include __DIR__ . '/Config/controller.config.php';
    }
}