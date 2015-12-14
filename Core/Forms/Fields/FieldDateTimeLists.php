<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 28.08.15
 * Time: 16:19
 */

namespace LW\Core\Forms\Fields;


class FieldDateTimeLists extends FieldList
{
    private $min;
    private $max;
    private $onlyDate;

    function __construct($name, $caption, $minYear = 1900, $maxYear = 2050, $onlyDate = false, $attr = array())
    {
        $this->name = $name;
        $this->caption = $caption;
        $this->attr = $attr;
        $this->value = null;
        $this->min = $minYear;
        $this->max = $maxYear;
        $this->onlyDate = $onlyDate;
        $this->rules = array();
    }

    public function setYears($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
        return $this;
    }

    public function onlyDate($value = true)
    {
        $this->onlyDate = $value;
    }

    public function setValue($value)
    {
        if (is_null($value))
        {
            $this->value = $value;
            return $this;
        }

        $format = 'd.m.Y h:m';
        if ($this->onlyDate)
        {
            $format = 'd.m.Y';
        }

        if (is_array($value))
        {
            if ($this->onlyDate)
            {
                $value = implode('.', $value);
            } else
            {
                $date = array($value['day'], $value['month'], $value['year']);
                $time = array($value['hour'], $value['minute']);
                $value = implode('.', $date);
                $value = $value.implode(':', $time);
            }
        }

        if(!($value instanceof \DateTime))
        {
            $value = \DateTime::createFromFormat($format, $value);
        }

        $this->value = $value;
        return $this;
    }

    private function renderOptions($from, $to, $key)
    {
        $templateOption = '<option value="%s" %s>%s</option>';

        $renderOptions = '';
        for ($i = $from; $i <= $to; $i++)
        {
            if ($i == $key)
            {
                $selected = 'selected';
            } else
            {
                $selected = '';
            }
            $renderOptions = $renderOptions . sprintf($templateOption, $i, $selected, $i);
        }

        return $renderOptions;
    }

    public function renderSelf()
    {
        $templateSelect = '<select name="%s" %s>%s</select>';
        $templateDiv = '<div>%s</div>';
        /**
         * @var \DateTime $value
         */
        $name = $this->getName();
        $dmy = array(1, 1, $this->max, 0, 0);

        $value = $this->getValueOrDefault();

        if (isset($value))
        {
            $dmy[0] = $value->format('d');
            $dmy[1] = $value->format('m');
            $dmy[2] = $value->format('Y');
            $dmy[3] = $value->format('h');
            $dmy[4] = $value->format('m');
        }

        //Days
        $renderOptions = $this->renderOptions(1, 31, $dmy[0]);
        $renderField = sprintf($templateSelect, $name . '[day]', $this->getAttrAsString(), $renderOptions);

        //Month
        $renderOptions = $this->renderOptions(1, 12, $dmy[1]);
        $renderField = $renderField . sprintf($templateSelect, $name . '[month]', $this->getAttrAsString(), $renderOptions);

        //Year
        $renderOptions = $this->renderOptions($this->min, $this->max, $dmy[2]);
        $renderField = $renderField . sprintf($templateSelect, $name . '[year]', $this->getAttrAsString(), $renderOptions);

        if (!$this->onlyDate)
        {
            //Hours
            $renderOptions = $this->renderOptions($this->min, $this->max, $dmy[3]);
            $renderField = $renderField . sprintf($templateSelect, $name . '[hour]', $this->getAttrAsString(), $renderOptions);

            //Minutes
            $renderOptions = $this->renderOptions($this->min, $this->max, $dmy[4]);
            $renderField = $renderField . sprintf($templateSelect, $name . '[minute]', $this->getAttrAsString(), $renderOptions);
        }

        $renderField = sprintf($templateDiv, $renderField);

        return $renderField;
    }
} 