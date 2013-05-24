<?php

namespace Custom\View\Helper\Navigation;

/**
 * Menu.php
 * *-------------------------
 *
 *
 *
 * PHP versions 5
 *
 * LICENSE: This source file is from Smarter Ver2.0, which is a comprehensive
 * shopping engine
 * that helps consumers to make smarter buying decisions online. We empower
 * consumers to compare
 * the attributes of over one million products in the common channels and common
 * categories
 * and to read user product reviews in order to make informed purchase
 * decisions. Consumers can then
 * research the latest promotional and pricing information on products listed at
 * a wide selection of
 * online merchants, and read user reviews on those merchants.
 * The copyrights is reserved by http://www.mezimedia.com.
 * Copyright (c) 2006, Mezimedia. All rights reserved.
 *
 * @author Yaron Jiang <yjiang@corp.valueclick.com.cn>
 * @copyright (C) 2004-2013 Mezimedia.com
 * @license http://www.mezimedia.com PHP License 5.0
 * @version CVS: $Id: Menu.php,v 1.1 2013/04/15 10:56:31 rock Exp $
 * @link http://www.dahongbao.com/
 * @deprecated File deprecated in Release 3.0.0
 *            
 */
use Zend\View\Exception;
use \Zend\View\Helper\Navigation\Menu as Father;

class Menu extends Father
{
    public function renderPartial($container = null, $partial = null) {
        $this->parseContainer ( $container );
        if (null === $container) {
            $container = $this->getContainer ();
        }
        
        if (null === $partial) {
            $partial = $this->getPartial ();
        }
        
        if (empty ( $partial )) {
            throw new Exception\RuntimeException ( 'Unable to render menu: No partial view script provided' );
        }
        
        $found = $this->findActive($container);
        if ($found) {
            $foundPage  = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage = null;
        }
        
        $pages = array ();
        
        $iterator = new \RecursiveIteratorIterator ( $container, \RecursiveIteratorIterator::SELF_FIRST);
        
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();
            $isActive = $page->isActive(true);
            if ($depth < 0 || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibility
                continue;
            } 
            $page->depth = $depth;
            $page->isActive = $isActive;
            $pages[] = $page;
            
        }
        
        $model = array (
            'container' => $pages 
        );
        
        if (is_array ( $partial )) {
            if (count ( $partial ) != 2) {
                throw new Exception\InvalidArgumentException ( 'Unable to render menu: A view partial supplied as ' . 'an array must contain two values: partial view ' . 'script and module where script can be found' );
            }
            
            $partialHelper = $this->view->plugin ( 'partial' );
            return $partialHelper ( $partial [0], /*$partial[1], */$model );
        }
        
        $partialHelper = $this->view->plugin ( 'partial' );
        return $partialHelper ( $partial, $model );
    }
}