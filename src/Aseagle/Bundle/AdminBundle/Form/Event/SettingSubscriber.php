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

/**
 * SettingSubscriber
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class SettingSubscriber implements EventSubscriberInterface
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
            FormEvents::PRE_SET_DATA => 'preSet',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function preSet(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        
        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            if ($data->getId() != null) {
                $form->add('key', null, array ( 
                    'label' => 'Key', 
                    'attr' => array ( 
                        'class' => 'form-control', 
                        'placeholder' => 'Key',
                        'readonly' => true 
                    ) 
                ));
            } else {
                $form->add('key', null, array (
                    'label' => 'Key',
                    'attr' => array (
                        'class' => 'form-control',
                        'placeholder' => 'Key',
                    )
                ));
            }
        }
        
    }    
}