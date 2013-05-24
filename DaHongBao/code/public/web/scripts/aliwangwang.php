<?php 
/**
 * search.php
 *-------------------------
 *
 * do action
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive shopping engine 
 * that helps consumers to make smarter buying decisions online. We empower consumers to compare 
 * the attributes of over one million products in the common channels and common categories
 * and to read user product reviews in order to make informed purchase decisions. Consumers can then 
 * research the latest promotional and pricing information on products listed at a wide selection of 
 * online merchants, and read user reviews on those merchants.
 * The copyrights is reserved by http://www.mezimedia.com.  
 * Copyright (c) 2006, Mezimedia. All rights reserved.
 *
 * @author     cooc <cooc_luo@smater.com.cn>
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: aliwangwang.php,v 1.1 2013/04/15 10:58:05 rock Exp $
 * @link       http://www.smarter.com.cn/
 * @deprecated File deprecated in Release 3.0.0
 */

require_once("../etc/const.inc.php");
require_once(__ROOT_PATH . "lib/functions/func.Common.php");


//创建Controller层对象
$action = new AliwangwangAction();
//调用执行函数
$action->execute();

?>