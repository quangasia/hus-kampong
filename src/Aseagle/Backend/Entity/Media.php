<?php

namespace Aseagle\Backend\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="Aseagle\Backend\Repository\MediaRepository")
 */
class Media
{
    /* Content type */
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_FILE  = 3;
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="path", type="text", nullable=true)
     */
    private $path;
    
    /**
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;
     
    /**
     * @ORM\ManyToOne(targetEntity="Content")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    private $content;
  
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ctime", type="datetime", nullable=true)
     */
    private $created;
    
    /**
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

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
     * Set path
     *
     * @param string $path
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Media
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
     * Set created
     *
     * @param \DateTime $created
     * @return Media
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Media
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
     * Set content
     *
     * @param \Aseagle\Backend\Entity\Content $content
     * @return Media
     */
    public function setContent(\Aseagle\Backend\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Aseagle\Backend\Entity\Content 
     */
    public function getContent()
    {
        return $this->content;
    }
}
