<?php

namespace Aseagle\Bundle\CoreBundle\Service;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Acl {
    protected $container;

    /**
     * Construct function
     * 
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;    
    }
    
    /**
     * Save acl for new object
     * 
     * @param Object $object
     * @param Integer $mask
     */
    public function saveObjectAcl($object, $mask = MaskBuilder::MASK_OWNER) {
         // creating the ACL
        $objectIdentity = ObjectIdentity::fromDomainObject($object);
        $acl = $this->container->get('security.acl.provider')->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $user = $this->container->get('security.context')->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant owner access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $this->container->get('security.acl.provider')->updateAcl($acl);
    }
    
    /**
     * Checking permission of user
     * 
     * @param Object $object
     * @return boolean
     */
    public function isOwner($object) {
        if ($this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN')
            || $this->container->get('security.context')->isGranted('OWNER', $object)) {
            return true;
        }
        
        return false;
    }
}

