<?php 
/*
 * Created on 2009-12-04
 * travel.php
 * -------------------------
 * 
 * 
 * 
 * @author thomas fu
 * @email thomas_fu@mezimedia.com;
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: travel_search.php,v 1.1 2013/04/15 10:58:07 rock Exp $
 * @link       http://www.smarter.com/
 */

require_once("../etc/const.inc.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");

//创建Controller层对象
if ($_REQUEST["tour"]) {
	$action = new TravelAction();
}
else if ($_REQUEST["hotel"]) {
	$action = new HotelAction();
}
else {
	$action = new TicketAction();
}
//调用执行函数
$action->execute("search");

?>