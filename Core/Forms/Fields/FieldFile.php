<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 09.09.15
 * Time: 16:33
 */

namespace LW\Core\Forms\Fields;


class FieldFile extends Field
{
    public function renderSelf()
    {
        $template = '<input name="%s"  type="file" value="%s" %s/>';
        $value = $this->getValueOrDefault();
        if (!isset($value))
        {
            $value = '';
        }
        $renderField = sprintf($template, $this->getName(), $value, $this->getAttrAsString());
        return $renderField;
    }
} 