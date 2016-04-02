<?php

/*
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\AdminBundle\Form\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * SettingFilter
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class SettingFilter extends AbstractType {
   
    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', null, array ( 
            'label' => 'Name', 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm', 
                'placeholder' => 'Name',
            ),
            'required' => false 
        ))->add('value', null, array ( 
            'label' => 'Value', 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm', 
                'placeholder' => 'Value' 
            ),
            'required' => false
        ))
        ->add('key', null, array ( 
            'label' => 'Key', 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm', 
                'placeholder' => 'Key' 
            ),
            'required' => false
        ))->add('locale', null, array ( 
            'label' => 'Language', 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm', 
                'placeholder' => 'Language' 
            ),
            'required' => false
        ));

    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'setting';
    }
}
