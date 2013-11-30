<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
 
 
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('email', 'email')		
        	->add('firstName')
        	->add('lastName')
        	->add('gender', 'eeemarv_gender_type')
        	->add('birthday', 'birthday', array(
				'empty_value' => '',
				'empty_data' => null))                 	
        	->add('streetName')
        	->add('houseNumber')
        	->add('bus', 'text', array(
				'required' => false))	
       		->add('place', 'entity')
			->add('website', 'url', array(
				'required' => false))
 			->add('phone', 'text', array(
				'required' => false))
			->add('mobile', 'text', array(
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
			'validation_groups' => array('xxxxx')
		));
	}    
}
