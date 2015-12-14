<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 28.08.15
 * Time: 17:02
 */

namespace LW\Core\Forms\Validation\Rules;


use LW\Core\Forms\Fields\Field;
use LW\Core\Forms\Validation\Rule;

class Length extends Rule
{
    /**
     * @param integer $minLength
     * @param integer $maxLength
     */
    function __construct($minLength, $maxLength)
    {
        $this->params['min'] = $minLength;
        $this->params['max'] = $maxLength;
    }

    public function check(Field $field)
    {
        $min = $this->params['min'];
        $max = $this->params['max'];
        $length = strlen($field->getValue());

        if ($max != 0)
        {
            $this->errorMessage = 'Длина вводимого значения для поля ' . $field->getCaption() . ' должна быть не больше ' . $max;
        }
        if ($min != 0)
        {
            $this->errorMessage = 'Длина вводимого значения для поля ' . $field->getCaption() . ' должна быть не меньше ' . $min;
        }

        if ($max != 0 && $min != 0)
        {
            $this->errorMessage = 'Длина вводимого значения для поля ' . $field->getCaption() . ' должна быть не меньше ' . $min . ' и не больше ' . $max;
        }

        if (($min == 0 && $length > $max) || ($max == 0 && $length < $min) || (($min != 0 && $max != 0) && ($length < $min || $length > $max)))
        {
            return false;
        }

        return true;
    }
} 