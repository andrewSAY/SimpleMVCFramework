<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 26.08.15
 * Time: 10:08
 */

namespace LW\Core\Forms\Fields;


abstract class FieldList extends Field
{
    protected $choiceList;
    protected $choiceIsClass;
    protected $choiceIsMultiSelect;

    function __construct($name, $caption, $choiceList, $useChoiceListAsClass = false , $isMultiSelect = false, $attr = array(), $defaultValue = null)
    {
        $this->name = $name;
        $this->caption = $caption;
        $this->attr = $attr;
        $this->defaultValue = $defaultValue;
        $this->value = null;
        $this->rules = array();
        $this->choiceList = $choiceList;
        $this->choiceIsClass = $useChoiceListAsClass;
        $this->choiceIsMultiSelect = $isMultiSelect;
    }

    public function isMultiSelect()
    {
        return $this->choiceIsMultiSelect;
    }

    public function getChoiceList()
    {
        if(!$this->choiceIsClass)
        {
            return $this->choiceList;
        }

        if(!is_array($this->choiceList))
        {
           return call_user_func($this->choiceList);
        }

        if(!array_key_exists('class' ,$this->choiceList))
        {
            throw new \Exception('Not found key "class" for FieldList.');
        }

        if(!array_key_exists('method' ,$this->choiceList))
        {
            throw new \Exception('Not found key "method" for FieldList.');
        }

        return call_user_func(array(
            (new $this->choiceList['class']()),
                $this->choiceList['method']
        )
        );
    }
} 