<?php
/*
 * Created on 2010-01-06
 * travel.php
 * -------------------------
 * 
 * 
 * 
 * @author thomas fu
 * @email thomas_fu@mezimedia.com;
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: async_travelHotRegion.php,v 1.1 2013/04/15 10:58:05 rock Exp $
 * @link       http://www.smarter.com/
 */

require_once("../etc/const.inc.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");

//创建Controller层对象
$action = new TravelAction();
//调用执行函数
$action->execute("hotRegionAjax");

?>