<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller
{  
    public function indexAction()
    {	
        return $this->render('EeemarvBundle::login.html.twig', array());
    }    
    
    public function postAction()
    {		
        return $this->render('EeemarvBundle:Default:index.html.twig');
    }         
}
