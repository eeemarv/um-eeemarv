<?php

namespace Eeemarv\EeemarvBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Eeemarv\EeemarvBundle\Entity\Transaction;
use Eeemarv\EeemarvBundle\Form\TransactionType;

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
		$this->repo = $em->getRepository('EeemarvBundle:Message');
    }

	
    /**
     * @Secure(roles="ROLE_USER")
     * @Template
     * @Route("/transactions")
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
     * @Secure(roles="ROLE_USER")
     */
    public function createAction(Request $request)
    {
        $entity  = new Transaction();
        $form = $this->createForm('eeemarv_transaction_type', $entity);
        $form->bind($request);

        if ($form->isValid()) {

            $user = $this->getUser();
            $entity->setFromUser($user);
            $entity->setFromCode($user->getCode());
            $entity->setFromName($user->getUsername());
            $toUser = $this->em->getRepository('EeemarvBundle:User')->findOneByCode($entity->getToCode());
            $entity->setToName($toUser->getUsername());
            $entity->setToUser($toUser);
            $user->setBalance($user->getBalance() - $entity->getAmount());
            $toUser->setBalance($toUser->getBalance() + $entity->getAmount());
            $this->em->persist($user);
            $this->em->persist($toUser);
            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirect($this->generateUrl('eeemarv_transactions_show', array('id' => $entity->getId())));
        }

        return $this->render('EeemarvBundle:Transaction:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Transaction entity.
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $entity = new Transaction();
        $form   = $this->createForm('lets_transact_transactiontype', $entity);

        return $this->render('EeemarvBundle:Transaction:new.html.twig', array(
            'entity' => $entity,
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
            'entity'      => $transaction,       
            );
    }

}
