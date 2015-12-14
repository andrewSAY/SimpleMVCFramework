<?php

namespace LW\Model\Entity\TaskTracker;

use Doctrine\ORM\Mapping as ORM;
use LW\Model\Entity\User;

/**
 * TaskReport
 * @ORM\Entity(repositoryClass="LW\Model\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="task_reports")
 */
class TaskReport
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="body", type="text", nullable=false)
     */
    private $body;

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
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="reports")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    private $task;

    /**
     * @ORM\ManyToOne(targetEntity="LW\Model\Entity\User", inversedBy="taskReports")
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
     * Set body
     *
     * @param string $body
     * @return TaskReport
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return TaskReport
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
     * @return TaskReport
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


    public function setTask(Task $task)
    {
        $this->task = $task;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setUserId($id)
    {
        $this->userId = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PrePersist()
     */
    public function onCreated()
    {
        $this->dateCreate = new \DateTime();
    }
}
        