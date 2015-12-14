<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 31.08.15
 * Time: 16:54
 */

namespace LW\Core\Forms\Validation\Rules;


use LW\Core\Forms\Fields\Field;
use LW\Core\Forms\Validation\Rule;

class IsUniqueInMethod extends Rule
{
    /**
     * @param $object
     * @param $methodName
     * @param array $params
     */
    function __construct($object, $methodName, $params = array())
    {
        $this->params = array(
            array($object, $methodName),
            $params
        );
    }

    public function check(Field $field)
    {
        $this->errorMessage = 'Значение, указанное для поля ' . $field->getCaption() . ' не является уникальным';
        $this->params[1][] = $field->getValue();
        if (!call_user_func_array($this->params[0], $this->params[1]))
        {
            return false;
        }

        return true;
    }
} 