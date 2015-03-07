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
use Aseagle\Bundle\EcommerceBundle\Entity\Product;
use Aseagle\Bundle\EcommerceBundle\Entity\Order;
use Aseagle\Bundle\EcommerceBundle\Entity\OrderItem;
use Aseagle\Bundle\FrontBundle\Form\Type\OrderType;
use Aseagle\Bundle\UserBundle\Entity\User;

/**
 * CartController
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class CartController extends Controller {

    /**
     * @param integer $pid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToCartAction($pid) {
        $productManager = $this->get('product_manager');
        
        $session = $this->getRequest()->getSession();
        $product = $productManager->getObject($pid);
        if ($product instanceof Product) {
            $products = $session->get('productOrder', array ());
            if (!array_key_exists($pid, $products)) {
                $products [$product->getId()] = 1;
                $session->set('productOrder', $products);
            } else {
                $products [$product->getId()] = $products [$product->getId()] + 1;
                $session->set('productOrder', $products);
            }
            
            $msg = $this->container->get('translator')->trans('Add to cart successful');
            $this->get('session')->getFlashBag()->add('success', $msg);
            $referer = $this->getRequest()->headers->get('referer');
            
            return $this->redirect($referer);
        }
        return $this->redirect($this->generateUrl('front_homepage'));
    }
    
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewCartAction()
    {
        $productManager = $this->get('product_manager');
        $session = $this->getRequest()->getSession();
        
        $productsInCart = $session->get('productOrder', array());
        if ($this->getRequest()->getMethod() == 'POST') {
            $updateCart = $this->getRequest()->get('cart');
            foreach ($updateCart as $key => $item) {
                if (array_key_exists($key, $productsInCart)) {
                    if ($item <= 0) {
                        unset($productsInCart[$key]);
                    } else {
                        $productsInCart[$key] = $item;
                    }
                }
            }
            $session->set('productOrder', $productsInCart);
        }
        
        $products = array();
        if (count($productsInCart)) {
            $productIds = array_keys($productsInCart);
            $products = $productManager->getRepository()->findBy(array('id' => $productIds));
        }
    
        $order = new Order();
        foreach ($products as $product) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setQuantity($productsInCart[$product->getId()]);
            $orderItem->setProduct($product);
            $order->addOrderItem($orderItem);
        }
        $form = $this->createForm(new OrderType($this->container), $order);
    
        return $this->render('AseagleFrontBundle:Cart:index.html.twig', array(
            'products' => $products,            
            'form' => $form->createView()
        ));
    }
    
    public function removeCartItemAction() {
        $pid = $this->getRequest()->get('pid');
        $session = $this->getRequest()->getSession();
        $productsInCart = $session->get('productOrder', array());
        
        if (array_key_exists($pid, $productsInCart)) {
            unset($productsInCart[$pid]);
            $session->set('productOrder', $productsInCart);
        }
        
        $referer = $this->getRequest()->headers->get('referer');
        
        return $this->redirect($referer);
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction()
    {
        $productManager = $this->get('product_manager');
        $orderManager = $this->get('order_manager');
        
        $session = $this->getRequest()->getSession();
        $user = $this->get('security.context')->getToken()->getUser();
    
        $productsInCart = $session->get('productOrder', array());
        $products = array();
        if (count($productsInCart)) {
            $productIds = array_keys($productsInCart);
            $products = $productManager->getRepository()->findBy(array('id' => $productIds));
        }
        
        $order = new Order();
        if ($this->getRequest()->getMethod() == 'POST') {
            $form = $this->createForm(new OrderType($this->container), $order);
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $order->setUser($user);
                $order->setStatus(2);
                $orderManager->save($order);
                $session->set('productOrder', array());
                $msg = $this->container->get('translator')->trans('Add to cart successful');
                $this->get('session')->getFlashBag()->add('success', $msg);
        
                return $this->redirect($this->generateUrl('front_checkout_success'));
            } else {
        
                $msg = $this->container->get('translator')->trans('Error! please check you information');
                $this->get('session')->getFlashBag()->add('error', $msg);
            }
        } else {    
            foreach ($products as $product) {
                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setQuantity($productsInCart[$product->getId()]);
                $orderItem->setProduct($product);
                $order->addOrderItem($orderItem);
            }
        }
        
        if ($user instanceof User) {
            $order->setUser($user);
            $order->setFullname($user->getFullname())
                 ->setEmail($user->getEmail())
                 ->setAddress($user->getAddress())
                 ->setPhone($user->getPhone());
        }
        $form = $this->createForm(new OrderType($this->container), $order);
    
        return $this->render('AseagleFrontBundle:Cart:checkout.html.twig', array(
            'products' => $products,
            'form' => $form->createView()
        ));
    }
    
    public function checkoutSuccessAction() {
        return $this->render('AseagleFrontBundle:Cart:success.html.twig');
    }
    
}