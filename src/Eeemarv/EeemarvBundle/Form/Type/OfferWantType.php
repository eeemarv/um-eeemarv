<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfferWantType extends AbstractType
{
    public function getName()
    {
        return 'eeemarv_offer_want_type';
    }
 
     public function getParent()
    {
        return 'choice';
    }   
    
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'choices' => array('o' => 'offer', 'w' => 'want'), 
			'empty_value' => '',
			'empty_data' => null,
		));
	}    
}
