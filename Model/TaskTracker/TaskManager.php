<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 02.09.15
 * Time: 17:11
 */

namespace LW\Model\TaskTracker;


use Doctrine\ORM\EntityManager;
use LW\Model\Dictionary\Dictionary;
use LW\Model\Entity\TaskTracker\Task;
use LW\Model\Entity\TaskTracker\TaskReport;
use LW\Model\Entity\TaskTracker\TaskUser;
use LW\Model\Entity\User;
use LW\Model\Repository\TaskRepository;

class TaskManager
{

    private $accessOpened;
    private $wasChanged;
    /**
     * @var TaskRepository
     */
    private $dataRepository;
    /**
     * @var Task $task
     */
    private $task;


    function __construct(EntityManager $em)
    {
        $this->dataRepository = $em->getRepository('LW\Model\Entity\TaskTracker\Task');
        $this->accessOpened = false;
        $this->wasChanged = false;
    }

    public function getTasksByOwner(User $user, $state = null)
    {
        $dictionary = new Dictionary();
        $fields = array(
            't.id as id',
            't.theme as theme',
            't.dateCreate as dateCreate',
            't.state as state'
        );

        $where = array(
            'tu.userId' => $user->getId(),
            'tu.role' => $dictionary->taskRoles()->CREATOR[0]
        );

        if ($state != null)
        {
            if ($state != 'ALL')
            {
                if (in_array($state, $dictionary->taskStates()->getListOfKeys()))
                {
                    $where['t.state'] = $state;
                }
            }
        }

        $order = array(
            't.dateCreate' => 'desc'
        );

        return $this->dataRepository->getTasks($fields, $order, $where);
    }

    public function getTasksByDestination(User $user, $state = null)
    {
        $dictionary = new Dictionary();
        $fields = array(
            't.id as id',
            't.theme as theme',
            't.dateCreate as dateCreate',
            't.state as state'
        );

        $where = array(
            'tu.userId' => $user->getId(),
            'tu.role' => $dictionary->taskRoles()->EXECUTOR[0]
        );

        if ($state != null)
        {
            if ($state != 'ALL')
            {
                if (in_array($state, $dictionary->taskStates()->getListOfKeys()))
                {
                    $where['t.state'] = $state;
                }
            }
        }

        $order = array(
            't.dateCreate' => 'desc'
        );

        return $this->dataRepository->getTasks($fields, $order, $where);
    }

    public function getTaskForShow(User $user, $taskId, &$roleList)
    {
        $roles = $this->dataRepository->getRolesInTask($taskId, $user->getId());
        if (count($roles) > 0)
        {
            foreach ($roles as $item)
            {
                $roleList[] = $item['role'];
            }
            $this->accessOpened = true;
        }

        if ($this->accessHasOpened())
        {
            $this->task = $this->dataRepository->find($taskId);
        }

        return $this->task;
    }

    /**
     * @param User $user
     * @param $taskId
     * @return null|Task
     */
    public function getTaskForEdit(User $user, $taskId)
    {
        $roles = $this->dataRepository->getRolesInTask($taskId, $user->getId());
        if (count($roles) > 0)
        {
            $dictionary = new Dictionary();
            foreach ($roles as $item)
            {
                if ($item['role'] == $dictionary->taskRoles()->CREATOR[0])
                {
                    $this->accessOpened = true;
                    break;
                }
            }
        }

        if ($this->accessHasOpened())
        {
            $this->task = $this->dataRepository->find($taskId);
        }

        return $this->task;
    }

    public function createTask(User $user, Task $task)
    {
        $dictionary = new Dictionary();
        if ($user->getRole() != $dictionary->userRoles()->ROLE_BOSS[0])
        {
            return;
        }

        $this->accessOpened = true;
        $this->task = $task;
        $this->task->setState($dictionary->taskStates()->CREATED[0]);

        $this->dataRepository->persist($this->task);

        $author = new TaskUser();
        $author->setUser($user);
        $author->setRole($dictionary->taskRoles()->CREATOR[0]);
        $author->setTask($this->task);
        $this->dataRepository->persist($author);

        foreach ($task->getUsers() as $userTask)
        {
            $userTask->setRole($dictionary->taskRoles()->EXECUTOR[0]);
            $userTask->setUser($this->dataRepository->findUser($userTask->getUserId()));
            $this->dataRepository->persist($userTask);
        }

        $this->wasChanged = true;
    }

    public function editTask(User $user, Task $task)
    {
        $dictionary = new Dictionary();
        if ($user->getRole() != $dictionary->userRoles()->ROLE_BOSS[0])
        {
            return;
        }

        $this->task = $task;

        $newUserList = $task->getUsers();

        foreach ($newUserList as $userTask)
        {
            if ($userTask->getUserId() == $user->getId() && $userTask->getRole() == $dictionary->taskRoles()->CREATOR[0])
            {
                continue;
            }
            if ($userTask->getId() != null)
            {
                $this->dataRepository->remove($userTask);
                continue;
            }
            $userTask->setRole($dictionary->taskRoles()->EXECUTOR[0]);
            $userTask->setUser($this->dataRepository->findUser($userTask->getUserId()));
            $this->dataRepository->persist($userTask);
        }

        $this->wasChanged = true;

    }

    public function taskStateChange(User $user, $taskId, $newState)
    {
        $this->task = $this->dataRepository->find($taskId);
        $roles = $this->dataRepository->getRolesInTask($taskId, $user->getId());
        $currentState = $this->task->getState();
        $model = $this->buildModel();

        foreach ($roles as $role)
        {
            if (array_key_exists($role['role'], $model))
            {
                if (array_key_exists($currentState, $model[$role['role']]))
                {
                    if (in_array($newState, $model[$role['role']][$currentState]))
                    {
                        $this->accessOpened = true;
                        break;
                    }
                }
            }
        }

        if (!$this->accessHasOpened())
        {
            return;
        }

        $this->task->setState($newState);
        $states = (new Dictionary())->taskStates();
        switch ($newState)
        {
            case $states->RUNNING[0]:
                if (!$this->task->getDateAccept())
                {
                    $this->task->setDateAccept(new \DateTime());
                }
                break;
            case $states->CANCELED[0]:
                $this->task->setDateCancel(new \DateTime());
                break;
            case $states->COMPLETED[0]:
                $this->task->setDateComplete(new \DateTime());
                break;
            default:
                break;
        }
        $this->wasChanged = true;
    }

    public function addReport(User $user, $taskId, TaskReport $report)
    {
        $roles = $this->dataRepository->getRolesInTask($taskId, $user->getId());
        if (count($roles) > 0)
        {
            $this->accessOpened = true;
        }

        if ($this->accessHasOpened())
        {
            $this->task = $this->dataRepository->find($taskId);
            $report->setTask($this->task);
            $report->setUser($user);
            $this->dataRepository->persist($report);

            $this->wasChanged = true;
        }

        return;
    }

    public function buildModel()
    {
        $dictonary = new Dictionary();
        $roles = $dictonary->taskRoles();
        $states = $dictonary->taskStates();

        $model = array(
            $roles->CREATOR[0] => array(
                $states->CREATED[0] => array($states->CANCELED[0]),
                $states->ON_REVIEW[0] => array(
                    $states->CANCELED[0],
                    $states->RETURNED[0],
                    $states->COMPLETED[0]
                )
            ),
            $roles->EXECUTOR[0] => array(
                $states->CREATED[0] => array($states->RUNNING[0]),
                $states->RUNNING[0] => array($states->ON_REVIEW[0]),
                $states->RETURNED[0] => array($states->RUNNING[0])
            )
        );

        return $model;
    }

    public function accessHasOpened()
    {
        return $this->accessOpened;
    }

    public function save()
    {
        if (!$this->accessOpened)
        {
            return false;
        }

        if (!$this->wasChanged)
        {
            return true;
        }

        $this->dataRepository->save();
        $this->wasChanged = false;

        return true;
    }

} 