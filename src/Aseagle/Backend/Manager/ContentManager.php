<?php
/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Backend\Manager;

use Aseagle\Backend\Manager\Base\ObjectManagerInterface;

/**
 * ContentManager
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class ContentManager implements ObjectManagerInterface {
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    protected $container;

    /**
     * @param \Doctrine\ORM\EntityManager $manager
     */
    public function __construct(\Doctrine\ORM\EntityManager $manager, $container) {
        $this->entityManager = $manager;
        $this->_container = $container;
    }

    /* (non-PHPdoc)
     * @see \Aseagle\Backend\Manager\Base\ObjectManagerInterface::getRepository()
     */
    public function getRepository() {
        return $this->entityManager->getRepository('AseagleBackend:Content');
    }

    /* (non-PHPdoc)
     * @see \Aseagle\Backend\Manager\Base\ObjectManagerInterface::createObject()
     */
    public function createObject() {
        return new \Aseagle\Backend\Entity\Content();
    }

    /* (non-PHPdoc)
     * @see \Aseagle\Backend\Manager\Base\ObjectManagerInterface::getObject()
     */
    public function getObject($gid) {
        return $this->getRepository()->find($gid);
    }

    /**
     * @param object $object
     */
    public function save($object) {
        $this->_container->get('doctrine')->getManager()->persist($object);
        $this->_container->get('doctrine')->getManager()->flush();
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

