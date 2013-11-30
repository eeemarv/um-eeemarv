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
     * @Route("/places/new")
     * @Method({"GET"})
     */
    public function createAction(Request $request)
    {
        $entity  = new Place();
        $form = $this->createForm('eeemarv_place_type', $entity);
        if ($request->isMethod('POST')){
			$form->bind($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();

				return $this->redirect($this->generateUrl('eeemarv_eeemarv_place_show', array('id' => $entity->getId())));
			}
		}
        return $this->render('EeemarvBundle:Place:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }



    /**
     * Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/places/{id}", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Place entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm('eeemarv_place_type', $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();
			$request->getSession()->getFlashBag()->add('success', 'flash.success.edit.place');
            return $this->redirect($this->generateUrl('eeemarv_places_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Place entity.
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EeemarvBundle:Place')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Place entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eeemarv_places'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
