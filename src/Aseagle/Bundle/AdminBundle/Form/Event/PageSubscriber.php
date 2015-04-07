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
use Doctrine\ORM\EntityRepository;
use Aseagle\Bundle\CoreBundle\Helper\Html;
use Aseagle\Backend\Entity\Content;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * PageSubscriber
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class PageSubscriber implements EventSubscriberInterface
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
        $article->setType(Content::TYPE_PAGE);
    }
}