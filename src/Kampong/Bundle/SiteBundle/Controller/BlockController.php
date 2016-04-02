<?php
namespace Kampong\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Aseagle\Backend\Entity\Category;
use Aseagle\Backend\Entity\Banner;

/**
 * BlockController
 *
 * @author  Quang Tran <quang.tran@aseagle.com>
 * @access  public
 * @package Kampong
 */
class BlockController extends Controller
{

    public function topMenuAction($route)
    {
        return $this->render('KampongSiteBundle:Block:top-menu.html.twig', array('route' => $route));
    }

    public function homepageSlideAction()
    {
        $banners = $this->get('backend')->getBannerManager()->getRepository()->findBy(array(
            'enabled' => true,
            'position' => Banner::TYPE_HOMEPAGE_SLIDE
        ));

        return $this->render('KampongSiteBundle:Block:homepage-slide.html.twig', array(
                    'banners' => $banners
        ));
    }

    public function mapAction()
    {

        return $this->render('KampongSiteBundle:Block:map.html.twig');
    }

    public function leftBannerAction()
    {
        $banners = $this->get('backend')->getBannerManager()->getRepository()->findBy(array(
            'enabled' => true,
            'position' => Banner::TYPE_BANNER_LEFT
        ));
        return $this->render('KampongSiteBundle:Block:left-banner.html.twig', array(
                    'banners' => $banners
        ));
    }

    public function leftMenuAction($id = null)
    {
        $entities = $this->get('backend')
            ->getCategoryManager()
            ->getRepository()->getMenuByLocale($this->getRequest()->getLocale());

        $categories = $brandCats = array();
        foreach ($entities as $entity) {
            if ($entity[0]->getParent() == null) {
                $categories[0][] = $entity;
            } else {
                $categories[$entity[0]->getParent()->getId()][] = $entity;
            }
        }

        return $this->render('KampongSiteBundle:Block:left-menu.html.twig', array(
            'categories' => $categories,
            'id' => $id != null ? $id : 0
        ));
    }
    
    public function commentAction($postId)
    {
        return $this->render('KampongSiteBundle:Block:comment.html.twig');
    }

    public function headBannerAction($position)
    {           
        $banner = $this->get('backend')->getBannerManager()->getRepository()->findOneBy(array(
                        'enabled' => true,
                        'position' => $position
                   )); 
        return $this->render('KampongSiteBundle:Block:head-banner.html.twig', array('banner' => $banner));     
    }
}
