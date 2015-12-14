<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 18.03.15
 * Time: 15:23
 */

namespace LW\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use LW\Core\UserSession\UserInterface;
use LW\Model\Dictionary\UserRoles;

/**
 * User
 * @ORM\Entity(repositoryClass="LW\Model\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="users")
 */

class User implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="was_checked", type="boolean")
     */
    private $wasChecked;

    /**
     * @var boolean
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;


    /**
     * @var string
     * @ORM\Column(name="role", type="string")
     */
    private $role;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="second_name", type="string", nullable=true)
     */
    private $secondName;

    /**
     * @var integer
     * @ORM\Column(name="phone", type="bigint")
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(name="work_position", type="string", nullable=true)
     */
    private $workPosition;

    /**
     * @var string
     * @ORM\Column(name="work_place", type="string", nullable=true)
     */
    private $workPlace;


    /**
     * @var \DateTime
     * @ORM\Column(name="date_born", type="datetime", nullable=true)
     */
    private $dateBorn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Link", mappedBy="user", cascade={"remove"})
     */
    private $links;

    /**
     * @ORM\OneToMany(targetEntity="LW\Model\Entity\TaskTracker\TaskUser", mappedBy="user", cascade={"remove"})
     */
    private $taskUsers;

    /**
     * @ORM\OneToMany(targetEntity="LW\Model\Entity\TaskTracker\TaskReport", mappedBy="user", cascade={"remove"})
     */
    private $taskReports;

    /**
     * Constructor
     */

    public $captchaCode;

    public function __construct()
    {
        $this->links = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @ORM\PrePersist()
     */
    public  function setCreatedAtValue()
    {
        if(!$this->getCreatedAt())
        {
            $this->createdAt = new \DateTime();
            $this->updatedAt = $this->createdAt;
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public  function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
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

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setUsername($name)
    {
        $this->username = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
 * Set password
 *
 * @param string $password
 * @return User
 */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set wasChecked
     *
     * @param boolean $wasChecked
     * @return User
     */
    public function setWasChecked($wasChecked)
    {
        $this->wasChecked = $wasChecked;

        return $this;
    }

    /**
     * Get wasChecked
     *
     * @return boolean
     */
    public function getWasChecked()
    {
        return $this->wasChecked;
    }

    /**
     * Set isActive
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        if(is_null($isActive) && is_null($this->getIsActive()))
        {
            $isActive = false;
        }
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add links
     *
     * @param Link $links
     * @return User
     */
    public function addLink(Link $links)
    {
        $this->links[] = $links;

        return $this;
    }

    /**
     * Remove links
     *
     * @param Link $links
     */
    public function removeLink(Link $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
    }

    public function getRoles()
    {
       // return self::getRoleListForAssert();
        return array($this->role);
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    public static function getRoleList()
    {
        return (new UserRoles())->getList();
    }

    public static function getRoleListForAssert()
    {
        return (new UserRoles())->getListOfKeys();
    }

    /**
     * Set role
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setRolePre()
    {
        if(!$this->getRole())
        {
            $this->setRole('');
        }
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set secondName
     *
     * @param string $secondName
     * @return User
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;

        return $this;
    }

    /**
     * Get secondName
     *
     * @return string 
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set workPosition
     *
     * @param string $workPosition
     * @return User
     */
    public function setWorkPosition($workPosition)
    {
        $this->workPosition = $workPosition;

        return $this;
    }

    /**
     * Get workPosition
     *
     * @return string 
     */
    public function getWorkPosition()
    {
        return $this->workPosition;
    }

    /**
     * Set workPlace
     *
     * @param string $workPlace
     * @return User
     */
    public function setWorkPlace($workPlace)
    {
        $this->workPlace = $workPlace;

        return $this;
    }

    /**
     * Get workPlace
     *
     * @return string 
     */
    public function getWorkPlace()
    {
        return $this->workPlace;
    }

    /**
     * Set dateBorn
     *
     * @param string $dateBorn
     * @return User
     */
    public function setDateBorn($dateBorn)
    {
        $this->dateBorn = $dateBorn;

        return $this;
    }

    /**
     * Get dateBorn
     *
     * @return string 
     */
    public function getDateBorn()
    {
        return $this->dateBorn;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setDefaultActive()
    {
        if(is_null($this->getIsActive()))
        {
            $this->isActive = false;
        }
    }

    public function getTaskUsers()
    {
        return $this->taskUsers;
    }
}
