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

use Aseagle\Bundle\AdminBundle\Form\Type\GroupType;
use Symfony\Component\HttpFoundation\Response;
use Aseagle\Bundle\CoreBundle\Helper\Html;

/**
 * GroupController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class GroupController extends BaseController {

    /**
     * Group listing
     */
    public function indexAction() {
        $formFilter = $this->creatFormFilter();
        
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
         
            switch ($request->get('customActionName')) {
                case 'delete' :
                    $response = $this->delete($this->getRequest()->get('id', array()), 'user_group_manager');
                    break;
                case 'publish' :
                    $response = $this->setStatus($this->getRequest()->get('id', array()), 'user_group_manager');
                    break;
                case 'unpublished' :
                    $response = $this->setStatus($this->getRequest()->get('id', array()), 'user_group_manager', 'unpublished');
                    break;
                default :
            }
            
            $filterForm = $this->creatFormFilter();
            $filterForm->bind($request);
            $filters = $filterForm->getData();
            
            $page = $this->getRequest()->get('page', 1);
            $limit = $this->container->getParameter('item_per_page');
            $offset = $limit * ($page - 1);
            
            $order = array ();
            $orderMapping = array ( 
                'id', 
                'name', 
                'type', 
                'role', 
                'created', 
                'enabled' 
            );
            $orderColumnNumber = $request->get('order', array ( 
                'column' => 1 
            ));
            if ($orderColumnNumber [0] ['column'] && isset($orderMapping [$orderColumnNumber [0] ['column']])) {
                $orderDir = $request->get('order', array ( 
                    'dir' => 'asc' 
                ));
                $order [$orderMapping [$orderColumnNumber [0] ['column']]] = $orderDir [0] ['dir'];
            }
            
            $entities = $this->get('user_group_manager')->getRepository()->getList($filters, $order, $limit, $offset);
            $total = $this->get('user_group_manager')->getRepository()->getTotal($filters);
            
            $rData = array ();
            foreach ($entities as $item) {
                $rData [] = array ( 
                    '<input type="checkbox" name="ids[]" class="check" value="' . $item->getId() . '"/>', 
                    $item->getName(), 
                    ($item->getType() == 0) ? $this->get('translator')->trans('User Level') :  $this->get('translator')->trans('Manager Level'),
                    $item->getRole(), 
                    is_object($item->getCreated()) ? $item->getCreated()->format('d/m/Y') : '', 
                    Html::showStatusInTable($this->container, $item->getEnabled()), 
                    Html::showActionButtonsInTable($this->container, array ( 
                        'edit' => $this->generateUrl('admin_group_new', array ( 
                            'id' => $item->getId() 
                        )) 
                    )) 
                );
            }
            
            $response['data'] = $rData;
            $response['recordsFiltered'] = $total;
            $response['recordsTotal'] = $total;
            
            return new Response(json_encode($response));
        }
        
        return $this->render('AseagleAdminBundle:Group:index.html.twig', array ( 
            'formFilter' => $formFilter->createView() 
        ));
    }

    /**
     * newAction
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction() {
        $uid = $this->getRequest()->get('id', NULL);
        if (NULL !== $uid) {
            $entity = $this->get('user_group_manager')->getObject($uid);
        } else {
            $entity = $this->get('user_group_manager')->createObject();
        }
        
        $form = $this->createForm(new GroupType($this->container), $entity, array ( 
            'isadd' => (NULL !== $uid) ? false : true 
        ));
        
        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                /* Save object */
                $this->get('user_group_manager')->save($entity);
                
                if (NULL == $uid) {
                    /* If a new object */
                    $this->get('session')->getFlashBag()->add('success', $this->container->get('translator')->trans('Created sucessful', array (), 'messages'));
                    return $this->redirect($this->generateUrl('admin_group_new', array ( 
                        'id' => $entity->getId() 
                    )));
                }
                $this->get('session')->getFlashBag()->add('success', $this->container->get('translator')->trans('Updated sucessful', array (), 'messages'));
            } else {
                $this->get('session')->getFlashBag()->add('error', $this->container->get('translator')->trans('Error has occurred while saving object', array (), 'messages'));
            }
        }
        
        return $this->render('AseagleAdminBundle:Group:new.html.twig', array ( 
            'form' => $form->createView() 
        ));
    }

    /**
     * deleteAction
     */
    public function deleteAction() {
        $ids = $this->getRequest()->get('id');
        if (! empty($ids)) {
            $entities = $this->get('user_group_manager')->getRepository()->findBy(array ( 
                'id' => $ids 
            ));
            foreach ($entities as $entity) {
                if ($entity->isSystem() != true) {
                    $this->get('user_group_manager')->delete($entity);
                }
            }
        }
    }

    /**
     * publishAction
     */
    public function publishAction() {
        $ids = $this->getRequest()->get('id');
        if (! empty($ids)) {
            $entities = $this->get('user_group_manager')->getRepository()->findBy(array ( 
                'id' => $ids 
            ));
            switch ($this->getRequest()->get('type', 'publish')) {
                case 'publish' :
                    foreach ($entities as $entity) {
                        $entity->setEnabled(true);
                        $this->get('user_group_manager')->save($entity);
                    }
                    break;
                case 'unpublished' :
                    foreach ($entities as $entity) {
                        $entity->setEnabled(false);
                        $this->get('user_group_manager')->save($entity);
                    }
                    break;
                default :
            }
        }
    }

    /**
     *
     * @return \Symfony\Component\Form\FormBuilder
     */
    protected function creatFormFilter() {
        $form = $this->createFormBuilder()->add('name', 'text', array ( 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'Username'
            ) 
        ))->add('type', 'choice', array ( 
            'required' => false, 
            'empty_value' => 'Group Type', 
            'choices' => array ( 
                '1' => 'Normal', 
                '0' => 'Manager' 
            ), 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm' 
            ) 
        ))->add('role', 'text', array ( 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'Role Name'
            ) 
        ))->add('created_from', 'date', array ( 
            'required' => false, 
            'widget' => 'single_text', 
            'format' => 'dd/MM/yyyy', 
            'attr' => array ( 
                'readonly' => true, 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'From'
            ) 
        ))->add('created_to', 'date', array ( 
            'required' => false, 
            'widget' => 'single_text', 
            'format' => 'dd/MM/yyyy', 
            'attr' => array ( 
                'readonly' => true, 
                'class' => 'form-control form-filter input-sm',
                'placeholder' => 'To'
            ) 
        ))->add('enabled', 'choice', array ( 
            'required' => false, 
            'empty_value' => 'Status', 
            'choices' => array ( 
                '1' => 'Publish', 
                '0' => 'Un-publish' 
            ), 
            'attr' => array ( 
                'class' => 'form-control form-filter input-sm' 
            ) 
        ));
        
        return $form->getForm();
    }
}
