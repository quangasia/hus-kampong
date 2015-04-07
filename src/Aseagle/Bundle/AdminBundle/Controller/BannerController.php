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

use Aseagle\Bundle\CoreBundle\Helper\Html;

/**
 * BannerController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class BannerController extends BaseController {

    /*
     * (non-PHPdoc)
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
            switch($item->getPosition()) {
                case 1:
                    $pos = 'Slide Homepage';
                    break;
                case 2:
                    $pos = 'Banner Promotion';
                    break;
                case 3:
                    $pos = 'Banner News';
                    break;
                case 4:
                    $pos = 'Banner Food';
                    break;
                default:
            }

            $grid [] = array ( 
                '<input type="checkbox" name="ids[]" class="check" value="' . $item->getId() . '"/>', 
                Html::showImage($this->container, $item->getImage()), 
                '<a href="' . $this->generateUrl('admin_banner_new', array ( 
                    'id' => $item->getId() 
                )) . '">' . $item->getName() . '</a>', 
                $pos,
                is_object($item->getCreated()) ? $item->getCreated()->format('d/m/Y') : '', 
                Html::showStatusInTable($this->container, $item->getEnabled()), 
                Html::showActionButtonsInTable($this->container, array ( 
                    'edit' => $this->generateUrl('admin_banner_new', array ( 
                        'id' => $item->getId() 
                    )) 
                )) 
            );
        }
        return $grid;
    }
}
