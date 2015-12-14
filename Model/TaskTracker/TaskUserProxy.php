<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 03.09.15
 * Time: 9:45
 */

namespace LW\Model\TaskTracker;


use LW\Cache\Doctrine\__CG__\LW\Model\Entity\User;

class TaskUserProxy extends User
{
    private $roleInTask;

    public function setRoleInTask($role)
    {
        $this->roleInTask = $role;
    }

    public function getRoleInTask()
    {
       return $this->roleInTask;
    }
} 