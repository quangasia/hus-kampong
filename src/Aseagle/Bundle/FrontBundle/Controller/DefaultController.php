<?php
/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Aseagle\Bundle\ContentBundle\Entity\Category;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /* @var $categoryManager \Aseagle\Bundle\ContentBundle\Manager\CategoryManager */
        $categoryManager = $this->get('category_manager');
        
        /* @var $brandManager \Aseagle\Bundle\ContentBundle\Manager\BrandManager */
        $brandManager = $this->get('brand_manager');
        
        /* @var $productManager \Aseagle\Bundle\ContentBundle\Manager\ProductManager */
        $productManager = $this->get('product_manager');
        
        /* @var $bannerManager \Aseagle\Bundle\ContentBundle\Manager\BannerManager */
        $bannerManager = $this->get('banner_manager');
        
        $entities = $categoryManager->getRepository()->findBy(array(
            'enabled' => true, 
            'type' => Category::TYPE_PRODUCT,
        ), array('ordering' => 'ASC'));
        
        $products = $productManager->getRepository()->getProductOnHomepage();
        $productHomepage = $productRand = array();
        foreach ($products as $product) {
            $productHomepage[$product['cid']][] = $product;
            $productRand[$product['id']] = $product;
        }
        
        $categories = $brandCats = array();
        foreach ($entities as $entity) {
            if ($entity->getParent() == null) {
                $categories[0][] = $entity;
                $brandCats[] = $entity->getId();
            } else {
                $categories[$entity->getParent()->getId()][] = $entity;
            } 
        }
        
        /* Get brands of each category*/
        $brandByCat = array();
        if (count($brandCats)) {
            $brands = $brandManager->getRepository()->getBrandByCategories($brandCats);
            foreach ($brands as $item) {
                $brandByCat[$item['cid']][] = $item;               
            }          
        }
        
        /* Get banner */
        $banners = $bannerManager->getRepository()->findBy(array('enabled'=>true));
        $bannerById = array();
        foreach ($banners as $banner) {
            $bannerById[$banner->getPosition()] = $banner;
        }
        
        
        return $this->render('AseagleFrontBundle:Default:index.html.twig', array(
            'categories' => $categories,
            'brandByCat' => $brandByCat,
            'productHomepage' => $productHomepage,
            'productRand' => $productRand,
            'banners' => $bannerById
        ));
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leftSidebarAction() {
        /* @var $categoryManager \Aseagle\Bundle\ContentBundle\Manager\CategoryManager */
        $categoryManager = $this->get('category_manager');
        
        /* @var $brandManager \Aseagle\Bundle\ContentBundle\Manager\BrandManager */
        $brandManager = $this->get('brand_manager');
        
        $entities = $categoryManager->getRepository()->findBy(array(
            'enabled' => true,
            'type' => Category::TYPE_PRODUCT,
            'parent' => null
        ), array('ordering' => 'ASC'));
        
        /*Get brands*/
        $brands = $brandManager->getRepository()->findBy(array(
            'enabled' => true,
        ), array(), 4, 0);
        
        $consultants = $categoryManager->getRepository()->findBy(array(
            'enabled' => true,
            'type' => Category::TYPE_POST,
            'parent' => 1
        ), array('ordering' => 'ASC'));
        
        
        return $this->render('AseagleFrontBundle:Default:left-sidebar.html.twig', array(
            'categories' => $entities,
            'brands' => $brands,
            'consultants' => $consultants
        ));
    }
    
    
    public function productInCartAction() {
        $productManager = $this->get('product_manager');
        $session = $this->getRequest()->getSession();
        
        $productsInCart = $session->get('productOrder', array());
        $products = array();
        if (count($productsInCart)) {
            $productIds = array_keys($productsInCart);
            $products = $productManager->getRepository()->findBy(array('id' => $productIds));
        }
        
        return $this->render('AseagleFrontBundle:Blocks:product-in-cart.html.twig', array(
            'products' => $products,
        ));
    }
    
}
