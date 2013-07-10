<?php
/**
 * Mezi Framework
 *
 * @category   Mezi
 * @package    Mezi_Loader
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Loader.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

/**
 * The Mezi_Loader class includes methods to help us load files dynamically.
 *
 * @category   Mezi
 * @package    Mezi_Loader
 */
class Mezi_Loader
{
    /**
     * Loads a class from a PHP file.  The filename must be formatted as "$class.php".
     *
     * @param string $class
     * @return void
     * @throws Mezi_Exception
     */
    public static function loadClass ($class)
    {
        if (class_exists($class, FALSE) || interface_exists($class, FALSE)) {
            return;
        }

        /* autodiscover the path from the class name */
        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        include_once $file;

        if (!class_exists($class, FALSE) && !interface_exists($class, FALSE)) {
            throw new Tracking_Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
        }
    }

    /**
     * Register {@link autoload()} with spl_autoload()
     *
     * @return void
     * @throws Mezi_Exception if spl_autoload() is not found.
     */
    public static function registerAutoload ()
    {
        if (!function_exists('spl_autoload_register')) {
            require_once 'Mezi/Exception.php';
            throw new Tracking_Exception('spl_autoload does not exist in this PHP installation');
        }

        spl_autoload_register(array(__CLASS__, 'loadClass'));
    }
}