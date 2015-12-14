<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 20.08.15
 * Time: 15:52
 */

namespace LW\Core;


class Solution
{
    private $type;
    private $data;

    function __construct($type, $data)
    {
        if($type < 0 || $type > 4)
        {
            throw new \Exception('Value '.$type.' not found in list of solution types');
        }
        $this->type = $type;
        $this->data = $data;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getData()
    {
        return $this->data;
    }

} 