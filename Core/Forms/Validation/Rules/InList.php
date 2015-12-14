<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 28.08.15
 * Time: 17:17
 */

namespace LW\Core\Forms\Validation\Rules;


use LW\Core\Forms\Fields\Field;
use LW\Core\Forms\Validation\Rule;

class InList extends Rule
{
    function __construct($list)
    {
        $this->params = $list;
    }

    public function check(Field $field)
    {
        $list = $this->params;
        $this->errorMessage = 'Значение поля '.$field->getCaption().' должно назодиться в пределах списка';
        if(!in_array($field->getValue(), $list))
        {
            return false;
        }

        return true;
    }
} 