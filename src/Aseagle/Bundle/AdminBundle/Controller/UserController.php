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

use Aseagle\Bundle\AdminBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Response;
use Aseagle\Bundle\CoreBundle\Helper\Html;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * UserController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class UserController extends BaseController {

    
    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\AdminBundle\Controller\BaseController::indexAction()
     */
    public function indexAction() {
        return parent::indexAction();
    }

    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\AdminBundle\Controller\BaseController::grid()
     */
    protected function grid($entities) {
        $grid = array ();
        foreach ($entities as $item) {
            $grid [] = array ( 
                '<input type="checkbox" name="ids[]" class="check" value="' . $item->getId() . '"/>', 
                $item->getUsername(), 
                $item->getFullname(), 
                $item->getEmail(), 
                is_object($item->getCreated()) ? $item->getCreated()->format('d/m/Y') : '', 
                Html::showStatusInTable($this->container, $item->isEnabled()), 
                Html::showActionButtonsInTable($this->container, array ( 
                    'edit' => $this->generateUrl('admin_user_new', array ( 
                        'id' => $item->getId() 
                    )) 
                )) 
            );
        }
        
        return $grid;
    }

    /**
     * profileAction
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function profileAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        if (! $user instanceof UserInterface) {
            throw $this->createAccessDeniedException('Permission Denied');
        }
        
        return $this->forward('AseagleAdminBundle:Base:new', array ( 
            'id' => $user->getId(), 
            '_controller' => 'AseagleAdminBundle:Base:new', 
            '_manager' => 'user_manager', 
            '_class' => 'Aseagle\Bundle\AdminBundle\Controller\UserController',
            '_form' => '\Aseagle\Bundle\AdminBundle\Form\Type\UserType', 
            '_view' => 'AseagleAdminBundle:User:profile.html.twig',
            '_ignoreAcl' => true 
        ));
    }

}
