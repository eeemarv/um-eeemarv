<?php

namespace Eeemarv\EeemarvBundle\Form\EventListener;


use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContext;


class TransactionEventListener implements EventSubscriberInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @var EntityManager
     */
    private $em;
    
    /**
     * @var SecurityContext
     */
    private $securityContext;    
    

    /**
     * @param factory FormFactoryInterface
     * @param em EntityManager
     * @param securityContext SecurityContext
     */
    public function __construct(FormFactoryInterface $factory, EntityManager $em, SecurityContext $securityContext)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => 'preSubmit',
        );
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $toCode = $data['toCode'];       
        
        
        $toCode = strtolower($toCode);
        $toCode = trim($toCode);
        list($toCode) = explode(' ', $toCode);
        $toCodeArray = explode('/', $toCode);

		if (sizeof($toCodeArray) == 0) {
			$msg = 'The code is empty.';
            throw new \Exception(sprintf($msg, $toCode));
        }		

        $toUser = $this->em
            ->getRepository('EeemarvBundle:User')
            ->findByCode($toCodeArray[0]);

        if (!$toUser) {
            $msg = 'The user with code %s could not be found.';
            throw new \Exception(sprintf($msg, $toCode));
        }
        
        
        if (!$toUser->getActivated()) {
            $msg = 'The user with code %s is not Active.';
            throw new \Exception(sprintf($msg, $toCode));
        }

		// todo external users check
		

        $form = $event->getForm();		

		$form
            ->add('toName', 'hidden', array(
				'data' => $toUser->getUsername(),
				))           
             ->add('toUser', 'hidden', array(
				'data' => $toUser->getId(),
				));	

		
		return $event;
    }

				



}
