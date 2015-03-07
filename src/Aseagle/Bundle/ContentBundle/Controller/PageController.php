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

use Aseagle\Bundle\AdminBundle\Form\Type\PageType;
use Symfony\Component\HttpFoundation\Response;
use Aseagle\Bundle\CoreBundle\Helper\Html;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Aseagle\Bundle\ContentBundle\Entity\Content;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * PageController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class PageController extends BaseController {
   
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
                '<a href="' . $this->generateUrl('admin_page_new', array ( 
                    'id' => $item->getId() 
                )) . '">' . $item->getTitle() . '</a>', 
                is_object($item->getCreated()) ? $item->getCreated()->format('d/m/Y') : '', 
                Html::showStatusInTable($this->container, $item->getEnabled()), 
                Html::showActionButtonsInTable($this->container, array ( 
                    'edit' => $this->generateUrl('admin_page_new', array ( 
                        'id' => $item->getId() 
                    )) 
                )) 
            );
        }
        
        return $grid;
    }
}
