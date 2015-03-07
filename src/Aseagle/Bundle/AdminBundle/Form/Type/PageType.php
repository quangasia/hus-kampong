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
use Aseagle\Bundle\AdminBundle\Form\Event\PageSubscriber;

/**
 * PageType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class PageType extends AbstractType {
    
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
        $builder->add('title', null, array ( 
            'label' => 'Title', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Title',
            ),
            'required' => true 
        ))->add('slug', null, array ( 
            'label' => 'Slug', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Slug' 
            ) 
        ))->add('content', null, array ( 
            'label' => 'Content', 
            'attr' => array ( 
                'class' => 'form-control tinymce', 
                'placeholder' => 'Content',
                'data-theme' => 'advanced' 
            ) 
        ))->add('metaTitle', null, array ( 
            'label' => 'Meta Title', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Meta Title' 
            ) 
        ))->add('metaContent', 'textarea', array ( 
            'label' => 'Meta Description', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Meta Description' 
            ),
            'required' => false 
        ))->add('metaKeywords', null, array ( 
            'label' => 'Meta Keywords', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Meta Keywords' 
            ) 
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
        
        $builder->addEventSubscriber(new PageSubscriber($this->container));
        
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
            'data_class' => 'Aseagle\Bundle\ContentBundle\Entity\Content', 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'page';
    }
}
