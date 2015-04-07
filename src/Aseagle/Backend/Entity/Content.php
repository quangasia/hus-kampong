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

use Aseagle\Bundle\CoreBundle\Helper\Html;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="contents1")
 * @ORM\Entity(repositoryClass="Aseagle\Backend\Repository\ContentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Content {
    /* Content type */
    const TYPE_POST = 1;
    const TYPE_PAGE = 2;
    const TYPE_FOOD = 3;
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
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
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(name="picture", type="string", length=512, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(name="tags", type="string", length=512, nullable=true)
     */
    private $tags;
    
    /**
     * @ORM\Column(name="short_description", type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @ORM\Column(name="meta_title", type="string", length=512, nullable=true)
     */
    private $metaTitle;

    /**
     * @ORM\Column(name="meta_content", type="string", length=512, nullable=true)
     */
    private $metaContent;

    /**
     * @ORM\Column(name="meta_keywords", type="string", length=512, nullable=true)
     */
    private $metaKeywords;

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
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="content_category")
     */
    private $categories;
    
    /**
     * @ORM\ManyToOne(targetEntity="Aseagle\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;
    
    /**
     * @ORM\Column(name="feature", type="boolean", nullable=true)
     */
    private $feature;
    
    /**
     * @ORM\Column(name="system", type="boolean", nullable=true)
     */
    private $system;
    
    /**
     * @ORM\Column(name="rate_point", type="float", nullable=true)
     */
    private $ratePoint;
    
    /**
     * @ORM\Column(name="rate_count", type="integer", nullable=true)
     */
    private $rateCount;
    
    
    /**
     * @ORM\Column(name="time", type="integer", nullable=true)
     */
    private $time;

    /**
     * Set title
     *
     * @param string $title
     * @return Content
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
     * @return Content
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
     * @return Content
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Content
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Content
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

    /**
     * Set tags
     *
     * @param string $tags
     * @return Content
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return Content
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaContent
     *
     * @param string $metaContent
     * @return Content
     */
    public function setMetaContent($metaContent)
    {
        $this->metaContent = $metaContent;

        return $this;
    }

    /**
     * Get metaContent
     *
     * @return string 
     */
    public function getMetaContent()
    {
        return $this->metaContent;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return Content
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Content
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
     * @return Content
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
     * @return Content
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
        
        $this->setUpdated(new \DateTime(date('Y-m-d H:i:s')));
        if (NULL === $this->getCreated()) {
            $this->setCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
       
    /**
     * Set author
     *
     * @param \Aseagle\Bundle\UserBundle\Entity\User $author
     * @return Content
     */
    public function setAuthor(\Aseagle\Bundle\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Aseagle\Bundle\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categories
     *
     * @param \Aseagle\Bundle\ContentBundle\Entity\Category $categories
     * @return Content
     */
    public function addCategory(Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $categories
     */
    public function removeCategory(Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * @var integer
     */
    private $pageView;


    /**
     * Set pageView
     *
     * @param integer $pageView
     * @return Content
     */
    public function setPageView($pageView)
    {
        $this->pageView = $pageView;

        return $this;
    }

    /**
     * Get pageView
     *
     * @return integer 
     */
    public function getPageView()
    {
        return $this->pageView;
    }
    


    /**
     * Set system
     *
     * @param boolean $system
     * @return Content
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
     * Set feature
     *
     * @param boolean $feature
     * @return Content
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;

        return $this;
    }

    
    /**
     * Get feature
     *
     * @return boolean 
     */
    public function isFeature()
    {
        return $this->feature;
    }
    
    /**
     * Get feature
     *
     * @return boolean
     */
    public function getFeature()
    {
        return $this->feature;
    }
    
    


    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Content
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Content
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set ratePoint
     *
     * @param integer $ratePoint
     * @return Content
     */
    public function setRatePoint($ratePoint)
    {
        $this->ratePoint = $ratePoint;

        return $this;
    }

    /**
     * Get ratePoint
     *
     * @return integer 
     */
    public function getRatePoint()
    {
        return $this->ratePoint;
    }

    /**
     * Set rateCount
     *
     * @param integer $rateCount
     * @return Content
     */
    public function setRateCount($rateCount)
    {
        $this->rateCount = $rateCount;

        return $this;
    }

    /**
     * Get rateCount
     *
     * @return integer 
     */
    public function getRateCount()
    {
        return $this->rateCount;
    }
}
