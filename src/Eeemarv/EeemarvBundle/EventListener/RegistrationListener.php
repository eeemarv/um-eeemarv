<?php


namespace Eeemarv\EeemarvBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Eeemarv\EeemarvBundle\Manager\UserManager;

class RegistrationListener implements EventSubscriberInterface
{
	/*
	* @var UserManager
	*/
    private $userManager;
    

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        );
    }
    
    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
		$user->setUsername($this->userManager->presetUsername($user));
		$user->setCode($this->userManager->presetCode($user));		
		$user->setLocale($event->getRequest()->getLocale());
    }    
}
