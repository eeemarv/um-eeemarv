<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Eeemarv\EeemarvBundle\Entity\Transaction;
use Eeemarv\EeemarvBundle\Form\TransactionType;
use Eeemarv\EeemarvBundle\Entity\User;
use Eeemarv\EeemarvBundle\Entity\Message;

//@
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


class TransactionController extends Controller
{
	private $em;
	private $repo;

     /**
     * @DI\InjectParams({"em" = @DI\Inject("doctrine.orm.entity_manager")})
     */
    public function __construct($em)
    {
        $this->em = $em;
		$this->repo = $em->getRepository('EeemarvBundle:Transaction');
    }

	
    /**
     * Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions")
     * Route("/transactions/page/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1}) 
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
		$query = $this->em->createQueryBuilder()
			->select('t')
			->from('EeemarvBundle:Transaction', 't')
			->where('t.parent is null')
			->getQuery();
		$paginator = $this->get('knp_paginator');
		$page = $request->query->get('page', 1);		
		$pagination = $paginator->paginate($query, $page, 25, array(
			'defaultSortFieldName' => 't.transactionAt',
			'defaultSortDirection' => 'desc',
			));			
        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions/user/{code}")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function userAction(Request $request, User $user)
    {
		$query = $this->em->createQueryBuilder()
			->select('t')
			->from('EeemarvBundle:Transaction', 't')
			->where('t.parent is null')
			->andWhere('t.toUser = :user or t.fromUser = :user') 
			->setParameter('user', $user)
			->getQuery();
		$paginator = $this->get('knp_paginator');
		$page = $request->query->get('page', 1);		
		$pagination = $paginator->paginate($query, $page, 25, array(
			'defaultSortFieldName' => 't.transactionAt',
			'defaultSortDirection' => 'desc',
			));	

        return $this->render('EeemarvBundle:Transaction:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
    
     /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions/message/{id}")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function messageAction(Request $request, Message $message)
    {
		$query = $this->em->createQueryBuilder()
			->select('t')
			->from('EeemarvBundle:Transaction', 't')
			->where('t.parent is null')
			->andWhere('t.message = :message') 
			->setParameter('message', $message)
			->getQuery();
		$paginator = $this->get('knp_paginator');
		$page = $request->query->get('page', 1);		
		$pagination = $paginator->paginate($query, $page, 25, array(
			'defaultSortFieldName' => 't.transactionAt',
			'defaultSortDirection' => 'desc',
			));

        return $this->render('EeemarvBundle:Transaction:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }   
     



    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions/new")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $transaction  = new Transaction();
        $form = $this->createForm('eeemarv_transaction_type', $transaction);
        $form->bind($request);

        if ($form->isValid()) {

            $user = $this->getUser();
            $transaction->setFromUser($user);
            $transaction->setFromCode($user->getCode());
            $transaction->setFromName($user->getUsername());
            $userRepo = $this->em->getRepository('EeemarvBundle:User');     
            $toUser = $userRepo->findOneBy(array('code' => $transaction->getToCode()));
            // exception
            
            
            $distance = $userRepo->getDistance($user, $toUser);
			$transaction->setDistance($distance);
            $transaction->setToName($toUser->getUsername());
            $transaction->setToUser($toUser);
            $transaction->setConfirmedAt(new \DateTime());
            $transaction->setConfirmed(true);      
            $user->setBalance($user->getBalance() - $transaction->getAmount());
            $toUser->setBalance($toUser->getBalance() + $transaction->getAmount());
            $this->em->persist($user);
            $this->em->persist($toUser);
            $this->em->persist($transaction);        
            $this->em->flush();                                 
			$request->getSession()->getFlashBag()->add('success', 'flash.success.new.transaction');
            return $this->redirect($this->generateUrl('eeemarv_eeemarv_transaction_index'));
        }

        return $this->render('EeemarvBundle:Transaction:new.html.twig', array(
       //     'transaction' => $transaction,
            'form'   => $form->createView(),
			));
    }

    /**
     * Displays a form to create a new Transaction entity.
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions/new")
     * @Method({"GET"})
     */
    public function newAction()
    {
        $transaction = new Transaction();
        $form = $this->createForm('eeemarv_transaction_type', $transaction);

        return array(
//            'transaction' => $transaction,
            'form'   => $form->createView(),
        );
    }
    
     /**
     * Displays a form to create a new Transaction entity with message and user preset.
     * Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions/new/message/{id}")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function newMessageAction(Request $request, Message $message)
    {
        $toUser = $message->getUser();		
        $transaction = new Transaction();
        if ($message->getIsOffer() && $toUser != $this->getUser() && $toUser->getIsActive()){   
			$transaction->setToCode($message->getUser()->getCode());
		}     
        $transaction->setDescription($message->getSubject().' (#'.$message->getId().')');
        $transaction->setMessage($message);
        $transaction->setAmount($message->getPrice());
           
        $form = $this->createForm('eeemarv_transaction_type', $transaction);

        return $this->render('EeemarvBundle:Transaction:new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }   
    
      /**
     * Displays a form to create a new Transaction entity with user preset.
     * Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions/new/user/{code}")
     * @Method({"GET"})
     * @ParamConverter
     */
    public function newUserAction(Request $request, User $user)
    {		
		if ($user == $this->getUser()){   
			$request->getSession()->getFlashBag()->add('alert', 'flash.warning.transaction.new_user_same');
			return $this->redirect($this->generateUrl('eeemarv_eeemarv_transaction_new')); 
		}	
		
        $transaction = new Transaction();
		$transaction->setToCode($user->getCode()); 
		       
        $form = $this->createForm('eeemarv_transaction_type', $transaction);
       
        return $this->render('EeemarvBundle:Transaction:new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }    
    
    

    /**
     * Finds and displays a Transaction entity.
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions/{id}", requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @ParamConverter 
     */
    public function showAction(Transaction $transaction)
    {
        return array(
            'transaction'      => $transaction,       
            );
    }

}
