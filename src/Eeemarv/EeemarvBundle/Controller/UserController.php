<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Eeemarv\EeemarvBundle\Entity\User;
use Eeemarv\EeemarvBundle\Form\UserType;

//@
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;




 

class UserController extends Controller
{
	private $em;
	private $repo;

     /**
     * @DI\InjectParams({"em" = @DI\Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em)
    {
        $this->em = $em;
		$this->repo = $em->getRepository('EeemarvBundle:User');
    }

     /**
     * Lists all Active User entities for prefetch typeahead.
     * @Secure(roles="ROLE_USER")
     * @Route("/users/typeahead")
     * @Method({"GET"})
     */

    public function typeaheadAction()
    {

        $userRows = $this->repo->findTypeaheadUsers();

		$newUserTime = time() - 86400 * $this->container->getParameter('new_user_days');
        
        foreach ($userRows as &$userRow){
			$userRow['a'] = ($userRow['a'] && date_timestamp_get($userRow['a']) > $newUserTime) ? 1 : 0;
			$userRow['le'] = ($userRow['le']) ? 1 : 0;
			$userRow['s'] = ($userRow['s']) ? 1 : 0;
			$userRow['e'] = ($userRow['e']) ? 1 : 0;						
		}	

        return new JsonResponse($userRows);
    }  
    
     public function jsonAction()
    {

   //     $users = $this->repo->findAjaxUsers();
        
   //     return new JsonResponse($users);
        return null;
    }








    /**
     * Secure(roles="ROLE_USER")
     * @Template
     * @Route("/users")
     * @Method({"GET"})
     */

    public function indexAction(Request $request)
    {
		$user = $this->getUser();
    /*    $query = $this->repo->getGeoQuery($user->getLatitude(), $user->getLongitude());
		$paginator = $this->get('knp_paginator');
		$page = $request->query->get('page', 1);
		$pagination = $paginator->paginate($query, $page, 10);        

        return array(
            'pagination' => $pagination,
		); */
		$users = $this->repo->findGeo($user->getLatitude(), $user->getLongitude());
        return array(
            'users' => $users,
		);	
    }


    /**
     * Displays a form to create a new User entity.
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/users/new")
     * @Method({"GET"})
     */
    public function newAction()
    {
        $user = new User();
        $form   = $this->createForm('eeemarv_user_type', $user);

        return array(
            'user' => $user,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new User entity.
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/users")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $user  = new User();
        $form = $this->createForm('eeemarv_user_type', $user);    
        $form->bind($request);

        if ($form->isValid()) {
			if ($user->getIsActive()){
				$user->setActivatedBy($this->getUser());				
				$user->setActivatedAt(new \DateTime());
			}
			$user->setLocale($request->getLocale());
            $this->em->persist($user);
            $this->em->flush();
			$request->getSession()->getFlashBag()->add('success', 'flash.success.create.user');
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_user_show', array('code' => $user->getCode())));
        }

        return $this->render('EeemarvBundle:User:new.html.twig', array(
            'user' => $user,
            'form'   => $form->createView(),
        ));
    }







    /**
     * Lists all active User entities.
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/users/active")
     * @Method({"GET"})
     * 
     */
    public function activeAction()
    {
		$user= $this->getUser(); //$this->get('security.context')->getToken()->getUser();
		
        $memebers = $em->getRepository('EeemarvBundle:User')->findMembers($user->getLatitude(), $user->getLongitude());

        return array(
            'members' => $members,
        );
    }



    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/users/{code}")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function showAction(User $user)
    {
		$deleteForm = $this->createDeleteForm($user);
		
        return array(
            'user'      => $user,
            'delete_form' => $deleteForm->createView(),
            );
    }




    /**
     * Finds and displays a User entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function show2Action($code) /// merge show and show2
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EeemarvBundle:User')->findOneByCode($code);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($slug);

        return $this->render('EeemarvBundle:User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $this->deleteForm->createView(),        
            ));
    }

    /**
     * Displays a form to edit an existing User entity.
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/users/{code}/edit")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function editAction(User $user)
    {
        $form = $this->createForm('eeemarv_user_type', $user);

        return array(
            'user'      => $user,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/users/{code}/edit")
     * @Method({"PUT"})
     * @ParamConverter
     */
    public function updateAction(Request $request, User $user)
    {
        $form = $this->createForm(new UserType(), $user);
        $form->bind($request);

        if ($editForm->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('eeemarv_eeemarv_user_show', array('code' => $code)));
        }

        return $this->render('EeemarvBundle:User:edit.html.twig', array(
            'user'      => $user,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/users/{code}")
     * @Method({"DELETE"})
     * @ParamConverter
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user->getCode());
        $form->bind($request);

        if ($form->isValid()) {
            $em->remove($user);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_user_index'));
    }
    
    
     
    
     

    /**
     * Creates a form to delete a User entity by code
     *
     * @param mixed $code
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder(array('code' => $user->getCode()))
            ->add('code', 'hidden')
            ->getForm()
        ;
    }
}
