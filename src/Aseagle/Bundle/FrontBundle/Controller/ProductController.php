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
use Aseagle\Bundle\FrontBundle\Form\Type\ReviewType;
use Aseagle\Bundle\EcommerceBundle\Entity\ProductReview;
use Aseagle\Bundle\UserBundle\Entity\User;

class ProductController extends Controller
{
    public function productByBrandAction()
    {
        /* @var $contentManager \Aseagle\Bundle\EcommerceBundle\Manager\ProductManager */
        $productManager = $this->get('product_manager');
        
        /* @var $contentManager \Aseagle\Bundle\EcommerceBundle\Manager\BrandManager */
        $brandManager = $this->get('brand_manager');
        
        $id = $this->getRequest()->get('id', NULL);
        if (NULL == $id) {
            $this->createNotFoundException('Page not found');
        }        
        
        $page = $this->getRequest()->get('page', 1);
        $limit = $this->container->getParameter('front_item_per_page', 12);
        $offset = ($page - 1) * $limit;
        
        $brand = $brandManager->getObject($id);
        $products = $productManager->getRepository()->getProductByBrand($id, array('created' => 'desc'), $limit, $offset);
        $total = $productManager->getRepository()->getProductByBrand($id, array(), 0, 0, true);
        
        return $this->render('AseagleFrontBundle:Product:product-brand.html.twig', array(
            'products' => $products,
            'brand' => $brand,
            'paging' => $this->paging($total)
        ));
        
    }
    
    public function listAction()
    {
        /* @var $contentManager \Aseagle\Bundle\EcommerceBundle\Manager\ProductManager */
        $productManager = $this->get('product_manager');
        
        /* @var $contentManager \Aseagle\Bundle\EcommerceBundle\Manager\CategoryManager */
        $categoryManager = $this->get('category_manager');
        
        $id = $this->getRequest()->get('id', NULL);
        if (NULL == $id) {
            $this->createNotFoundException('Page not found');
        }        
        
        $page = $this->getRequest()->get('page', 1);
        $limit = $this->container->getParameter('front_item_per_page', 12);
        $offset = ($page - 1) * $limit;
        
        $category = $categoryManager->getObject($id);
        $products = $productManager->getRepository()->getProductByCategory($id, array('created' => 'desc'), $limit, $offset);
        $total = $productManager->getRepository()->getProductByCategory($id, array(), 0, 0, true);
        
        return $this->render('AseagleFrontBundle:Product:list.html.twig', array(
            'products' => $products,
            'category' => $category,
            'paging' => $this->paging($total)
        ));
    
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction()
    {
        /* @var $productManager \Aseagle\Bundle\EcommerceBundle\Manager\ProductManager */
        $productManager = $this->get('product_manager'); 
        
        $id = $this->getRequest()->get('id', NULL);
        if (NULL == $id) {
            $this->createNotFoundException('Page not found');
        }
        $product = $productManager->getObject($id);
        
        $review = new ProductReview();
        $reviewForm = $this->createForm(new ReviewType($this->container), $review);
        if ($this->getRequest()->getMethod() == 'POST') {
            $reviewForm->bind($this->getRequest());
            if ($reviewForm->isValid()) {
                $user = $this->get('security.context')->getToken()->getUser();
                if ($user instanceof User) {
                    $review->setUser($user);
                }
                $review->setEnabled(true);
                $review->setProduct($product);
                $productManager->save($review);
            }
        }
        
        
        
        if (!$product) {
            $this->createNotFoundException('Page not found');
        }
        
        return $this->render('AseagleFrontBundle:Product:detail.html.twig', array(
            'product' => $product,
            'form' => $reviewForm->createView()
        ));
    }

    
    public function searchAction() {
        /* @var $contentManager \Aseagle\Bundle\EcommerceBundle\Manager\ProductManager */
        $productManager = $this->get('product_manager');
        
        $keyword = $this->getRequest()->get('keyword');
        
        $page = $this->getRequest()->get('page', 1);
        $limit = $this->container->getParameter('front_item_per_page', 12);
        $offset = ($page - 1) * $limit;
        
        $products = $productManager->getRepository()->searchByKeyword($keyword, $limit, $offset);
        $total = $productManager->getRepository()->searchByKeyword($keyword, 0, 0, true);
        
        return $this->render('AseagleFrontBundle:Product:search.html.twig', array(
            'products' => $products,
            'paging' => $this->paging($total),
            'keyword' => $keyword
        ));
        
    }
    
    /**
     * Paging product
     *
     * @param type $total
     * @param type $current
     * @param type $template
     */
    protected function paging($total, $current=1, $template='AseagleFrontBundle:Blocks:pagination.html.twig')
    {
        $perPage = $this->container->getParameter('front_item_per_page');;
        $lastPage = ceil($total / $perPage);
        $previousPage = $current > 1 ? $current - 1 : 1;
        $nextPage = $current < $lastPage ? $current + 1 : $lastPage;
    
        return $this->renderView($template, array(
            'lastPage' => $lastPage,
            'previousPage' => $previousPage,
            'currentPage' => $current,
            'nextPage' => $nextPage,
            'total' => $total
        ));
    }
}
