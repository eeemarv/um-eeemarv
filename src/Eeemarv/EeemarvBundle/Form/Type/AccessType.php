<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccessType extends AbstractType
{
    public function getName()
    {
        return 'eeemarv_access';
    }
 
     public function getParent()
    {
        return 'choice';
    }   
    
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$choices_array = array();
		
		for ($i = 0; $i < 9; $i++)
		{
			$choices_array[$i] = (string) $i;
		}	
		
		$resolver->setDefaults(array(
			'choices' => $choices_array,
			'expanded' => true
		));
	}    
}
