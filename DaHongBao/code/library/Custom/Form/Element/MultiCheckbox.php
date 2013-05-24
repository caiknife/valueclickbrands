<?php
/**
* MultiCheckbox.php
*-------------------------
*
* 
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
* @author Yaron Jiang <yjiang@corp.valueclick.com.cn>
* @copyright (C) 2004-2013 Mezimedia.com
* @license http://www.mezimedia.com PHP License 5.0
* @version CVS: $Id: MultiCheckbox.php,v 1.1 2013/04/15 10:56:30 rock Exp $
* @link http://www.dahongbao.com/
* @deprecated File deprecated in Release 3.0.0
*/
namespace Custom\Form\Element;

use Zend\Form\ElementInterface;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Validator\Explode as ExplodeValidator;

/**
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 */
class MultiCheckbox extends Checkbox
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'multi_checkbox',
    );

    /**
     * @var bool
    */
    protected $useHiddenElement = false;

    /**
     * @var string
     */
    protected $uncheckedValue = '';

    /**
     * @var array
     */
    protected $valueOptions = array();

    /**
     * @return array
    */
    public function getValueOptions()
    {
        return $this->valueOptions;
    }

    /**
     * @param  array $options
     * @return MultiCheckbox
     */
    public function setValueOptions(array $options)
    {
        $this->valueOptions = $options;

        // Update Explode validator haystack
        if ($this->validator instanceof ExplodeValidator) {
            $validator = $this->validator->getValidator();
            $validator->setHaystack($this->getValueOptionsValues());
        }

        return $this;
    }

    /**
     * Set options for an element. Accepted options are:
     * - label: label to associate with the element
     * - label_attributes: attributes to use when the label is rendered
     * - value_options: list of values and labels for the select options
     *
     * @param  array|\Traversable $options
     * @return MultiCheckbox|ElementInterface
     * @throws InvalidArgumentException
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($this->options['value_options'])) {
            $this->setValueOptions($this->options['value_options']);
        }
        // Alias for 'value_options'
        if (isset($this->options['options'])) {
            $this->setValueOptions($this->options['options']);
        }

        return $this;
    }

    /**
     * Set a single element attribute
     *
     * @param  string $key
     * @param  mixed  $value
     * @return MultiCheckbox|ElementInterface
     */
    public function setAttribute($key, $value)
    {
        // Do not include the options in the list of attributes
        // TODO: Deprecate this
        if ($key === 'options') {
            $this->setValueOptions($value);
            return $this;
        }
        return parent::setAttribute($key, $value);
    }


    /**
     * Get only the values from the options attribute
     *
     * @return array
     */
    protected function getValueOptionsValues()
    {
        $values = array();
        $options = $this->getValueOptions();
        foreach ($options as $key => $optionSpec) {
            $value = (is_array($optionSpec)) ? $optionSpec['value'] : $key;
            $values[] = $value;
        }
        if ($this->useHiddenElement()) {
            $values[] = $this->getUncheckedValue();
        }
        return $values;
    }

    /**
     * Sets the value that should be selected.
     *
     * @param mixed $value The value to set.
     * @return MultiCheckbox
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    



    function setCheckboxOptions(array $data)
    {
        $re = array();
        $selected = $this->getValue();
        foreach($data as $k => $v){
            $row = array(
                'value' => $k,
                'label' => $v
            );
    
            if(isset($selected) && in_array($k , $selected)){
                $row['selected'] = "selected";
            }
    
            $re[] = $row;
        }
    
        $this->setValueOptions($re);
    }
}