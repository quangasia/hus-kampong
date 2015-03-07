<?php

namespace Aseagle\Bundle\UserBundle\DataFixtures\ORM\LoadUser;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Aseagle\Bundle\UserBundle\Entity\User;

/**
 * Load user, group, role data
 */
class LoadUserData implements FixtureInterface, ContainerAwareInterface
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
        // Setup roles and group
        $groups = array(
            array('Administrator','ROLE_ADMIN',1,true),
            array('Manager','ROLE_MANAGER',1,true),
            array('User','ROLE_USER',0, true),          
        );
        
        $users = array(
            array('sadmin', 'sadmin', 'Quang Tran', 'sadmin@lifecare.vn', 'Administrator',true),
            array('admin', 'admin', 'Administrator', 'admin@lifecare.vn', 'Administrator',true),
            array('manager', 'manager', 'Manager', 'manager@lifecare.vn', 'Manager',false),
            array('user', 'user', 'User', 'user@lifecare.vn', 'User',false),
        );
        
        
        
        /* @var $groupManager \Aseagle\Bundle\UserBundle\Manager\GroupManager */
        $groupManager = $this->container->get('user_group_manager');  
        foreach ($groups as $item) {
            /* @var $group \Aseagle\Bundle\UserBundle\Entity\UserGroup */
            $group = $groupManager->createObject();
            $group->setName($item[0])
                  ->setRole($item[1])
                  ->setType($item[2])  
                  ->setSystem($item[3])
                  ->setEnabled(true)
            ;
            $groupManager->save($group);
        }
        
        /* @var $userManager \Aseagle\Bundle\UserBundle\Manager\UserManager */
        $userManager = $this->container->get('user_manager');
        foreach ($users as $item) {
            /* @var $user \Aseagle\Bundle\UserBundle\Entity\User */
            $user = $userManager->createObject();
            $user->setUsername($item[0])
                    ->setEmail($item[3])
                    ->setPassword($item[1])
                    ->setFullname($item[2])
                    ->setLocked(false)
                    ->setExpired(false)
                    ->setEnabled(true)
                    ->setSystem($item[5])
            ;
            
            $group = $groupManager->getRepository()->findOneBy(array('name'=>$item[4]));
            if ($group instanceof \Aseagle\Bundle\UserBundle\Entity\UserGroup) {
                $user->setGroup($group);            
            }
            
            //Encrypt password
            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);        
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());  
            
            $user->setPassword($password);
            $userManager->save($user);
        }
    }

}