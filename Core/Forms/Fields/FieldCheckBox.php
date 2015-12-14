<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 26.08.15
 * Time: 9:55
 */

namespace LW\Core\Forms\Fields;


class FieldCheckBox extends Field
{
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function renderSelf()
    {
        $template = '<input name="%s" type="checkbox" %s value ="%s" %s/>';
        $value = $this->getValueOrDefault();
        $checked = '';
        if(is_null($value))
        {
            $value = false;
        }
        if($value)
        {
            $checked = 'checked';
        }
        $renderField = sprintf($template, $this->getName(), $this->getAttrAsString(), 1, $checked);
        return $renderField;
    }
} 