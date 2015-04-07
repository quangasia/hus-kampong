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
use Aseagle\Backend\Entity\Category;

/**
 * CategorySubscriber
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class CategorySubscriber implements EventSubscriberInterface
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
            FormEvents::PRE_SET_DATA => 'preSet',
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
        $category = $event->getData();
        $category->setType(Category::TYPE_POST);
    }
    
    /**
     * @param FormEvent $event
     */
    public function preSet(FormEvent $event) 
    {
        $category = $event->getData();
        $form = $event->getForm();
        if (NULL != $category->getId()) {
            $categoryId = $category->getId();
            $form->add('parent', null, array ( 
                'label' => 'Parent Category',
                'property' => 'propertyName',
                'class' => 'AseagleBackend:Category',
                'empty_value' => "Select...",
                'query_builder' => function(EntityRepository $er) use ($categoryId) {
                    return $er->createQueryBuilder('o')
                        ->where('o.type = :type')
                        ->setParameter(':type', Category::TYPE_POST)
                        ->andWhere('o.enabled = 1')
                        ->andWhere('o.id <> :id')
                        ->setParameter(':id', $categoryId)
                        ->orderBy('o.root, o.lft, o.ordering', 'ASC');
                },
                'attr' => array ( 
                    'class' => 'form-control', 
                    'placeholder' => 'Category' 
                ) 
            ));
        } 
    }
}