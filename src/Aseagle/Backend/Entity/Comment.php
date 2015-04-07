<?php

namespace Aseagle\Backend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="Aseagle\Backend\Repository\CommentRepository")
 */
class Comment
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;
    
    /**
     * @ORM\ManyToOne(targetEntity="Aseagle\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Content")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    private $content;
    
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
     * @ORM\ManyToOne(targetEntity="Comment")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

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
     * @ORM\Column(name="fullname", type="string", length=255, nullable=true)
     */
    private $fullname;
    
    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;
    
    /**
     * @ORM\Column(name="rate_point", type="float", nullable=true)
     */
    private $ratePoint;

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
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * Set user
     *
     * @param \Aseagle\Bundle\UserBundle\Entity\User $user
     * @return Comment
     */
    public function setUser(\Aseagle\Bundle\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Aseagle\Bundle\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set content
     *
     * @param \Aseagle\Backend\Entity\Content $content
     * @return Comment
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

    /**
     * Set parent
     *
     * @param \Aseagle\Backend\Entity\Comment $parent
     * @return Comment
     */
    public function setParent(\Aseagle\Backend\Entity\Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Aseagle\Backend\Entity\Comment 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return Comment
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Comment
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
     * Set lft
     *
     * @param integer $lft
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * @return Comment
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
     * Set ratePoint
     *
     * @param integer $ratePoint
     * @return Comment
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
}
