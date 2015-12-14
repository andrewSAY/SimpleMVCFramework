<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 26.08.15
 * Time: 9:42
 */

namespace LW\Core\Forms\Fields;


class FieldPassword extends Field
{
    public function renderSelf()
    {
        $template = '<input name="%s"  type="password" value="%s" %s/>';
        $value = $this->getValueOrDefault();
        if (!isset($value))
        {
            $value = '';
        }
        $renderField = sprintf($template, $this->getName(), $value, $this->getAttrAsString());
        return $renderField;
    }
} 