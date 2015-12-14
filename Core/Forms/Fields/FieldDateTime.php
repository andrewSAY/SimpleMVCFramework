<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 26.08.15
 * Time: 12:31
 */

namespace LW\Core\Forms\Fields;


class FieldDateTime extends Field
{
    protected $format = 'd.m.Y H:i';

    public function setDateFormat($format)
    {
        $this->format = $format;
    }

    public function setValue($value)
    {

        if(is_null($value))
        {
            $this->value = $value;
            return $this;
        }

        if($value == '')
        {
            $this->value = null;
            return $this;
        }

        if (!($value instanceof \DateTime))
        {
            if (is_numeric($value))
            {
                $value = new \DateTime($value);
            }
            if (is_string($value))
            {
                $value = \DateTime::createFromFormat($this->format, $value);
            }
        }

        $this->value = $value;

        return $this;
    }

    public function renderSelf()
    {
        $template = '<input name="%s"  type="text" value="%s" %s/>';
        $value = $this->getValueOrDefault();
        if (!($value instanceof \DateTime))
        {
            $value = '';
        }
        else
        {
            $value = $value->format($this->format);
        }

        $renderField = sprintf($template, $this->getName(), $value, $this->getAttrAsString());
        return $renderField;
    }
} 