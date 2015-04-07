<?php
/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Backend\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Aseagle\Bundle\CoreBundle\Helper\Html;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Aseagle\Backend\Repository\ContentRepository")
 * @ORM\HasLifecycleCallbacks
 */
Class Category {
    const TYPE_POST = 1;
    const TYPE_MENU = 2;
    
    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mtime", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ctime", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", nullable=true)
     */
    protected $lft;
    
    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", nullable=true)
     */
    protected $rgt;
    
    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer", nullable=true)
     */
    protected $lvl;
    
    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    protected $root;
    
    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id")
     */
    private $children;
    
    /**
     * @ORM\Column(name="ordering", type="integer", nullable=true)
     */
    private $ordering;
    
    /**
     * @ORM\Column(name="system", type="boolean", nullable=true)
     */
    private $system;
      

    /**
     * Set title
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Category
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
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Category
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Category
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Category
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamp()
    {
        /*Updated the slug string*/
        if (NULL != $this->getSlug()) {
            $this->slug = $this->title;
        }        
        $this->slug = Html::slugify($this->slug);
        
        /*Updated timestamp*/
        $this->setUpdated(new \DateTime(date('Y-m-d H:i:s')));
        if (NULL === $this->getCreated()) {
            $this->setCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
    
    /**
     * @return string
     */
    public function propertyName()
    {
        return str_repeat("—", $this->lvl) . " " . $this->title;
    }
    


    /**
     * Set parent
     *
     * @param \Aseagle\Backend\Entity\Category $parent
     * @return Category
     */
    public function setParent(\Aseagle\Backend\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Aseagle\Bundle\ContentBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Category
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add children
     *
     * @param \Aseagle\Backend\Entity\Category $children
     * @return Category
     */
    public function addChild(\Aseagle\Backend\Entity\Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Aseagle\Backend\Entity\Category $children
     */
    public function removeChild(\Aseagle\Backend\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
    


    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return Category
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }
    


    /**
     * Set system
     *
     * @param boolean $system
     * @return Category
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
    public function getSystem()
    {
        return $this->system;
    }
    
    /**
     * Is system
     *
     * @return boolean
     */
    public function isSystem()
    {
        return $this->system;
    }
    /**
     * @var string
     */
    private $picture;


    /**
     * Set picture
     *
     * @param string $picture
     * @return Category
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
