<?php

namespace LW\Model\Entity\TaskTracker;

use Doctrine\ORM\Mapping as ORM;
use LW\Model\Dictionary\TaskLevels;
use LW\Model\Dictionary\TaskSpeeds;
use LW\Model\Dictionary\TaskStates;
use LW\Model\Entity\User;

/**
 * Task
 * @ORM\Entity(repositoryClass="LW\Model\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="tasks")
 */
class Task
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
     * @ORM\Column(name="theme", type="string", length=255, nullable=false)
     */
    private $theme;

    /**
     * @var string
     * @ORM\Column(name="body", type="text", nullable=false)
     */
    private $body;

    /**
     * @var string
     * @ORM\Column(name="state", type="string", length=10, nullable=false)
     */
    private $state;

    /**
     * @var string
     * @ORM\Column(name="hard_level", type="string", length=10, nullable=false)
     */
    private $hardLevel;

    /**
     * @var string
     * @ORM\Column(name="speed_level", type="string", length=10, nullable=false)
     */
    private $speedLevel;

    /**
     * @var DateTime
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var DateTime
     * @ORM\Column(name="date_accept", type="datetime", nullable=true)
     */
    private $dateAccept;

    /**
     * @var DateTime
     * @ORM\Column(name="date_cancel", type="datetime", nullable=true)
     */
    private $dateCancel;

    /**
     * @var DateTime
     * @ORM\Column(name="date_complete", type="datetime", nullable=true)
     */
    private $dateComplete;

    /**
     * @var DateTime
     * @ORM\Column(name="date_limit", type="datetime", nullable=true)
     */
    private $dateLimit;

    /**
     * @var boolean
     * @ORM\Column(name="file", type="boolean", nullable=true)
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="TaskReport", mappedBy="task", cascade={"remove"})
     * @ORM\OrderBy({"dateCreate" = "DESC"})
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity="TaskUser", mappedBy="task", cascade={"remove"})
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reports = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set theme
     *
     * @param string $theme
     * @return Task
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Task
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
     * Set state
     *
     * @param string $state
     * @return Task
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    public function getHardLevel()
    {
        return $this->hardLevel;
    }

    public function setHardLevel($value)
    {
        $this->hardLevel = $value;

        return $this;
    }

    public function getSpeedLevel()
    {
        return $this->speedLevel;
    }

    public function setSpeedLevel($value)
    {
        $this->speedLevel = $value;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }


    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Task
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
     * Set dateAccept
     *
     * @param \DateTime $dateAccept
     * @return Task
     */
    public function setDateAccept($dateAccept)
    {
        $this->dateAccept = $dateAccept;

        return $this;
    }

    /**
     * Get dateAccept
     *
     * @return \DateTime
     */
    public function getDateAccept()
    {
        return $this->dateAccept;
    }

    /**
     * Set dateCancel
     *
     * @param \DateTime $dateCancel
     * @return Task
     */
    public function setDateCancel($dateCancel)
    {
        $this->dateCancel = $dateCancel;

        return $this;
    }

    /**
     * Get dateCancel
     *
     * @return \DateTime
     */
    public function getDateCancel()
    {
        return $this->dateCancel;
    }

    /**
     * Set dateComplete
     *
     * @param \DateTime $dateComplete
     * @return Task
     */
    public function setDateComplete($dateComplete)
    {
        $this->dateComplete = $dateComplete;

        return $this;
    }

    /**
     * Get dateComplete
     *
     * @return \DateTime
     */
    public function getDateComplete()
    {
        return $this->dateComplete;
    }

    public function setDateLimit($dateLimit)
    {
        ;
        $this->dateLimit = $dateLimit;

        return $this;
    }

    public function getDateLimit()
    {
        return $this->dateLimit;
    }

    /**
     * Add reports
     *
     * @param \LW\Model\Entity\TaskTracker\TaskReport $reports
     * @return Task
     */
    public function addReport(\LW\Model\Entity\TaskTracker\TaskReport $reports)
    {
        $this->reports[] = $reports;

        return $this;
    }

    /**
     * Remove reports
     *
     * @param \LW\Model\Entity\TaskTracker\TaskReport $reports
     */
    public function removeReport(\LW\Model\Entity\TaskTracker\TaskReport $reports)
    {
        $this->reports->removeElement($reports);
    }

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * Add users
     *
     * @param \LW\Model\Entity\TaskTracker\TaskUser $users
     * @return Task
     */
    public function addUser(\LW\Model\Entity\TaskTracker\TaskUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    public function setUsers($value)
    {
        if (is_array($value))
        {
            foreach ($value as $item)
            {
                $taskUser = new TaskUser();
                $taskUser->setTask($this);
                $taskUser->setUserId($item->id);
                $this->addUser($taskUser);
            }

            return;
        }
    }

    /**
     * Remove users
     *
     * @param \LW\Model\Entity\TaskTracker\TaskUser $users
     */
    public function removeUser(\LW\Model\Entity\TaskTracker\TaskUser $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
 * @ORM\PrePersist()
 */
    public function onCreated()
    {
        $this->dateCreate = new \DateTime();
    }
}
        