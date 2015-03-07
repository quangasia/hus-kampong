<?php
/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\UserBundle\Manager;

use Aseagle\Bundle\CoreBundle\Manager\ObjectManagerInterface;
use Doctrine\ORM\EntityManager;

/**
 * GroupManager
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class GroupManager implements ObjectManagerInterface {
    
    /**
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * @param ntityManager $manager
     */
    public function __construct(EntityManager $manager) {
        $this->entityManager = $manager;
    }

    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\CoreBundle\Manager\ObjectManagerInterface::getRepository()
     */
    public function getRepository() {
        return $this->entityManager->getRepository('AseagleUserBundle:UserGroup');
    }
    
    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\CoreBundle\Manager\ObjectManagerInterface::createObject()
     */
    public function createObject() {
        return new \Aseagle\Bundle\UserBundle\Entity\UserGroup();
    }
    
    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\CoreBundle\Manager\ObjectManagerInterface::getObject()
     */
    public function getObject($gid) {
        return $this->getRepository()->find($gid);
    }
    
    /**
     * @param object $object
     */
    public function save($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
    

    /**
     * @param object $object
     * @param string $flush
     */
    public function delete($object, $flush = true) {
        $this->entityManager->remove($object);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}

