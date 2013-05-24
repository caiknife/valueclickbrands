<?php
/**
 * function.url.php
 *-------------------------
 *
 * typecomment
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Space Ver1.0
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @author     Rollenc <rollenc_luo@mezimedia.com>
 * @copyright  (C) 2004-2008 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: function.url.php,v 1.1 2013/04/15 10:58:01 rock Exp $
 * @link       http://www.smarter.com.cn/
 */

function smarty_function_url($params, &$smarty)
{
	$f = $params['f'];
	unset($params['f']);
	if(! function_exists(array('UrlManager', $f))) {
		//throw new Exception("URLManager::$f Not exists");
	}
	echo UrlManager::$f($params);

}