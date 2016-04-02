<?php

namespace Kampong\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Aseagle\Backend\Entity\Content;

class FoodController extends Controller
{

    public function indexAction()
    {
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $categoryManager = $this->get('backend')->getCategoryManager();
        
        $locale = $this->getRequest()->getLocale();
        $catId = $this->getRequest()->get('catid', 0);
        if ($catId == 0) {
            $this->createNotFoundException();
        }
        $category = $categoryManager->getRepository()->getCategory($catId, $locale);
        if (is_null($category)) {
            $this->createNotFoundException();
        }

        $page = $this->getRequest()->get('page', 1);
        $limit = $this->container->getParameter('front_item_per_page', 6);
        $offset = ($page - 1) * $limit;

        $foods = $contentManager->getRepository()->getPosts(array('catId'=>$catId,'locale'=>$locale), array(), $limit, $offset);
        $total = $contentManager->getRepository()->getPosts(array('catId'=>$catId,'locale'=>$locale), array(), null, null, true);

        return $this->render('KampongSiteBundle:Food:index.html.twig', array(
                    'foods' => $foods,
                    'paging' => $this->paging($total, $page),
                    'category' => $category
        ));
    }

    public function detailAction()
    {
        $id = $this->getRequest()->get('id', 0);
        if ($id == 0) {
            $this->createNotFoundException();
        }
        
        $locale = $this->getRequest()->getLocale();
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $detail = $contentManager->getRepository()->getPost($id, $locale);

        
        $otherFoods = $contentManager->getRepository()->getList(
                array(
            'enabled' => true,
            'type' => Content::TYPE_FOOD,
            'locale' => $locale
                ), array(
            'created' => 'DESC'
                ), 6, 0
            );

        if (is_null($detail)) {
            $this->createNotFoundException();
        }

        return $this->render('KampongSiteBundle:Food:detail.html.twig', array(
                    'detail' => $detail,
                    'otherFoods' => $otherFoods,
                    'postId' => $id
        ));
    }

    public function allAction()
    {
        $locale = $this->getRequest()->getLocale();
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();

        $page = $this->getRequest()->get('page', 1);
        $limit = $this->container->getParameter('front_item_per_page', 6);
        $offset = ($page - 1) * $limit;

        $foods = $contentManager->getRepository()->getList(
                array('enabled' => true, 'type' => Content::TYPE_FOOD, 'locale'=>$locale), array('created' => 'desc'), $limit, $offset
        );
        $total = $contentManager->getRepository()->getTotal(array('enabled' => true, 'type' => Content::TYPE_FOOD, 'locale'=>$locale));

        return $this->render('KampongSiteBundle:Food:all.html.twig', array(
                    'foods' => $foods,
                    'paging' => $this->paging($total, $page)
        ));
    }

    /**
     * Paging product
     *
     * @param type $total
     * @param type $current
     * @param type $template
     */
    protected function paging($total, $current = 1, $template = 'KampongSiteBundle:Block:pagination.html.twig')
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
