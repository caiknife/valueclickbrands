<?php
/*
 * package_name : AlbumController.php
 * ------------------
 * typecomment
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: IndexController.php,v 1.1 2013/04/15 10:57:07 rock Exp $
 */
namespace BackEnd\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController 
{

    public function indexAction()
    {
        return new ViewModel();
    }
}
?>