<?php

/**
 * Build redirect URL with given array
 *
 * @category   Tracking
 * @package    Tracking_Redirector
 */
class Tracking_Redirector
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
    public static function factory($redirectorType = self::DEFAULT_TYPE) {
        $redirectorType = ucfirst(strtolower($redirectorType));
        @include_once("Tracking/Redirector/$redirectorType.php");
        if (class_exists($className = "Tracking_Redirector_$redirectorType", FALSE)) {
             return new $className();
        } else {
            @include_once('Tracking/Redirector/' .  self::DEFAULT_TYPE . '.php');
            if (class_exists($className = 'Tracking_Redirector_' . self::DEFAULT_TYPE, FALSE)) {
               return new $className();
            } else {
               throw new Tracking_Redirector_Exception("Can NOT load $className!");
            }
        }
    }
}