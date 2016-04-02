<?php 
/*
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\AdminBundle\Form\Event;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;
use Aseagle\Bundle\CoreBundle\Helper\Html;
use Aseagle\Backend\Entity\Category;
use Aseagle\Backend\Entity\ContentLanguage;

/**
 * CategorySubscriber
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class CategorySubscriber implements EventSubscriberInterface
{
    protected $_container;
    protected $_contentType;
    
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $contentType) 
    {
        $this->_container = $container;
        $this->_contentType = $contentType;
    }
    
    /**
     * @return multitype:string 
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_BIND => 'preBind',
            FormEvents::POST_BIND => 'postBind',
            FormEvents::PRE_SET_DATA => 'preSet',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function preBind(FormEvent $event)
    {
        $data = $event->getData();
        foreach ($data['contentLangs'] as $key => $cl) {
            $cl['slug'] =  empty($cl['slug']) ? Html::slugify($cl['title']) : Html::slugify($cl['slug']);
            $data['contentLangs'][$key] = $cl;
        }
     }
    
    
    /**
     * @param FormEvent $event
     */
    public function preSet(FormEvent $event) 
    {
        $contentType = $this->_contentType;
        $category = $event->getData();
        $form = $event->getForm();
        if (NULL != $category->getId()) {
            $categoryId = $category->getId();
            $form->add('parent', null, array ( 
                'label' => 'Parent Category',
                'property' => 'propertyName',
                'class' => 'AseagleBackend:Category',
                'empty_value' => "Select...",
                'query_builder' => function(EntityRepository $er) use ($categoryId, $contentType) {
                    return $er->createQueryBuilder('o')
                        ->where('o.type = :type')
                        ->setParameter(':type', $contentType)
                        ->andWhere('o.enabled = 1')
                        ->andWhere('o.id <> :id')
                        ->setParameter(':id', $categoryId)
                        ->orderBy('o.root, o.lft, o.ordering', 'ASC');
                },
                'attr' => array ( 
                    'class' => 'form-control', 
                    'placeholder' => 'Category' 
                ) 
            ));
        }
        
        $languages = $this->_container->get('backend')->getLanguageManager()->getLanguages();
        $langCodes = array();
        foreach ($languages as $lang) {
            $langCodes[$lang['code']] = $lang['code'];
        }
        
        $contentLangs = $category->getContentLangs();
        if (!is_null($contentLangs) && $category->getContentLangs()->count() > 0) {

        } else {
            foreach ($langCodes as $code) {
                $lang = new ContentLanguage();
                $lang->setLang($code);
                $category->addContentLang($lang);
            }   
        }


    }

    /**
     * @param FormEvent $event
     */
    public function postBind(FormEvent $event) {
        $category = $event->getData();
        foreach ($category->getContentLangs() as $contentLang) {
            $contentLang->setCategory($category);
            $slug = $contentLang->getSlug();
            $slug =  empty($slug) ? Html::slugify($contentLang->getTitle()) : Html::slugify($contentLang->getSlug());
            $contentLang->setSlug($slug);
            $this->_container->get('doctrine')->getManager()->persist($contentLang);
        }
        $category->setType($this->_contentType);
    }

}
