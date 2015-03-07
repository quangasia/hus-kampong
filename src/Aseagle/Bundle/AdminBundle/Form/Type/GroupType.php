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

/**
 * GroupType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class GroupType extends AbstractType {
    
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
        $builder->add('name', null, array ( 
            'label' => 'Group Name', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Group Name',
            ),
            'required' => true 
        ))->add('type', 'choice', array (
            'label' => 'Group Type', 
            'required' => true, 
            'empty_value' => 'Select...', 
            'choices' => array ( 
                '0' => 'User Level', 
                '1' => 'Manager Level' 
            ), 
            'attr' => array ( 
                'class' => 'form-control' 
            )  
        ))->add('desc', null, array ( 
            'label' => 'Description', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Description' 
            ) 
        ))->add('role', null, array ( 
            'label' => 'Role Name', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Role Name' 
            ),
            'required' => true 
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
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
            'data_class' => 'Aseagle\Bundle\UserBundle\Entity\UserGroup', 
            'isadd' => true 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'group';
    }
}
