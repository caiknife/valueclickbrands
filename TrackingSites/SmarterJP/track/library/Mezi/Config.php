<?php
/**
 * Mezi Framework
 *
 * @category   Mezi
 * @package    Mezi_Config
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Config.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

/**
 * Base class for Mezi Config
 *
 * @category   Mezi
 * @package    Mezi_Config
 */
class Mezi_Config implements Countable, Iterator
{
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Tracking_Config
     */
    protected static $_instance = NULL;

    /**
     * Whether in-memory modifications to configuration data are allowed
     *
     * @var boolean
     */
    protected $_readOnly;

    /**
     * Iteration index
     *
     * @var integer
     */
    protected $_index;

    /**
     * Number of elements in configuration data
     *
     * @var integer
     */
    protected $_count;

    /**
     * Contains array of configuration data
     *
     * @var array
     */
    protected $_data;

    /**
     * Contains which config file sections were loaded. This is NULL
     * if all sections were loaded, a string name if one section is loaded
     * and an array of string names if multiple sections were loaded.
     *
     * @var mixed
     */
    protected $_loadedSection;

    /**
     * This is used to track section inheritance. The keys are names of sections that
     * extend other sections, and the values are the extended sections.
     *
     * @var array
     */
    protected $_extends = array();

    /**
     * Load file error string.
     *
     * Is null if there was no error while file loading
     *
     * @var string
     */
    protected $_loadFileErrorStr = null;

    /**
     * Mezi_Config provides a property based interface to
     * an array. The data are read-only unless $readOnly
     * is set to FALSE on construction.
     *
     * Mezi_Config also implements Countable and Iterator to
     * facilitate easy access to the data.
     *
     * @param  array   $array
     * @param  boolean $readOnly
     * @return void
     */
    public function __construct($array, $readOnly = TRUE)
    {
        $this->_readOnly = (boolean) $readOnly;
        $this->_loadedSection = NULL;
        $this->_index = 0;
        $this->_data = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->_data[$key] = new self($value, $this->_readOnly);
            } else {
                $this->_data[$key] = $value;
            }
        }
        $this->_count = count($this->_data);
    }

    /**
     * Retrieve a value and return $default if there is no element set.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = NULL)
    {
        $result = $default;
        if (array_key_exists($name, $this->_data)) {
            $result = $this->_data[$name];
        }
        return $result;
    }

    /**
     * Magic function so that $obj->value will work.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Only allow setting of a property if $readOnly
     * was set to FALSE on construction. Otherwise, throw an exception.
     *
     * @param  string $name
     * @param  mixed  $value
     * @throws Mezi_Config_Exception
     * @return void
     */
    public function __set($name, $value)
    {
        if ($this->_readOnly) {
            throw new Mezi_Config_Exception('Mezi_Config is read only');
        }

        $this->_data[$name] = is_array($value) ? new self($value, FALSE) : $value;

        $this->_count = count($this->_data);
    }

    /**
     * Singleton instance
     *
     * @return Mezi_Config
     */
    public static function getInstance()
    {
        if (NULL === self::$_instance) {
            self::$_instance = new self(array(), FALSE);
        }

        return self::$_instance;
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this->_data as $key => $value) {
            if ($value instanceof Mezi_Config) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    /**
     * Support isset() overloading on PHP 5.1
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * Support unset() overloading on PHP 5.1
     *
     * @param  string $name
     * @throws Mezi_Config_Exception
     * @return void
     */
    public function __unset($name)
    {
        if ($this->_readOnly) {
            throw new Mezi_Config_Exception('Mezi_Config is read only');
        }

        unset($this->_data[$name]);

        $this->_count = count($this->_data);
    }

    /**
     * Defined by Countable interface
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->_data);
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->_data);
    }

    /**
     * Defined by Iterator interface
     */
    public function next()
    {
        next($this->_data);
        ++$this->_index;
    }

    /**
     * Defined by Iterator interface
     */
    public function rewind()
    {
        reset($this->_data);
        $this->_index = 0;
    }

    /**
     * Defined by Iterator interface
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->_index < $this->_count;
    }

    /**
     * Returns the section name(s) loaded.
     *
     * @return mixed
     */
    public function getSectionName()
    {
        return $this->_loadedSection;
    }

    /**
     * Returns TRUE if all sections were loaded
     *
     * @return boolean
     */
    public function areAllSectionsLoaded()
    {
        return $this->_loadedSection === NULL;
    }

    /**
     * Merge another Mezi_Config with this one. The items
     * in $merge will override the same named items in
     * the current config.
     *
     * @param Mezi_Config $merge
     * @param string $section
     * @return Mezi_Config
     */
    public function merge(Mezi_Config $merge, $section = NULL)
    {
        if (is_null($section)) {
            foreach($merge as $key => $item) {
                if(array_key_exists($key, $this->_data)) {
                    if($item instanceof Mezi_Config && $this->$key instanceof Mezi_Config) {
                        $this->$key = $this->$key->merge($item);
                    } else {
                        $this->$key = $item;
                    }
                } else {
                    $this->$key = $item;
                }
            }
        } else {
            $this->$section = $merge;
        }
        return $this;
    }

    /**
     * Prevent any more modifications being made to this instance. Useful
     * after merge() has been used to merge multiple Mezi_Config objects
     * into one object which should then not be modified again.
     */
    public function setReadOnly()
    {
        $this->_readOnly = TRUE;
    }

    /**
     * Throws an exception if $extendingSection may not extend $extendedSection,
     * and tracks the section extension if it is valid.
     *
     * @param  string $extendingSection
     * @param  string $extendedSection
     * @throws Mezi_Config_Exception
     * @return void
     */
    protected function _assertValidExtend($extendingSection, $extendedSection)
    {
        /* detect circular section inheritance */
        $extendedSectionCurrent = $extendedSection;
        while (array_key_exists($extendedSectionCurrent, $this->_extends)) {
            if ($this->_extends[$extendedSectionCurrent] == $extendingSection) {
                throw new Mezi_Config_Exception('Illegal circular inheritance detected');
            }
            $extendedSectionCurrent = $this->_extends[$extendedSectionCurrent];
        }
        /* remember that this section extends another section */
        $this->_extends[$extendingSection] = $extendedSection;
    }

}