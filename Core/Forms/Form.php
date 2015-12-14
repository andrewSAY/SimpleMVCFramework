<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 15:46
 */

namespace LW\Core\Forms;

use LW\Core\Forms\Fields\FieldHidden;
use LW\Core\Forms\Validation\Rule;
use LW\Core\Forms\Validation\Validator;
use LW\Core\Request;
use LW\Core\Forms\Fields\Field;

class Form
{
    private $name;
    private $method;
    private $action;
    private $fields;
    private $errors;
    private $hash;
    private $attr;
    private $render;

    function __construct($name, $action, $method = 'POST', $attr = array())
    {
        $this->name = $name;
        $this->method = $method;
        $this->action = $action;
        $this->fields = array();
        $this->hash = array();
        $this->attr = $attr;
        $this->render = new FormRender($this);
        $this->errors = array();

        $this->addField(new FieldHidden($this->getName() . '_submitted', '', array(), time()));
    }

    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;
        $this->hash[count($this->hash)] = $field->getName();
        return $this;
    }

    public function addFieldList($list)
    {
        foreach ($list as $field)
        {
            $field[$field->getName()] = $field;
            $this->hash[count($this->hash)] = $field->getName();
        }
    }

    public function getField($key)
    {
        if(is_int($key))
        {
            $field = $this->getByNumber($key);
        }
        if(is_string($key))
        {
            $field = $this->getByName($key);
        }

        return $field;
    }

    /**
     * @param $fieldName
     * @return Field
     * @throws \Exception
     */
    public function getByName($fieldName)
    {
        if (!array_key_exists($fieldName, $this->fields))
        {
            throw new \Exception('Not found field with name "' . $fieldName . '" in form ' . $this->name);
        }
        return $this->fields[$fieldName];
    }

    /**
     * @param $fieldNumber
     * @return Field
     * @throws \Exception
     */
    public function getByNumber($fieldNumber)
    {
        if (!array_key_exists($fieldNumber, $this->hash))
        {
            throw new \Exception('Not found field with order number "' . $fieldNumber . '" in form ' . $this->name);
        }
        return $this->fields[$this->hash[$fieldNumber]];
    }

    public function writeFromRequest(Request $request)
    {
        /**
         * @var Field $field
         */
        foreach ($this->fields as $field)
        {
            $reqFieldValue = $request->get($field->getName());
            $field->setValue($reqFieldValue);
        }
    }

    public function writeToEntity($entity)
    {
        /**
         * @var Field $field
         */
        foreach ($this->fields as $field)
        {
            $methodSet = strtolower('set'.$field->getName());
            if(method_exists($entity, $methodSet))
            {
               call_user_func(array($entity, $methodSet), $field->getValue());
            }
        }
    }

    public function writeFromEntity($entity)
    {
        /**
         * @var Field $field
         */
        foreach ($this->fields as $field)
        {
            $methodSet = strtolower('get'.$field->getName());
            if(method_exists($entity, $methodSet))
            {
                $field->setValue(call_user_func(array($entity, $methodSet)));
            }
        }
    }

    public function fieldsAsArray()
    {
        /**
         * @var Field $field
         */
        $hash = array();
        foreach ($this->fields as $field)
        {
            $hash[$field->getName()] = $field->getValue();
        }
        return $hash;
    }

    public function getFieldsCount()
    {
        return count($this->fields);
    }

    public function getSubmitCaption()
    {
        return $this->submitCaption;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function addError($messsage)
    {
        $this->errors[] = $messsage;
    }

    public function getAttr()
    {
        return $this->attr;
    }

    public function getAttrAsString()
    {
        $attrString = '';
        foreach ($this->getAttr() as $key => $value)
        {
            $attrString = $key . '=\'' . $value . '\'';
        }
        return $attrString;
    }

    public function getRender()
    {
        return $this->render;
    }

    public function addRule(Rule $rule, $fieldName)
    {
        $field = $this->getByName($fieldName);
        if (is_null($field))
        {
            $field = $this->getByNumber($fieldName);
        }
        $field->addRule($rule);

        return $this;
    }

    public function wasSubmitted()
    {
        if ($this->getByName($this->getName() . '_submitted')->getValue() != null)
        {
            return true;
        }

        return false;
    }

    public function isValid()
    {
        $valid = $this->wasSubmitted();
        foreach ($this->fields as $field)
        {
            if (!$field->valid())
            {
                $valid = false;
            }
        }

        return $valid;
    }
} 