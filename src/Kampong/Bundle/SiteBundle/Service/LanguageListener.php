<?php

namespace Kampong\Bundle\SiteBundle\Service;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LanguageListener implements EventSubscriberInterface
{
    private $_container;

    public function __construct($container = null)
    {
        $this->_container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {   
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }   
        
        $langRepo = $this->_container->get('backend')->getLanguageManager()->getRepository();
        if (($locale = $request->get('_locale'))) {
            $request->getSession()->set('_locale', $locale);
            $request->setLocale($locale);

            $language = $langRepo->findOneBy(array('code' => $locale));
            $request->getSession()->set('language', $language);
        } elseif ($request->getSession()->get('_locale') != null) {
            $request->setLocale($request->getSession()->get('_locale'));
        } else {                
            $language = $langRepo->findOneBy(array('isDefault' => true));
            $request->setLocale($language->getCode());

            $request->getSession()->set('_locale', $language->getCode());
            $request->getSession()->set('language', $language);                
        }   
    }
    static public function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }

    public function getLanguages()
    {

        $langRepo = $this->_container->get('backend')->getLanguageManager()->getRepository();
        return $langRepo->findBy(array('active' => true), array('isDefault' => 'DESC'));
    }
}

