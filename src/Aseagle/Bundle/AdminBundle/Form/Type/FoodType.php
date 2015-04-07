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
use Aseagle\Bundle\AdminBundle\Form\Event\FoodSubscriber;
use Aseagle\Backend\Entity\Category;

/**
 * FoodType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class FoodType extends AbstractType {
    
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
        ))->add('shortDescription', null, array ( 
            'label' => 'Short Description', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Short Description',
            ) 
        ))->add('content', null, array ( 
            'label' => 'Content', 
            'attr' => array ( 
                'class' => 'form-control tinymce', 
                'placeholder' => 'Content',
                'data-theme' => 'advanced' 
            ) 
        ))->add('time', null, array ( 
            'label' => 'Time', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Time' 
            ) 
        ))->add('picture', 'hidden', array ( 
            'label' => 'Picture', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Picture',
                'data-type' => 'elfinder-input-field'
            ) 
        ))->add('categories', null, array ( 
            'label' => 'Menus',
            'property' => 'propertyName',
            'class' => 'AseagleBackend:Category',
            "expanded" => true,
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('o')
                    ->where('o.type = :type')
                    ->setParameter(':type', Category::TYPE_MENU)
                    ->orderBy('o.root, o.lft, o.ordering', 'ASC');
            },
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Categories' 
            ) 
        ))->add('tags', null, array ( 
            'label' => 'Tags', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Tags' 
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
        ))->add('feature', 'checkbox', array ( 
            'label' => 'Feature',
            'required' => false, 
            'attr' => array ( 
                'class' => 'form-control' 
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
        
        $builder->addEventSubscriber(new FoodSubscriber($this->container));
        
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
            'data_class' => 'Aseagle\Backend\Entity\Content', 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'food';
    }
}
