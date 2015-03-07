<?php

namespace Aseagle\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserGroup
 */
class UserGroup
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var string
     */
    private $desc;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var string
     */
    private $role;

    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var \DateTime
     */
    private $created;


    /**
     * Set name
     *
     * @param string $name
     * @return UserGroup
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
     * Set type
     *
     * @param integer $type
     * @return UserGroup
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set desc
     *
     * @param string $desc
     * @return UserGroup
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string 
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return UserGroup
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return UserGroup
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Get property name
     * 
     * @return string
     */
    public function getPropertyName()
    {
        return $this->name;
    }
    
   /** Set created
    *
    * @param \DateTime $created
    * @return User
    */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }
    
    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    /**
     * lifecycle-callback
     * prePersist
     * preUpdate
     */
    public function updatedTimestamp()
    {
        if($this->getCreated() == null)
        {
            $this->setCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
    
    /**
     * @var boolean
     */
    private $system;


    /**
     * Set system
     *
     * @param boolean $system
     * @return UserGroup
     */
    public function setSystem($system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return boolean 
     */
    public function isSystem()
    {
        return $this->system;
    }
}
