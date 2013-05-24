<?php
/**
 * class.template.php
 *-------------------------
 *
 * The index page of the site,show all the channels.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive shopping engine
 * that helps consumers to make smarter buying decisions online. We empower consumers to compare
 * the attributes of over one million products in the computer and consumer electronics categories
 * and to read user product reviews in order to make informed purchase decisions. Consumers can then
 * research the latest promotional and pricing information on products listed at a wide selection of
 * online merchants, and read user reviews on those merchants.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2005, Mezimedia. All rights reserved.
 *
 * @author     Genray <genray@mezimedia.com>
 * @copyright  (C) 2004-2005 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: class.Template.php,v 1.1 2013/04/15 10:57:54 rock Exp $
 * @link       http://www.smarter.com/
 * @deprecated File deprecated in Release 2.0.0
 */
require_once dirname(__FILE__)."/class.Debug.php";
require_once('Smarty.class.php');
class sTemplate extends Smarty
{
	function sTemplate() 
	{
		$this->template_dir = __ROOT_TPLS_TPATH;
		$this->compile_dir  = __FILE_FULLPATH."tplcache/templates_c/";
		$this->config_dir   = __SETTING_FULLPATH."tplcache/configs/";
		$this->cache_dir    = __FILE_FULLPATH."tplcache/smartycache/";
		$this->debugging    = Debug::isShowTplDebug();
		parent::Smarty();
	}
	
	function setTemplate($tpl_name)
	{
		$this->tpl_name = $tpl_name;
		$this->assign("__Top_Navigation", array(
        	array('Name'=>'优惠券', 'URL'=>'/'),
        	array('Name'=>'折扣信息', 'URL'=>'/discount_hot.html'),
        	array('Name'=>'劲爆特价', 'URL'=>'/onlinediscount.html'),
        	array('Name'=>'旅游度假', 'URL'=>'/travel.html'),
        	array('Name'=>'社区', 'URL'=>'/bbs/'),
        	));
	}

    function displayTemplate()
    {
        $this->display($this->tpl_name);
    }

	function bulkAssign($array) {
        while (list($key, $value) = each($array)) {
            $this->assign($key, $value);
        }
    }
    
    

    function getTemplateContents()
    {
        return $this->fetch($this->tpl_name);
    }
}

?>