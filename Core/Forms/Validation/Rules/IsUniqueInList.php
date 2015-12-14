<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 31.08.15
 * Time: 16:24
 */

namespace LW\Core\Forms\Validation\Rules;


use LW\Core\Forms\Fields\Field;
use LW\Core\Forms\Validation\Rule;

class IsUniqueInList extends Rule
{
    function __construct($list)
    {
        $this->params = $list;
    }

    public function check(Field $field)
    {
        $this->errorMessage = 'Значение, указанное для поля '.$field->getCaption().' не является уникальным';

        if(in_array($field->getValue(), $this->params))
        {
            return false;
        }

        return true;
    }
} 