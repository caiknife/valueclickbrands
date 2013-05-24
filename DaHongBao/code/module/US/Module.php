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
 * @Version  : CVS: $Id: Module.php,v 1.6 2013/04/22 06:34:40 rizhang Exp $
 */
namespace US;

use Zend\Mvc\MvcEvent;
use Custom\Util\PathManager;
use Custom\Util\TrackingFE;

class Module
{
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

    public function onBootstrap($e)
    {
        $app = $e->getParam('application');
        $app->getEventManager()->attach(MvcEvent::EVENT_DISPATCH_ERROR, function($e) {
            $sm = $e->getApplication()->getServiceManager();
            if ($e->getParam('exception')) {
                $e->getResponse()->setStatusCode(404);
                $report = sprintf("Exception: %s\nTrace:\n%s\n", 
                    $e->getParam('exception')->getMessage(), 
                    $e->getParam('exception')->getTraceAsString()
                );
                $sm->get('log')->err($report);
            }
            TrackingFE::execNotFoundHeader('404');
        });

        // image
        $config = $app->getServiceManager()->get('Config');
        $this->siteConfg = $config['siteUrlConfig']($_SERVER['HTTP_HOST']);
        define("__IMAGE_DOMAIN_NAME", $this->siteConfg['__IMAGE_DOMAIN_NAME']);

        // semTag
        $this->semTag = $config['semTag'];
        define("TAG_SERVICE_BASE_URL", $this->semTag['TAG_SERVICE_BASE_URL']);
        define("__BAIDU_TAG_SERVICE",  $this->semTag['__BAIDU_TAG_SERVICE']);

        $app->getEventManager()->attach(MvcEvent::EVENT_RENDER, array($this, 'setDefaultView'), 100);
    }

    public function setDefaultView($event)
    {
        // 定义常量
        $config = $event->getApplication()->getServiceManager()->get('Config');
        $this->siteConfg = $config['siteUrlConfig']($_SERVER['HTTP_HOST']);
        $this->siteConfg['helpCenterUrl']  = PathManager::getArticleCateUrl(); // 帮助中心

        // 模板赋值
        $viewModel = $event->getViewModel();
        $viewModel->setVariables(array(
            'siteConfg' => $this->siteConfg,
            'sessionId' => \Tracking_Session::getInstance()->getSessionId(),
            'requestId' => \Tracking_Session::getInstance()->getRequestId(),
        ));
    }
}
?>