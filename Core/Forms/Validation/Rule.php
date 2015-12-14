<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 28.08.15
 * Time: 9:52
 */

namespace LW\Core\Forms\Validation;


use LW\Core\Forms\Fields\Field;

abstract class Rule
{
    /**
     * @var Field $field
     */
    protected $errorMessage;
    protected $params;

    function __construct($params = array())
    {
        $this->params = $params;
    }

    abstract public function check(Field $field);

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
} 