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

}
