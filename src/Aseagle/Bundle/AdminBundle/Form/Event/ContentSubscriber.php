<?php 
/*
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\AdminBundle\Form\Event;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Aseagle\Bundle\CoreBundle\Helper\Html;
use Aseagle\Backend\Entity\Content;
use Symfony\Component\Security\Core\User\UserInterface;

use Aseagle\Backend\Entity\ContentLanguage;

/**
 * ContentSubscriber
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class ContentSubscriber implements EventSubscriberInterface
{
    protected $_container;
    protected $_contentType;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $contentType = null) 
    {
        $this->_container = $container;
        $this->_contentType = $contentType;
    }

    /**
     * @return multitype:string 
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_BIND => 'preBind',
            FormEvents::POST_BIND => 'postBind',
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $languages = $this->_container->get('backend')->getLanguageManager()->getLanguages();
        $langCodes = array();
        foreach ($languages as $lang) {
            $langCodes[$lang['code']] = $lang['code'];
        }

        if (!is_null($data->getContentLangs()) && $data->getContentLangs()->count() > 0) {

        } else {
            foreach ($langCodes as $code) {
                $lang = new ContentLanguage();
                $lang->setLang($code);
                $data->addContentLang($lang);
            }   
        }
    }

    /**
     * @param FormEvent $event
     */
    public function preBind(FormEvent $event)
    {
        $data = $event->getData();
        foreach ($data['contentLangs'] as $key => $cl) {
            $cl['slug'] =  empty($cl['slug']) ? Html::slugify($cl['title']) : Html::slugify($cl['slug']);
            $data['contentLangs'][$key] = $cl;
        }
    }

    /**
     * @param FormEvent $event
     */
    public function postBind(FormEvent $event) {
        $article = $event->getData();
        $user = $this->_container->get('security.context')->getToken()->getUser();
        if ($user instanceof UserInterface) {
            $article->setAuthor($user);
        }

        foreach ($article->getContentLangs() as $contentLang) {
            $contentLang->setContent($article);
            $slug = $contentLang->getSlug();
            $slug =  empty($slug) ? Html::slugify($contentLang->getTitle()) : Html::slugify($contentLang->getSlug());
            $contentLang->setSlug($slug);
            $this->_container->get('doctrine')->getManager()->persist($contentLang);
        }
        $article->setType($this->_contentType);


        $images = $article->getImages();
        if (count($images) > 0) {
            foreach ($images as $image) {
                $image->setContent($article);
                $image->setType(1);
                $this->_container->get('doctrine')->getManager()->persist($image);
            }

            if (null !== $article->getId()) {
                $imageDir = $this->_container->get('kernel')->getRootDir() . '/../web/uploads/products/';
                $imgList = $this->_container->get('backend')->getMediaManager()->getRepository()->findBy(array('content'=>$article->getId()));
                foreach ($imgList as $image) {
                    if (!$article->getImages()->contains($image)) {
                        if (file_exists($imageDir . $image->getPath())) {
                            unlink($imageDir . $image->getPath());
                            $this->_container->get('backend')->getMediaManager()->delete($image);
                        }
                    }
                }

            }
        }

    }
}
