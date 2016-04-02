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
 * @ORM\Table(name="content_lang")
 * @ORM\Entity(repositoryClass="Aseagle\Backend\Repository\ContentLanguageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ContentLanguage {
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
     * @ORM\Column(name="long_text", type="text", nullable=true)
     */
    private $longText;

    /**
     * @ORM\Column(name="tags", type="string", length=512, nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(name="lang", type="string", length=4)
     */
    private $lang;
    
    /**
     * @ORM\Column(name="short_text", type="text", nullable=true)
     */
    private $shortText;

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
     * @ORM\ManyToOne(targetEntity="Content", cascade={"persist"})
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    private $content;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", cascade={"persist"})
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="id")
     */
    private $category;
 
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
     * Set title
     *
     * @param string $title
     * @return ContentLanguage
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
     * @return ContentLanguage
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
     * Set longText
     *
     * @param string $longText
     * @return ContentLanguage
     */
    public function setLongText($longText)
    {
        $this->longText = $longText;

        return $this;
    }

    /**
     * Get longText
     *
     * @return string 
     */
    public function getLongText()
    {
        return $this->longText;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return ContentLanguage
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
     * Set shortText
     *
     * @param string $shortText
     * @return ContentLanguage
     */
    public function setShortText($shortText)
    {
        $this->shortText = $shortText;

        return $this;
    }

    /**
     * Get shortText
     *
     * @return string 
     */
    public function getShortText()
    {
        return $this->shortText;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return ContentLanguage
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
     * @return ContentLanguage
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
     * @return ContentLanguage
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
     * Set content
     *
     * @param \Aseagle\Backend\Entity\Content $content
     * @return ContentLanguage
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
     * Set lang
     *
     * @param string $lang
     * @return ContentLanguage
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getLang()
    {
        return $this->lang;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * Set category
     *
     * @param \Aseagle\Backend\Entity\Category $category
     * @return ContentLanguage
     */
    public function setCategory(\Aseagle\Backend\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Aseagle\Backend\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
