<?php

namespace Kampong\Bundle\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    const CONTACT_PAGE_ID = 2;
    const ABOUTUS_PAGE_ID = 5;
    const RECRUITMENT_PAGE_ID = 63;

    public function indexAction()
    {
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $foods = $contentManager->getRepository()->getList(array('enabled' => true, 'feature' => true), array('created' => 'DESC'));

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
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $settingManager = $this->get('backend')->getSettingManager();
        $contactDetail = $contentManager->getRepository()->find(self::CONTACT_PAGE_ID);
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
        /* @var $productManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $aboutusContent = $contentManager->getRepository()->find(self::ABOUTUS_PAGE_ID);

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

    public function recruitmentAction()
    {
        /* @var $contentManager \Aseagle\Backend\Manager\ContentManager */
        $contentManager = $this->get('backend')->getContentManager();
        $recruitmentContent = $contentManager->getRepository()->find(self::RECRUITMENT_PAGE_ID);

        return $this->render('KampongSiteBundle:Default:recruitment.html.twig', array(
                    'detail' => $recruitmentContent
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

}
