<?php 
/*
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\FrontBundle\Form\Event;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Aseagle\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * UserSubscriber
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class UserSubscriber implements EventSubscriberInterface
{
    protected $container;
    
    protected $currentPassword;
    
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
            FormEvents::POST_BIND => 'postBind',
            FormEvents::PRE_SET_DATA => 'preSet'
        );
    }

    /**
     * @param FormEvent $event
     */
    public function postBind(FormEvent $event)
    {
        $user = $event->getData();
        $factory = $this->container->get('security.encoder_factory');
        
        $encoder = $factory->getEncoder($user);
        $password = $user->getPassword();
        if (!empty($password)) {
            $newPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($newPassword);
        } else {
            $user->setPassword($this->currentPassword);
        }   
        
        if (NULL == $user->getGroup()) {
            $userGroup = $this->container->get('user_group_manager')->getObject(3);
            if ($userGroup) {
                $user->setGroup($userGroup);
            }
        }

        $user->setSystem(0);
        if (NULL == $user->isEnabled()) {
            $user->setEnabled(true);
        }
    }

    /**
     * @param FormEvent $event
     */
    public function preSet(FormEvent $event) 
    {
        $user = $event->getData();
        $form = $event->getForm();
        
        if ($user instanceof User && $user->getId() != null ) {
            $this->currentPassword = $user->getPassword();
            $form->add('username', null, array ( 
                'label' => 'Username', 
                'attr' => array ( 
                    'class' => 'form-control', 
                    'placeholder' => 'Username',
                    'readonly' => true
                ),
                'required' => true 
            ))->add('password', 'repeated', array ( 
                'type' => 'password', 
                'invalid_message' => 'The password fields must match.', 
                'options' => array ( 
                    'attr' => array ( 
                        'class' => 'form-control' 
                    ) 
                ), 
                'first_options' => array ( 
                    'label' => 'Password' 
                ), 
                'second_options' => array ( 
                    'label' => 'Repeat Password' 
                ), 
                'required' => false, 
                'attr' => array ( 
                    'class' => 'form-control' 
                ) 
            ));
        }  
        
    }
}