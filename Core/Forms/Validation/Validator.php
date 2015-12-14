<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 28.08.15
 * Time: 9:50
 */

namespace LW\Core\Forms\Validation;


use LW\Core\Forms\Fields\Field;
use LW\Core\Forms\Form;

class Validator
{
    private $form;
    //private $rules;

    function __construct(Form $form)
    {
        $this->form = $form;
        /*$this->rules = array();
        for($i = 0; $i < $this->form->getFieldsCount(); $i++)
        {
            $this->rules[$this->form->getByNumber($i)->getName()] = array();
        }*/
    }

    public function check()
    {
        $check = true;
        foreach($this->rules as $key=>$value)
        {
            foreach($value as $rule)
            {
                if(!$rule->check())
                {
                    $this->form->getByName($key)->addError($rule->getErrorMessage());
                    $check = false;
                }
            }
        }
        return $check;
    }

    public function addRule(Rule $rule, Field $field)
    {
        $this->rules[$field->getName()][] = $rule;
    }
} 