<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 25.08.15
 * Time: 16:35
 */

namespace LW\Core\Forms;
use LW\Core\Forms\Fields\Field;

class FormRender
{
    private $form;

    function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function rendForm()
    {
        $formHtml = $this->rendBeginForm();
        for($i = 0; $i<$this->form->getFieldsCount(); $i++)
        {
            $formHtml = $formHtml.$this->rendLabel($i).$this->rendField($i);
            $formHtml = $formHtml.$this->rendFieldErrors($i).'<br/>';
        }
        $formHtml = $formHtml.'</form>';

        $formHtml = $formHtml.$this->rendFormErrors();

        return $formHtml;
    }

    public function rendBeginForm()
    {
        $template = '<form name="%s" method="%s" action="%s" %s>';
        $beginForm = sprintf($template, $this->form->getName(), $this->form->getMethod(), $this->form->getAction(),$this->form->getAttrAsString());
        return $beginForm;
    }

    public function rendEndForm()
    {
        $fieldSubmitted = $this->rendField($this->form->getName().'_submitted');
        $template = '</form>';
        $endForm = $fieldSubmitted.$template;
        return $endForm;
    }

    public function rendLabel($fieldKey)
    {
        $template = '<label>%s</label>';
        $field = $this->form->getField($fieldKey);
        $label = sprintf($template, $field->getCaption());

        return $label;
    }

    public function rendCaption($fieldKey)
    {
        $field = $this->getField($fieldKey);
        return $field->getCaption();
    }

    public function rendField($fieldKey)
    {
        $field = $this->getField($fieldKey);
        return $this->rend($field);
    }

    public function rendFieldErrors($fieldKey)
    {
        $errors = $this->getField($fieldKey)->getErrors();
        if(count($errors) == 0)
        {
            return;
        }
        $templateUl = '<ul>%s</ul>';
        $templateLi= '<li>%s</li>';
        $errorsHtml = '';

        foreach($errors as $error)
        {
            $errorsHtml = $errorsHtml.sprintf($templateLi, $error);
        }
        $errorsHtml = sprintf($templateUl, $errorsHtml);

        return $errorsHtml;
    }

    public function rendFormErrors()
    {
        $errors = $this->form->getErrors();
        if(count($errors) == 0)
        {
            return;
        }
        $templateUl = '<ul>%s</ul>';
        $templateLi= '<li>%s</li>';
        $errorsHtml = '';

        foreach($errors as $error)
        {
            $errorsHtml = $errorsHtml.sprintf($templateLi, $error);
        }
        $errorsHtml = sprintf($templateUl, $errorsHtml);

        return $errorsHtml;
    }

    private function rend(Field $field)
    {
       return $field->renderSelf();
    }


    private function getField($fieldKey)
    {
        $field = $this->form->getField($fieldKey);
        return $field;
    }
} 