<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;
use Eeemarv\EeemarvBundle\Util\UniqueIdGenerator;




class RegistrationFormType extends BaseType
{

	/**
	 * @var UniqueIdGenerator
	 **/
	
	private $uniqueIdGenerator;
	
	/*
	 * @var string
	 * the user class name
	 */ 
	
	private $user;

	public function __construct($user, UniqueIdGenerator $uniqueIdGenerator)
	{
		parent::__construct($user);
		$this->uniqueIdGenerator = $uniqueIdGenerator;
	}	
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
		parent::buildForm($builder, $options);

	//	$builder->get('email')->get('type')->getName();

		$builder				
			->add('username', 'hidden', array(   
				'data' => hash('md5', uniqid('username', true))))
			->add('plainPassword', 'hidden', array(
				'data' => hash('md5', uniqid('plainPassword', true)))) 
			->add('firstName', 'text')
			->add('lastName', 'text')
			->add('gender', 'eeemarv_gender_type', array(
				'expanded' => true,
				))
			->add('birthday', 'birthday', array(
				'empty_value' => '',
				'empty_data' => null))
			->add('street' )
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
			->add('uniqueId', 'hidden', array(
				'data' => $this->uniqueIdGenerator->generate(),
				))	
			->add('recaptcha', 'ewz_recaptcha', array(
				'attr' => array('options' => array('theme' => 'clean')),
				'mapped' => false,
				'constraints'   => array(new True())))
			->add('send', 'submit');
    }

    public function getName()
    {
        return 'eeemarv_registration_type';
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		parent::setDefaultOptions($resolver);
	}  	

}
