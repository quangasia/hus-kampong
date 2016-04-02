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

/**
 * ContentLanguageType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class ContentLanguageType extends AbstractType {

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
        ))->add('shortText', null, array ( 
            'label' => 'Short Description', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Short Description',
            ),
            'required' => false 
        ))->add('longText', null, array ( 
            'label' => 'Content', 
            'attr' => array ( 
                'class' => 'form-control tinymce', 
                'placeholder' => 'Content',
                'data-theme' => 'advanced' 
            ), 
            'required' => false 
        ))->add('tags', null, array ( 
            'label' => 'Tags', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Tags' 
            ), 
            'required' => false 
        ))->add('metaTitle', null, array ( 
            'label' => 'Meta Title', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Meta Title' 
            ),
            'required' => false 
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
            ), 
            'required' => false 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
            'data_class' => 'Aseagle\Backend\Entity\ContentLanguage', 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'content_lang';
    }
}
