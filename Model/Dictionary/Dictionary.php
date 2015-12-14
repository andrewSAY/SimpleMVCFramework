<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 03.09.15
 * Time: 9:50
 */

namespace LW\Model\Dictionary;


class Dictionary
{
    private $levels;
    private $speeds;
    private $states;
    private $roles;
    private $taskRoles;

    function __construct()
    {
        $this->levels = new TaskLevels();
        $this->speeds = new TaskSpeeds();
        $this->states = new TaskStates();
        $this->roles = new UserRoles();
        $this->taskRoles = new TaskRoles();
    }


    public function taskLevels()
    {
        return $this->levels;
    }

    public function taskSpeeds()
    {
        return $this->speeds;
    }


    public function taskStates()
    {
        return $this->states;
    }

    public function userRoles()
    {
        return $this->roles;
    }

    public function taskRoles()
    {
        return $this->taskRoles;
    }

} 