<?php
/*
* package_name : SubscriptionController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: SubscriptionController.php,v 1.2 2013/04/20 09:50:44 thomas_fu Exp $
*/
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;
use CommModel\Subscription\Subscription;
use Custom\Util\Utilities;

class SubscriptionController extends UsController 
{
    /*
     * 用户订阅 ajax调用
     */
    public function indexAction()
    {
        $email = $this->params()->fromQuery('email');
        preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/", $email, $match);
        if (empty($match)) {
            throw new \Exception("Subscription email: {$email} error");
        }
        //判断是否已经订阅
        $subscriptionTable = $this->getSubscriptionTable();
        $result = $subscriptionTable->isExistEmail($email, self::SITEID);
        $uid = $_COOKIE['UID'] ? $_COOKIE['UID'] : 0;
        if (empty($result)) {
            $insertValues = array(
                'Email'  => $email,
                'UID'    => $uid,
                'SiteID' => self::SITEID,
                'CreatDateTime' => Utilities::getDateTime('Y-m-d H:i:s'),
                'LastChangeDateTime' => Utilities::getDateTime('Y-m-d H:i:s'),
            );
            $subscriptionTable->insert($insertValues);
            $msg = $email."订阅成功";
        }else{
            $msg = $email."已经被使用";
        }
        echo $msg; exit;
    }

    private function getSubscriptionTable(){
        return $this->getServiceLocator()->get('CommModel\Subscription\Subscription');
    } 
	
}
?>