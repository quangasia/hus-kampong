<?php
namespace Aseagle\Bundle\UserBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Voter\FieldVote;

/**
 * Acl service
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class Acl
{
    protected $_container;

    /**
     * Init acl
     *
     * @param Container $container Container
     */
    public function __construct(Container $container)
    {
        $this->_container = $container;
    }

    /**
     * Check allow permission
     *
     * @param string $action The action for permission
     * @param object $object The object for premission
     * @param string $option The class subfix
     *
     * @return Boolean
     */
    public function isAllow($action, $object, $option = '')
    {
        $securityContext = $this->_container->get('security.context');
        $subfix = ($option != '') ? "_$option" : "";
        $class = is_string($object) ? $object : get_class($object);     
        
        $objectIdentity = new ObjectIdentity('class', $class . $subfix);
        if (false == $securityContext->isGranted($action, $objectIdentity)
                && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            return false;
        }

        return true;
    }

    /**
     * Check allow permission
     *
     * @param string $action The action for permission
     * @param object $object The object for premission
     *
     * @return Exception
     */
    public function allowAccess($action, $object, $option = '')
    {
        if (!$this->isAllow($action, $object, $option)) {
            throw new AccessDeniedException('Access Denied', 403);
        }
    }

    /**
     * Check allow permission on each class field
     *
     * @param string $action The action for permission
     * @param object $class  The class path for premission
     * @param object $field  The field for premission
     *
     * @return Boolean
     */
    public function isAllowField($action, $class, $field)
    {
        $securityContext = $this->_container->get('security.context');
        $objectIdentity = new ObjectIdentity('class', $class);

        $object = new FieldVote($objectIdentity, $field);

        if (false === $securityContext->isGranted($action, $object)
                && !$securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            return false;
        }

        return true;
    }
}

