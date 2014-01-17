<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Eeemarv\EeemarvBundle\Form\EventListener\TransactionEventListener;
use Eeemarv\EeemarvBundle\Util\UniqueIdGenerator;


class TransactionType extends AbstractType
{

	/**
	 * @var TransactionEventListener
	 **/

	private $transactionEventListener;
	
	/**
	 * @var UniqueIdGenerator
	 **/
	
	private $uniqueIdGenerator;
	
	
	public function __construct(TransactionEventListener $transactionEventListener, UniqueIdGenerator $uniqueIdGenerator)
	{
		$this->transactionEventListener = $transactionEventListener;
		$this->uniqueIdGenerator = $uniqueIdGenerator;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {					
        $builder
			->add('message', 'hidden_entity', array(
				'class' => 'Eeemarv\EeemarvBundle\Entity\Message',
				'required' => false,
				))
            ->add('toCode', 'eeemarv_code_type')				         
            ->add('amount', 'eeemarv_amount_type')
            ->add('description')
            ->add('uniqueId', 'hidden', array(
				'data' => $this->uniqueIdGenerator->generate(),
				))
			->add('create', 'submit');
 //           ->addEventListener(
		//		'',
			//	array($this->transactionEventListener, 'method'),
				//)//////////////////////////////////////////////////////////////
            

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eeemarv\EeemarvBundle\Entity\Transaction',
			'validation_groups' => array('new'),            
        ));    
    }

    public function getName()
    {
        return 'eeemarv_transaction_type';
    }
}
