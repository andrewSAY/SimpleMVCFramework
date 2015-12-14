<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 26.08.15
 * Time: 10:58
 */

namespace LW\Core\Forms\Fields;


class FieldDropDawnList extends FieldList
{
    public function renderSelf()
    {
        $templateSelect = '<select name="%s" %s %s>%s</select>';
        $templateOption = '<option value="%s" %s>%s</option>';


        $renderOptions = '';
        foreach($this->getChoiceList() as $key=>$caption)
        {
            if($this->keyExistInValue($key))
            {
                $selected = 'selected';
            }
            else
            {
                $selected = '';
            }
            $renderOptions =  $renderOptions.sprintf($templateOption, $key, $selected, $caption);
        }
        $name = $this->getName();
        $multiple ='';
        if($this->choiceIsMultiSelect)
        {
            $name = $this->getName().'[]';
            $multiple = 'multiple';
        }
        $renderField = sprintf($templateSelect, $name, $multiple, $this->getAttrAsString(), $renderOptions);
        return $renderField;
    }
} 