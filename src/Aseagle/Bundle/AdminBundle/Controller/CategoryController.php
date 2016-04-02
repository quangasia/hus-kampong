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

use Aseagle\Bundle\AdminBundle\Form\Type\CategoryType;
use Symfony\Component\HttpFoundation\Response;
use Aseagle\Bundle\CoreBundle\Helper\Html;
use Doctrine\ORM\EntityRepository;
use Aseagle\Bundle\ContentBundle\Entity\Category;

/**
 * CategoryController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class CategoryController extends BaseController {

    /* (non-PHPdoc)
     * @see \Aseagle\Bundle\AdminBundle\Controller\BaseController::indexAction()
     */
    public function indexAction() {
        return parent::indexAction();
    }

    /*
     * (non-PHPdoc)
     * @see \Aseagle\Bundle\AdminBundle\Controller\BaseController::grid()
     */
    protected function grid($entities) {
        $grid = array ();
        foreach ($entities as $item) {
            $grid [] = array ( 
                '<input type="checkbox" name="ids[]" class="check" value="' . $item[0]->getId() . '"/>', 
                '<a href="' . $this->generateUrl('admin_category_new', array ( 
                    'id' => $item[0]->getId() 
                )) . '">' . str_repeat("—", $item[0]->getLvl()) ." ".  $item['title'] . '</a>', 
                is_object($item[0]->getCreated()) ? $item[0]->getCreated()->format('d/m/Y') : '', 
                Html::showStatusInTable($this->container, $item[0]->getEnabled()), 
                Html::showActionButtonsInTable($this->container, array ( 
                    'edit' => $this->generateUrl('admin_category_new', array ( 
                        'id' => $item[0]->getId() 
                    )) 
                )) 
            );
        }
        
        return $grid;
    }
}
