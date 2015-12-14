<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 26.08.15
 * Time: 9:47
 */

namespace LW\Core\Forms\Fields;


class FieldTextArea extends Field
{
    public function renderSelf()
    {
        $template = '<textarea name="%s" %s>%s</textarea>';
        $value = $this->getValueOrDefault();
        if (!isset($value))
        {
            $value = '';
        }
        $renderField = sprintf($template, $this->getName(), $this->getAttrAsString(), $value);
        return $renderField;
    }
} 