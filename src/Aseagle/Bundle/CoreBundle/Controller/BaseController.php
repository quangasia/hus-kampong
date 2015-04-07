<?php

/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\Request;

/**
 * BaseController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class BaseController extends Controller {

    /**
     *
     * @param Request $request            
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction() {
        $manager = $this->getRequest()->get('_manager');
        if (NULL == $manager) {
            throw $this->createNotFoundException('Missing manager argument');
        }
    
        $form = $this->getRequest()->get('_form');
        if (NULL == $form) {
            throw $this->createNotFoundException('Missing form argument');
        }
    
        $template = $this->getRequest()->get('_view');
        if (NULL == $template) {
            throw $this->createNotFoundException('Missing view argument');
        }
    
        $class = $this->getRequest()->get('_class');
        if (NULL == $form) {
            throw $this->createNotFoundException('Missing form argument');
        }
    
        $entityMgr = $this->get('aseagle.service')->getEntity($manager);
        if (is_null($entityMgr)) {
            $entityMgr = $this->get($manager);
        }
        
        $oid = $this->getRequest()->get('id', NULL);
        if (NULL !== $oid) {
            if ($this->getRequest()->get('_ignoreAcl', null) != null  && !$this->get('user_acl')->isAllow('EDIT', $class)) {
                throw $this->createAccessDeniedException('Permission Denied');
            }
            $entity = $entityMgr->getObject($oid);
        } else {
            if (!$this->get('user_acl')->isAllow('CREATE', $class)) {
                throw $this->createAccessDeniedException('Permission Denied');
            }
            $entity = $entityMgr->createObject();
        }
    
        $form = $this->createForm(new $form($this->container), $entity);
    
        if ('POST' === $this->getRequest()->getMethod()) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                /* Save object */
                $entityMgr->save($entity);
    
                if (NULL == $oid) {
                    /* If a new object */
                    $msg = $this->container->get('translator')->trans('Created successful');
                    $this->get('session')->getFlashBag()->add('success', $msg);
    
                    $currentRoute = $this->getRequest()->get('_route');
    
                    if ($this->getRequest()->get('saveedit')) {
                        return $this->redirect($this->generateUrl($currentRoute, array (
                            'id' => $entity->getId()
                        )));
                    }
    
                    return $this->redirect($this->generateUrl($currentRoute));
                }
                $msg = $this->container->get('translator')->trans('Updated successful');
                $this->get('session')->getFlashBag()->add('success', $msg);
            } else {
                $msg = $this->container->get('translator')->trans('Error has occurred while saving object');
                $this->get('session')->getFlashBag()->add('error', $msg);
            }
        }
        
        return $this->render($template, array ( 
            'form' => $form->createView(),
            'entity' => ($entity->getId() != null) ? $entity : null 
        ));
    }

    /**
     * indexAction
     */
    public function indexAction() {
        $manager = $this->getRequest()->get('_manager');
        if (NULL == $manager) {
            throw $this->createNotFoundException('Missing manager argument');
        }
        
        $form = $this->getRequest()->get('_form');
        if (NULL == $form) {
            throw $this->createNotFoundException('Missing form argument');
        }
        
        $template = $this->getRequest()->get('_view');
        if (NULL == $template) {
            throw $this->createNotFoundException('Missing view argument');
        }
        
        $columns = $this->getRequest()->get('_columns', '[]');
        if (NULL == $columns) {
            throw $this->createNotFoundException('Missing form argument');
        }
        
        $class = $this->getRequest()->get('_class');
        if (NULL == $form) {
            throw $this->createNotFoundException('Missing form argument');
        }
        
        $entityMgr = $this->get('aseagle.service')->getEntity($manager);
        if (is_null($entityMgr)) {
            $entityMgr = $this->get($manager);
        }
        
        $filterForm = $this->createForm(new $form());
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
            $msgDenied = $this->container->get('translator')->trans('Permission Denied');
            switch ($request->get('customActionName')) {
                case 'delete' :
                    if ($this->get('user_acl')->isAllow('DELETE', $class)) {
                        $response = $this->delete($request->get('id', array ()), $manager);
                    } else {
                        $response['customActionMessage'] = $msgDenied;
                    }
                    break;
                case 'publish' :
                    if ($this->get('user_acl')->isAllow('EDIT', $class)) {
                        $response = $this->setStatus($request->get('id', array ()), $manager);
                    } else {
                        $response['customActionMessage'] = $msgDenied;
                    }
                    break;
                case 'unpublished' :
                    if ($this->get('user_acl')->isAllow('EDIT', $class)) {
                        $response = $this->setStatus($request->get('id', array ()), $manager, 'unpublished');
                    } else {
                        $response['customActionMessage'] = $msgDenied;
                    }
                    break;
                default :
            }
            
            $filterForm->bind($request);
            $filters = $filterForm->getData();
            
            $jsonFilters = $this->getRequest()->get('_filter', '{}');
            $options = json_decode($jsonFilters, true);
            $filters = array_merge($filters, $options);
            
            $offset = $this->getRequest()->get('start', 0);
            $limit = $this->getRequest()->get('length', $this->container->getParameter('item_per_page'));
            
            $order = array ();
            $orderMapping = json_decode($columns, true);
            $orderColumnNumber = $request->get('order', array ( 
                'column' => (NULL != ($col = array_search('created', $orderMapping))) ? $col : 1 
            ));
            
            if ($orderColumnNumber [0] ['column'] && isset($orderMapping [$orderColumnNumber [0] ['column']])) {
                $orderDir = $request->get('order', array ( 
                    'dir' => 'desc' 
                ));
                $order [$orderMapping [$orderColumnNumber [0] ['column']]] = $orderDir [0] ['dir'];
            }
            
            $entities = $entityMgr->getRepository()->getList($filters, $order, $limit, $offset);
            $total = $entityMgr->getRepository()->getTotal($filters);
            $grid = $this->grid($entities);
            
            $response ['data'] = $grid;
            $response ['recordsFiltered'] = $total;
            $response ['recordsTotal'] = $total;
            
            return new Response(json_encode($response));
        }
        
        if (!$this->get('user_acl')->isAllow('VIEW', $class)) {
            throw $this->createAccessDeniedException('Permission Denied');
        }
        
        return $this->render($template, array ( 
            'formFilter' => $filterForm->createView() 
        ));
    }

    /**
     *
     * @param array $items            
     * @return multitype:
     */
    protected function grid($entities) {
        return array ();
    }

    /**
     *
     * @param array $ids            
     * @param string $manager
     *            The object manager service id
     */
    protected function delete($ids, $manager) {
        if (! empty($ids)) {
            $entityMgr = $this->get('aseagle.service')->getEntity($manager);
            if (is_null($entityMgr)) {
                $entityMgr = $this->get($manager);
            }
            
            $entities = $entityMgr->getRepository()->findBy(array ( 
                'id' => $ids 
            ));
            
            $records = 0;
            foreach ($entities as $entity) {
                if (method_exists($entity, 'isSystem') && $entity->isSystem()) {
                    $msg = $this->container->get('translator')->trans('Error! this is a object of system, you can\'t delete this record');
                    return array (
                        'customActionMessage' => $msg
                    );
                } else {
                    try {
                        $this->get($manager)->delete($entity);
                    } catch (\Doctrine\DBAL\DBALException $e) {
                        $msg = $this->container->get('translator')->trans('Error! please deteted or updated the related items before remove this records');
                        return array ( 
                            'customActionMessage' => $msg 
                        );
                    }
                    $records ++;
                }
            }
            
            if ($records) {
                $msg = $this->container->get('translator')->trans('%d records deleted');
                return array ( 
                    'customActionMessage' => str_replace('%d', $records, $msg), 
                    'customActionStatus' => 'OK' 
                );
            }
        }
        
        return array ();
    }

    /**
     *
     * @param array $ids            
     * @param string $manager            
     * @param string $type            
     */
    protected function setStatus($ids, $manager, $type = 'publish') {
        if (! empty($ids)) {
            
            $entityMgr = $this->get('aseagle.service')->getEntity($manager);
            if (is_null($entityMgr)) {
                $entityMgr = $this->get($manager);
            }
            
            $entities = $entityMgr->getRepository()->findBy(array ( 
                'id' => $ids 
            ));
            switch ($type) {
                case 'publish' :
                    $records = 0;
                    foreach ($entities as $entity) {
                        $entity->setEnabled(true);
                        $this->get($manager)->save($entity);
                        $records ++;
                    }
                    
                    if ($records) {
                        $msg = $this->container->get('translator')->trans('%d records published');
                        return array ( 
                            'customActionMessage' => str_replace('%d', $records, $msg), 
                            'customActionStatus' => 'OK' 
                        );
                    }
                    break;
                case 'unpublished' :
                    $records = 0;
                    foreach ($entities as $entity) {
                        $entity->setEnabled(false);
                        $this->get($manager)->save($entity);
                        $records ++;
                    }
                    
                    if ($records) {
                        $msg = $this->container->get('translator')->trans('%d records unpublished');
                        return array ( 
                            'customActionMessage' => str_replace('%d', $records, $msg), 
                            'customActionStatus' => 'OK' 
                        );
                    }
                    break;
                default :
            }
        }
        
        return array ();
    }
}