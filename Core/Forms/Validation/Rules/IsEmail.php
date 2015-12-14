<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 31.08.15
 * Time: 9:47
 */

namespace LW\Core\Forms\Validation\Rules;


use LW\Core\Forms\Fields\Field;
use LW\Core\Forms\Validation\Rule;

class IsEmail extends Rule
{
    public function check(Field $field)
    {
        $patternEmail = '/[?+@?+]/';
        $this->errorMessage = 'Поле '.$field->getCaption().' должно содержать валидный адрес e-mail';

        if(!preg_match($patternEmail, $field->getValue()))
        {
            return false;
        }
        return true;
    }
} 