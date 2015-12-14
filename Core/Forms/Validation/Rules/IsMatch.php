<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 31.08.15
 * Time: 9:51
 */

namespace LW\Core\Forms\Validation\Rules;


use LW\Core\Forms\Fields\Field;
use LW\Core\Forms\Validation\Rule;

class IsMatch extends Rule
{
    function __construct($pattern, $errorMessage)
    {
        $this->params['pattern'] = $pattern;
        $this->errorMessage = $errorMessage;
    }

    public function check(Field $field)
    {
        if(!preg_match($this->params['pattern'], $field->getValue()))
        {
            return false;
        }

        return true;
    }
} 