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
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(name="picture", type="string", length=512, nullable=true)
     */
    private $picture;

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
     * @ORM\OneToMany(targetEntity="ContentLanguage", mappedBy="content", cascade={"all"})
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    private $contentLangs;

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
     * @ORM\Column(name="price", type="string", length=100, nullable=true)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="content", cascade={"all"})
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    private $images;

    /**
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
     * Set price
     *
     * @param integer $price
     * @return Content
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
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

    /**
     * Add images
     *
     * @param \Aseagle\Backend\Entity\Media $images
     * @return Content
     */
    public function addImage(\Aseagle\Backend\Entity\Media $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Aseagle\Backend\Entity\Media $images
     */
    public function removeImage(\Aseagle\Backend\Entity\Media $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add contentLangs
     *
     * @param \Aseagle\Backend\Entity\ContentLanguage $contentLangs
     * @return Content
     */
    public function addContentLang(\Aseagle\Backend\Entity\ContentLanguage $contentLangs)
    {
        $this->contentLangs[] = $contentLangs;

        return $this;
    }

    /**
     * Remove contentLangs
     *
     * @param \Aseagle\Backend\Entity\ContentLanguage $contentLangs
     */
    public function removeContentLang(\Aseagle\Backend\Entity\ContentLanguage $contentLangs)
    {
        $this->contentLangs->removeElement($contentLangs);
    }

    /**
     * Get contentLangs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContentLangs()
    {
        return $this->contentLangs;
    }
}
