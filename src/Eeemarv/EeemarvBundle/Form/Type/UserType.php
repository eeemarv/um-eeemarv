<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Eeemarv\EeemarvBundle\Util\UniqueIdGenerator;

 
 
class UserType extends AbstractType
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
            ->add('uniqueId', 'hidden', array(
				'data' => $this->uniqueIdGenerator->generate(),
				))
            ->add('plainPassword', 'hidden', array(
				'data' => $this->uniqueIdGenerator->generate(),
				))				        
        	->add('email', 'email')
        	->add('code')
        	->add('username')		
        	->add('firstName')
        	->add('lastName')
        	->add('gender', 'eeemarv_gender_type', array(
				'expanded' => true,
				))
        	->add('birthday', 'birthday', array(
				'empty_value' => '',
				'empty_data' => null))                 	
        	->add('street')
        	->add('houseNumber')
        	->add('box', 'text', array(
				'required' => false))	
       		->add('place', 'entity', array(
       			'class' => 'EeemarvBundle:Place',
				'empty_value' => '',
				'empty_data' => null)) 			
			->add('phone', 'text', array(
				'required' => false))
			->add('mobile', 'text', array(
				'required' => false))
			->add('website', 'url', array(
				'required' => false))
			->add('isActive', 'checkbox', array(
				'required' => false));
    }

    public function getName()
    {
        return 'eeemarv_user_type';
    }
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Eeemarv\EeemarvBundle\Entity\User',
			'validation_groups' => array('new')
		));
	}    
}
