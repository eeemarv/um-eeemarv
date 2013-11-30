<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Eeemarv\EeemarvBundle\Entity\Message;
use Eeemarv\EeemarvBundle\Entity\MessageTranslation;

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
     * @Secure(roles="ROLE_ANONYMOUS")
     * @Template
     * @Route("/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function publicPageAction(Request $request, Message $message)
    {
/*		$query = $this->em->createQuery('select m, mt from EeemarvBundle:MessageTranslation mt
					join mt.message m
					where mt.id = :id and mt.locale = :locale') // and  m.published = 1
				->setParameter('id', $id)
				->setParameter('locale', $request->getLocale());
		try {
			return $query->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}		
*/


        $entity = $this->repo->findOneBy(array('id' => $id, 'locale' => $request->getLocale()));
 
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Message entity.');
        }       
        

        return array(
            'entity' => $message,
        );
    }



    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $entities = $this->repo->findBy(array(), array('createdAt' => 'desc'));

        return array(
            'entities' => $entities,
        );
    }

    
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/new")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $entity  = new Message();
        $form = $this->createForm('eeemarv_message_type', $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setUser($this->getUser());
            					

            $this->em->persist($entity);
            $this->em->flush();          

            return $this->redirect($this->generateUrl('eeemarv_eeemarv_messages_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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

        $entity = new Message();
        $form   = $this->createForm('eeemarv_message_type', $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
		);
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function showAction(Request $request, Message $message)
    {
        $deleteForm = $this->createDeleteForm($message);

        return array(
            'entity'      => $message,
            'delete_form' => $deleteForm->createView()
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
        $editForm = $this->createForm('eeemarv_message_type', $message);
        $deleteForm = $this->createDeleteForm($message->getId());

        return array(
            'entity'      => $message,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/messages/{id}", requirements={"id" = "\d+"})
     * @Method({"POST"})   
     * (change to update method)
     * @ParamConverter 
     */
    public function updateAction(Request $request, Message $message)
    {
        $deleteForm = $this->createDeleteForm($message);
        $editForm = $this->createForm('eeemarv_message_type', $message);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->em->persist($message);
            $this->em->flush();

            return $this->redirect($this->generateUrl('eeemarv_messages_edit', array('id' => $message->getId())));
        }

        return array(
            'entity'      => $message,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
			
            $this->em->remove($entity);
            $this->em->flush();
        }

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_messages_index'));
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
