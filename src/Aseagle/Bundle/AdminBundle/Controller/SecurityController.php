<?php

/**
 * This file is part of the package_name package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * SecurityController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class SecurityController extends Controller {

    /**
     * indexAction
     *
     * @return type
     */
    public function indexAction() {
        return $this->render ('AseagleFrontBundle:Security:index.html.twig');
    }

    /**
     * loginAction
     *
     * @return type
     */
    public function loginAction() {
        $request = $this->getRequest ();
        $session = $request->getSession ();
        // get the login error if there is one
        if ($request->attributes->has (SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get (SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get (SecurityContext::AUTHENTICATION_ERROR);
            $session->remove (SecurityContext::AUTHENTICATION_ERROR);
        }
        
        $response = new Response ();
        $cookies = $request->cookies;
        if ($cookies->has ('uid')) {
            $user = $this->container->get ('user_manager')->getObject ($cookies->get ('uid'));
            if ($user instanceof \Aseagle\Bundle\UserBundle\Entity\User && $user->getFbId () == '') {
                $response->setContent ($this->renderView ('AseagleFrontBundle:Security:lock.html.twig', array ( 
                    'last_username' => $session->get (SecurityContext::LAST_USERNAME), 
                    'error' => $error, 
                    'user' => $user 
                )));
                
                return $response;
            }
        }
        $response->setContent ($this->renderView ('AseagleAdminBundle:Security:login.html.twig', array ( 
            'last_username' => $session->get (SecurityContext::LAST_USERNAME), 
            'error' => $error 
        )));
        
        return $response;
    }

    /**
     * checkAction
     *
     * @throws \RuntimeException
     */
    public function checkAction() {
        throw new \RuntimeException ('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * Switch login
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function switchLoginAction() {
        $request = $this->getRequest ();
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse ($this->generateUrl ('qt_front_login'));
        $cookies = $request->cookies;
        if ($cookies->has ('uid')) {
            $response->headers->clearCookie ('uid');
        }
        
        return $response;
    }
}
