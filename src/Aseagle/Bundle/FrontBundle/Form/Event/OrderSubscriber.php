<?php 
/*
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\FrontBundle\Form\Event;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityRepository;
use Aseagle\Bundle\CoreBundle\Helper\Html;

/**
 * OrderSubscriber
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class OrderSubscriber implements EventSubscriberInterface
{
    protected $container;
    
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) 
    {
        $this->container = $container;
    }
    
    /**
     * @return multitype:string 
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_BIND => 'postBind'
        );
    }
    
    /**
     * @param FormEvent $event
     */
    public function postBind(FormEvent $event) {
        $order = $event->getData();
        
        
        $total = 0;
        foreach ($order->getOrderItems() as $item) {
            $item->setOrder($order);
//             $this->container->get('order_manager')->save($item, false);
            $total = $total + ($item->getQuantity() * $item->getProduct()->getPrice());
        }
        $order->setTotal($total);
        
        if (NULL == $order->getId()) {
            $order->setStatus(2);
        }            
    }   
  
} 