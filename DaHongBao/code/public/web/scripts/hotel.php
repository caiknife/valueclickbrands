<?php
/*
 * Created on 2009-12-10
 * travel.php
 * -------------------------
 * 酒店人口
 * 
 * 
 * @author thomas fu
 * @email thomas_fu@mezimedia.com;
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: hotel.php,v 1.1 2013/04/15 10:58:06 rock Exp $
 * @link       http://www.smarter.com/
 */

require_once("../etc/const.inc.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");

//创建Controller层对象
$action = new HotelAction();
//调用执行函数
$action->execute();

?>