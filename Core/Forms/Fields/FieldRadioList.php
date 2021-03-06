<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 26.08.15
 * Time: 11:05
 */

namespace LW\Core\Forms\Fields;


class FieldRadioList extends FieldList
{
    public function renderSelf()
    {
        $template = '<input type="radio" name="%s" value="%s" %s %s/><br/>';
        $value = $this->getValueOrDefault();
        if (!isset($value))
        {
            $value = '';
        }
        $renderField = '';
        foreach ($this->getChoiceList() as $key => $caption)
        {
            if ($this->keyExistInValue($key))
            {
                $checked = 'checked';
            } else
            {
                $checked = '';
            }
            $name = $this->getName();
            if($this->choiceIsMultiSelect)
            {
                $name = $this->getName().'[]';
            }
            $renderField = sprintf($template, $name, $value, $this->getAttrAsString(), $checked);
        }

        return $renderField;
    }
} 