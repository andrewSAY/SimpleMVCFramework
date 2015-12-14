<?php
/**
 * Created by PhpStorm.
 * User: Сапрыкин А_Ю
 * Date: 18.03.15
 * Time: 15:42
 */

namespace LW\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 * @ORM\Entity(repositoryClass="LW\Model\Repository\GroupRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="groups")
 */

class Group
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
     * @var integer
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="owner", type="integer")
     */
    private $owner;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */

    private $isActive;

    /**
     * @var integer
     * @ORM\Column(name="inner_order", type="integer")
     */
    private $innerOrder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Link", mappedBy="group", cascade={"remove"})
     */
    private $links;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->links = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue()
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
    public function setUpdatedAtValue()
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
     * @return Group
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
     * Set owner
     *
     * @param integer $owner
     * @return Group
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return integer 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Group
     */
    public function setIsActive($isActive)
    {
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
     * @return Group
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
     * @return Group
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
     * @return Group
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

    /**
     * Set innerOrder
     *
     * @param integer $innerOrder
     * @return Group
     */
    public function setInnerOrder($innerOrder)
    {
        $this->innerOrder = $innerOrder;

        return $this;
    }

    /**
     * Get innerOrder
     *
     * @return integer 
     */
    public function getInnerOrder()
    {
        return $this->innerOrder;
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
}
