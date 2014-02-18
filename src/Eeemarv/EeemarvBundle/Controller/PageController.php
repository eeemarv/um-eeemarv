<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Eeemarv\EeemarvBundle\Entity\Page;
use Eeemarv\EeemarvBundle\Entity\PageTranslation;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

//@
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


class PageController extends Controller
{
	private $em;
	private $repo;

     /**
     * @DI\InjectParams({"em" = @DI\Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em)
    {
        $this->em = $em;
		$this->repo = $em->getRepository('EeemarvBundle:Page');
    }
 




    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/pages")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $pages = $this->repo->findBy(array(), array('position' => 'asc'));

        return array(
            'pages' => $pages,
        );
    }
 


    
     /**
     * @Secure(roles="ROLE_ANONYMOUS")
     * @Template
     * @Route("/{slug}")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function showPublicAction(Request $request, Page $page)
    {
		if (!$page->getPublished()){
			throw new AccessDeniedException();		
		}
		
        return array(
            'page' => $page,
        );
    }
 
     /**
     * @Secure(roles="ROLE_ANONYMOUS")
     * @Template
     * @Route("/")
     * @Method({"GET"})
     */
    public function showHomeAction(Request $request)
    {
		$page = new Page();
		
		
        return $this->render('EeemarvBundle:Page:showPublic.html.twig', array(
            'page' => $page,
        ));
    } 
 
 
 
 
 
 
 
    
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/pages/new")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $page  = new Page();
        $form = $this->createForm('eeemarv_page_type', $page);
        $form->bind($request);

        if ($form->isValid()) {
            $this->em->persist($page);
            $this->em->flush();          
			$request->getSession()->getFlashBag()->add('success', 'flash.success.new.page');
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_page_show', array('slug' => $page->getSlug())));
        }

        return $this->render('EeemarvBundle:Page:new.html.twig',array(
//            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/pages/new")
     * @Method({"GET"}) 
     * 
     */
    public function newAction()
    {
        $page = new Page();

        return array(
            'page' => $page,
            'form'   => $this->createForm('eeemarv_page_type', $page)->createView(),
		);
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/{slug}/edit")
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function editAction(Request $request, Page $page)
    {
        $editForm = $this->createForm('eeemarv_page_type', $page);

        return array(
            'page'      => $page,
            'edit_form'   => $editForm->createView(),
        );
    }




   
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/pages/{slug}")
     * @Method({"PUT"})
     * @ParamConverter 
     */
    public function updateAction(Request $request, Page $page)
    {       
        $editForm = $this->createForm('eeemarv_page_type', $page);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->em->persist($page);
            $this->em->flush();
 			$request->getSession()->getFlashBag()->add('success', 'flash.success.update.page');
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_page_show', array('slug' => $page->getSlug())));
        }

        return  $this->render('EeemarvBundle:Page:show.html.twig', array(
            'page'      => $page,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm($page)->createView(),
        ));
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/pages/{slug}")
     * @Method({"DELETE"})
     * @ParamConverter  
     */
    public function deleteAction(Request $request, Page $page)
    {
        $form = $this->createDeleteForm($page);
        $form->bind($request);

        if ($form->isValid()) {
			
            $this->em->remove($page);
            $this->em->flush();
 			$request->getSession()->getFlashBag()->add('success', 'flash.success.delete.page');           
        }

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_page_index'));
    }

    /**
     * Deletes a Category entity.
     * @Secure(roles="ROLE_USER")
     * @ParamConverter 
     */
    public function removeAction(Request $request, Page $page)
    {
		$this->em->remove($page);
		$this->em->flush();
		$request->getSession()->getFlashBag()->add('success', 'flash.success.delete.page'); 
        return $this->redirect($this->generateUrl('eeemarv_eeemarv_page_index'));
    }

	/** 
	 * @Secure(roles="ROLE_USER")
	 * @Route("/pages/{slug}/up")
	 * @ParamConverter
	 */
	public function upAction(Request $request, Page $page)
	{
		$position = $page->getPosition();
		if ($position){
			$page->setPosition($position - 1);
			$this->em->persist($page);		
			$this->em->flush();
			$request->getSession()->getFlashBag()->add('success', 'flash.success.move_up.page');
		} else {
			$request->getSession()->getFlashBag()->add('error', 'flash.error.move_up.page');			
		}	
        return $this->redirect($this->generateUrl('eeemarv_eeemarv_page_index'));
	}	
	
	/** 
	 * @Secure(roles="ROLE_USER")
	 * @ParamConverter 
	 */
	public function downAction(Request $request, Page $page)
	{
		$page->setPosition($page->getPosition()+1);
        $this->em->persist($page);		
		$this->em->flush();		
		$request->getSession()->getFlashBag()->add('success', 'flash.success.move_down.page');
        return $this->redirect($this->generateUrl('eeemarv_eeemarv_page_index'));
	}	






	/** 
	 * Secure(roles="ROLE_ADMIN")
     * @Route("/pages/{slug}/button")
     * @Method({"POST"})
     * 
	 */
	public function buttonAction(Request $request, $slug)
	{	
		if ($request->get('up', null)) {
            return $this->forward('EeemarvBundle:Page:up', array('slug' => $slug));
		}
		if ($request->get('down', null)) {
            return $this->forward('EeemarvBundle:Page:down', array('slug' => $slug));
		}		
		if ($request->get('publish', null)) {
            return $this->forward('EeemarvBundle:Page:publish', array('slug' => $slug));
		}
		if ($request->get('left', null)) {
            return $this->forward('EeemarvBundle:Page:hide', array('slug' => $slug));
		}
		if ($request->get('edit', null)) {
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_page_edit', array('slug' => $slug)));
		}
		if ($request->get('delete', null)) {
            return $this->forward('EeemarvBundle:Page:remove', array('slug' => $slug));
		}

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_category_index'));		
	}


     /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/pages/{slug}")
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function showAction(Request $request, Page $page)
    {
        return array(
			'delete_form' => $this->createDeleteForm($page)->createView(),
            'page' => $page,
        );
    } 






    /**
     * @param Page $slug 
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Page $page)
    {
        return $this->createFormBuilder(array('slug' => $page->getSlug()))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }       
}
