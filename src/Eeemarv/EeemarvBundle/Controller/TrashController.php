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


class TrashController extends Controller
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
     * @Secure(roles="ROLE_ADMIN")
     * @Template
     * @Route("/trash")
     * @Method({"GET"}) 
     * @ParamConverter
     */
    public function indexAction(Request $request)
    {
		$comments = $this->repo->findBy(array('message' => $message), array('createdAt' => 'asc'));
		$lastComment = end($comments);
		$lastCommentAt = ($lastComment) ? $lastComment->getCreatedAt() : new \DateTime();			
        $comment = new Comment();
		$comment->setMessage($message);
		$form = $this->createForm('eeemarv_comment_type', $comment);
		$form->get('lastCommentAt')->setData($lastCommentAt);
		
        return array(
			'comments' => $comments,
            'form'   => $form->createView(),
		);
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/trash/user/{code}")
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function editAction(Request $request, User $user)
    {
        $editForm = $this->createForm('eeemarv_comment_type', $comment);
		$comments = array();
        return array(
            'comments'      => $comments,
            'edit_form'   => $editForm->createView(),
        );
    }




       
}
