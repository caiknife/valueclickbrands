<?php
/*
* package_name : RedirController.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: RedirController.php,v 1.1 2013/04/15 10:57:19 rock Exp $
*/
namespace US\Controller;

use Custom\Mvc\Controller\UsController;
use Zend\View\Model\ViewModel;

class RedirController extends UsController 
{
    public function indexAction()
    {
        chdir(dirname(__DIR__));
        require '../../library/Track/scripts/redir.php';
        exit;
    }
}
?>