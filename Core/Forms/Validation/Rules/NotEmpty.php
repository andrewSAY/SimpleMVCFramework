<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 28.08.15
 * Time: 9:55
 */

namespace LW\Core\Forms\Validation\Rules;


use LW\Core\Forms\Fields\Field;
use LW\Core\Forms\Fields\FieldList;
use LW\Core\Forms\Validation\Rule;

class NotEmpty extends Rule
{

    public function check(Field $field)
    {
        $this->errorMessage = 'Поле '.$field->getCaption().' должно быть заполнено';
        if($field instanceof FieldList)
        {
            $this->errorMessage = 'Должно быть выбрано значение для поля '.$field->getCaption();
        }

        if($field->getValue() == null)
        {
            return false;
        }
        if($field->getValue() == '')
        {
            return false;
        }
        return true;
    }
} 