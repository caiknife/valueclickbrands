<?php
/*
 * Created on 2009-12-10
 * travel.php
 * -------------------------
 * �Ƶ��˿�
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

//����Controller�����
$action = new HotelAction();
//����ִ�к���
$action->execute();

?>