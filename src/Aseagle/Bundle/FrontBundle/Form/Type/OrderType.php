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
use Aseagle\Bundle\FrontBundle\Form\Event\OrderSubscriber;

/**
 * OrderType
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class OrderType extends AbstractType {
    
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
        $builder->add('fullname', null, array ( 
            'label' => 'Full Name', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Full Name',
            ),
            'required' => true 
        ))->add('email', null, array ( 
            'label' => 'Email', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Email' 
            ),
            'required' => true
        ))->add('phone', null, array ( 
            'label' => 'Phone', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Phone' 
            ),
            'required' => true
        ))->add('address', null, array ( 
            'label' => 'Address', 
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Address' 
            ),
            'required' => true
        ))->add('orderItems', 'collection', array ( 
            'label' => 'Order Items',
            'type' => new OrderItemType(),            
            'attr' => array ( 
                'class' => 'form-control', 
                'placeholder' => 'Order Items' 
            ),
            'allow_add' => true,
            'by_reference' => true,
            'allow_delete' => true
        ));        
        
        $builder->addEventSubscriber(new OrderSubscriber($this->container));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array ( 
            'data_class' => 'Aseagle\Bundle\EcommerceBundle\Entity\Order', 
        ));
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'order';
    }
}
