<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Eeemarv\EeemarvBundle\Entity\Category;
use Eeemarv\EeemarvBundle\Entity\Message;
use Eeemarv\EeemarvBundle\Entity\MessageTranslation;
use Eeemarv\EeemarvBundle\Entity\User;

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


class MessageController extends Controller
{
	private $em;
	private $repo;

     /**
     * @DI\InjectParams({"em" = @DI\Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em)
    {
        $this->em = $em;
		$this->repo = $em->getRepository('EeemarvBundle:Message');
    }
 
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
		$query = $this->em->createQueryBuilder()
			->select('m, u')
			->from('EeemarvBundle:Message', 'm')
			->join('m.user', 'u')
			->getQuery();
		$paginator = $this->get('knp_paginator');
		$page = $request->query->get('page', 1);		
		$pagination = $paginator->paginate($query, $page, 25, array(
			'defaultSortFieldName' => 'm.createdAt',
			'defaultSortDirection' => 'desc',
			));			
        return array(
            'pagination' => $pagination,
        );
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/user/{code}")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function userAction(Request $request, User $user)
    {
		$query = $this->em->createQueryBuilder()
			->select('m')
			->from('EeemarvBundle:Message', 'm')
			->where('m.user = :user')
			->setParameter('user', $user)
			->getQuery();
		$paginator = $this->get('knp_paginator');
		$page = $request->query->get('page', 1);		
		$pagination = $paginator->paginate($query, $page, 25, array(
			'defaultSortFieldName' => 'm.createdAt',
			'defaultSortDirection' => 'desc',
			));	
        return $this->render('EeemarvBundle:Message:index.html.twig', array(
			'user'		=> $user,
            'pagination' => $pagination,
        ));
    }    
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/category/{id}")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function categoryAction(Request $request, Category $category)
    {
		$query = $this->em->createQueryBuilder()
			->select('m')
			->from('EeemarvBundle:Message', 'm')
			->where('m.category = :category')
			->setParameter('category', $category)
			->getQuery();
		$paginator = $this->get('knp_paginator');
		$page = $request->query->get('page', 1);		
		$pagination = $paginator->paginate($query, $page, 25, array(
			'defaultSortFieldName' => 'm.createdAt',
			'defaultSortDirection' => 'desc',
			));	
        return $this->render('EeemarvBundle:Message:index.html.twig', array(
			'category' => $category,
            'pagination' => $pagination,
        ));
    }      
   
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/new")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $message  = new Message();
        $form = $this->createForm('eeemarv_message_type', $message);
        $form->bind($request);

        if ($form->isValid()) {
            $message->setUser($this->getUser());
            $this->em->persist($message);
            $this->em->flush();          
			$request->getSession()->getFlashBag()->add('success', 'flash.success.new.message');
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_message_show', array('id' => $message->getId(), 'slug' => $message->getSlug())));
        }

        return $this->render('EeemarvBundle:Message:new.html.twig',array(
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/new")
     * @Method({"GET"}) 
     * 
     */
    public function newAction()
    {
        $message = new Message();
        return array(
            'form'   => $this->createForm('eeemarv_message_type', $message)->createView(),
		);
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/{id}/edit", requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function editAction(Request $request, Message $message)
    {
        $form = $this->createForm('eeemarv_message_type', $message);

        return array(
            'message'      => $message,
            'form'   => $form->createView(),
        );
    }




    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/{id}/{slug}", requirements={"id" = "\d+"}) 
     *
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function showAction(Request $request, Message $message, $id, $slug)
    {
		if ($slug != $message->getSlug()){
			return $this->redirect($this->generateUrl('eeemarv_eeemarv_message_show', array('id' => $message->getId(), 'slug' => $message->getSlug())));
		}	
				
        return array(
            'message'      => $message,
            'delete_form' => $this->createDeleteForm($message)->createView(),
            'comment_form' => $this->createForm('eeemarv_comment_type')->createView(),
        );
    }
    
     /**
     * @Secure(roles="ROLE_USER")
     * @Route("/messages/{id}", requirements={"id" = "\d+"}) 
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function showIdAction(Request $request, Message $message, $id)
    {
		return $this->redirect($this->generateUrl('eeemarv_eeemarv_message_show', array('id' => $message->getId(), 'slug' => $message->getSlug())));
    }

   
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/{id}", requirements={"id" = "\d+"})
     * @Method({"PUT"})
     * @ParamConverter 
     */
    public function updateAction(Request $request, Message $message)
    {       
        $editForm = $this->createForm('eeemarv_message_type', $message);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->em->persist($message);
            $this->em->flush();
 			$request->getSession()->getFlashBag()->add('success', 'flash.success.update.message');
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_message_show', array('id' => $message->getId())));
        }

        return  $this->render('EeemarvBundle:Message:show.html.twig', array(
            'message'      => $message,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm($message)->createView(),
            'comment_form' => $this->createForm('eeemarv_comment_type')->createView(),
        ));
    }
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/{id}", requirements={"id" = "\d+"})
     * @Method({"DELETE"})
     * @ParamConverter  
     */
    public function deleteAction(Request $request, Message $message)
    {
        $form = $this->createDeleteForm($message);
        $form->bind($request);

        if ($form->isValid()) {
			
            $this->em->remove($message);
            $this->em->flush();
 			$request->getSession()->getFlashBag()->add('success', 'flash.success.delete.message');           
        }

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_message_index'));
    }

    /**
     * @param Message $id 
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Message $message)
    {
        return $this->createFormBuilder(array('id' => $message->getId()))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }       
}
