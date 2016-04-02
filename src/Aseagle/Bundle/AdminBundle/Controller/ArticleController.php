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

use Symfony\Component\HttpFoundation\Response;
use Aseagle\Bundle\CoreBundle\Helper\Html;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Aseagle\Bundle\ContentBundle\Entity\Content;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * ArticleController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class ArticleController extends BaseController {

    /**
     * indexAction
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
                '<input type="checkbox" name="ids[]" class="check" value="' . $item[0]->getId() . '"/>',
                '<a href="'.$this->generateUrl('admin_article_new', array ('id' => $item[0]->getId())).'">' . $item['title'] . '</a>',
                is_object($item[0]->getAuthor()) ? $item[0]->getAuthor()->getFullname() : '_',
                (NULL != $item[0]->getPageView()) ? $item[0]->getPageView() : 0,
                is_object($item[0]->getCreated()) ? $item[0]->getCreated()->format('d/m/Y') : '',
                Html::showStatusInTable($this->container, $item[0]->getEnabled()),
                Html::showActionButtonsInTable($this->container, array (
                    'edit' => $this->generateUrl('admin_article_new', array (
                        'id' => $item[0]->getId()
                    ))
                ))
            );
        }
        
        return $grid;
    }
}
