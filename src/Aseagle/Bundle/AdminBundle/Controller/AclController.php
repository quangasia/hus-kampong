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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

/**
 * AclController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class AclController extends BaseController {
    
    /**
     *
     * @var array
     */
    protected $_aclValues;

    /**
     * Initial value for acl
     */
    public function __construct() {
        $this->_aclValues = array ( 
            'view' => MaskBuilder::MASK_VIEW, 
            'create' => MaskBuilder::MASK_CREATE, 
            'edit' => MaskBuilder::MASK_EDIT, 
            'delete' => MaskBuilder::MASK_DELETE, 
            'owner' => MaskBuilder::MASK_OWNER 
        );
    }

    /**
     *
     * @todo index action
     *      
     * @return Response
     */
    public function indexAction() {
        // check access permision
        $request = $this->getRequest();
        $form = $this->createAclForm();
        
        $aclManager = $this->get('user_acl_manager');
        
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access Denied', 403);
        }
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            
            // Get role from post data
            $role = $form ['role']->getData();
            
            // Get permission value from post data
            $permission = $this->getRequest()->get('permission');
            
            // Exchanges all keys with their associated values in array
            $aclValue = array_flip($this->_aclValues);
            
            $aclData = $aclManager->getRepository()->findBy(array (), array ( 
                'type' => 'ASC' 
            ));
            foreach ($aclData as $aclItem) {
                $permission = $permission != null ? $permission : array ();
                if (! array_key_exists($aclItem->getClass(), $permission)) {
                    $permission [$aclItem->getClass()] [] = 0;
                }
            }
            
            // Loop to update acl of classes
            if (sizeof($permission)) {
                foreach ($permission as $class => $permissionValues) {
                    if (sizeof($permissionValues)) {
                        $builder = new MaskBuilder();
                        foreach ($permissionValues as $value) {
                            if (isset($aclValue [$value])) {
                                $builder->add($aclValue [$value]);
                            }
                        }
                        $this->updateClassAcl($class, $role->getRole(), $builder->get());
                    } else {
                        $this->updateClassAcl($class, $role->getRole(), null);
                    }
                }
                
                // Set flash message
                $message = $this->get('translator')->trans('Updated successful');
                $this->get('session')->getFlashBag()->add('success', $message);
            }
        } else {
            $role = $this->get('user_group_manager')->getRepository()->findOneByRole('ROLE_ADMIN');
            $form ['role']->setData($role);
        }
        
        return $this->render('AseagleAdminBundle:Acl:index.html.twig', array ( 
            'form' => $form->createView(), 
            'rid' => isset($role) ? $role->getId() : null 
        ));
    }

    /**
     *
     * @todo index action
     *      
     * @return Response
     */
    public function aclClassFieldsAction() {
        $request = $this->getRequest();
        $form = $this->createAclForm();
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            
            // Get role from post data
            $role = $form ['role']->getData();
            
            // Get permission value from post data
            $permission = $this->getRequest()->get('permission', array ());
            
            // Exchanges all keys with their associated values in array
            $aclValue = array_flip($this->_aclValues);
            
            $aclData = $this->container->getParameter('acl_class_fields');
            foreach ($aclData as $aclItem) {
                foreach ($aclItem ['fields'] as $key => $field) {
                    if (! array_key_exists($aclItem ['class'], $permission)) {
                        $permission [$aclItem ['class']] [$key] = 0;
                    } elseif (isset($permission [$aclItem ['class']]) && ! array_key_exists($key, $permission [$aclItem ['class']])) {
                        $permission [$aclItem ['class']] [$key] = 0;
                    }
                }
            }
            
            // Loop to update acl of classes
            if (sizeof($permission)) {
                foreach ($permission as $class => $permissionValues) {
                    if (sizeof($permissionValues)) {
                        foreach ($permissionValues as $keyField => $fieldValue) {
                            $builder = new MaskBuilder();
                            if (is_array($fieldValue) && sizeof($fieldValue) > 0) {
                                foreach ($fieldValue as $value) {
                                    if (isset($aclValue [$value])) {
                                        $builder->add($aclValue [$value]);
                                    }
                                }
                                
                                $this->updateClassFieldsAcl($class, $keyField, $role, $builder->get());
                            } else {
                                $this->updateClassFieldsAcl($class, $keyField, $role, 0);
                            }
                        }
                    }
                }
                
                // Set flash message
                $message = $this->get('translator')->trans('update_entity_success');
                $this->get('session')->getFlashBag()->add('success', $message);
            }
        }
        
        return $this->render('AseagleAdminBundle:Acl:class-fields.html.twig', array ( 
            'form' => $form->createView(), 
            'rid' => isset($role) ? $role->getId() : null 
        ));
    }

    /**
     * load acl of a role
     *
     * @param integer $rid
     *            The id role
     *            
     * @return Response
     */
    public function loadAclOfRoleAction($rid) {
        $aclData = $this->get('user_acl_manager')->getRepository()->findBy(array (), array ( 
            'type' => 'ASC' 
        ));
        
        // if request by get method
        if ($rid == null) {
            $rid = $this->getRequest()->get('roleid');
        }
        
        // Get role security indentity of role
        $group = $this->get('user_group_manager')->getRepository()->find($rid);
        $osi = new RoleSecurityIdentity($group->getRole());
        
        // Loop classes which need to set permission
        foreach ($aclData as $classItem) {
            $oid = new ObjectIdentity('class', $classItem->getClass());
            $acl = $this->doLoadAcl($oid);
            
            // Exchanges all keys with their associated values in array
            $aclValue = array_flip($this->_aclValues);
            
            // Get class aces
            $aceClassCollection = $acl->getClassAces();
            
            if (sizeof($aceClassCollection)) {
                // Loop get acl value for show on view
                foreach ($aceClassCollection as $aceClass) {
                    if ($aceClass->getSecurityIdentity()->equals($osi)) {
                        foreach ($aclValue as $key => $aclValue) {
                            if ($aceClass->getMask() & $key) {
                                $acls [$classItem->getId()] [$aclValue] = true;
                            } else {
                                $acls [$classItem->getId()] [$aclValue] = false;
                            }
                        }
                    }
                }
            }
        }
        
        return $this->render('AseagleAdminBundle:Acl:ajax.html.twig', array ( 
            'aclData' => $aclData, 
            'aclMask' => $this->_aclValues, 
            'acls' => isset($acls) ? $acls : array () 
        ));
    }

    /**
     * load acl of a role
     *
     * @param integer $rid
     *            The id role
     *            
     * @return Response
     */
    public function loadAclFieldsOfRoleAction($rid) {
        $entityManager = $this->getDoctrine()->getManager();
        $aclData = $this->container->getParameter('acl_class_fields');
        
        // if request by get method
        if ($rid == null) {
            $rid = $this->getRequest()->get('roleid');
        }
        
        // Get role security indentity of role
        $role = $entityManager->getRepository('QTUserBundle:Role')->find($rid);
        $osi = new RoleSecurityIdentity($role->getRole());
        
        foreach ($aclData as $classItem) {
            $oid = new ObjectIdentity('class', $classItem ['class']);
            $acl = $this->doLoadAcl($oid);
            
            // Exchanges all keys with their associated values in array
            $aclValue = array_flip($this->_aclValues);
            
            foreach ($classItem ['fields'] as $field => $value) {
                $aceClassCollection = $acl->getClassFieldAces($field);
                
                if (sizeof($aceClassCollection)) {
                    foreach ($aceClassCollection as $aceClass) {
                        if ($aceClass->getSecurityIdentity()->equals($osi)) {
                            if (sizeof($aclValue)) {
                                foreach ($aclValue as $key => $permissionValue) {
                                    if ($aceClass->getMask() & $key) {
                                        $acls [$classItem ['class']] [$field] [$permissionValue] = true;
                                    } else {
                                        $acls [$classItem ['class']] [$field] [$permissionValue] = false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $this->render('QTAdminBundle:Acl:ajax-fields.html.twig', array ( 
            'aclData' => $aclData, 
            'aclMask' => $this->_aclValues, 
            'acls' => isset($acls) ? $acls : array () 
        ));
    }

    /**
     * Load acl of a object
     *
     * @param ObjectIdentityInterface $objectIdentity
     *            ad
     *            
     * @return type
     */
    protected function doLoadAcl(ObjectIdentityInterface $objectIdentity) {
        try {
            $acl = $this->get('security.acl.provider')->createAcl($objectIdentity);
        } catch (AclAlreadyExistsException $ex) {
            $acl = $this->get('security.acl.provider')->findAcl($objectIdentity);
        }
        
        return $acl;
    }

    /**
     * Update acl for class object
     *
     * @param string $class
     *            The class path
     * @param string $role
     *            The role name
     * @param integer $mask
     *            The mask number
     *            
     *            return void
     */
    protected function updateClassAcl($class, $role, $mask) {
        // check access permision
        $aclProvider = $this->container->get('security.acl.provider');
        
        $oid = new ObjectIdentity('class', $class);
        
        /* @var $acl \Symfony\Component\Security\Acl\Domain\Acl */
        $acl = $this->doLoadAcl($oid);
        
        // Load object indentity
        $securityIdentity = new RoleSecurityIdentity($role);
        
        // grant owner access
        if (sizeof($acl->getClassAces())) {
            $index = 0;
            $exist = false;
            foreach ($acl->getClassAces() as $aceClass) {
                if ($aceClass->getSecurityIdentity()->equals($securityIdentity)) {
                    $exist = true;
                    $acl->updateClassAce($index, $mask);
                }
                $index ++;
            }
            
            if (! $exist) {
                $acl->insertClassAce($securityIdentity, $mask);
            }
        } else {
            $acl->insertClassAce($securityIdentity, $mask);
        }
        
        $aclProvider->updateAcl($acl);
    }

    /**
     * Update acl for class object
     *
     * @param string $class
     *            The class path
     * @param string $field
     *            The field of class
     * @param string $role
     *            The role name
     * @param integer $mask
     *            The mask number
     *            
     *            return void
     */
    protected function updateClassFieldsAcl($class, $field, $role, $mask) {
        $aclProvider = $this->container->get('security.acl.provider');
        
        $oid = new ObjectIdentity('class', $class);
        
        /* @var $acl \Symfony\Component\Security\Acl\Domain\Acl */
        $acl = $this->doLoadAcl($oid);
        
        // Load object indentity
        $securityIdentity = new RoleSecurityIdentity($role);
        
        // grant owner access
        if (sizeof($acl->getClassFieldAces($field))) {
            $index = 0;
            $exist = false;
            foreach ($acl->getClassFieldAces($field) as $aceClassField) {
                if ($aceClassField->getSecurityIdentity()->equals($securityIdentity)) {
                    $exist = true;
                    $acl->updateClassFieldAce($index, $field, $mask);
                }
                $index ++;
            }
            
            if (! $exist) {
                $acl->insertClassFieldAce($field, $securityIdentity, $mask);
            }
        } else {
            $acl->insertClassFieldAce($field, $securityIdentity, $mask);
        }
        
        $aclProvider->updateAcl($acl);
    }

    /**
     *
     * @todo Create a acl form
     *      
     * @return Form
     */
    protected function createAclForm() {
        $form = $this->createFormBuilder()->add('role', 'entity', array ( 
            'class' => 'AseagleUserBundle:UserGroup', 
            'property' => 'role', 
            'required' => false, 
            'empty_value' => 'Select...', 
            'attr' => array ( 
                'class' => 'form-control' 
            ) 
        ))->getForm();
        
        return $form;
    }
}
