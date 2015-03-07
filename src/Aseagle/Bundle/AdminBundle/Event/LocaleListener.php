<?php
/**
 * This file is part of the Aseagle package.
 *
 * (c) Quang Tran <quang.tran@aseagle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aseagle\Bundle\AdminBundle\Event;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * LocaleListener
 *
 * @author Quang Tran <quang.tran@aseagle.com>
 */
class LocaleListener implements EventSubscriberInterface {
    
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * Constructor
     * 
     * @param string $entityManager
     */
    public function __construct($entityManager = null, $locale = null) {
        $this->entityManager = $entityManager;
        $this->defaultLocale = $locale;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        if (! $request->hasPreviousSession()) {
            return;
        }
        
        if (($locale = $request->get('_locale'))) {
            $request->getSession()->set('_locale', $locale);
            $request->setLocale($locale);
        } elseif ($request->getSession()->get('_locale') != null) {
            $request->setLocale($request->getSession()->get('_locale'));
        } else {
            $request->setLocale($this->defaultLocale);
            $request->getSession()->set('_locale', $this->defaultLocale);
        }
    }

    /**
     * @return multitype:multitype:multitype:string number   
     */
    static public function getSubscribedEvents() {
        return array ( 
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array ( 
                array ( 
                    'onKernelRequest', 
                    17 
                ) 
            ) 
        );
    }
}
?>
