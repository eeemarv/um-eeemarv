<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Eeemarv\EeemarvBundle\Util\UniqueIdGenerator;


class MessageType extends AbstractType
{

	/**
	 * @var UniqueIdGenerator
	 **/
	
	private $uniqueIdGenerator;
	
	
	public function __construct(UniqueIdGenerator $uniqueIdGenerator)
	{
		$this->uniqueIdGenerator = $uniqueIdGenerator;
	}


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		//	->add('type', 'eeemarv_offer_want_type')  /// -> new datamodel ! (to do)
		/*	->add('images', 'collection', array(
				'allow_add' => true,
				'allow_delete' => true,
				'data' => 'Eeemarv\EeemarvBundle\Entity\MessageImage',
				'type' => 'image',
				'options' => array(
					'required' => false,
					'attr' => array()
					
					
				))) */
            ->add('subject')
  			->add('category', 'entity', array(
				'class' => 'EeemarvBundle:Category',
				'empty_value' => '',
				'empty_data' => null,
				'property' => 'identedName',
				'query_builder' => function(EntityRepository $er) {
						return $er->createQueryBuilder('c')->orderBy('c.left', 'ASC');
					},
				))                    
            ->add('content', 'eeemarv_ckeditor_type', array(
				'config_name' => 'eeemarv_message',
				))
            ->add('price', 'eeemarv_amount_type', array(
				'required' => false))
            ->add('uniqueId', 'hidden', array(
				'data' => $this->uniqueIdGenerator->generate(),
				))
			->add('create', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Eeemarv\EeemarvBundle\Entity\Message'
        ));
    }

    public function getName()
    {
        return 'eeemarv_message_type';
    }
}
