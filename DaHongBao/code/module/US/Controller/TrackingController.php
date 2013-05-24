<?php
/*
* package_name : TrackingController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: TrackingController.php,v 1.1 2013/04/18 13:27:48 rizhang Exp $
*/
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;

class TrackingController extends UsController 
{
    public function indexAction()
    {
        chdir(dirname(__DIR__));
        require '../../library/Track/scripts/redir.php';
        exit;
    }

    public function asyncAction()
    {
        chdir(dirname(__DIR__));
        require '../../library/Track/scripts/async_tracker.php';
        exit;
    }
}
?>