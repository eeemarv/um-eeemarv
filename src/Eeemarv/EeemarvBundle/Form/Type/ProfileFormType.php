<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;


class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
	
		$builder
		/*	->add('image', 'file', array(
				'required' => false))  */
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
				'required' => false));
		parent::buildForm($builder, $options);	
		$builder->remove('username');			
				
    }

    public function getName()
    {
        return 'eeemarv_profile_type';
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		parent::setDefaultOptions($resolver);
	}  	

}

