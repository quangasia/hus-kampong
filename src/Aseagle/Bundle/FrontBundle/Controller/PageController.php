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

class PageController extends Controller
{
    public function consultantAction()
    {
        /* @var $contentManager \Aseagle\Bundle\ContentBundle\Manager\ContentManager */
        $contentManager = $this->get('content_manager');
                
        $id = $this->getRequest()->get('id', NULL);
        if (NULL == $id) {
            $this->createNotFoundException('Page not found');
        }      
          
        $page = $this->getRequest()->get('page', 1);
        $limit = $this->container->getParameter('front_item_per_page', 12);
        $offset = ($page - 1) * $limit;
        
        $posts = $contentManager->getRepository()->getPostByCategory($id, array(), $limit, $offset);
        $total = $contentManager->getRepository()->getPostByCategory($id, array(), 0, 0, true);
        
        return $this->render('AseagleFrontBundle:Page:list-post.html.twig', array(
            'posts' => $posts,
            'paging' => $this->paging($total)
        ));
        
    }
    
    public function listAction()
    {
        /* @var $contentManager \Aseagle\Bundle\ContentBundle\Manager\ContentManager */
        $contentManager = $this->get('content_manager');
        $id = $this->getRequest()->get('id', NULL);
        if (NULL == $id) {
            $this->createNotFoundException('Page not found');
        }
    
        $posts = $contentManager->getRepository()->getPostByCategory($id, array(), 10, 0);
    
        return $this->render('AseagleFrontBundle:Page:list-post.html.twig', array(
            'posts' => $posts
        ));
    
    }
    
    public function detailAction()
    {
        /* @var $contentManager \Aseagle\Bundle\ContentBundle\Manager\ContentManager */
        $contentManager = $this->get('content_manager'); 
        
        $id = $this->getRequest()->get('id', NULL);
        if (NULL == $id) {
            $this->createNotFoundException('Page not found');
        }
        
        $page = $contentManager->getObject($id);
        
        return $this->render('AseagleFrontBundle:Page:detail.html.twig', array(
            'page' => $page
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
