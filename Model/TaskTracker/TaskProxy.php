<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 02.09.15
 * Time: 16:37
 */

namespace LW\Model\TaskTracker;


use LW\Model\Entity\TaskTracker\Task;
use LW\Model\Entity\TaskTracker\TaskUser;

class TaskProxy extends Task
{
    private $author;

    public function createTask()
    {
        $task = new Task();
        foreach ($this as $field => $value)
        {
            $task->$field = $value;
        }

        return $task;
    }

    public function setUsers($value)
    {
        if (is_object($value))
        {
            $value = json_decode($value);
            foreach ($value as $item)
            {
                $taskUser = new TaskUser();
                $taskUser->setTask($this);
                $taskUser->setUserId($item->id);
                $this->addUser($taskUser);
            }
        }
    }

    public function  setAuthor(TaskUserProxy $user)
    {
        $this->author = $user;
    }

    public function getAuthor()
    {
        return $this->author;
    }

} 