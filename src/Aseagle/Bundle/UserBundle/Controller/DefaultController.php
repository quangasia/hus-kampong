<?php

namespace Aseagle\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        /* @var $userManager \Aseagle\Bundle\UserBundle\Manager\UserManager */
        $userManager = $this->container->get('user_manager');
        
        /* @var $user \Aseagle\Bundle\UserBundle\Entity\User */
        $user = $userManager->createObject();        
        
//        
//        echo "<pre>";
//        print_r($user);
//        die("debug");
//        
        return $this->render('AseagleUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
