<?php

namespace Aseagle\Bundle\AdminBundle\DataFixtures\ORM\LoadAcl;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load acl data
 */
class LoadAclData implements FixtureInterface, ContainerAwareInterface
{

    private $container;

    /**
     * Set container
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Function to load data
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $aclDatas = array (
            array ('name' => 'User Manager', 'class' => 'Aseagle\Bundle\AdminBundle\Controller\UserController', 'type' => 1),
            array ('name' => 'Group Manager', 'class' => 'Aseagle\Bundle\AdminBundle\Controller\GroupController', 'type' => 1),
            array ('name' => 'Post Manager', 'class' => 'Aseagle\Bundle\AdminBundle\Controller\ArticleController', 'type' => 1),
            array ('name' => 'Category Manager', 'class' => 'Aseagle\Bundle\AdminBundle\Controller\CategoryController', 'type' => 1),
            array ('name' => 'Page Manager', 'class' => 'Aseagle\Bundle\AdminBundle\Controller\PageController', 'type' => 1),
            array ('name' => 'Setting Manager', 'class' => 'Aseagle\Bundle\AdminBundle\Controller\SetttingController', 'type' => 1),
            array ('name' => 'Banner Manager', 'class' => 'Aseagle\Bundle\AdminBundle\Controller\BannerController', 'type' => 1),
        );

        $manager = $this->container->get('user_acl_manager');
        foreach ($aclDatas as $item) {
            if (!$this->isExist($manager, $item['class'])) {
                $entity = $manager->createObject();
                $entity->setName($item['name']);
                $entity->setClass($item['class']);
                $entity->setType($item['type']);
                $manager->save($entity);
            }
        }
    }

    /**
     * Check class exist
     *
     * @param type $class
     */
    public function isExist($manager, $class)
    {

        $object = $manager->getRepository()->findByClass($class);
        if ($object) {
            return true;
        }

        return false;
    }

}