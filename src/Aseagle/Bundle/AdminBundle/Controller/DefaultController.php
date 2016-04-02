<?php

/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * DefaultController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class DefaultController extends Controller {

    /**
     * Admin Dashboard
     */
    public function indexAction() {
        return $this->render('AseagleAdminBundle:Default:index.html.twig');
    }

    /**
     * Render a javascript file
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jsTranslateAction() {
        $response = new Response(
                $this->renderView('AseagleAdminBundle:Default:js-translate.html.twig'));
        $response->headers->set('Content-Type', 'application/javascript');
        $response->headers->set('Cache-Control', 'no-cache');
        
        return $response;
    }
    
    /**
     * @param Request $request
     * @param string $lang
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function switchLanguageAction(Request $request, $lang)
    {
        $request->getSession()->set('_locale', $lang);
        $request->setLocale($lang);
    
        $referer = $request->headers->get('referer');
        if ($referer == null) {
            $referer = $this->generateUrl('admin_homepage');
        }
    
        return $this->redirect($referer);
    }

    public function statsBlockAction() {
        $customers = $this->get('user_manager')->getRepository()->getTotal(array('group' => 3));
        
        return $this->render('AseagleAdminBundle:Default:stats.html.twig', array(
            'customerTotal' => $customers,
        ));
    }

    public function migrateAction() {
/*        $lang = new \Aseagle\Backend\Entity\Language();
        $lang->setCode('vi')
            ->setIsDefault(true)
            ->setName('Tiếng Việt')
            ->setActive(true);
        $this->get('backend')->getLanguageManager()->save($lang);

        $lang = new \Aseagle\Backend\Entity\Language();
        $lang->setCode('en')
            ->setIsDefault(false)
            ->setName('English')
            ->setActive(true);
        $this->get('backend')->getLanguageManager()->save($lang);
 

        $langs = $this->get('backend')->getLanguageManager()->getLanguages();
        
        $contentManager = $this->get('backend')->getContentManager();
        $contents = $contentManager->getRepository()->findAll();
        foreach ($contents as $content) {
            foreach ($langs as $key=>$lang) {
                $contentLang = new \Aseagle\Backend\Entity\ContentLanguage();
                $contentLang->setTitle($content->getTitle())
                    ->setSlug($content->getSlug())
                    ->setLang($key)
                    ->setShortText($content->getShortDescription())
                    ->setLongText($content->getContent())
                    ->setMetaTitle($content->getMetaTitle())
                    ->setMetaContent($content->getMetaContent())
                    ->setMetaKeyWords($content->getMetaKeywords())
                    ->setContent($content);
                $this->get('doctrine')->getManager()->persist($contentLang);
                $this->get('doctrine')->getManager()->flush();

            }
        }

        $categoryManager = $this->get('backend')->getCategoryManager();
        $categories = $categoryManager->getRepository()->findAll();
        foreach ($categories as $category) {
            foreach ($langs as $key=>$lang) {
                $contentLang = new \Aseagle\Backend\Entity\ContentLanguage();
                $contentLang->setTitle($category->getTitle())
                    ->setSlug($category->getSlug())
                    ->setLang($key)
                    ->setLongText($category->getDescription())
                    ->setCategory($category);
                $this->get('doctrine')->getManager()->persist($contentLang);
                $this->get('doctrine')->getManager()->flush();

            }
        }


        echo "Done";
 */
    }
}
