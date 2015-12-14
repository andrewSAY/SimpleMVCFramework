<?php

namespace LW\Model\Entity\TaskTracker;

use Doctrine\ORM\Mapping as ORM;
use LW\Model\Entity\User;

/**
 * TaskUser
 * @ORM\Entity(repositoryClass="LW\Model\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="task_users")
 */
class TaskUser
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DateTime
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var integer
     * @ORM\Column(name="task_id", type="integer", nullable=false)
     */
    private $taskId;

    /**
     * @var integer
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(name="role", type="string", length=15, nullable=false)
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="users")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private $task;

    /**
     *@ORM\ManyToOne(targetEntity="LW\Model\Entity\User", inversedBy="taskUsers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return TaskUser
     */
    public function setDateCreate(\DateTime $dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set taskId
     *
     * @param integer $taskId
     * @return TaskUser
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Get taskId
     *
     * @return integer
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return TaskUser
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function setTask(Task $task)
    {
        $this->task = $task;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function addReport(TaskReport $report)
    {
        $this->reports[] = $report;
    }

    public function getReports()
    {
        return $this->getReports();
    }

    /**
     * @ORM\PrePersist()
     */
    public function onCreated()
    {
        $this->dateCreate = new \DateTime();
    }
}
        