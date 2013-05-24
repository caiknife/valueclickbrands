<?php
/*
 * Created on 2009-5-7
 * prodlist.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @email fan_xu@mezimedia.com; x.huan@163.com
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: prodlist.php,v 1.1 2013/04/15 10:58:06 rock Exp $
 * @link       http://www.smarter.com/
 */

require_once("../etc/const.inc.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");

//创建Controller层对象
$action = new ProductListAction();
//调用执行函数
$action->execute();

?>