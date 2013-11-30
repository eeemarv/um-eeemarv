<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * Secure(roles="ROLE_USER")
     * @Template
     * @Route("/users")
     * @Method({"GET"})
     */

    public function indexAction()
    {
        $entities = $this->repo->findAll();

        return array(
            'entities' => $entities,
		);
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
        return array(
            'user'      => $user,
            );
    }





    /**
     * Creates a new User entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function createAction(Request $request)
    {
        $entity  = new User();
        $form = $this->createForm(new UserType(), $entity);    
        $form->bind($request);

        if ($form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirect($this->generateUrl('eeemarv_eeemarv_user_show', array('code' => $entity->getCode())));
        }

        return $this->render('EeemarvBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new User entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createForm(new UserType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
            'delete_form' => $deleteForm->createView(),        
            ));
    }

    /**
     * Displays a form to edit an existing User entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function editAction($code)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EeemarvBundle:User')->findOneByCode($code);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $deleteForm = $this->createDeleteForm($code);

        return $this->render('EeemarvBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing User entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function updateAction(Request $request, $code)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EeemarvBundle:User')->findOneByCode($code);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($code);
        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eeemarv_users_edit', array('code' => $code)));
        }

        return $this->render('EeemarvBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Request $request, $code)
    {
        $form = $this->createDeleteForm($code);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EeemarvBundle:User')->findOneByCode($code);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eeemarv_users'));
    }
    
    
     /**
     * Lists all User entities.
     * @Secure(roles="ROLE_USER")
     */

    public function jsonAction($code)
    {
		if ($code){		
			return $this->remoteAction($code);
		}	

        $em = $this->getDoctrine()->getManager();

        $ajaxUserArray = $em->getRepository('EeemarvBundle:User')->findAjaxUsers();
        
        return new JsonResponse($ajaxUserArray);
    }   

    /**
     * Creates a form to delete a User entity by code
     *
     * @param mixed $code
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($code)
    {
        return $this->createFormBuilder(array('code' => $code))
            ->add('code', 'hidden')
            ->getForm()
        ;
    }
}
