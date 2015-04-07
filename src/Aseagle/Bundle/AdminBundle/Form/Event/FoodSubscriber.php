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

/**
 * FoodSubscriber
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class FoodSubscriber implements EventSubscriberInterface
{
    protected $container;
    
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) 
    {
        $this->container = $container;
    }
    
    /**
     * @return multitype:string 
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_BIND => 'preBind',
            FormEvents::POST_BIND => 'postBind',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function preBind(FormEvent $event)
    {
        $data = $event->getData();
        $data['slug'] = empty($data['slug']) ? Html::slugify($data['title']) : Html::slugify($data['slug']);
          
        /* Replacing new value for slug field */
        $event->setData($data);
    }
    
    /**
     * @param FormEvent $event
     */
    public function postBind(FormEvent $event) {
        $article = $event->getData();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if ($user instanceof UserInterface) {
            $article->setAuthor($user);
        }
        $article->setType(Content::TYPE_FOOD);
        
        $images = $article->getImages();
        foreach ($images as $image) {
            $image->setContent($article);
            $image->setType(1);
            //$this->container->get('backend')->getMediaManager()->save($image, false);
            $this->container->get('doctrine')->getManager()->persist($image);
        }

        if (null !== $article->getId()) {
            $imageDir = $this->container->get('kernel')->getRootDir() . '/../web/uploads/products/';
            $imgList = $this->container->get('backend')->getMediaManager()->getRepository()->findBy(array('content'=>$article->getId()));
            foreach ($imgList as $image) {
                if (!$article->getImages()->contains($image)) {
                    if (file_exists($imageDir . $image->getPath())) {
                        unlink($imageDir . $image->getPath());
                        $this->container->get('backend')->getMediaManager()->delete($image);
                    }
                }
            }

        }
    }
}
