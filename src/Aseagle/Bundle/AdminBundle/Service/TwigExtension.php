<?php
namespace Aseagle\Bundle\AdminBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * TwigExtension
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class TwigExtension extends \Twig_Extension
{
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var unknown
     */
    protected $mapping = array (
        'admin_group' => 'Aseagle\Bundle\AdminBundle\Controller\GroupController',
        'admin_user' => 'Aseagle\Bundle\AdminBundle\Controller\UserController',
        'admin_acl' => 'Aseagle\Bundle\AdminBundle\Controller\AclController',
        'admin_brand' => 'Aseagle\Bundle\AdminBundle\Controller\BrandController',
        'admin_category' => 'Aseagle\Bundle\AdminBundle\Controller\CategoryController',
        'admin_productcategory' => 'Aseagle\Bundle\AdminBundle\Controller\ProductCategoryController',
        'admin_product' => 'Aseagle\Bundle\AdminBundle\Controller\ProductController',
        'admin_article' => 'Aseagle\Bundle\AdminBundle\Controller\ArticleController',
        'admin_page' => 'Aseagle\Bundle\AdminBundle\Controller\PageController',
        'elfinder' => 'Aseagle\Bundle\AdminBundle\Controller\ArticleController',
        'admin_order' => 'Aseagle\Bundle\AdminBundle\Controller\OrderController',
        'admin_setting' => 'Aseagle\Bundle\AdminBundle\Controller\SettingController',
        'admin_banner' => 'Aseagle\Bundle\AdminBundle\Controller\BannerController',
    );
    
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
            new \Twig_SimpleFunction('menuItem', array($this, 'menuItem'))
        );
    }
    
    /**
     * @param unknown $route
     * @param unknown $label
     * @param string $icon
     */
    public function menuItem($route, $inRoutes, $label, $icon='') {
        $active = $disabled = $adisabled = '';
        $currentRoute = $this->container->get('request')->get('_route');
        if (in_array($currentRoute, $inRoutes)) {
            $active = 'active';
        }
        
        $url = $this->container->get('router')->generate($route);
        if (($route == 'admin_acl' && !$this->container->get('security.context')->isGranted('ROLE_ADMIN'))
                || ($route != 'admin_acl' && !$this->container->get('user_acl')->isAllow('VIEW', $this->mapping[$route]))) {
            $disabled = 'disabled-link';
            $adisabled = 'disable-target';
            $url = 'javascript:void(0);';
        } 
        $html = "<li class=\"{$active} {$disabled}\"><a href=\"{$url}\" class=\"{$adisabled}\"> <i class=\"{$icon}\"></i> {$label}</a></li>";
        
        return $html;
    }
 
    
    /* (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'twig_extension';
    }
}