<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{


	private $inlineTemplate = false;

	/**  to be called in template **/

    public function inlineLoginAction(Request $request)
    {
		$this->inlineTemplate = true;
		return $this->loginAction($request);	
	}

    protected function renderLogin(array $data)
    {
		$template = ($this->inlineTemplate) ? 'EeemarvBundle:Security:inline_login.html.' : 'FOSUserBundle:Security:login.html.';
        $template = $template . $this->container->getParameter('fos_user.template.engine');
        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
