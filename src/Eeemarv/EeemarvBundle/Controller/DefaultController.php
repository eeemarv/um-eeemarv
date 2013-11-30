<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Eeemarv\EeemarvBundle\Entity\MessageTranslation;


//@
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


class DefaultController extends Controller
{
	
     /**
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     * @Template
     * @Route("/contact")
     * @Method({"GET", "POST"})
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm('eeemarv_contact_type');
        
        if ($request->isMethod('POST')){
			$form->bind($request);
			
			if ($form->isValid()) {
				$message = \Swift_Message::newInstance()
					->setSubject($form->get('subject')->getData())
					->setFrom($form->get('email')->getData())
					->setTo('arcencielregenboog@yahoo.co.uk')
					->setBody($this->renderView('EeemarvBundle:Mail:contact.html.twig', array(
								'ip' => $request->getClientIp(),
								'message' => $form->get('message')->getData(),
						)));
				$this->get('mailer')->send($message);
				$request->getSession()->getFlashBag()->add('success', 'flash.success.contact');
				return $this->redirect($this->generateUrl('eeemarv_eeemarv_default_contact'));
			}
		}

        return array('form' => $form->createView());
    }	
	
	
	
	
     /**
     * @Secure(roles="IS_AUTHENTICATED_ANONYMOUSLY")
     * @Template
     * @Route("/", defaults={"slug"="home"})
     * @Route("/{slug}")
     * @Method({"GET"})
     * ParamConverter("messageTranslation", options{"mapping":{"_locale":"locale", "slug", "slug"}})
     */		
	
    public function pageAction(MessageTranslation $messageTranslation)
    {
		
	
        return array('messageTranslation' => $messageTranslation);
    }
 
 
  



}



  

