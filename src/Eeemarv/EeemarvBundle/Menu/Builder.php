<?php

namespace Eeemarv\EeemarvBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Security\Core\SecurityContext;


class Builder
{

    private $factory;
    private $translator;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, Translator $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }	
   
    public function langMenu(Request $request, $locales)
    {		
        $menu = $this->factory->createItem('root');
        
		$current_locale = $request->getLocale();
		
		$route = $request->attributes->get('_route');
        $routeParams = $request->attributes->get('_route_params');

		$item = $menu->addChild('language')->setLabel($this->translator->trans('language_name'))->setCurrent(true);
			
		foreach ($locales as $locale)
		{
			$current = ($current_locale === $locale) ? true : false;
			$item->addChild($locale, array('route' => $route, 'routeParameters' => array_merge($routeParams, array('_locale' => $locale)))) //'org_locale' => $current_locale
				->setLabel($this->translator->trans('language_name', array(), 'messages', $locale))
				->setCurrent($current);
		}	
		
        return $menu;
    }    

    public function personalMenu(Request $request, SecurityContext $securityContext)
    {
        $menu = $this->factory->createItem('root');
        
        $user = $securityContext->getToken()->getUser();
      
        $userName = $user->getUsername();
        $code = $user->getCode();

		$item = $menu->addChild('personal', array('route' => 'fos_user_security_logout'))
			->setLabel($code.' '.$userName);
		$item->addChild('profile', array('route' => 'fos_user_profile_show'))
			->setLabel($this->translator->trans('profile'));
		$item->addChild('dl', array('attributes' => array('divider' => true)));					
		$item->addChild('logout', array('route' => 'fos_user_security_logout'))
			->setLabel($this->translator->trans('logout'));		
		

        return $menu;
    } 



    public function userMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');


		$menu->addChild('messages', array('route' => 'eeemarv_eeemarv_message_index'))
			->setLabel($this->translator->trans('messages'));
		$menu->addChild('members', array('route' => 'eeemarv_eeemarv_user_index'))
			->setLabel($this->translator->trans('users'));
		$menu->addChild('transactions', array('route' => 'eeemarv_eeemarv_transaction_index'))
			->setLabel($this->translator->trans('transactions'));			

        return $menu;
    } 
 
 
    
    public function adminMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('register', array('route' => 'fos_user_registration_register'))->setLabel('.icon_fa-pencil '.$translator->trans('register'))->setCurrent(false);        
        $menu->addChild('login', array('route' => 'fos_user_security_login'))->setLabel('.fa-star '.$translator->trans('login'))->setCurrent(true);

        return $menu;
    }
    
    public function publicMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        
        $menu->setCurrentUri($request->getRequestUri()); //
        
		$menu->addChild('login', array('route' => 'fos_user_security_login'))
			->setLabel($this->translator->trans('login'));
        $menu->addChild('register', array('route' => 'fos_user_registration_register'))
			->setLabel($this->translator->trans('registration'));
		$menu->addChild('contact', array('route' => 'eeemarv_eeemarv_default_contact'))
			->setLabel($this->translator->trans('contact'));


        return $menu;
    }    
    
    
       
    
  
}
