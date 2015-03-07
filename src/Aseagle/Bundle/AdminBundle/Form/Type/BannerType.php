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
use Doctrine\ORM\EntityRepository;
use Aseagle\Bundle\ContentBundle\Entity\Category;

/**
 * BannerType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class BannerType extends AbstractType {
    
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
            'label' => 'Banner Name', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Banner Name',
            ),
            'required' => true 
        ))->add('link', null, array ( 
            'label' => 'Link', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Link' 
            ) 
        ))->add('image', 'hidden', array ( 
            'label' => 'Image', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Image',
                'data-type' => 'elfinder-input-field'
            ) 
        ))->add('position', 'text', array ( 
            'label' => 'Level', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Level'
            ) 
        ))
        ->add('enabled', 'choice', array ( 
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
            'data_class' => 'Aseagle\Bundle\ContentBundle\Entity\Banner', 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'banner';
    }
}
