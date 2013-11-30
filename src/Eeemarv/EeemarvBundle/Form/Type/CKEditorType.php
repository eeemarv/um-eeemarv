<?php

namespace Eeemarv\EeemarvBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Request;

// This type brings in the locale in the ckeditor

class CKEditorType extends AbstractType
{
	private $request;
	
    public function __construct(Request $request)
    {
        $this->request = $request;
    }	
	
    public function getName()
    {
        return 'eeemarv_ckeditor_type';
    }
 
     public function getParent()
    {
        return 'ckeditor';
    }   
    
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'config' => 
				array ('language' => $this->request->getLocale())
		));
	}    
}
