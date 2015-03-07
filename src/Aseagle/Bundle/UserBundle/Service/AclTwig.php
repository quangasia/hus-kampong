<?php
namespace Aseagle\Bundle\UserBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AclTwig
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class AclTwig extends \Twig_Extension
{
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
       
    /* (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('isAllow', array($this, 'isAllow'))
        );
    }
    
    /**
     * Check permission on each class fields
     *
     * @param string $action
     * @param string $class
     * @param string $field
     */
    public function isAllow($action, $route, $mapping = array()) {
        if (isset($mapping[$route])) {
            return $this->container->get('user_acl')->isAllow($action, $mapping[$route]);
        } else {
            return false;
        }
    }
    
    /* (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'acl_extension';
    }
}