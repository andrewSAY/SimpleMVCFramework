<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 02.09.15
 * Time: 17:30
 */

namespace LW\Model\Dictionary;


abstract class DictionaryAbstract
{
    private $cache;

    public function getList()
    {
        if($this->cache != null)
        {
            return $this->cache;
        }
        foreach($this as $field=>$value)
        {
            if($field == 'cache')
            {
                continue;
            }
            $list[$field] = $value[1];
        }
        $this->cache = $list;

        return $list;
    }

    public function getListOfKeys()
    {
        return array_keys($this->getList());
    }

    public function inList($value)
    {
        if(in_array($value ,$this->getList()))
        {
            return true;
        }

        return false;
    }
} 