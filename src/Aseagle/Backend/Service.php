<?php
namespace Aseagle\Backend;

use Symfony\Component\DependencyInjection\Container;
//Managers class
use Aseagle\Backend\Manager\SettingManager;
use Aseagle\Backend\Manager\ContentManager;
use Aseagle\Backend\Manager\BannerManager;
use Aseagle\Backend\Manager\CategoryManager;
use Aseagle\Backend\Manager\CommentManager;
use Aseagle\Backend\Manager\MediaManager;
use Aseagle\Backend\Manager\LanguageManager;

/**
 * Service class
 */
class Service 
{
    protected $_container = null;
    protected $_entityManager = null;
    protected $_settingManager = null;
    protected $_contentManager = null;
    protected $_categoryManager = null;
    protected $_bannerManager = null;
    protected $_mediaManager = null;
    protected $_commentManager = null;
    protected $_languageManager = null;
    
    /**
     * __construct
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->_container = $container;
        $this->_entityManager = $container->get('doctrine')->getManager();
    }
    
    /**
     * getSettingManager
     * 
     * @return SettingManager
     */
    public function getSettingManager()
    {
        if (is_null($this->_settingManager)) {
            $this->_settingManager = new SettingManager($this->_entityManager);
        }
        return $this->_settingManager;
    }
    
    /**
     * getContentManager
     * 
     * @return ContentManager
     */
    public function getContentManager()
    {
        if (is_null($this->_contentManager)) {
            $this->_contentManager = new ContentManager($this->_entityManager, $this->_container);
        }
        return $this->_contentManager;
    }
    
    /**
     * getCategoryManager
     * 
     * @return CategoryManager
     */
    public function getCategoryManager()
    {
        if (is_null($this->_categoryManager)) {
            $this->_categoryManager = new CategoryManager($this->_entityManager);
        }
        return $this->_categoryManager;
    }
    
    /**
     * getBannerManager
     * 
     * @return BannerManager
     */
    public function getBannerManager()
    {
        if (is_null($this->_bannerManager)) {
            $this->_bannerManager = new BannerManager($this->_entityManager);
        }
        return $this->_bannerManager;
    }
    
    /**
     * getMediaManager
     * 
     * @return type
     */
    public function getMediaManager()
    {
        if (is_null($this->_mediaManager)) {
            $this->_mediaManager = new MediaManager($this->_entityManager);
        }
        return $this->_mediaManager;
    }
    
    /**
     * getCommentManager
     * 
     * @return CommentManager
     */
    public function getCommentManager()
    {
        if (is_null($this->_commentManager)) {
            $this->_commentManager = new CommentManager($this->_entityManager);
        }
        return $this->_commentManager;
    }

    /**
     * getLanguageManager
     *
     * @return LanguageManager
     */
    public function getLanguageManager()
    {
        if (is_null($this->_languageManager)) {
            $this->_languageManager = new LanguageManager($this->_entityManager);
        }

        return $this->_languageManager;
    }

    /**
     * getManager
     * 
     * @param string $key
     * 
     * @return ObjectManagerInterface
     */
    public function getManager($key)
    {
        $manager = null;
        switch ($key) {
            case 'setting':
                $manager = $this->getSettingManager();
                break;
            case 'content':
                $manager = $this->getContentManager();
                break;
            case 'category':
                $manager = $this->getCategoryManager();
                break;
            case 'banner':
                $manager = $this->getBannerManager();
                break;
            case 'comment':
                $manager = $this->getCommentManager();
                break;
            case 'media':
                $manager = $this->getMediaManager();
                break;
            case 'language':
                $manager = $this->getLanguageManager();
                break;
            default :
                $manager = null;
        }
        return $manager;
    }
}
