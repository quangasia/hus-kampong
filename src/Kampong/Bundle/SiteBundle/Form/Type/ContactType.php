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
 * ContactType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class ContactType extends AbstractType {
    
    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fullname', null, array ( 
            'label' => 'Fullname', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Họ tên',
            ),
            'required' => true 
        ))->add('email', 'email', array ( 
            'label' => 'Email', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Email' 
            ) 
        ))->add('title', 'text', array ( 
            'label' => 'Title', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Tiêu đề' 
            ) 
        ))->add('phone', 'text', array ( 
            'label' => 'Phone number', 
            'attr' => array ( 
                'class' => 'form-control ratePoint', 
                'placeholder' => 'Số điện thoại' 
            ) 
        ))->add('content', 'textarea', array ( 
            'label' => 'Content', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Nội dung',
            ),
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'contact';
    }
}
