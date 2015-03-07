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
use Aseagle\Bundle\AdminBundle\Form\Event\ArticleSubscriber;
use Aseagle\Bundle\ContentBundle\Entity\Category;

/**
 * ArticleFilter
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class ArticleFilter extends AbstractType {
    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', 'text', array ( 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'Title'
            ) 
        ))->add('author', 'text', array ( 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'Author'
            ) 
        ))->add('category', 'entity', array (
            'class' => 'AseagleContentBundle:Category',
            'property' => 'propertyName',
            'empty_value' => 'Category',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('o')
                ->where('o.type = :type')
                ->setParameter(':type', 'post')
                ->orderBy('o.root, o.lft, o.ordering', 'ASC');
            }, 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'Category'
            ) 
        ))->add('pageView', null, array (            
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'Hits'
            ) 
        ))->add('created_from', 'date', array ( 
            'required' => false, 
            'widget' => 'single_text', 
            'format' => 'dd/MM/yyyy', 
            'attr' => array ( 
                'readonly' => true, 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'From'
            ) 
        ))->add('created_to', 'date', array ( 
            'required' => false, 
            'widget' => 'single_text', 
            'format' => 'dd/MM/yyyy', 
            'attr' => array ( 
                'readonly' => true, 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'To'
            ) 
        ))->add('enabled', 'choice', array ( 
            'required' => false, 
            'empty_value' => 'Status', 
            'choices' => array ( 
                '1' => 'Publish', 
                '0' => 'Un-publish' 
            ), 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm' 
            ) 
        ));
        
    }

    /*
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName() {
        return 'content';
    }
}
