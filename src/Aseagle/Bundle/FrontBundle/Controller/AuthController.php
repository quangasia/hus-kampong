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
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Aseagle\Bundle\FrontBundle\Form\Type\UserType;
use Aseagle\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction() {
        $userManager = $this->get('user_manager');
        
        $user = $this->get('security.context')->getToken()->getUser();
        if (!$user instanceof User) {
            $user = $userManager->createObject();
        }
      
        $form = $this->createForm(new UserType($this->container), $user);        
        if ($this->getRequest()->getMethod() == 'POST') {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $userManager->save($user);
                
                $msg = $this->container->get('translator')->trans('Register successful, please login to your account');
                $this->get('session')->getFlashBag()->add('success', $msg);
                
                return $this->redirect($this->generateUrl('front_login'));
            } else {
                $msg = $this->container->get('translator')->trans('Register failed, please check your information');
                $this->get('session')->getFlashBag()->add('error', $msg);
            }
        }
        
        return $this->render('AseagleFrontBundle:Auth:register.html.twig', array ( 
            'form' => $form->createView(),
            'title' => (NULL == $user->getId()) ? 'Register' : 'Profile' 
        ));
    }

    /**
     * loginAction
     *
     * @return type
     */
    public function loginAction() {
        $request = $this->getRequest();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        $response = new Response();
        $response->setContent($this->renderView('AseagleFrontBundle:Auth:login.html.twig', array ( 
            'last_username' => $session->get(SecurityContext::LAST_USERNAME), 
            'error' => $error 
        )));
        
        return $response;
    }

    /**
     * fbLogin
     *
     * @return type
     */
    public function fbLoginAction()
    {
    
        $params = array(
            'appId'  => $this->container->getParameter('fb_app_id'),
            'secret' => $this->container->getParameter('fb_app_secret'),
        );
        $facebook = new \Facebook($params);
    
        try {
            //get profile
            $userProfile = $facebook->api('/me');
            if (is_array($userProfile)) {
                $user = $this->get('user_manager')->getRepository()->findOneByFbId($userProfile['id']);
                if ($user instanceof User) {
                    $token = new UsernamePasswordToken($user, null, 'member_area', $user->getRoles());
                    $this->get('security.context')->setToken($token);
                } else {
                    $user = $this->get('user_manager')->getRepository()->findOneByEmail($userProfile['email']);
                    if ($user instanceof User) {
                        $token = new UsernamePasswordToken($user, null, 'member_area', $user->getRoles());
                        $this->get('security.context')->setToken($token);
                    } else {
                        $user = new User();
                        $user->setFullname($userProfile['first_name'] . ' ' . $userProfile['last_name']);
                        $user->setUsername($userProfile['name']);
                        $user->setFbId($userProfile['id']);
                        $user->setGender($userProfile['gender']);
                        $user->setEmail($userProfile['email']);
    
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();
    
                        $token = new UsernamePasswordToken($user, null, 'front_area', $user->getRoles());
                        $this->get('security.context')->setToken($token);
                    }
                }
    
                return $this->redirect($this->generateUrl('front_homepage'));
            }
        } catch(\FacebookApiException $e) {
            $params = array(
                'scope' => 'email',
                'redirect_uri' => $this->generateUrl('front_fb_login', array(), true)
            );
            $loginURL = $facebook->getLoginUrl($params);
            die('<script>window.top.location.href="' . $loginURL . '";</script>');
        }
    
    }
    
    /**
     * checkAction
     *
     * @throws \RuntimeException
     */
    public function checkAction() {
        throw new \RuntimeException(
                'You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }
}
