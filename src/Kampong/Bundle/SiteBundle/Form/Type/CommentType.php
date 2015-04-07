<?php

/*
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Kampong\Bundle\SiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * FoodType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class CommentType extends AbstractType {
    
    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fullname', null, array ( 
            'label' => 'Fullname', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Fullname',
            ),
            'required' => true 
        ))->add('email', 'email', array ( 
            'label' => 'Email', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Email' 
            ) 
        ))->add('ratePoint', 'hidden', array ( 
            'label' => 'Rate', 
            'attr' => array ( 
                'class' => 'form-control ratePoint', 
            ) 
        ))->add('comment', null, array ( 
            'label' => 'Comment', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Comment',
            ),
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
            'data_class' => 'Aseagle\Backend\Entity\Comment',
            'csrf_protection' => false,
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'comment';
    }
}
