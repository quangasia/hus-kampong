<?php

namespace Kampong\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Aseagle\Backend\Entity\Content;

class NewsController extends Controller
{

    public function indexAction()
    {
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        
        $page = $this->getRequest()->get('page', 1);
        $limit = $this->container->getParameter('front_item_per_page', 6);
        $offset = ($page - 1) * $limit;
        
        $posts = $contentManager->getRepository()->getList(
                array('enabled' => true, 'type' => Content::TYPE_POST),
                array('created' => 'DESC'), $limit, $offset);
        $total = $contentManager->getRepository()->getTotal(array('enabled' => true, 'type' => Content::TYPE_POST));

        return $this->render('KampongSiteBundle:News:index.html.twig', array(
            'posts' => $posts,
            'paging' => $this->paging($total, $page)
        ));
    }

    public function detailAction()
    {
        $id = $this->getRequest()->get('id', 0);
        if ($id == 0) {
            $this->createNotFoundException();
        }
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $detail = $contentManager->getRepository()->find($id);

        $otherPosts = $contentManager->getRepository()->findBy(
                array(
            'enabled' => true,
            'type' => Content::TYPE_POST,
                ), array(
            'created' => 'DESC'
                ), 7, 0
        );

        if (is_null($detail)) {
            $this->createNotFoundException();
        }

        return $this->render('KampongSiteBundle:News:detail.html.twig', array(
                    'detail' => $detail,
                    'otherPosts' => $otherPosts,
                    'postId' => $id
        ));
    }

    /**
     * Paging product
     *
     * @param type $total
     * @param type $current
     * @param type $template
     */
    protected function paging($total, $current=1, $template='KampongSiteBundle:Block:pagination.html.twig')
    {
        $perPage = $this->container->getParameter('front_item_per_page');
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
