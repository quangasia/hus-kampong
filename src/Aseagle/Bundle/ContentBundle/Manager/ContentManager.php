<?php
/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\ContentBundle\Manager;

use Aseagle\Bundle\CoreBundle\Manager\ObjectManagerInterface;

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

    /**
     * @param \Doctrine\ORM\EntityManager $manager
     */
    public function __construct(\Doctrine\ORM\EntityManager $manager) {
        $this->entityManager = $manager;
    }

    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\CoreBundle\Manager\ObjectManagerInterface::getRepository()
     */
    public function getRepository() {
        return $this->entityManager->getRepository('AseagleContentBundle:Content');
    }

    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\CoreBundle\Manager\ObjectManagerInterface::createObject()
     */
    public function createObject() {
        return new \Aseagle\Bundle\ContentBundle\Entity\Content();
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
    public function save($object) {
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

