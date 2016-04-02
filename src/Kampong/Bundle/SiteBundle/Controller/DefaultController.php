<?php

namespace Kampong\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Aseagle\Backend\Entity\Content;

class DefaultController extends Controller
{

    const CONTACT_PAGE_ID = 2;
    const ABOUTUS_PAGE_ID = 5;
    const RECRUITMENT_PAGE_ID = 63;

    public function indexAction()
    {
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $foods = $contentManager->getRepository()->getFoodOnHomepageByLocale($this->getRequest()->getLocale());

        $content = $this->renderView('KampongSiteBundle:Default:index.html.twig', array(
                    'foods' => $foods
        ));
        $response = new Response($content, Response::HTTP_OK);
        return $response;

        /*return $this->render('KampongSiteBundle:Default:index.html.twig', array(
                    'foods' => $foods
                ));*/
    }

    public function contactAction()
    {
        $locale = $this->getRequest()->getLocale();
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $settingManager = $this->get('backend')->getSettingManager();
        $contactDetail = $contentManager->getRepository()->getPost(self::CONTACT_PAGE_ID, $locale);
        $setting = $settingManager->getRepository()->findOneByKey('contact_email');

        $message = '';
        $form = $this->createForm(new \Kampong\Bundle\SiteBundle\Form\Type\ContactType());
        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $data = $form->getData();
                if (!is_null($setting)) {
                    $mailer = $this->get('mailer');
                    $message = $mailer->createMessage()
                            ->setSubject('[Liên hệ][' . $data['fullname'] . '] ' . $data['title'])
                            ->setFrom($data['email'])
                            ->setTo($setting->getValue())
                            ->setBody(
                            $this->renderView('KampongSiteBundle:Email:contact.html.twig', array(
                                'data' => $data
                            )), 'text/html');
                    $mailer->send($message);

                    $notice = 'Cám ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ liên hệ với bạn sớm.';
                } 
            }
        }

        return $this->render('KampongSiteBundle:Default:contact.html.twig', array(
                    'detail' => $contactDetail,
                    'form' => $form->createView(),
                    'notice' => isset($notice) ? $notice : null
        ));
    }

    public function aboutUsAction()
    {
        $locale = $this->getRequest()->getLocale();
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $aboutusContent = $contentManager->getRepository()->getPost(self::ABOUTUS_PAGE_ID, $locale);

        return $this->render('KampongSiteBundle:Default:aboutus.html.twig', array(
                    'detail' => $aboutusContent
        ));
     } 

    public function galleryAction()
    {
        $images = array();
        $webDir = $this->get('kernel')->getRootDir() . "/../web";
        foreach (glob($webDir . '/uploads/files/gallery/*') as $filename) {
            $images[] = '/uploads/files/gallery/' . basename($filename);
        }

        return $this->render('KampongSiteBundle:Default:gallery.html.twig', array(
                    'images' => $images
        ));
    }

    public function recruitmentDetailAction()
    {
        $locale = $this->getRequest()->getLocale();
        $id = $this->getRequest()->get('id');
        /* @var $contentManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $recruitmentContent = $contentManager->getRepository()->getPost($id, $locale);

        return $this->render('KampongSiteBundle:Default:recruitment.html.twig', array(
                    'detail' => $recruitmentContent
        ));

    }

    public function recruitmentAction()
    {
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        
        $page = $this->getRequest()->get('page', 1);
        $limit = $this->container->getParameter('front_item_per_page', 6);
        $offset = ($page - 1) * $limit;

        $locale = $this->getRequest()->getLocale(); 
        $posts = $contentManager->getRepository()->getList(
                array('enabled' => true, 'type' => Content::TYPE_PAGE, 'locale'=>$locale), 
                array('created' => 'DESC'), null, null);
        foreach ($posts as $key => $post) {
            if (in_array($post['id'], array(2, 5))) {
                unset($posts[$key]);
            }
        }

        return $this->render('KampongSiteBundle:Default:recruitment-list.html.twig', array(
            'posts' => $posts,
        ));
    }
    
    public function switchLanguageAction($_locale)
    {
        $request = $this->getRequest();
        $request->getSession()->set('_locale', $_locale); 
        $request->setLocale($_locale);
        
        $referer = $request->headers->get('referer');
        
        
        if ($referer == null) {
            $referer = $this->generateUrl('kampong_site_homepage');
        } 
        
        return $this->redirect($referer);
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
