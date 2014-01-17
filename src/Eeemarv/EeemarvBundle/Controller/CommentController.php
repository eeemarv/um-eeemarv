<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Eeemarv\EeemarvBundle\Entity\Comment;
use Eeemarv\EeemarvBundle\Entity\User;
use Eeemarv\EeemarvBundle\Entity\Message;

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


class CommentController extends Controller
{
	private $em;
	private $repo;

     /**
     * @DI\InjectParams({"em" = @DI\Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em)
    {
        $this->em = $em;
		$this->repo = $em->getRepository('EeemarvBundle:Comment');
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/comments/message/{id}")
     * @Method({"POST"})
     * @ParamConverter
     */
    public function createAction(Request $request, Message $message)
    {			
        $comment  = new Comment();
        $form = $this->createForm('eeemarv_comment_type', $comment);
        $form->bind($request);
		$lastCommentAt = $form->get('lastCommentAt')->getData();
	
        if ($form->isValid()) {	
			$comment->setMessage($message);			
			$comment->setContent(strip_tags($comment->getContent()));
            $this->em->persist($comment);
            $message->setCommentCount($message->getCommentCount() + 1);
            $message->setLastCommentAt($comment->getCreatedAt());
            $this->em->persist($message);            
            $this->em->flush();
                        
			$comment = new Comment();
			$comment->setMessage($message);
			$form = $this->createForm('eeemarv_comment_type', $comment);                    
        }
        
 		$comments = $this->repo->findAfter($lastCommentAt, $message);
 		$lastCommentAt = ($message->getLastCommentAt()) ? $message->getLastCommentAt() : $lastCommentAt;
 		$form->get('lastCommentAt')->setData($lastCommentAt);	
        return $this->render('EeemarvBundle:Comment:new.html.twig',array(
			'message' => $message,
			'comments' => $comments, 
            'form'   => $form->createView(),
        ));        
    }
    

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/comments/message/{id}")
     * @Method({"GET"}) 
     * @ParamConverter
     */
    public function newAction(Request $request, Message $message)
    {
		$comments = $this->repo->findAfter($message->getCreatedAt(), $message);			
        $comment = new Comment();
		$comment->setMessage($message);
		$form = $this->createForm('eeemarv_comment_type', $comment);
		$lastCommentAt = ($message->getLastCommentAt()) ? $message->getLastCommentAt() : new \DateTime;
		$form->get('lastCommentAt')->setData($lastCommentAt);
		
        return array(
			'message' => $message,
			'comments' => $comments,
            'form'   => $form->createView(),
		);
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * Route("/comments/{id}/edit", requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function editAction(Request $request, Comment $comment)
    {
        $form = $this->createForm('eeemarv_comment_type', $comment);

        return array(
            'comment'      => $comment,
            'form'   => $form->createView(),
        );
    }

    
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/comments/{id}", requirements={"id" = "\d+"})
     * @Method({"DELETE"})
     * @ParamConverter  
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $form = $this->createDeleteForm($comment);
        $form->bind($request);

        if ($form->isValid()) {
			
            $this->em->remove($comment);
            $this->em->flush();
 			$request->getSession()->getFlashBag()->add('success', 'flash.success.delete.message');           
        }

        return $this->redirect($this->generateUrl('eeemarv_eeemarv_message_index'));
    }
    
  
    
    

    /**
     * @param Comment $id 
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder(array('id' => $comment->getId()))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }       
}
