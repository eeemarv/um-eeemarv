<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenderType extends AbstractType
{
    public function getName()
    {
        return 'eeemarv_gender_type';
    }
 
     public function getParent()
    {
        return 'choice';
    }   
    
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'choices' => array('m' => 'male', 'f' => 'female'), 
			'empty_data' => null,
		));
	}    
}
