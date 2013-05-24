<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Redirector
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Async.php,v 1.1 2013/04/15 10:58:19 rock Exp $
 */

/**
 * Build redirect URL with given array
 *
 * @category   Tracking
 * @package    Tracking_Redirector
 */
class Tracking_Async
{
    /**
     * default redirector type
     */
    const DEFAULT_TYPE  = 'General';

    /**
     * create Tracking_Redirector by redirector type
     *
     * @param string $redirectorType
     * @return Tracking_Redirector_Abstract
     * @throw Tracking_Redirector_Exception
     */
    public static function factory($asyncType = self::DEFAULT_TYPE) {
        $asyncType = ucfirst(strtolower($asyncType));
        @include_once("Tracking/Async/$asyncType.php");
        if (class_exists($className = "Tracking_Async_$asyncType", FALSE)) {
             return new $className();
        } else {
            @include_once('Tracking/Async/' .  self::DEFAULT_TYPE . '.php');
            if (class_exists($className = 'Tracking_Async_' . self::DEFAULT_TYPE, FALSE)) {
               return new $className();
            } else {
               throw new Tracking_Async_Exception("Can NOT load $className!");
            }
        }
    }
}