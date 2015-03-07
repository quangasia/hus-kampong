<?php

/*
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Aseagle\Bundle\AdminBundle\Form\Event\UserSubscriber;

/**
 * UserType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class UserType extends AbstractType {
    
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     * 
     * @param ContainerInterface $container            
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('username', null, array ( 
            'label' => 'Username', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Username',
            ),
            'required' => true 
        ))->add('email', null, array ( 
            'label' => 'Email', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Email' 
            ) 
        ))->add('fullname', null, array ( 
            'label' => 'Full Name', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Full Name' 
            ) 
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
            'required' => true, 
            'attr' => array ( 
                'class' => 'form-control' 
            ) 
        ))->add('group', null, array ( 
            'label' => 'Group',
            'property' => 'propertyName', 
            'empty_value' => "Select a group",
            'required' => true,
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Group' 
            ) 
        ))->add ('dob', null, array ( 
            'label' => 'Birthday', 
            'required' => false, 
            'widget' => 'single_text', 
            'format' => 'dd/MM/yyyy', 
            'attr' => array ( 
                'class' => 'form-control mask_date', 
                'placeholder' => '15/06/1985' 
            ) 
        ))->add ('phone', null, array ( 
            'label' => 'Phone Number', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Your phone number' 
            ), 
            'required' => false 
        ))->add('enabled', 'choice', array ( 
            'label' => 'Status',
            'required' => false, 
            'empty_value' => 'Select...', 
            'choices' => array ( 
                '1' => 'Publish', 
                '0' => 'Un-publish' 
            ), 
            'attr' => array ( 
                'class' => 'form-control' 
            ) 
        ));
        
        $builder->addEventSubscriber(new UserSubscriber($this->container));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
            'data_class' => 'Aseagle\Bundle\UserBundle\Entity\User', 
            'isadd' => true 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'user';
    }
}
