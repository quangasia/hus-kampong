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
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="banner")
 * @ORM\Entity(repositoryClass="Aseagle\Backend\Repository\BannerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Banner {
    const TYPE_HOMEPAGE_SLIDE = 1;
    const TYPE_BANNER_LEFT = 2;
    const TYPE_BANNER_NEWS = 3;
    const TYPE_BANNER_FOOD = 4;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(name="image", type="string", length=512, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

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
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="name", type="string", length=256, nullable=true)
     */
    private $name;


    /**
     * Set position
     *
     * @param integer $position
     * @return Banner
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Banner
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Banner
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Banner
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
     * Set created
     *
     * @param \DateTime $created
     * @return Banner
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
     * Set type
     *
     * @param integer $type
     * @return Banner
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
     * @return Banner
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Banner
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
}
