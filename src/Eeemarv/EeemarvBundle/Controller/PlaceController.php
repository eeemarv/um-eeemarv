<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Eeemarv\EeemarvBundle\Entity\Place;
use Eeemarv\EeemarvBundle\Form\PlaceType;

//@
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


class PlaceController extends Controller
{
	private $em;
	private $repo;

    /**
     * @DI\InjectParams({"em" = @DI\Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em)
    {
        $this->em = $em;
		$this->repo = $em->getRepository('EeemarvBundle:Place');
    }		
	

    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/places")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $entities = $this->repo->findAll();
        return array('entities' => $entities);
    }


    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/places")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $place  = new Place();
        $form = $this->createForm('eeemarv_place_type', $place);
        if ($request->isMethod('POST')){
			$form->bind($request);

			if ($form->isValid()) {
				$this->em->persist($place);
				$this->em->flush();
				$request->getSession()->getFlashBag()->add('success', 'flash.success.create.place');
				return $this->redirect($this->generateUrl('eeemarv_eeemarv_place_index'));
			}
		}
        return $this->render('EeemarvBundle:Place:new.html.twig', array(
            'entity' => $place,
            'form'   => $form->createView(),
        ));
    }
    
    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/places/new")
     * @Method({"GET"})
     */
    public function newAction()
    {
        $place = new Place();
        $form   = $this->createForm('eeemarv_place_type', $place);
        return array(
            'entity' => $place,
            'form'   => $form->createView(),
			);
    }

    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/places/{id}", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @ParamConverter
     */
    public function showAction(Place $place)
    {
        return array(
            'entity'      => $place,        
            );
    }






    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/places/{id}/edit", requirements={"id"="\d+"})
     * @Method({"GET"})
     * @ParamConverter
     */
    public function editAction(Request $request, Place $place)
    {
        $form = $this->createForm('eeemarv_place_type', $place);

        return array(
            'place'      => $place,
            'form'        => $form->createView(),
        );
    }

    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/places/{id}", requirements={"id"="\d+"})
     * @Method({"PUT"})
     * @ParamConverter
     */
    public function updateAction(Request $request, Place $place)
    {
        $form = $this->createForm('eeemarv_place_type', $place);
        $form->bind($request);

        if ($form->isValid()) {
            $this->em->persist($place);
            $this->em->flush();
			$request->getSession()->getFlashBag()->add('success', 'flash.success.edit.place');
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_place_index'));
        }
        
        return $this->render('EeemarvBundle:Place:edit.html.twig', array(
            'place'      => $place,
            'edit_form'   => $form->createView(),
        ));
    }






    /**
     * Deletes a Place entity.
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/places/{id}", requirements={"id"="\d+"})
     * @Method({"DELETE"})
     * @ParamConverter
     */
    public function deleteAction(Request $request, Place $place)
    {
        $form = $this->createDeleteForm($place->getId());
        $form->bind($request);

        if ($form->isValid()) {

            $em->remove($place);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_place_index'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
