<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 16:02
 */

namespace LW\Core\Forms\Fields;


use LW\Core\Forms\Validation\Rule;

abstract class Field
{
    protected $name;
    protected $caption;
    protected $attr;
    protected $value;
    protected $defaultValue;
    protected $errors;
    protected $rules;

    function __construct($name, $caption, $attr = array(), $defaultValue = null)
    {
        $this->name = $name;
        $this->caption = $caption;
        $this->attr = $attr;
        $this->defaultValue = $defaultValue;
        $this->value = null;
        $this->errors = array();
        $this->rules = array();
    }

    abstract public function renderSelf();


    public function getName()
    {
        return $this->name;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function getAttr()
    {
        return $this->attr;
    }

    public function getAttrAsString()
    {
        $attrString = '';
        foreach ($this->getAttr() as $key => $value)
        {
            $attrString = $key . '=\'' . $value.'\'';
        }
        return $attrString;
    }

    public function getValue()
    {
        return $this->value;
    }

     public function setValue($value)
    {
        $this->value = $value;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function getValueOrDefault()
    {
        if(isset($this->value))
        {
            return $this->value;
        }
        if(isset($this->defaultValue))
        {
            return $this->defaultValue;
        }

        return null;
    }

    public function addRule(Rule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    public function valid()
    {
        $valid = true;
        if(!is_array($this->rules))
        {
            return $valid;
        }

        foreach($this->rules as $rule)
        {
            if(!$rule->check($this))
            {
                $valid = false;
                $this->errors[] = $rule->getErrorMessage();
            }
        }

        return $valid;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected  function keyExistInValue($key)
    {
        if(is_array($this->getValueOrDefault()))
        {
            return array_key_exists($key,$this->getValueOrDefault());
        }

        return ($key == $this->getValueOrDefault());
    }

}