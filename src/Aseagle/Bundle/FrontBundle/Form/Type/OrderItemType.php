<?php

/*
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * OrderItemType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class OrderItemType extends AbstractType {
 
    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('product', null, array ( 
            'label' => 'Product', 
            'property' => 'name',
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Product',
            ),
            'required' => true 
        ))->add('quantity', null, array ( 
            'label' => 'Price', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Price' 
            ) 
        ));
        
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
            'data_class' => 'Aseagle\Bundle\EcommerceBundle\Entity\OrderItem', 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'product_order';
    }
}
